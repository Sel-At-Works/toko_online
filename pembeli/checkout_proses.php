<?php
session_start();
include '../config/koneksi.php';

/* CEK LOGIN */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}


/* ================= WAJIB UPLOAD BUKTI ================= */
if (empty($_FILES['bukti']['name'])) {
    $_SESSION['error'] = 'Silakan upload bukti transfer terlebih dahulu';
    header("Location: checkout.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);
$userQ = mysqli_query($conn, "
    SELECT 
        u.nama,
        u.alamat,
        pp.no_telepon
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



/* ===== ambil keranjang ===== */
$cart = mysqli_query($conn, "
    SELECT
        k.produk_id,
        k.qty,
        p.nama_produk,
        p.harga,
        p.penjual_id
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


/* ================= BUKTI TRANSFER ================= */
$status = 'pending';
$bukti  = null;

if (
    !isset($_FILES['bukti']) ||
    $_FILES['bukti']['error'] !== UPLOAD_ERR_OK
) {
    $_SESSION['error'] = 'Upload bukti gagal. Silakan ulangi.';
    header("Location: checkout.php");
    exit;
}

/* VALIDASI MIME TYPE (LEBIH AMAN DARI EXTENSION) */
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $_FILES['bukti']['tmp_name']);
finfo_close($finfo);

$allowedMime = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png'
];

if (!isset($allowedMime[$mime])) {
    $_SESSION['error'] = 'Format bukti harus JPG atau PNG';
    header("Location: checkout.php");
    exit;
}

$ext = $allowedMime[$mime];

$bukti = 'bukti_' . time() . '_' . $pembeli_id . '.' . $ext;

move_uploaded_file(
    $_FILES['bukti']['tmp_name'],
    '../uploads/bukti/' . $bukti
);

$status = 'menunggu_verifikasi';


/* ================= SIMPAN TRANSAKSI ================= */
mysqli_query($conn, "
    INSERT INTO transaksi 
    (pembeli_id, no_telepon, total, bukti_transfer, status)
    VALUES 
    ($pembeli_id, '$no_telepon', $total, ".($bukti ? "'$bukti'" : "NULL").", '$status')
");


$transaksi_id = mysqli_insert_id($conn);

foreach ($penjualTotal as $penjual_id => $total_penjual) {
    mysqli_query($conn, "
        INSERT INTO transaksi_penjual
        (transaksi_id, penjual_id, total, status)
        VALUES
        ($transaksi_id, $penjual_id, $total_penjual, 'menunggu_verifikasi')
    ");
}




/* ================= DETAIL TRANSAKSI ================= */
foreach ($items as $item) {
    mysqli_query($conn, "
        INSERT INTO transaksi_detail (transaksi_id, produk_id, qty, harga)
        VALUES ($transaksi_id, {$item['produk_id']}, {$item['qty']}, {$item['harga']})
    ");
}




/* ================= ALAMAT ================= */
$alamat = [
    'nama'     => $user['nama'],
    'telp'     => $no_telepon,
    'alamat'   => $user['alamat'] ?: 'Alamat belum diisi',
    'kota'     => '-',        // kalau belum ada kolom
    'provinsi' => '-'         // kalau belum ada kolom
];


/* ================= SIMPAN INVOICE KE SESSION ================= */
$_SESSION['invoice'] = [
    'kode'    => 'INV-' . date('Ymd') . '-' . $transaksi_id,
    'tanggal' => date('d M Y H:i'),
    'status'  => 'Menunggu Verifikasi',
    'total'   => $total,
    'alamat'  => $alamat,
    'items'   => $items
];

/* ================= KOSONGKAN KERANJANG ================= */
mysqli_query($conn, "DELETE FROM keranjang WHERE pembeli_id = $pembeli_id");

/* ================= REDIRECT ================= */
header("Location: checkout_sukses.php?transaksi_id=$transaksi_id");
exit;
