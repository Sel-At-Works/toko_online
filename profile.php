<?php
session_start();

/* ================== CEK LOGIN ================== */
if (!isset($_SESSION['user'])) {
    header("Location: /toko_online/login.php");
    exit;
}

include 'config/koneksi.php';

$user = $_SESSION['user'];
$uid  = (int) $user['id'];

/* ================== PROFILE PEMBELI ================== */
$profilPembeli = null;
if ($user['role'] === 'pembeli') {
    $profilPembeli = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT no_telepon
        FROM pembeli_profile
        WHERE user_id = $uid
    "));
}

/* ================== PROFILE PENJUAL ================== */
$profilPenjual = null;
if ($user['role'] === 'penjual') {
    $profilPenjual = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT bank, no_rekening, qris
        FROM penjual_profile
        WHERE user_id = $uid
    "));
}

/* ================== DASHBOARD SESUAI ROLE ================== */
$baseUrl = '/';

switch ($user['role']) {
    case 'super_admin':
        $dashboardUrl = $baseUrl . 'super_admin/dashboard.php';
        break;
    case 'penjual':
        $dashboardUrl = $baseUrl . 'penjual/dashboard.php';
        break;
    case 'pembeli':
        $dashboardUrl = $baseUrl . 'pembeli/dashboard.php';
        break;
    default:
        $dashboardUrl = $baseUrl . 'login.php';
}

/* ================== FOTO PROFILE ================== */
$foto = !empty($user['foto']) ? $baseUrl . $user['foto'] : null;

/* ===== INISIAL USER ===== */
$nama = trim($user['nama']);
$inisial = '';
$namaArr = explode(' ', $nama);

if (count($namaArr) >= 2) {
    $inisial = strtoupper(substr($namaArr[0],0,1) . substr($namaArr[1],0,1));
} else {
    $inisial = strtoupper(substr($namaArr[0],0,2));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen flex items-center justify-center px-6">

<div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl overflow-hidden grid grid-cols-1 md:grid-cols-3">

<!-- LEFT -->
<div class="bg-gradient-to-b from-teal-600 to-emerald-500 p-8 text-white
            flex flex-col items-center justify-center gap-4">

<?php if ($foto): ?>

    <!-- FOTO ASLI -->
    <img src="<?= htmlspecialchars($foto) ?>"
         class="w-28 h-28 rounded-full border-4 border-white shadow-lg
                object-cover bg-white">

<?php else: ?>

    <!-- INISIAL -->
    <div class="w-28 h-28 rounded-full border-4 border-white shadow-lg
                bg-white flex items-center justify-center
                text-3xl font-extrabold text-teal-600">
        <?= $inisial ?>
    </div>

<?php endif; ?>

    <h2 class="text-xl font-bold text-center">
        <?= htmlspecialchars($user['nama']) ?>
    </h2>

    <p class="text-teal-100 text-sm">
        <?= ucwords(str_replace('_', ' ', $user['role'])) ?>
    </p>

    <a href="edit_profile.php"
       class="mt-4 bg-white text-teal-600 px-6 py-2 rounded-full
              font-semibold shadow hover:bg-teal-50 transition">
        Edit Profil
    </a>

</div>


<!-- RIGHT -->
<div class="md:col-span-2 p-10">

<h3 class="text-2xl font-bold text-gray-800 mb-6">Informasi Akun</h3>

<!-- GENERAL INFO -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">

    <div>
        <p class="text-gray-500">Role</p>
        <p class="font-semibold text-teal-600">
            <?= ucwords(str_replace('_', ' ', $user['role'])) ?>
        </p>
    </div>

    <div>
        <p class="text-gray-500">Email</p>
        <p class="font-semibold"><?= htmlspecialchars($user['email']) ?></p>
    </div>

    <div class="md:col-span-2">
        <p class="text-gray-500">Alamat</p>
        <p><?= htmlspecialchars($user['alamat'] ?? '-') ?></p>
    </div>

    <?php if ($user['role'] === 'pembeli'): ?>
    <div>
        <p class="text-gray-500">Nomor Telepon</p>
        <p class="font-semibold">
            <?= htmlspecialchars($profilPembeli['no_telepon'] ?? '-') ?>
        </p>
    </div>
    <?php endif; ?>

</div>

<!-- DATA PENJUAL -->
<?php if ($user['role'] === 'penjual'): ?>
<div class="mt-10">

<div class="flex items-center justify-between mb-4">
    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
        <span class="bg-teal-100 text-teal-600 p-2 rounded-lg">💳</span>
        Data Pembayaran Penjual
    </h3>

    <a href="/penjual/lengkapi_profil_penjual.php"
       class="text-teal-600 font-semibold hover:underline">
        Edit
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 rounded-2xl p-6 border text-sm">

    <div>
        <p class="text-gray-500">Nama Bank</p>
        <p class="font-semibold"><?= $profilPenjual['bank'] ?? '-' ?></p>
    </div>

    <div>
        <p class="text-gray-500">No Rekening</p>
        <p class="font-mono font-semibold"><?= $profilPenjual['no_rekening'] ?? '-' ?></p>
    </div>

    <div class="md:col-span-2">
        <p class="text-gray-500 mb-3">QRIS</p>

        <?php if (!empty($profilPenjual['qris'])): ?>
            <img src="/<?= $profilPenjual['qris'] ?>"
                 onclick="openQrisModal(this.src)"
                 class="w-28 cursor-pointer rounded-lg border hover:scale-105 transition">
        <?php else: ?>
            <span class="italic text-gray-400">QRIS belum diunggah</span>
        <?php endif; ?>
    </div>

</div>
</div>
<?php endif; ?>

<div class="mt-10">
    <a href="<?= $dashboardUrl ?>"
       class="text-gray-500 hover:text-teal-600 font-medium">
        ← Kembali ke Dashboard
    </a>
</div>

</div>
</div>

<!-- MODAL -->
<div id="qrisModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
<div class="bg-white p-4 rounded-xl relative">
    <button onclick="closeQrisModal()"
            class="absolute -top-3 -right-3 bg-red-500 text-white w-8 h-8 rounded-full">✕</button>
    <img id="qrisModalImg" class="rounded-xl w-80">
</div>
</div>

<script>
function openQrisModal(src){
    document.getElementById('qrisModalImg').src = src;
    document.getElementById('qrisModal').classList.remove('hidden');
    document.getElementById('qrisModal').classList.add('flex');
}
function closeQrisModal(){
    document.getElementById('qrisModal').classList.add('hidden');
}
</script>

</body>
</html>
