<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {

    // 1️⃣ Ambil data keranjang (qty & produk_id)
    $query = mysqli_query($conn, "
        SELECT produk_id, qty 
        FROM keranjang 
        WHERE id = $id 
          AND pembeli_id = $pembeli_id
    ");

    if ($row = mysqli_fetch_assoc($query)) {
        $produk_id = (int)$row['produk_id'];
        $qty       = (int)$row['qty'];

        // // 2️⃣ Kembalikan stok produk
        // mysqli_query($conn, "
        //     UPDATE produk 
        //     SET stok = stok + $qty 
        //     WHERE id = $produk_id
        // ");

        // 3️⃣ Hapus item dari keranjang
        mysqli_query($conn, "
            DELETE FROM keranjang 
            WHERE id = $id
        ");
    }
}

header("Location: keranjang.php");
exit;
