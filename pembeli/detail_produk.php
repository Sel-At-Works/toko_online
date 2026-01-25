<?php
session_start();
include '../config/koneksi.php';

// pastikan PEMBELI login
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: produk.php");
    exit;
}

// ================= AMBIL DATA PRODUK =================
$query = mysqli_query($conn, "
    SELECT p.*, k.nama_kategori
    FROM produk p
    LEFT JOIN kategori k ON p.kategori_id = k.id
    WHERE p.id = '$id'
    LIMIT 1
");

$produk = mysqli_fetch_assoc($query);
if (!$produk) {
    header("Location: produk.php");
    exit;
}

$gambar = $produk['gambar']
    ? "../uploads/" . $produk['gambar']
    : "https://cdn-icons-png.flaticon.com/512/2847/2847978.png";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-teal-50 to-sky-100 min-h-screen">

<div class="max-w-7xl mx-auto p-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-teal-600">Detail Produk</h1>
        <a href="produk.php"
           class="px-5 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition">
           ← Kembali
        </a>
    </div>

    <!-- CARD DETAIL -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="grid grid-cols-1 md:grid-cols-2">

            <!-- GAMBAR -->
            <div class="bg-gray-100 flex items-center justify-center p-8">
                <img src="<?= $gambar ?>"
                     class="max-h-[420px] w-full object-contain hover:scale-105 transition">
            </div>

            <!-- INFO -->
            <div class="p-8 space-y-6">

                <div>
                    <h2 class="text-3xl font-bold">
                        <?= htmlspecialchars($produk['nama_produk']) ?>
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Kategori:
                        <span class="font-semibold">
                            <?= htmlspecialchars($produk['nama_kategori'] ?? '-') ?>
                        </span>
                    </p>
                </div>

                <!-- DESKRIPSI -->
                <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-700 max-h-32 overflow-y-auto">
                    <?= nl2br(htmlspecialchars($produk['deskripsi'])) ?: '-' ?>
                </div>

                <!-- INFO GRID -->
                <div class="grid grid-cols-2 gap-4">

                    <div class="bg-gray-100 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Harga</p>
                        <p class="font-extrabold text-xl text-teal-600">
                            Rp <?= number_format($produk['harga']) ?>
                        </p>
                    </div>

                    <div class="bg-gray-100 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Stok</p>
                        <p class="font-bold text-lg">
                            <?= $produk['stok'] > 0 ? $produk['stok'] : 'Habis' ?>
                        </p>
                    </div>

                </div>

                <!-- STATUS -->
                <div class="p-4 rounded-xl
                    <?= $produk['stok'] > 0
                        ? 'bg-green-50 border border-green-200'
                        : 'bg-red-50 border border-red-200' ?>">
                    <p class="text-sm font-semibold
                        <?= $produk['stok'] > 0 ? 'text-green-700' : 'text-red-700' ?>">
                        <?= $produk['stok'] > 0
                            ? '✔ Produk Tersedia'
                            : '✖ Stok Habis' ?>
                    </p>
                </div>

                <!-- AKSI -->
                <div class="flex gap-4 pt-4">

                    <?php if ($produk['stok'] > 0) { ?>
                        <a href="keranjang_tambah.php?id=<?= $produk['id'] ?>"
                           class="px-6 py-3 bg-teal-500 hover:bg-teal-600
                                  text-white rounded-xl font-semibold transition">
                           🛒 Tambah ke Keranjang
                        </a>
                    <?php } else { ?>
                        <button disabled
                           class="px-6 py-3 bg-gray-300 text-gray-500
                                  rounded-xl font-semibold cursor-not-allowed">
                           Stok Habis
                        </button>
                    <?php } ?>

                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
