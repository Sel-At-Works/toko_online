<?php
include 'config/koneksi.php';

// =====================
// AMBIL DATA
// =====================
$nama     = $_POST['nama'];
$email    = $_POST['email'];
$nik      = $_POST['nik'];
// VALIDASI NIK HARUS 16 DIGIT ANGKA
if (!preg_match('/^[0-9]{16}$/', $nik)) {
    echo "<script>
        alert('NIK harus berupa 16 digit angka!');
        history.back();
    </script>";
    exit;
}
$alamat   = $_POST['alamat'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];
$role     = $_POST['role'];

// =====================
// VALIDASI PASSWORD
// =====================
if ($password !== $password_confirm) {
    die("Password tidak sama!");
}

// =====================
// CEK APAKAH SUPER ADMIN SUDAH ADA
// =====================
$cekSuperAdmin = mysqli_query(
    $conn,
    "SELECT id FROM users WHERE role_id = 1 LIMIT 1"
);

$superAdminAda = mysqli_num_rows($cekSuperAdmin) > 0;

// =====================
// TENTUKAN ROLE_ID
// =====================
if ($role === 'super_admin') {

    if ($superAdminAda) {
        die("Super Admin sudah ada. Tidak bisa mendaftar lagi.");
    }

    $role_id = 1;

} elseif ($role === 'penjual') {
    $role_id = 2;

} elseif ($role === 'pembeli') {
    $role_id = 3;

} else {
    die("Role tidak valid!");
}

// =====================
// HASH PASSWORD
// =====================
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// =====================
// CEK NIK SUDAH ADA ATAU BELUM
// =====================
$cekNik = mysqli_query($conn, "SELECT id FROM users WHERE nik = '$nik' LIMIT 1");

if (mysqli_num_rows($cekNik) > 0) {
    echo "<script>
        alert('NIK sudah terdaftar! Silakan gunakan NIK lain.');
        history.back();
    </script>";
    exit;
}

// =====================
// INSERT DATABASE
// =====================
$query = "INSERT INTO users (role_id, nik, nama, email, alamat, password)
          VALUES ('$role_id', '$nik', '$nama', '$email', '$alamat', '$password_hash')";

mysqli_query($conn, $query);

// =====================
// REDIRECT
// =====================
header("Location: login.php");
exit;
