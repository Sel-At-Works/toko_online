<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8">

    <h2 class="text-2xl font-bold text-center mb-6">Edit Profile</h2>

    <!-- FOTO SAAT INI -->
    <div class="flex justify-center mb-4">
        <img src="<?= $user['foto'] ?? 'uploads/profile/default.png'; ?>"
             class="w-24 h-24 rounded-full object-cover border">
    </div>

    <form action="update_profile.php" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-5">

        <input type="hidden" name="id" value="<?= $user['id']; ?>">

        <!-- FOTO -->
        <div>
            <label class="block text-sm font-semibold mb-1">Foto Profile</label>
            <input type="file" name="foto"
                   accept="image/*"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <!-- NAMA -->
        <div>
            <label class="block text-sm font-semibold mb-1">Nama</label>
            <input type="text" name="nama"
                   value="<?= htmlspecialchars($user['nama']); ?>"
                   class="w-full border rounded-lg px-4 py-2"
                   required>
        </div>

        <!-- EMAIL -->
        <div>
            <label class="block text-sm font-semibold mb-1">Email</label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($user['email']); ?>"
                   class="w-full border rounded-lg px-4 py-2"
                   required>
        </div>

        <!-- ALAMAT -->
        <div>
            <label class="block text-sm font-semibold mb-1">Alamat</label>
            <textarea name="alamat" rows="3"
                      class="w-full border rounded-lg px-4 py-2"><?= htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
        </div>

        <!-- ACTION -->
        <div class="flex justify-between pt-4">
            <a href="profile.php" class="text-gray-500">Batal</a>
            <button class="bg-teal-600 text-white px-6 py-2 rounded-full">
                Simpan
            </button>
        </div>

    </form>
</div>

</body>
</html>
