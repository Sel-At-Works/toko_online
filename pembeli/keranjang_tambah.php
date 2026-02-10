<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);
$produk_id  = intval($_GET['id']);

// ambil stok produk
$produk = mysqli_query($conn, "
    SELECT stok 
    FROM produk 
    WHERE id = $produk_id
");
$data = mysqli_fetch_assoc($produk);

if (!$data || $data['stok'] <= 0) {
    header("Location: produk.php?stok_habis=1");
    exit;
}

// cek apakah produk sudah ada di keranjang
$cek = mysqli_query($conn, "
    SELECT id, qty 
    FROM keranjang 
    WHERE pembeli_id = $pembeli_id 
      AND produk_id = $produk_id
");

if (mysqli_num_rows($cek) > 0) {
    mysqli_query($conn, "
        UPDATE keranjang 
        SET qty = qty + 1 
        WHERE pembeli_id = $pembeli_id 
          AND produk_id = $produk_id
    ");
} else {
    mysqli_query($conn, "
        INSERT INTO keranjang (pembeli_id, produk_id, qty)
        VALUES ($pembeli_id, $produk_id, 1)
    ");
}

// // 🔥 KURANGI STOK PRODUK
// mysqli_query($conn, "
//     UPDATE produk 
//     SET stok = stok - 1 
//     WHERE id = $produk_id
// ");

header("Location: produk.php");
exit;
