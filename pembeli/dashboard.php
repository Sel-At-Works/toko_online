<?php
session_start();
include '../config/koneksi.php';

// ================= CEK LOGIN =================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$nama = $_SESSION['user']['nama'] ?? 'Pembeli';

// ================= AMBIL KATEGORI =================
$queryKategori = mysqli_query($conn, "
    SELECT id, nama_kategori, gambar
    FROM kategori 
    ORDER BY nama_kategori ASC
");
$search = $_GET['search'] ?? '';

$queryKategori = mysqli_query($conn, "
    SELECT id, nama_kategori, gambar
    FROM kategori
    WHERE penjual_id IS NULL
    AND nama_kategori LIKE '%$search%'
    ORDER BY nama_kategori ASC
");

?>

<!DOCTYPE html>
<html lang="id">
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
            <h3 class="text-xl font-semibold">Your Book</h3>
            <a href="kategori.php"
               class="bg-teal-500 text-white px-4 py-1 rounded-full text-sm hover:bg-teal-600 transition">
                See All
            </a>
        </div>

        <!-- BOOK LIST -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">

            <?php if (mysqli_num_rows($queryKategori) > 0): ?>
                <?php while ($kat = mysqli_fetch_assoc($queryKategori)): ?>

                    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

                        <!-- GAMBAR -->
                        <div class="h-40 bg-gray-100 flex items-center justify-center p-3">
                            <?php if (!empty($kat['gambar'])): ?>
                                <img src="../uploads/kategori/<?= htmlspecialchars($kat['gambar']); ?>"
                                     class="max-h-full max-w-full object-contain"
                                     alt="<?= htmlspecialchars($kat['nama_kategori']); ?>">
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">No Image</span>
                            <?php endif; ?>
                        </div>

                        <!-- NAMA -->
                        <div class="p-3 text-center border-t">
                            <p class="font-semibold text-gray-700">
                                <?= htmlspecialchars($kat['nama_kategori']); ?>
                            </p>
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

</body>
</html>
