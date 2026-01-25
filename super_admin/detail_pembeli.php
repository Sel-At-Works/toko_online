<?php
include '../config/koneksi.php';

/* ================= VALIDASI ID ================= */
$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID pembeli tidak ditemukan di URL');
}

/* ================= AMBIL DATA PEMBELI ================= */
/* role_id contoh:
   1 = super_admin
   2 = penjual
   3 = pembeli
*/
$query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id = '$id' AND role_id = 3
");

if (!$query || mysqli_num_rows($query) === 0) {
    die('Data pembeli tidak ditemukan');
}

$data = mysqli_fetch_assoc($query);

/* ================= FOTO ================= */
$ada_foto = false;
$path_url = '';

if (!empty($data['foto'])) {
    $path_server = $_SERVER['DOCUMENT_ROOT'] . '/toko_online/' . $data['foto'];
    $path_url    = '/toko_online/' . $data['foto'];

    if (file_exists($path_server)) {
        $ada_foto = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pembeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">

<div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl overflow-hidden">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 p-6 text-white">
        <h2 class="text-2xl font-bold">Detail Pembeli</h2>
        <p class="text-sm opacity-90">Informasi lengkap akun pembeli</p>
    </div>

    <!-- CONTENT -->
    <div class="p-8">

        <!-- FOTO -->
        <div class="flex justify-center -mt-20 mb-6">
            <div class="w-32 h-32 rounded-full bg-white p-1 shadow-lg">
                <div class="w-full h-full rounded-full overflow-hidden border-4 border-blue-500">
                    <?php if ($ada_foto): ?>
                        <img src="<?= $path_url ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- NAMA -->
        <div class="text-center mb-8">
            <h3 class="text-xl font-semibold"><?= $data['nama'] ?></h3>
            <span class="inline-block mt-2 px-4 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                Pembeli
            </span>
        </div>

        <!-- DATA GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-gray-500">NIK</p>
                <p class="font-semibold text-gray-800"><?= $data['nik'] ?></p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-gray-500">Email</p>
                <p class="font-semibold text-gray-800"><?= $data['email'] ?></p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 md:col-span-2">
                <p class="text-gray-500">Alamat</p>
                <p class="font-semibold text-gray-800">
                    <?= $data['alamat'] ?: '-' ?>
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-gray-500">Tanggal Daftar</p>
                <p class="font-semibold text-gray-800">
                    <?= date('d M Y', strtotime($data['created_at'])) ?>
                </p>
            </div>

        </div>

        <!-- ACTION -->
        <div class="flex justify-between mt-10">
            <a href="pembeli.php"
               class="px-6 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 transition">
                ← Kembali
            </a>

            <a href="edit_pembeli.php?id=<?= $data['id'] ?>"
               class="px-6 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition shadow">
                ✏️ Edit Pembeli
            </a>
        </div>

    </div>
</div>

</body>
</html>
