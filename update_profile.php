<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id     = $_SESSION['user']['id'];
$nama   = $_POST['nama'];
$email  = $_POST['email'];
$alamat = $_POST['alamat'];

$fotoPath = $_SESSION['user']['foto']; // default foto lama

/* ================= UPLOAD FOTO ================= */
if (!empty($_FILES['foto']['name'])) {

    $folder = 'uploads/profile/';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $namaFile = 'user_' . $id . '_' . time() . '.' . $ext;
    $target = $folder . $namaFile;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
        $fotoPath = $target;
    }
}

/* ================= UPDATE DATABASE ================= */
$query = "UPDATE users SET 
            nama='$nama',
            email='$email',
            alamat='$alamat',
            foto='$fotoPath'
          WHERE id='$id'";

mysqli_query($conn, $query);

/* ================= UPDATE SESSION (INI WAJIB) ================= */
$_SESSION['user']['nama']   = $nama;
$_SESSION['user']['email']  = $email;
$_SESSION['user']['alamat'] = $alamat;
$_SESSION['user']['foto']   = $fotoPath;

/* ================= REDIRECT ================= */
header("Location: profile.php");
exit;
