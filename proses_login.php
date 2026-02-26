<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$query = mysqli_query($conn, "
    SELECT users.*, roles.nama_role 
    FROM users 
    JOIN roles ON users.role_id = roles.id 
    WHERE users.email = '$email'
");

$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "<script>alert('Email tidak ditemukan'); window.location='login.php';</script>";
    exit;
}

// ✅ CEK ONLINE
if ($user['status_login'] === 'online') {
    echo "<script>
        alert('Akun ini sedang login di perangkat lain!');
        window.location='login.php';
    </script>";
    exit;
}

if (!password_verify($password, $user['password'])) {
    echo "<script>alert('Password salah'); window.location='login.php';</script>";
    exit;
}

$_SESSION['user'] = [
    'id'     => $user['id'],
    'nama'   => $user['nama'],
    'email'  => $user['email'],
    'role'   => $user['nama_role'],
    'foto'   => $user['foto'],
    'alamat' => $user['alamat']
];

$_SESSION['user_id'] = $user['id'];
$_SESSION['role_id'] = $user['role_id'];

mysqli_query($conn, "
    UPDATE users 
    SET status_login='online'
    WHERE id={$user['id']}
");

if ($user['role_id'] == 1) {
    header("Location: super_admin/dashboard.php");
} elseif ($user['role_id'] == 2) {

    $uid = $user['id'];

    // cek data penjual
    $cek = mysqli_query($conn, "
        SELECT bank, no_rekening, qris
        FROM penjual_profile
        WHERE user_id = $uid
        LIMIT 1
    ");

    // belum ada profil sama sekali
    if (mysqli_num_rows($cek) === 0) {
        header("Location: penjual/lengkapi_profil_penjual.php");
        exit;
    }

    $profil = mysqli_fetch_assoc($cek);

    // data WAJIB
    if (
        empty($profil['bank']) ||
        empty($profil['no_rekening'])
    ) {
        header("Location: penjual/lengkapi_profil_penjual.php");
        exit;
    }

    // sudah lengkap
    header("Location: penjual/dashboard.php");
}
 elseif ($user['role_id'] == 3) {
    header("Location: ./pembeli/dashboard.php");
} else {
    header("Location: login.php");
}
exit;
