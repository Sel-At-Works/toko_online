<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sign In</title>

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

            <!-- GAMBAR -->
            <div class="flex justify-center">
                <img src="gambar/logo toko online.png"
                    class="w-[380px] max-w-full"
                    alt="Ilustrasi">
            </div>
        </div>

        <!-- RIGHT -->
        <div class="w-1/2 bg-gradient-to-b from-green-300 to-green-500 p-14 text-white">
            <h1 class="text-3xl font-bold mb-10">Sign In</h1>

            <form action="proses_login.php" method="POST" class="space-y-6">
                <div>
                    <label class="text-sm">Email Address</label>
                    <input type="email" name="email"
                        class="w-full bg-transparent border-b-2 border-white outline-none py-2 text-white">
                </div>

                <div>
                    <label class="text-sm">Password</label>
                    <input type="password" name="password"
                        class="w-full bg-transparent border-b-2 border-white outline-none py-2 text-white">
                </div>

                <button type="submit" name="login"
                    class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-xl text-lg font-semibold transition">
                    Login
                </button>

                <a href="forget_email.php" class="block text-center text-sm hover:underline">
                    Lupa Password
                </a>
            </form>

            <p class="text-center mt-12 text-sm">
                Already have an account?
                <a href="sign_up.php" class="font-semibold underline">Sign up</a>
            </p>
        </div>

    </div>

</body>

</html>