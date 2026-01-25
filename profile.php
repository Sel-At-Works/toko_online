<?php
session_start();

/* ================== CEK LOGIN ================== */
if (!isset($_SESSION['user'])) {
    header("Location: /toko_online/login.php");
    exit;
}

$user = $_SESSION['user'];

/* ================== DASHBOARD SESUAI ROLE ================== */
$baseUrl = '/toko_online/';

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
        break;
}

/* ================== FOTO PROFILE ================== */
$foto = !empty($user['foto'])
    ? $baseUrl . $user['foto']
    : $baseUrl . 'uploads/profile/default.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-teal-100 to-teal-200 min-h-screen flex items-center justify-center">

<div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 relative">

    <!-- AVATAR -->
    <div class="flex justify-center -mt-20 mb-4">
        <img src="<?= htmlspecialchars($foto) ?>"
             class="w-28 h-28 rounded-full border-4 border-white shadow-md object-cover"
             alt="Avatar">
    </div>

    <!-- TITLE -->
    <h2 class="text-center text-2xl font-bold text-gray-800 mb-1">
        Profile Saya
    </h2>

    <p class="text-center text-gray-500 mb-6">
        <?= ucwords(str_replace('_', ' ', htmlspecialchars($user['role']))) ?>
    </p>

    <!-- INFO -->
    <div class="space-y-4 text-gray-700 text-sm">

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Nama</span>
            <span><?= htmlspecialchars($user['nama']) ?></span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Email</span>
            <span><?= htmlspecialchars($user['email']) ?></span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Alamat</span>
            <span class="text-right max-w-[220px]">
                <?= htmlspecialchars($user['alamat'] ?? '-') ?>
            </span>
        </div>

        <div class="flex justify-between">
            <span class="font-semibold">Role</span>
            <span class="capitalize text-teal-600 font-semibold">
                <?= ucwords(str_replace('_', ' ', htmlspecialchars($user['role']))) ?>
            </span>
        </div>

    </div>

    <!-- ACTION -->
    <div class="mt-8 flex justify-between items-center">

        <a href="<?= htmlspecialchars($dashboardUrl) ?>"
           class="text-gray-500 hover:text-teal-600 font-semibold transition">
            ← Kembali ke Dashboard
        </a>

        <a href="edit_profile.php"
           class="bg-teal-600 text-white px-5 py-2 rounded-full
                  hover:bg-teal-700 transition font-semibold shadow">
            Edit Profile
        </a>

    </div>

</div>

</body>
</html>
