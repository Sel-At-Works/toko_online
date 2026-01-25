<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 to-blue-100">

    <div class="w-[90%] max-w-6xl h-[600px] bg-white rounded-2xl overflow-hidden flex shadow-lg">

        <!-- LEFT -->
        <div class="w-1/2 p-10 flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold text-green-500">Cahaya Nusantara</h2>
                <span class="text-gray-500 text-sm">Toko Buku</span>
            </div>

            <div class="flex justify-center">
                <img src="gambar/logo toko online.png"
                     class="w-[380px] max-w-full"
                     alt="Ilustrasi">
            </div>
        </div>

        <!-- RIGHT -->
        <div class="w-1/2 bg-gradient-to-b from-green-300 to-green-500 p-14 text-white flex flex-col justify-center">

            <h1 class="text-3xl font-bold mb-6">Reset Password</h1>

            <p class="text-sm mb-8 text-white/90">
                Silakan masukkan password baru Anda.
            </p>

            <form action="proses_reset_password.php" method="POST" class="space-y-6">

                <!-- TOKEN -->
                <input type="hidden" name="token"
                       value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

                <div>
                    <label class="text-sm">Password Baru</label>
                    <input type="password" name="password" required
                        class="w-full bg-transparent border-b-2 border-white outline-none py-2 text-white">
                </div>

                <div>
                    <label class="text-sm">Konfirmasi Password</label>
                    <input type="password" name="password_confirm" required
                        class="w-full bg-transparent border-b-2 border-white outline-none py-2 text-white">
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-xl
                           text-lg font-semibold transition">
                    Simpan Password
                </button>

            </form>

            <a href="login.php"
               class="text-center mt-8 text-sm hover:underline">
                Kembali ke Login
            </a>

        </div>

    </div>

</body>
</html>
