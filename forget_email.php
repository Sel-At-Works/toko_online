<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Hilangkan background putih autofill -->
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px transparent inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
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
            <h1 class="text-3xl font-bold mb-4">Forgot Password</h1>

            <p class="text-sm mb-10 text-white/90">
                Masukkan email yang terdaftar untuk mengatur ulang password Anda.
            </p>

            <form action="proses_forgot_password.php" method="POST" class="space-y-8">
                <div>
                    <label class="text-sm">Email Address</label>
                    <input type="email" name="email" required
                        class="w-full bg-transparent border-b-2 border-white outline-none py-2 text-white focus:outline-none">
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-xl text-lg font-semibold transition">
                    Cek Email
                </button>
            </form>

            <a href="login.php"
                class="text-center mt-8 text-sm hover:underline">
                Kembali ke Login
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if (isset($_SESSION['alert'])) {
    $a = $_SESSION['alert'];
    echo "
    <script>
        Swal.fire({
            title: ".json_encode($a['title']).",
            text: ".json_encode($a['text']).",
            icon: ".json_encode($a['icon']).",
            confirmButtonColor: '#16a34a'
        });
    </script>
    ";
    unset($_SESSION['alert']);
}
?>

</body>

</html>