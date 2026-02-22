<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id   = (int) $_SESSION['user']['id'];
$role = $_SESSION['user']['role'];

$nama   = mysqli_real_escape_string($conn, $_POST['nama']);
$email  = mysqli_real_escape_string($conn, $_POST['email']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');

$fotoPath = $_SESSION['user']['foto'] ?? 'uploads/profile/default.png';

/* ================= CEK EMAIL DUPLIKAT ================= */
$emailCheck = mysqli_query($conn, "
    SELECT id FROM users 
    WHERE email = '$email' AND id != $id
    LIMIT 1
");

if (mysqli_num_rows($emailCheck) > 0) {
    // Email sudah dipakai user lain
    $_SESSION['alert'] = "Email '$email' sudah digunakan!";
    header("Location: edit_profile.php");
    exit;
}

/* ================= NO TELEPON ================= */
if ($role === 'pembeli') {
    $no_telepon = preg_replace('/[^0-9]/', '', $_POST['no_telepon']);

    if (strlen($no_telepon) < 10 || strlen($no_telepon) > 13) {
        die('Nomor telepon tidak valid');
    }

    // CEK APAKAH SUDAH ADA DATA
    $cek = mysqli_query($conn, "
        SELECT id FROM pembeli_profile 
        WHERE user_id = $id 
        LIMIT 1
    ");

    if (mysqli_num_rows($cek) > 0) {
        // UPDATE
        mysqli_query($conn, "
            UPDATE pembeli_profile
            SET no_telepon = '$no_telepon'
            WHERE user_id = $id
        ");
    } else {
        // INSERT
        mysqli_query($conn, "
            INSERT INTO pembeli_profile (user_id, no_telepon)
            VALUES ($id, '$no_telepon')
        ");
    }
}

/* ================= UPLOAD FOTO ================= */
if (!empty($_FILES['foto']['name'])) {

    $folder = 'uploads/profile/';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $namaFile = 'user_' . $id . '_' . time() . '.' . $ext;
    $target = $folder . $namaFile;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
        $fotoPath = $target;
    }
}

/* ================= UPDATE USERS ================= */
mysqli_query($conn, "
    UPDATE users SET 
        nama   = '$nama',
        email  = '$email',
        alamat = '$alamat',
        foto   = '$fotoPath'
    WHERE id = $id
");

/* ================= UPDATE SESSION ================= */
$_SESSION['user']['nama']   = $nama;
$_SESSION['user']['email']  = $email;
$_SESSION['user']['alamat'] = $alamat;
$_SESSION['user']['foto']   = $fotoPath;

header("Location: profile.php");
exit;
