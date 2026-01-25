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
    SELECT nama, alamat 
    FROM users 
    WHERE id = $pembeli_id
");
$user = mysqli_fetch_assoc($userQ);


/* ================= VALIDASI BANK ================= */
$bank = strtolower(trim($_POST['bank'] ?? ''));
$no_rekening = preg_replace('/[^0-9]/', '', $_POST['no_rekening'] ?? '');

$bankDigit = [
    'bni' => 10,
    'bri' => 15,
    'mandiri' => 13,
    'bca' => 10
];

if (!isset($bankDigit[$bank]) || strlen($no_rekening) != $bankDigit[$bank]) {
    die('Nomor rekening tidak valid');
}

/* ================= TELEPON ================= */
$no_telepon = preg_replace('/[^0-9]/', '', $_POST['no_telepon'] ?? '');
if (strlen($no_telepon) < 10 || strlen($no_telepon) > 13) {
    die('Nomor telepon tidak valid');
}

/* ================= AMBIL KERANJANG ================= */
$items = [];
$total = 0;

$q = mysqli_query($conn, "
    SELECT k.produk_id, k.qty, p.nama_produk, p.harga
    FROM keranjang k
    JOIN produk p ON k.produk_id = p.id
    WHERE k.pembeli_id = $pembeli_id
");

while ($row = mysqli_fetch_assoc($q)) {
    $items[] = $row;
    $total += $row['harga'] * $row['qty'];
}

if (!$items) {
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
    (pembeli_id, bank, no_rekening, no_telepon, total, bukti_transfer, status)
    VALUES 
    ($pembeli_id, '$bank', '$no_rekening', '$no_telepon', $total, ".($bukti ? "'$bukti'" : "NULL").", '$status')
");

$transaksi_id = mysqli_insert_id($conn);

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
header("Location: checkout_sukses.php");
exit;
