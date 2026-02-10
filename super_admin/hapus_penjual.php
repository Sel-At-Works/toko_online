<?php
session_start();
include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;
$userLogin = $_SESSION['user_id'] ?? 0;

// ambil data penjual
$q = mysqli_query($conn, "
    SELECT foto, status_login 
    FROM users 
    WHERE id='$id' AND role_id=2
");

$user = mysqli_fetch_assoc($q);

// ❌ data tidak ditemukan
if (!$user) {
    echo 'NOT_FOUND';
    exit;
}

// ❌ akun sendiri
if ($id == $userLogin) {
    echo 'SELF';
    exit;
}

// ❌ sedang online
if ($user['status_login'] === 'online') {
    echo 'ONLINE';
    exit;
}

// =========================
// ✅ BOLEH DIHAPUS
// =========================
mysqli_query($conn, "
    DELETE FROM users 
    WHERE id='$id' AND role_id=2
");

// hapus foto jika ada
if (!empty($user['foto'])) {
    $path = $_SERVER['DOCUMENT_ROOT'] . '/toko_online/' . $user['foto'];
    if (file_exists($path)) {
        unlink($path);
    }
}

echo 'OK';
