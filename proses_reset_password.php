<?php
include 'config/koneksi.php';

// Ambil data
$token            = $_POST['token'] ?? '';
$password         = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// =====================
// VALIDASI INPUT
// =====================
if (empty($token) || empty($password) || empty($password_confirm)) {
    die("Data tidak lengkap");
}

if ($password !== $password_confirm) {
    die("Password tidak sama");
}

// =====================
// CEK TOKEN & EXPIRED
// =====================
$query = mysqli_query($conn, "
    SELECT id, reset_expired 
    FROM users 
    WHERE reset_token='$token'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    die("Token tidak valid");
}

$data = mysqli_fetch_assoc($query);

if (strtotime($data['reset_expired']) < time()) {
    die("Token sudah kedaluwarsa");
}

// =====================
// HASH PASSWORD BARU
// =====================
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// =====================
// UPDATE PASSWORD
// =====================
mysqli_query($conn, "
    UPDATE users 
    SET password='$password_hash',
        reset_token=NULL,
        reset_expired=NULL
    WHERE id='{$data['id']}'
");

// =====================
// REDIRECT
// =====================
header("Location: login.php?reset=success");
exit;
