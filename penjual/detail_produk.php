<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$penjual_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: produk.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT p.*, k.nama_kategori
    FROM produk p
    LEFT JOIN kategori k ON p.kategori_id = k.id
    WHERE p.id = '$id'
      AND p.penjual_id = '$penjual_id'
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

$keuntungan = $produk['harga'] - $produk['harga_modal'];
$margin_persen = ($produk['harga_modal'] > 0)
    ? round(($keuntungan / $produk['harga_modal']) * 100)
    : 0;
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
                    <h2 class="text-3xl font-bold"><?= $produk['nama_produk'] ?></h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Kategori:
                        <span class="font-semibold">
                            <?= $produk['nama_kategori'] ?? '-' ?>
                        </span>
                    </p>
                </div>

                <!-- DESKRIPSI -->
                <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-700 max-h-28 overflow-y-auto">
                    <?= nl2br($produk['deskripsi']) ?: '-' ?>
                </div>

                <!-- INFO GRID -->
                <div class="grid grid-cols-2 gap-4">

                    <div class="bg-gray-100 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Harga Modal</p>
                        <p class="font-bold text-lg">
                            Rp <?= number_format($produk['harga_modal']) ?>
                        </p>
                    </div>

                    <div class="bg-gray-100 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Harga Jual</p>
                        <p class="font-bold text-lg text-teal-600">
                            Rp <?= number_format($produk['harga']) ?>
                        </p>
                    </div>

                    <div class="bg-gray-100 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Stok</p>
                        <p class="font-bold text-lg"><?= $produk['stok'] ?></p>
                    </div>

                    <div class="bg-gray-100 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Keuntungan</p>
                        <p class="font-extrabold text-xl
                            <?= $keuntungan >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            Rp <?= number_format($keuntungan) ?>
                            <span class="text-sm font-semibold">
                                (<?= $margin_persen ?>%)
                            </span>
                        </p>
                    </div>

                </div>

                <!-- STATUS -->
                <div class="bg-green-50 border border-green-200 p-4 rounded-xl">
                    <p class="text-sm text-green-700 font-semibold">
                        ✔ Produk Tersedia
                    </p>
                </div>

                <!-- AKSI -->
                <div class="flex gap-4 pt-4">
                    <a href="edit_produk.php?id=<?= $produk['id'] ?>"
                       class="px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-white rounded-xl font-semibold transition">
                       ✏️ Edit
                    </a>

                    <?php if ($produk['stok'] > 0): ?>

                    <a href="#"
                    onclick="alert('Produk tidak bisa dihapus karena stok masih tersedia'); return false;"
                    class="px-6 py-3 bg-gray-400 hover:bg-gray-500 text-white rounded-xl font-semibold transition">
                    🗑️ Hapus
                    </a>

                    <?php else: ?>

                    <a href="hapus_produk.php?id=<?= $produk['id'] ?>"
                    onclick="return confirm('Yakin ingin menghapus produk ini?')"
                    class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold transition">
                    🗑️ Hapus
                    </a>

                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
