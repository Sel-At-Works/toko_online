<?php
session_start();
include '../config/koneksi.php'; // ⬅️ confiig koneksi

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'penjual') {
  header("Location: ../login.php");
  exit;
}

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

// ambil kategori (misalnya dibatasi 5)
$queryKategori = mysqli_query($conn, "
    SELECT * FROM kategori
    ORDER BY id DESC
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
          <div class="flex-1 relative">
            <input
              type="text"
              placeholder="Search Here"
              class="w-full px-12 py-3 rounded-full bg-white shadow focus:outline-none" />
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
          </div>

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

        <!-- GAMBAR (AMAN UNTUK PERSEGI & LANDSCAPE) -->
        <div class="h-40 bg-gray-100 flex items-center justify-center p-3">

          <?php if (!empty($kat['gambar'])): ?>
            <img
              src="../uploads/kategori/<?= htmlspecialchars($kat['gambar']); ?>"
              class="max-h-full max-w-full object-contain"
              alt="<?= htmlspecialchars($kat['nama_kategori']); ?>">
          <?php else: ?>
            <span class="text-gray-400 text-sm">No Image</span>
          <?php endif; ?>

        </div>

        <!-- NAMA KATEGORI -->
        <div class="p-3 text-center border-t">
          <p class="font-semibold text-gray-700">
            <?= htmlspecialchars($kat['nama_kategori']); ?>
          </p>
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

          <div class="bg-teal-100 rounded-xl p-4">
            <p class="font-semibold">Sains (2)</p>
            <p class="text-sm text-gray-500">andini</p>
          </div>

          <div class="bg-gray-100 rounded-xl p-4">
            <p class="font-semibold">IPAS (3)</p>
            <p class="text-sm text-gray-500">Dara Herdiati</p>
          </div>

          <div class="bg-gray-100 rounded-xl p-4">
            <p class="font-semibold">PHP (1)</p>
            <p class="text-sm text-gray-500">Husni</p>
          </div>
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