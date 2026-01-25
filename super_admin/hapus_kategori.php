<?php
session_start();
include '../config/koneksi.php';

/* ===== AMBIL ID ===== */
$id = $_GET['id'] ?? '';

if ($id == '') {
    echo "<script>
        alert('ID kategori tidak ditemukan');
        window.location='kategori.php';
    </script>";
    exit;
}

/* ===== CEK DATA ===== */
$cek = mysqli_query($conn, "SELECT id FROM kategori WHERE id='$id'");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>
        alert('Data kategori tidak ditemukan');
        window.location='kategori.php';
    </script>";
    exit;
}

/* ===== PROSES HAPUS ===== */
$hapus = mysqli_query($conn, "DELETE FROM kategori WHERE id='$id'");

if ($hapus) {
    echo "<script>
        alert('Kategori berhasil dihapus');
        window.location='kategori.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus kategori');
        window.location='kategori.php';
    </script>";
}
