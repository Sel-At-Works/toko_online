<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../config/koneksi.php';

// proteksi login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die('ID pembeli tidak ditemukan');
}

// ambil data pembeli
$get = mysqli_query($conn, "
    SELECT foto, status_login 
    FROM users 
    WHERE id='$id' AND role_id=3
");

if (!$get || mysqli_num_rows($get) == 0) {
    die('Data pembeli tidak ditemukan');
}

$data = mysqli_fetch_assoc($get);

// Cek jika user sedang online
if ($data['status_login'] === 'online') {
    die('ONLINE');
}

// Cek jika user sendiri
if ($id == $_SESSION['user_id']) {
    die('SELF');
}

// Ganti DELETE dengan soft delete (is_active = 0)
$hapus = mysqli_query($conn, "
    UPDATE users 
    SET is_active = 0 
    WHERE id='$id' AND role_id=3
");

if ($hapus) {
    echo 'OK'; // JS akan tangani notifikasi
} else {
    echo 'FAILED';
}