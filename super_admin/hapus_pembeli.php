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
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'ID pembeli tidak ditemukan'
        });
    </script>";
    exit;
}

// ambil data pembeli
$get = mysqli_query($conn, "
    SELECT foto 
    FROM users 
    WHERE id='$id' AND role_id=3
");

if (!$get || mysqli_num_rows($get) == 0) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Data pembeli tidak ditemukan'
        });
    </script>";
    exit;
}

$data = mysqli_fetch_assoc($get);

// hapus data pembeli
$hapus = mysqli_query($conn, "
    DELETE FROM users 
    WHERE id='$id' AND role_id=3
");

if ($hapus) {

    // hapus foto jika ada
    if (!empty($data['foto'])) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/toko_online/' . $data['foto'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data pembeli berhasil dihapus',
            timer: 2000,
            showConfirmButton: false
        });
    </script>";
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Query gagal dijalankan'
        });
    </script>";
}
