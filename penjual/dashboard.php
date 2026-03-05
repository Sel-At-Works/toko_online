<?php
session_start();
include '../config/koneksi.php'; // ⬅️ confiig koneksi

$currentPage = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'penjual') {
  header("Location: ../login.php");
  exit;
}

// AMBIL ID USER
$user_id = $_SESSION['user']['id']; 

date_default_timezone_set('Asia/Jakarta');

// Array nama hari & bulan Indonesia
$hariIndo = [
  'Sunday'    => 'Minggu',
  'Monday'    => 'Senin',
  'Tuesday'   => 'Selasa',
  'Wednesday' => 'Rabu',
  'Thursday'  => 'Kamis',
  'Friday'    => 'Jumat',
  'Saturday'  => 'Sabtu'
];

$bulanIndo = [
  'January'   => 'Januari',
  'February'  => 'Februari',
  'March'     => 'Maret',
  'April'     => 'April',
  'May'       => 'Mei',
  'June'      => 'Juni',
  'July'      => 'Juli',
  'August'    => 'Agustus',
  'September' => 'September',
  'October'   => 'Oktober',
  'November'  => 'November',
  'December'  => 'Desember'
];

// Ambil tanggal sekarang
$hari   = $hariIndo[date('l')];
$tanggal = date('d');
$bulan  = $bulanIndo[date('F')];
$tahun  = date('Y');

$search = $_GET['search'] ?? '';

