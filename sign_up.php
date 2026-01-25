<?php
include 'config/koneksi.php';

$cek = mysqli_query(
    $conn,
    "SELECT id FROM users WHERE role_id = 1 LIMIT 1"
);

$superAdminAda = mysqli_num_rows($cek) > 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 to-blue-100">

<div class="w-[95%] max-w-6xl bg-white rounded-2xl flex shadow-lg overflow-hidden">

    <!-- LEFT -->
    <div class="w-1/2 p-10 flex flex-col justify-between">
        <div>
            <h2 class="text-2xl font-bold text-green-500">Cahaya Nusantara</h2>
            <span class="text-gray-500 text-sm">Toko Buku</span>
        </div>

        <div class="flex justify-center">
            <img src="gambar/logo toko online.png"
                 class="w-[260px]"
                 alt="Ilustrasi">
        </div>
    </div>

    <!-- RIGHT -->
    <div class="w-1/2 bg-gradient-to-b from-green-300 to-green-500 p-10 text-white">

        <h1 class="text-2xl font-bold mb-6">Sign Up</h1>

        <form action="proses_signup.php" method="POST"
              class="grid grid-cols-2 gap-x-6 gap-y-4">

            <!-- NIK -->
<div>
    <label class="text-sm">NIK</label>
    <input
        type="text"
        name="nik"
        required
        maxlength="16"
        pattern="[0-9]{16}"
        inputmode="numeric"
        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
        placeholder="16 digit NIK"
        class="w-full bg-transparent border-b-2 border-white py-2 outline-none placeholder-white/70"
    >
</div>


            <!-- Nama -->
            <div>
                <label class="text-sm">Nama Lengkap</label>
                <input type="text" name="nama" required
                    class="w-full bg-transparent border-b-2 border-white py-2 outline-none">
            </div>

            <!-- Email -->
            <div>
                <label class="text-sm">Email</label>
                <input type="email" name="email" required
                    class="w-full bg-transparent border-b-2 border-white py-2 outline-none">
            </div>

            <!-- Password -->
            <div>
                <label class="text-sm">Password</label>
                <input type="password" name="password" required
                    class="w-full bg-transparent border-b-2 border-white py-2 outline-none">
            </div>

            <!-- Alamat (full width) -->
            <div class="col-span-2">
                <label class="text-sm">Alamat</label>
                <input type="text" name="alamat" required
                    class="w-full bg-transparent border-b-2 border-white py-2 outline-none">
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="text-sm">Confirm Password</label>
                <input type="password" name="password_confirm" required
                    class="w-full bg-transparent border-b-2 border-white py-2 outline-none">
            </div>

            <!-- Role -->
            <div>
                <label class="text-sm">Daftar Sebagai</label>
                <select name="role" required
                    class="w-full bg-transparent border-b-2 border-white py-2 outline-none text-white">
                    <option value="" class="text-black">-- Pilih Role --</option>
                    <option value="penjual" class="text-black">Penjual</option>
                    <option value="pembeli" class="text-black">Pembeli</option>
                    <?php if (!$superAdminAda): ?>
                        <option value="super_admin" class="text-black">Super Admin</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Button -->
            <div class="col-span-2 mt-4">
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-xl
                           text-lg font-semibold transition">
                    Daftar
                </button>
            </div>
        </form>

        <p class="text-center mt-6 text-sm">
            Sudah punya akun?
            <a href="login.php" class="font-semibold underline">Login</a>
        </p>

    </div>
</div>

</body>
</html>
