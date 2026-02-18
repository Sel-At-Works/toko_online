<?php
session_start();
include '../config/koneksi.php';

/* ================= CEK LOGIN PEMBELI ================= */
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role_id']) ||
    $_SESSION['role_id'] != 3
) {
    header("Location: /login.php");
    exit;
}

$pembeli_id   = $_SESSION['user_id'];
$transaksi_id = intval($_GET['id'] ?? 0);

if ($transaksi_id <= 0) {
    header("Location: pesan_pembeli.php");
    exit;
}

/* ================= CEK TRANSAKSI MILIK PEMBELI ================= */
$cek = mysqli_query($conn, "
    SELECT id 
    FROM transaksi 
    WHERE id = '$transaksi_id'
    AND pembeli_id = '$pembeli_id'
");

if (mysqli_num_rows($cek) == 0) {
    header("Location: pesan_pembeli.php");
    exit;
}

/* ================= UPDATE STATUS PENJUAL ================= */
mysqli_query($conn, "
    UPDATE transaksi_penjual
    SET status = 'selesai'
    WHERE transaksi_id = '$transaksi_id'
");

/* ================= CEK SEMUA PENJUAL ================= */
$cekSemua = mysqli_query($conn, "
    SELECT COUNT(*) AS total,
           SUM(status = 'selesai') AS selesai
    FROM transaksi_penjual
    WHERE transaksi_id = '$transaksi_id'
");

$dataCek = mysqli_fetch_assoc($cekSemua);

/* ================= UPDATE STATUS GLOBAL ================= */
if ($dataCek['total'] == $dataCek['selesai']) {
    mysqli_query($conn, "
        UPDATE transaksi
        SET status = 'selesai'
        WHERE id = '$transaksi_id'
    ");
}

/* ================= REDIRECT ================= */
$_SESSION['alert'] = [
    'title' => 'Berhasil',
    'text'  => 'Barang berhasil dikonfirmasi diterima',
    'icon'  => 'success'
];

header("Location: pesan_pembeli.php");
exit;
