<?php
session_start();
include '../config/koneksi.php';

// pastikan PEMBELI login
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

// ================== AMBIL KATEGORI ==================
$kategori = mysqli_query($conn, "
    SELECT * FROM kategori
    ORDER BY nama_kategori ASC
");

// ================== SEARCH ==================
$search = $_GET['search'] ?? '';
$search_safe = mysqli_real_escape_string($conn, strtolower($search));


$where_search = '';
if (!empty($search)) {
    $where_search = "AND (
        LOWER(p.nama_produk) LIKE '%$search_safe%' 
        OR LOWER(p.deskripsi) LIKE '%$search_safe%'
    )";
}

// ================== KATEGORI YANG DIPILIH ==================
$kategori_id = $_GET['kategori_id'] ?? '';
$where_kategori = $kategori_id ? "AND p.kategori_id = $kategori_id" : "";


// ================== AMBIL PRODUK (KHUSUS PEMBELI) ==================
$query = mysqli_query($conn, "
    SELECT p.*
    FROM produk p
    INNER JOIN kategori k ON p.kategori_id = k.id
    WHERE p.is_active = 1
    $where_kategori
    $where_search
    ORDER BY p.created_at DESC
");


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans h-screen overflow-hidden">

<div class="flex h-screen">

    <!-- SIDEBAR PEMBELI -->
    <?php include '../layouts/sidebar_pembeli.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8 overflow-y-auto">

        <!-- TOP BAR -->
        <div class="flex items-center justify-between mb-10">

            <!-- SEARCH -->
            <form method="GET" class="flex items-center gap-4 flex-1 max-w-3xl">

                <?php if (!empty($kategori_id)) { ?>
                    <input type="hidden" name="kategori_id" value="<?= $kategori_id ?>">
                <?php } ?>

                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="<?= htmlspecialchars($search) ?>"
                           placeholder="Cari produk..."
                           class="w-full pl-12 pr-4 py-3 rounded-full bg-white shadow focus:outline-none">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-teal-500">🔍</span>
                </div>
            </form>

            <!-- PROFIL -->
            <?php include '../layouts/profil_notifikasi.php'; ?>
        </div>

        <!-- TITLE -->
        <h1 class="text-4xl font-bold text-center mb-10 tracking-wide">
            PRODUK
        </h1>

        

        <!-- ================= KATEGORI FILTER ================= -->
        <div class="flex flex-wrap gap-3 justify-center mb-10">

            <a href="produk.php"
               class="px-5 py-2 rounded-full border
               <?= empty($kategori_id) ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' ?>">
               Semua
            </a>

            <?php while ($kat = mysqli_fetch_assoc($kategori)) { ?>
                <a href="produk.php?kategori_id=<?= $kat['id'] ?>"
                   class="px-5 py-2 rounded-full border
                   <?= ($kategori_id == $kat['id']) ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' ?>">
                   <?= $kat['nama_kategori'] ?>
                </a>
            <?php } ?>
        </div>

        <!-- ================= PRODUK LIST ================= -->
 <!-- ================= PRODUK LIST ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">

<?php while ($row = mysqli_fetch_assoc($query)) { ?>

<?php
$gambar = $row['gambar']
    ? '../uploads/' . $row['gambar']
    : 'https://cdn-icons-png.flaticon.com/512/2847/2847978.png';
?>

<div class="group bg-white rounded-3xl p-6 shadow
            transition-all duration-300
            hover:-translate-y-2 hover:shadow-2xl">

    <!-- GAMBAR -->
    <div class="relative w-full h-48 mb-5
                bg-gray-50 rounded-2xl
                flex items-center justify-center overflow-hidden">

        <img src="<?= $gambar ?>"
             class="max-h-full max-w-full object-contain
                    transition duration-300 group-hover:scale-110">

        <!-- BADGE STOK -->
        <span class="absolute top-3 right-3
                     px-3 py-1 text-xs font-bold rounded-full
                     <?= $row['stok'] > 0 
                        ? 'bg-green-100 text-green-700' 
                        : 'bg-red-100 text-red-600' ?>">
            <?= $row['stok'] > 0 ? 'Stok ' . $row['stok'] : 'Habis' ?>
        </span>
    </div>

    <!-- INFO -->
    <h3 class="font-semibold text-lg text-gray-800 text-center line-clamp-2">
        <?= htmlspecialchars($row['nama_produk']) ?>
    </h3>

    <p class="text-center text-teal-600 font-extrabold text-xl mt-2">
        Rp <?= number_format($row['harga']) ?>
    </p>


<!-- AKSI -->
<div class="flex justify-center gap-2 mt-5 flex-wrap">

    <a href="detail_produk.php?id=<?= $row['id'] ?>"
       class="flex items-center gap-1
              px-3 py-1.5 text-xs font-semibold
              rounded-full bg-blue-500 text-white
              hover:bg-blue-600 transition">
        👁️ Detail
    </a>

    <?php if ($row['stok'] > 0) { ?>
        <a href="keranjang_tambah.php?id=<?= $row['id'] ?>"
           onclick="return confirm('Tambah produk ke keranjang?')"
           class="flex items-center gap-1
                  px-3 py-1.5 text-xs font-semibold
                  rounded-full bg-teal-500 text-white
                  hover:bg-teal-600 transition">
            🛒 Keranjang
        </a>
    <?php } ?>

    <!-- 💬 CHAT PENJUAL -->
   <a href="../chat_app.php?lawan_id=<?= $row['penjual_id'] ?>"
   class="...">💬 Chat</a>
</div>

</div>

<?php } ?>

</div>


    </main>
</div>

</body>
</html>
