<?php
session_start();
include '../config/koneksi.php';

/* ================= CEK LOGIN PEMBELI ================= */
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header("Location: /login.php");
    exit;
}

$pembeli_id = $_SESSION['user_id'];
$tp_id = intval($_GET['tp_id'] ?? 0);

if ($tp_id <= 0) {
    header("Location: pesan_pembeli.php");
    exit;
}

/* ================= CEK TRANSAKSI PENJUAL MILIK PEMBELI ================= */
$cek = mysqli_query($conn, "
    SELECT tp.transaksi_id
    FROM transaksi_penjual tp
    JOIN transaksi t ON tp.transaksi_id = t.id
    WHERE tp.id = '$tp_id' AND t.pembeli_id = '$pembeli_id'
");

if (mysqli_num_rows($cek) == 0) {
    header("Location: pesan_pembeli.php");
    exit;
}

$data = mysqli_fetch_assoc($cek);
$transaksi_id = $data['transaksi_id'];

/* ================= UPDATE STATUS PENJUAL ================= */
mysqli_query($conn, "
    UPDATE transaksi_penjual
    SET status = 'selesai'
    WHERE id = '$tp_id'
");

/* ================= CEK SEMUA PENJUAL DI TRANSAKSI ================= */
$cekSemua = mysqli_query($conn, "
    SELECT COUNT(*) AS total,
           SUM(status = 'selesai') AS selesai
    FROM transaksi_penjual
    WHERE transaksi_id = '$transaksi_id'
");

$dataCek = mysqli_fetch_assoc($cekSemua);

/* ================= UPDATE STATUS GLOBAL JIKA SEMUA SELESAI ================= */
if ($dataCek['total'] == $dataCek['selesai']) {
    mysqli_query($conn, "
        UPDATE transaksi
        SET status = 'selesai'
        WHERE id = '$transaksi_id'
    ");
}

/* ================= REDIRECT DENGAN ALERT ================= */
$_SESSION['alert'] = [
    'title' => 'Berhasil',
    'text'  => 'Barang berhasil dikonfirmasi diterima',
    'icon'  => 'success'
];

header("Location: pesan_pembeli.php");
exit;