$queryProduk = mysqli_query($conn, "
    SELECT p.*
    FROM produk p
    WHERE p.penjual_id = $user_id
    AND p.is_active = 1
    AND p.nama_produk LIKE '%$search%'
    ORDER BY p.id DESC
    LIMIT 4
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Dashboard - Sari Anggrek</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans h-screen overflow-hidden">

<div class="flex h-screen">


    <!-- SIDEBAR -->
    <?php include '../layouts/sidebar_penjual.php'; ?>

    <!-- MAIN CONTENT -->
<main class="flex-1 p-8 grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-8 h-full overflow-hidden">



      <!-- ================= LEFT CONTENT ================= -->
    <section class="overflow-y-auto pr-2">

        <!-- Top Bar Profile & Notification-->
        <div class="flex items-center gap-4 mb-8">

          <!-- SEARCH -->
          <form method="GET" class="flex-1 relative">
            <input
              type="text"
              name="search"
              value="<?= $_GET['search'] ?? '' ?>"
              placeholder="Search Your Book"
              class="w-full px-12 py-3 rounded-full bg-white shadow focus:outline-none" />
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
          </form>

          <!-- PROFILE & NOTIFICATION -->
          <?php include '../layouts/profil_notifikasi.php'; ?>

        </div>


        <!-- BANNER -->
        <div class="bg-gradient-to-r from-teal-400 to-teal-600 rounded-3xl p-8
                  flex justify-between items-center text-white mb-10">
          <div>
            <h2 class="text-3xl font-bold leading-snug mb-4">
              Cahaya Nusantara<br>
              selalu didepan<br>
              melayani kebutuhan<br>
              anda
            </h2>

            <div class="flex gap-4">
              <button class="bg-white text-teal-600 px-5 py-2 rounded-full font-semibold">
                Get Started
              </button>
              <button class="border border-white px-5 py-2 rounded-full">
                Learn More
              </button>
            </div>
          </div>

          <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png"
            class="w-40 hidden md:block">
        </div>


        <!-- BOOK LIST -->
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">Your Book</h3>
          <a href="produk.php"
            class="bg-teal-500 text-white px-4 py-1 rounded-full text-sm hover:bg-teal-600 transition">
            See All
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
      <img
        src="../uploads/<?= htmlspecialchars($p['gambar']); ?>"
        class="max-h-full max-w-full object-contain
               transition-transform duration-500
               group-hover:scale-110"
        alt="<?= htmlspecialchars($p['nama_produk']); ?>">
    <?php else: ?>
      <span class="text-gray-400 text-sm">No Image</span>
    <?php endif; ?>

    <!-- BADGE -->
    <span class="absolute top-3 left-3 
                 bg-teal-500 text-white text-xs font-bold 
                 px-3 py-1 rounded-full shadow">
      PRODUK
    </span>
  </div>

  <!-- CONTENT -->
  <div class="p-4 text-center">

    <h3 class="font-semibold text-gray-800 
               line-clamp-2 min-h-[48px]">
      <?= htmlspecialchars($p['nama_produk']); ?>
    </h3>

    <p class="text-teal-600 font-extrabold text-lg mt-2">
      Rp <?= number_format($p['harga'],0,',','.'); ?>
    </p>

    <!-- ACTION -->
    <div class="mt-4 flex justify-center gap-2 opacity-0 
                group-hover:opacity-100 transition-all duration-300">

      <a href="edit_produk.php?id=<?= $p['id'] ?>"
         class="px-3 py-1.5 text-xs font-semibold rounded-full 
                bg-blue-500 text-white hover:bg-blue-600 transition">
        ✏️ Edit
      </a>

      <a href="hapus_produk.php?id=<?= $p['id'] ?>"
         onclick="return confirm('Yakin hapus produk ini?')"
         class="px-3 py-1.5 text-xs font-semibold rounded-full 
                bg-red-500 text-white hover:bg-red-600 transition">
        🗑 Hapus
      </a>
    </div>

  </div>
</div>

    <?php endwhile; ?>
  <?php else: ?>
    <div class="text-gray-500 col-span-full">
      Belum ada kategori
    </div>
  <?php endif; ?>

</div>


      </section>

      <!-- ================= RIGHT PANEL ================= -->
     <aside class="space-y-6 overflow-y-auto pr-2">



        <!-- CALENDAR -->
        <div class="bg-gradient-to-b from-teal-400 to-teal-600 text-white rounded-2xl p-6 text-center">
          <p class="text-sm"><?= $tahun . ' ' . $bulan; ?></p>
          <p class="text-4xl font-bold my-2"><?= $tanggal; ?></p>
          <p class="text-sm"><?= $hari; ?></p>
        </div>


        <!-- PESANAN MASUK -->
      <div class="bg-white rounded-2xl p-6 shadow space-y-4">
    <h4 class="font-semibold">Pesanan Masuk</h4>

    <?php
    $queryPesanan = mysqli_query($conn, "
    SELECT 
        tp.transaksi_id, 
        t.total, 
        t.created_at, 
        t.pembeli_id,
        u.nama AS nama_pembeli
    FROM transaksi_penjual tp
    JOIN transaksi t ON tp.transaksi_id = t.id
    JOIN users u ON t.pembeli_id = u.id
    WHERE tp.penjual_id = $user_id
    ORDER BY t.created_at DESC
");
    if (mysqli_num_rows($queryPesanan) > 0):
        while ($pesanan = mysqli_fetch_assoc($queryPesanan)):
    ?>
        <div class="bg-gray-100 rounded-xl p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold"><?= htmlspecialchars($pesanan['nama_pembeli']) ?></p>
                <p class="text-sm text-gray-500">Rp <?= number_format($pesanan['total'],0,',','.') ?></p>
            </div>
  <a href="../chat_app.php?lawan_id=<?= $pesanan['pembeli_id'] ?>&transaksi_id=<?= $pesanan['transaksi_id'] ?>"
   class="px-3 py-1 bg-blue-500 text-white rounded text-xs">
   Chat
</a>


        </div>
    <?php
        endwhile;
    else:
    ?>
        <p class="text-gray-500">Belum ada pesanan masuk</p>
    <?php endif; ?>
</div>


        <!-- MESSAGE -->
        <div class="bg-teal-500 text-white rounded-2xl p-6">
          <p class="font-semibold mb-2">Message</p>
          <p class="text-sm">
            Saya sudah memesan buku php,<br>
            ditunggu ya TL nya 🙏
          </p>
          <p class="text-xs mt-2 opacity-80">Husni · 1 Minute Ago</p>
        </div>

      </aside>

    </main>

  </div>

</body>

</html>