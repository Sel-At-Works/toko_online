<?php
session_start();

// kalau belum login → balik ke login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sari Anggrek</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <?php include '../layouts/sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-10 overflow-y-auto">

<!-- TOP BAR -->
<div class="flex justify-end mb-8">
  <?php include '../layouts/profil_notifikasi.php'; ?>
</div>
 


            <!-- BANNER -->
            <div class="bg-gradient-to-r from-teal-400 to-teal-600 rounded-3xl p-10 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold leading-snug">
                        Cahaya Nusantara<br>
                        selalu Memenuhi<br>
                        Dan Melayani Anda<br>
                        
                    </h2>
                </div>

                <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png"
                    class="w-36"
                    alt="Books">
            </div>
            <!-- ⬆️ BANNER SELESAI DI SINI -->


            <!-- SECTION KALENDER + MY ACCOUNT -->
            <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                <!-- ================= CARD KALENDER ================= -->
                <?php
                date_default_timezone_set('Asia/Jakarta');

                $bulan = date('n');
                $tahun = date('Y');
                $tanggal_hari_ini = date('j');

                $nama_bulan = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];

                $hari_awal = date('N', strtotime("$tahun-$bulan-01"));
                $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                ?>

                <div class="bg-white rounded-2xl shadow-md p-6 w-full">
                    <h4 class="text-center font-bold text-lg mb-4 text-gray-700">
                        <?= $nama_bulan[$bulan] . ' ' . $tahun ?>
                    </h4>

                    <div class="grid grid-cols-7 text-center text-sm font-semibold text-gray-500 mb-2">
                        <div>Sen</div>
                        <div>Sel</div>
                        <div>Rab</div>
                        <div>Kam</div>
                        <div>Jum</div>
                        <div>Sab</div>
                        <div>Min</div>
                    </div>

                    <div class="grid grid-cols-7 gap-2 text-center text-sm">
                        <?php for ($i = 1; $i < $hari_awal; $i++): ?>
                            <div></div>
                        <?php endfor; ?>

                        <?php for ($tgl = 1; $tgl <= $jumlah_hari; $tgl++): ?>
                            <div class="py-2 rounded-lg <?= ($tgl == $tanggal_hari_ini)
                                                            ? 'bg-teal-500 text-white font-bold'
                                                            : 'bg-gray-100 text-gray-700' ?>">
                                <?= $tgl ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- ================= CARD MY ACCOUNT ================= -->
                <div class="bg-gradient-to-br from-teal-400 to-teal-600 
                rounded-3xl p-8 text-white relative shadow-md w-full">

                    <h4 class="text-center text-lg font-semibold mb-6">
                        My Account
                    </h4>

                   <p><span class="font-semibold">Nama</span> : <?= $user['nama']; ?></p>
<p><span class="font-semibold">Level</span> : <?= ucwords(str_replace('_', ' ', $user['role'])); ?></p>
<p><span class="font-semibold">Email</span> : <?= $user['email']; ?></p>


                    <button class="absolute bottom-4 right-4 
                       bg-white text-teal-600 px-4 py-1.5 rounded-full shadow
                       flex items-center gap-2 text-sm font-semibold
                       hover:bg-gray-100 transition">
                        ✎ Edit
                    </button>
                </div>

            </div>
            <!-- <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png"
                class="w-36"
                alt="Books"> -->
    </div>

    </main>

    </div>

</body>

</html>