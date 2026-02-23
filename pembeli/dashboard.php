<?php
session_start();
include '../config/koneksi.php';

// ================= CEK LOGIN =================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$nama = $_SESSION['user']['nama'] ?? 'Pembeli';

// ================= AMBIL PRODUK (SEMUA PENJUAL) =================
$search = $_GET['search'] ?? '';

$queryProduk = mysqli_query($conn, "
    SELECT p.id, p.nama_produk, p.gambar, p.harga, u.nama AS nama_penjual
    FROM produk p
    JOIN users u ON p.penjual_id = u.id
    WHERE u.role_id = 2
      AND p.is_active = 1
      AND p.nama_produk LIKE '%$search%'
    ORDER BY p.created_at DESC
    LIMIT 8
");
?>

<!DOCTYPE html>
<html lang="id">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans h-screen overflow-hidden">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <?php include '../layouts/sidebar_pembeli.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8 overflow-y-auto">

        <!-- TOP BAR -->
       <div class="flex items-center gap-4 mb-10">

    <form method="GET" class="flex-1 relative">
        <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search); ?>"
            placeholder="Cari kategori..."
            class="w-full px-12 py-3 rounded-full bg-white shadow focus:outline-none"
        >
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
    </form>

    <?php include '../layouts/profil_notifikasi.php'; ?>
</div>

        <!-- HERO BANNER -->
        <div class="bg-gradient-to-r from-teal-400 to-teal-600
                    rounded-[40px] p-10 flex items-center justify-between
                    shadow-lg mb-14">

            <div class="text-white max-w-lg">
                <h1 class="text-3xl font-bold leading-snug mb-4">
                    Sari Anggrek <br>
                    selalu didepan <br>
                    melayani kebutuhan <br>
                    anda
                </h1>

                <div class="flex gap-4 mt-6">
                    <button class="px-6 py-2 bg-white text-teal-600 rounded-full font-semibold">
                        Get Started
                    </button>
                    <button class="px-6 py-2 border border-white rounded-full text-white">
                        Learn More
                    </button>
                </div>
            </div>

            <img src="https://cdn-icons-png.flaticon.com/512/2933/2933245.png"
                 class="w-64 hidden md:block">
        </div>

        <!-- BOOK LIST HEADER -->
        <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">Produk Terbaru</h3>
        <a href="produk.php"
        class="bg-teal-500 text-white px-4 py-1 rounded-full text-sm hover:bg-teal-600 transition">
            Lihat Semua Produk
        </a>
        </div>

        <!-- BOOK LIST -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">

            <?php if (mysqli_num_rows($queryProduk) > 0): ?>    
                <?php while ($p = mysqli_fetch_assoc($queryProduk)): ?>

<div class="group bg-white rounded-3xl shadow-sm 
            hover:shadow-2xl transition-all duration-300
            border border-gray-100 overflow-hidden
            hover:-translate-y-2">

    <!-- IMAGE -->
    <div class="relative h-44 bg-gradient-to-br from-gray-50 to-gray-100 
                flex items-center justify-center overflow-hidden">

        <?php if (!empty($p['gambar'])): ?>
            <img src="../uploads/<?= htmlspecialchars($p['gambar']); ?>"
                 class="max-h-full max-w-full object-contain
                        transition-transform duration-500
                        group-hover:scale-110">
        <?php else: ?>
            <span class="text-gray-400 text-sm">No Image</span>
        <?php endif; ?>

        <!-- BADGE BARU -->
        <span class="absolute top-3 left-3 
                     bg-teal-500 text-white text-xs font-bold 
                     px-3 py-1 rounded-full shadow">
            NEW
        </span>
    </div>

    <!-- CONTENT -->
    <div class="p-4 text-center">

        <h3 class="font-semibold text-gray-800 
                   line-clamp-2 min-h-[48px]">
            <?= htmlspecialchars($p['nama_produk']); ?>
        </h3>

        <p class="text-xs text-gray-500 mt-1">
            <?= htmlspecialchars($p['nama_penjual']); ?>
        </p>

        <p class="text-teal-600 font-extrabold text-lg mt-2">
            Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
        </p>

        <!-- ACTION -->
        <div class="mt-4 flex justify-center gap-2 opacity-0 
                    group-hover:opacity-100 transition-all duration-300">

            <a href="detail_produk.php?id=<?= $p['id'] ?>"
               class="px-3 py-1.5 text-xs font-semibold rounded-full 
                      bg-blue-500 text-white hover:bg-blue-600 transition">
                👁️ Detail
            </a>

            <a href="keranjang_tambah.php?id=<?= $p['id'] ?>"
               class="px-3 py-1.5 text-xs font-semibold rounded-full 
                      bg-teal-500 text-white hover:bg-teal-600 transition">
                🛒 Keranjang
            </a>
        </div>

    </div>
</div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center text-gray-400">
                    Belum ada kategori
                </div>
            <?php endif; ?>

        </div>

        <!-- FOOTER -->
        <div class="text-center text-gray-400 mt-16">
            © <?= date('Y') ?> Sari Anggrek
        </div>

    </main>

</div>
<script>
document.querySelectorAll('a[href^="keranjang_tambah.php"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault(); // cegah link langsung

        const url = this.href; // simpan URL untuk dituju
        const namaProduk = this.closest('.group').querySelector('h3').innerText;

        Swal.fire({
            title: 'Masukkan ke keranjang?',
            text: `Apakah Anda ingin menambahkan "${namaProduk}" ke keranjang?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pilih "Ya", lanjut ke URL keranjang
                window.location.href = url;
            }
        });
    });
});
</script>
</body>
</html>
