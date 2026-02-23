<?php
session_start();
include '../config/koneksi.php';

$penjual_id = intval($_SESSION['user_id']);
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: produk.php");
    exit;
}

// ================= CEK PRODUK =================
$cek = mysqli_query($conn, "
    SELECT gambar, stok 
    FROM produk 
    WHERE id = $id 
      AND penjual_id = $penjual_id
    LIMIT 1
");

$produk = mysqli_fetch_assoc($cek);

if (!$produk) {
    echo "<script>
        alert('Produk tidak ditemukan atau bukan milik Anda');
        window.location='produk.php';
    </script>";
    exit;
}

// ================= CEK STOK =================
if ($produk['stok'] > 0) {
    echo "<script>
        alert('Produk tidak bisa dihapus karena stok masih tersedia!');
        window.location='produk.php';
    </script>";
    exit;
}

// ================= SOFT DELETE =================
mysqli_query($conn, "
    UPDATE produk 
    SET is_active = 0
    WHERE id = $id 
      AND penjual_id = $penjual_id
");

// ================= REDIRECT =================
header("Location: produk.php?hapus=1");
exit;
?>