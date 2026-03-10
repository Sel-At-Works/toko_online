<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<aside class="w-64 bg-white shadow-md flex flex-col justify-between min-h-screen">
    <div>
        <!-- LOGO -->
        <div class="flex items-center gap-3 px-6 py-6">
            <span class="text-4xl font-bold text-teal-500">C</span>
            <h1 class="text-lg font-bold text-teal-600">
                Cahaya<br>Nusantara
            </h1>
        </div>

        <!-- MENU -->
        <nav class="mt-6 space-y-2 px-4">
           <a href="dashboard.php"
   class="block px-5 py-3 rounded-full font-semibold transition-all duration-200
   <?= $currentPage == 'dashboard.php'
      ? 'bg-gradient-to-r from-teal-400 to-teal-600 text-white shadow-lg scale-105'
      : 'text-gray-500 hover:bg-gray-100 hover:text-teal-600 active:scale-95' ?>">
    Dashboard
</a>


    <a href="../penjual/kategori.php"
   class="block px-5 py-3 rounded-full font-semibold transition-all duration-200
   <?= $currentPage == 'kategori.php'
      ? 'bg-gradient-to-r from-teal-400 to-teal-600 text-white shadow-lg scale-105'
      : 'text-gray-500 hover:bg-gray-100 hover:text-teal-600 active:scale-95' ?>">
    Kategori
</a>



          <!-- <a href="../penjual/admin.php"
   class="block px-5 py-3 rounded-full font-semibold transition-all duration-200
   <?= $currentPage == 'admin.php'
      ? 'bg-gradient-to-r from-teal-400 to-teal-600 text-white shadow-lg scale-105'
      : 'text-gray-500 hover:bg-gray-100 hover:text-teal-600 active:scale-95' ?>">
    Admin
</a> -->

          <a href="../penjual/pembeli.php"
   class="block px-5 py-3 rounded-full font-semibold transition-all duration-200
   <?= $currentPage == 'pembeli.php'
      ? 'bg-gradient-to-r from-teal-400 to-teal-600 text-white shadow-lg scale-105'
      : 'text-gray-500 hover:bg-gray-100 hover:text-teal-600 active:scale-95' ?>">
    pembeli
</a>

      <a href="../penjual/produk.php"
   class="block px-5 py-3 rounded-full font-semibold transition-all duration-200
   <?= $currentPage == 'produk.php'
      ? 'bg-gradient-to-r from-teal-400 to-teal-600 text-white shadow-lg scale-105'
      : 'text-gray-500 hover:bg-gray-100 hover:text-teal-600 active:scale-95' ?>">
    Produk
</a>


      <a href="../penjual/approve.php"
   class="block px-5 py-3 rounded-full font-semibold transition-all duration-200
   <?= $currentPage == 'approve.php'
      ? 'bg-gradient-to-r from-teal-400 to-teal-600 text-white shadow-lg scale-105'
      : 'text-gray-500 hover:bg-gray-100 hover:text-teal-600 active:scale-95' ?>">
    Approve
</a>


      <a href="../penjual/laporan_penjual.php"
   class="block px-5 py-3 rounded-full font-semibold transition-all duration-200
   <?= $currentPage == 'laporan_penjual.php'
      ? 'bg-gradient-to-r from-teal-400 to-teal-600 text-white shadow-lg scale-105'
      : 'text-gray-500 hover:bg-gray-100 hover:text-teal-600 active:scale-95' ?>">
    laporan
</a>

   <a href="../chat_app.php"
   class="block px-5 py-3 rounded-full font-semibold transition-all duration-200
   <?= $currentPage == 'chat_app.php'
      ? 'bg-gradient-to-r from-teal-400 to-teal-600 text-white shadow-lg scale-105'
      : 'text-gray-500 hover:bg-gray-100 hover:text-teal-600 active:scale-95' ?>">
    Chat
</a>




        </nav>
    </div>

    <!-- FOOTER SIDEBAR -->
    <div class="px-6 pb-6 space-y-4 text-gray-500 text-sm">
        <a href="../logout.php"
           class="flex items-center gap-2 transition-all duration-200
                  hover:text-red-500 active:scale-95">
            ⎋ <span>Sign Out</span>
        </a>

    <div onclick="window.location.href='/help.php'"
        class="flex items-center gap-2 cursor-pointer transition-all duration-200
                hover:text-teal-600 active:scale-95">
        ? <span>Help</span>
    </div>
    </div>
</aside>
