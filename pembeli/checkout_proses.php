<?php
session_start();
include '../config/koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);

/* ================= WAJIB UPLOAD BUKTI ================= */
if (!isset($_FILES['bukti'])) {
    $_SESSION['error'] = 'Silakan upload bukti transfer terlebih dahulu';
    header("Location: checkout.php");
    exit;
}

/* ================= DATA PEMBELI ================= */
$userQ = mysqli_query($conn, "
    SELECT u.nama, u.alamat, pp.no_telepon
    FROM users u
    LEFT JOIN pembeli_profile pp ON pp.user_id = u.id
    WHERE u.id = $pembeli_id
");
$user = mysqli_fetch_assoc($userQ);

if (empty($user['no_telepon'])) {
    $_SESSION['error'] = 'Lengkapi nomor telepon di profile terlebih dahulu';
    header("Location: ../profile.php");
    exit;
}

$no_telepon = $user['no_telepon'];

/* ================= AMBIL KERANJANG ================= */
$cart = mysqli_query($conn, "
    SELECT k.produk_id, k.qty, p.nama_produk, p.harga, p.penjual_id
    FROM keranjang k
    JOIN produk p ON k.produk_id = p.id
    WHERE k.pembeli_id = $pembeli_id
");

$items = [];
$total = 0;
$penjualTotal = [];

while ($row = mysqli_fetch_assoc($cart)) {
    $items[] = $row;
    $sub = $row['harga'] * $row['qty'];
    $total += $sub;

    if (!isset($penjualTotal[$row['penjual_id']])) {
        $penjualTotal[$row['penjual_id']] = 0;
    }
    $penjualTotal[$row['penjual_id']] += $sub;
}

if (count($items) === 0) {
    header("Location: keranjang.php");
    exit;
}

/* ================= SIMPAN TRANSAKSI ================= */
$status = 'menunggu_verifikasi';

mysqli_query($conn, "
    INSERT INTO transaksi (pembeli_id, no_telepon, total)
    VALUES ($pembeli_id, '$no_telepon', $total)
");

$transaksi_id = mysqli_insert_id($conn);

/* ================= TRANSAKSI PER PENJUAL ================= */
foreach ($penjualTotal as $penjual_id => $total_penjual) {

    // ✅ CEK METODE PEMBAYARAN
    if (empty($_POST['metode'][$penjual_id])) {
        $_SESSION['error'] = 'Metode pembayaran belum dipilih';
        header("Location: checkout.php");
        exit;
    }

    $metode = mysqli_real_escape_string(
        $conn,
        $_POST['metode'][$penjual_id]
    );

    // ✅ CEK BUKTI TRANSFER
    if (empty($_FILES['bukti']['name'][$penjual_id])) {
        $_SESSION['error'] = 'Semua bukti transfer wajib diupload';
        header("Location: checkout.php");
        exit;
    }

    $tmp  = $_FILES['bukti']['tmp_name'][$penjual_id];
    $mime = mime_content_type($tmp);

    if (!in_array($mime, ['image/jpeg', 'image/png'])) {
        $_SESSION['error'] = 'Bukti harus JPG atau PNG';
        header("Location: checkout.php");
        exit;
    }

    $ext = $mime === 'image/png' ? 'png' : 'jpg';
    $namaFile = "bukti_{$transaksi_id}_{$penjual_id}_" . time() . ".$ext";

    move_uploaded_file($tmp, "../uploads/bukti/$namaFile");

    // ✅ SIMPAN KE transaksi_penjual
    mysqli_query($conn, "
        INSERT INTO transaksi_penjual
        (
            transaksi_id,
            penjual_id,
            metode_pembayaran,
            total,
            bukti_transfer,
            status
        )
        VALUES
        (
            $transaksi_id,
            $penjual_id,
            '$metode',
            $total_penjual,
            '$namaFile',
            'menunggu_verifikasi'
        )
    ");
}


/* ================= DETAIL TRANSAKSI ================= */
foreach ($items as $item) {
    mysqli_query($conn, "
        INSERT INTO transaksi_detail
        (transaksi_id, produk_id, qty, harga)
        VALUES
        ($transaksi_id, {$item['produk_id']}, {$item['qty']}, {$item['harga']})
    ");
}

/* ================= INVOICE ================= */
$_SESSION['invoice'] = [
    'kode'    => 'INV-' . date('Ymd') . '-' . $transaksi_id,
    'tanggal' => date('d M Y H:i'),
    'status'  => 'Menunggu Verifikasi',
    'total'   => $total,
    'alamat'  => [
        'nama'   => $user['nama'],
        'telp'   => $no_telepon,
        'alamat' => $user['alamat'] ?: 'Alamat belum diisi'
    ],
    'items' => $items
];

/* ================= KOSONGKAN KERANJANG ================= */
mysqli_query($conn, "DELETE FROM keranjang WHERE pembeli_id = $pembeli_id");

/* ================= REDIRECT ================= */
header("Location: checkout_sukses.php?transaksi_id=$transaksi_id");
exit;
