<?php
session_start();
if ($_SESSION['role'] !== 'penjual') {
    die('Akses ditolak');
}

include '../config/koneksi.php';

$penjual_id = $_SESSION['user_id'];
$transaksi_id = $_POST['transaksi_id'];
$resi = $_POST['resi'];

/* validasi */
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT *
    FROM transaksi_penjual
    WHERE transaksi_id = '$transaksi_id'
    AND penjual_id = '$penjual_id'
"));

if (!$data) {
    die('Data tidak valid');
}

if ($data['approve'] !== 'setuju') {
    die('Pembayaran belum disetujui');
}

/* update */
mysqli_query($conn, "
    UPDATE transaksi_penjual
    SET resi = '$resi', status = 'dikirim'
    WHERE transaksi_id = '$transaksi_id'
    AND penjual_id = '$penjual_id'
");

header("Location: pesan_penjual.php");
exit;
