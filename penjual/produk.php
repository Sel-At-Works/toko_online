<?php
session_start();
include '../config/koneksi.php';

// pastikan penjual login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// ambil id penjual yang login
$penjual_id = $_SESSION['user_id'];

// ================== AMBIL KATEGORI ==================
$kategori = mysqli_query($conn, "
    SELECT * FROM kategori
    WHERE penjual_id = $penjual_id OR penjual_id IS NULL
    ORDER BY nama_kategori ASC
");

// ================== SEARCH ==================
$search = $_GET['search'] ?? '';
$search_safe = mysqli_real_escape_string($conn, $search);

$where_search = '';
if (!empty($search)) {
    $search_safe = strtolower($search_safe);
    $where_search = "AND (
        LOWER(p.nama_produk) LIKE '%$search_safe%' 
        OR LOWER(p.deskripsi) LIKE '%$search_safe%'
    )";
}

// ================== KATEGORI YANG DIPILIH ==================
$kategori_id = $_GET['kategori_id'] ?? '';
$where_kategori = $kategori_id ? "AND p.kategori_id = '".intval($kategori_id)."'" : "";

// ================== AMBIL PRODUK ==================
$query = mysqli_query($conn, "
    SELECT p.*
    FROM produk p
    JOIN kategori k ON p.kategori_id = k.id
    WHERE p.penjual_id = $penjual_id
    AND (k.penjual_id = $penjual_id OR k.penjual_id IS NULL)
    AND p.is_active = 1
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


    <!-- SIDEBAR -->
    <?php include '../layouts/sidebar_penjual.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8 overflow-y-auto">


        <!-- TOP BAR -->
        <div class="flex items-center justify-between mb-10">

            <!-- SEARCH (UI ONLY) -->
         <form method="GET" class="flex items-center gap-4 flex-1 max-w-3xl">

    <!-- PERTAHANKAN FILTER KATEGORI -->
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


            <!-- PROFILE -->
            <?php include '../layouts/profil_notifikasi.php'; ?>
        </div>

        <!-- TITLE -->
        <div class="relative flex items-center justify-end mb-10">
            <h1 class="absolute left-1/2 -translate-x-1/2 text-4xl font-bold tracking-wide">
                PRODUK
            </h1>

            <form action="tambah_produk.php" method="GET">
                <button type="submit"
                        class="flex items-center gap-2 bg-teal-500 text-white px-4 py-2 rounded-full font-semibold hover:bg-teal-600 transition">
                    <span class="text-2xl">+</span>
                    <span>Tambah Produk</span>
                </button>
            </form>
        </div>
        
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
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

<?php while ($row = mysqli_fetch_assoc($query)) { ?>

    <?php
    // ================= HITUNG MARGIN =================
    $margin = $row['harga'] - $row['harga_modal'];

    $margin_persen = ($row['harga_modal'] > 0)
        ? round(($margin / $row['harga_modal']) * 100)
        : 0;

    $gambar = $row['gambar']
        ? '../uploads/' . $row['gambar']
        : 'https://cdn-icons-png.flaticon.com/512/2847/2847978.png';
    ?>

    <div class="group bg-gradient-to-b from-teal-400 to-teal-600
                rounded-[36px] p-6 text-black relative
                transition-all duration-300 ease-out
                hover:-translate-y-2 hover:shadow-2xl
                hover:scale-[1.02]">

        <!-- GAMBAR -->
        <div class="w-32 h-32 mx-auto mb-6 bg-white/90
                    rounded-2xl border border-teal-100
                    shadow-inner
                    flex items-center justify-center
                    overflow-hidden">
            <img src="<?= $gambar ?>"
                 class="max-w-full max-h-full object-contain">
        </div>

        <!-- INFO -->
       <?php
$margin = $row['harga'] - $row['harga_modal'];
$margin_persen = ($row['harga_modal'] > 0)
    ? round(($margin / $row['harga_modal']) * 100)
    : 0;
?>

<p class="font-semibold text-lg"><?= $row['nama_produk'] ?></p>

<p class="text-sm opacity-90">
    Stok : <?= $row['stok'] ?>
</p>

<p class="font-bold mt-1">
    Rp <?= number_format($row['harga']) ?>
</p>

<span class="inline-block mt-3 px-4 py-1 rounded-full
font-extrabold text-sm
<?= $margin >= 0 
    ? 'bg-green-100 text-green-800' 
    : 'bg-red-100 text-red-800' ?>">
    Margin: Rp <?= number_format($margin) ?> (<?= $margin_persen ?>%)
</span>



        <!-- AKSI -->
        <div class="flex justify-center gap-5 mt-6 text-white">
            <a href="edit_produk.php?id=<?= $row['id'] ?>"
               class="p-2 rounded-full bg-white/20 hover:bg-yellow-400">✏️</a>

            <a href="detail_produk.php?id=<?= $row['id'] ?>"
               class="p-2 rounded-full bg-white/20 hover:bg-blue-400">👁️</a>

            <?php if ($row['stok'] > 0): ?>

            <a href="#"
            onclick="alert('Produk tidak bisa dihapus karena stok masih ada'); return false;"
            class="p-2 rounded-full bg-white/20 opacity-40 cursor-not-allowed">
            🗑️
            </a>

            <?php else: ?>

            <a href="hapus_produk.php?id=<?= $row['id'] ?>"
            onclick="return confirm('Yakin ingin menghapus produk ini?')"
            class="p-2 rounded-full bg-white/20 hover:bg-red-500">
            🗑️
            </a>

            <?php endif; ?>
        </div>

    </div>

<?php } ?>

</div>


    </main>
</div>

</body>
</html>
