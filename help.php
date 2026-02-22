<?php
session_start();

/*
ROLE:
pembeli
penjual
super_admin
*/

$role = $_SESSION['user']['role'] ?? 'guest'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pusat Bantuan Sistem</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-100 via-slate-50 to-slate-200 min-h-screen">
    <?php
// ===== DASHBOARD PATH BY ROLE (ROOT HELP) =====
if ($role === 'pembeli') {
    $dashboardLink = '/pembeli/dashboard.php';
} elseif ($role === 'penjual') {
    $dashboardLink = '/penjual/dashboard.php';
} elseif ($role === 'super_admin') {
    $dashboardLink = '/super_admin/dashboard.php';
} else {
    $dashboardLink = '/login.php';
}
?>

<div class="max-w-5xl mx-auto px-4 py-12">
    <!-- BACK BUTTON -->
<div class="mb-6">
<a href="<?= $dashboardLink ?>" 
   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
          bg-white shadow-md hover:shadow-lg transition-all
          text-gray-700 font-semibold hover:bg-teal-50
          hover:text-teal-600 active:scale-95">
    ⬅ Kembali ke Dashboard
</a>
</div>
    
    <!-- HEADER -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal-600 text-white text-3xl shadow-lg mb-4">
            ❓
        </div>
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text 
                   bg-gradient-to-r from-teal-600 to-cyan-500">
            Pusat Bantuan Sistem
        </h1>
        <p class="text-gray-500 mt-3 max-w-xl mx-auto">
            Panduan penggunaan sistem sesuai peran akun Anda
        </p>
    </div>

    <!-- ROLE BADGE -->
    <div class="flex justify-center mb-10">
        <?php if($role === 'pembeli'): ?>
            <div class="px-6 py-2 rounded-full bg-gradient-to-r from-teal-500 to-cyan-500 
                        text-white font-semibold shadow-md flex items-center gap-2">
                👤 Pembeli
            </div>
        <?php endif; ?>

        <?php if($role === 'penjual'): ?>
            <div class="px-6 py-2 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 
                        text-white font-semibold shadow-md flex items-center gap-2">
                🏪 Penjual
            </div>
        <?php endif; ?>

        <?php if($role === 'super_admin'): ?>
            <div class="px-6 py-2 rounded-full bg-gradient-to-r from-rose-500 to-red-500 
                        text-white font-semibold shadow-md flex items-center gap-2">
                🛡 Super Admin
            </div>
        <?php endif; ?>
    </div>

    <!-- CONTENT CARD -->
    <div class="backdrop-blur-xl bg-white/80 rounded-2xl shadow-xl p-6 md:p-8 space-y-4">

        <!-- ================= PEMBELI ================= -->
        <?php if($role === 'pembeli'): ?>
        <div id="pembeli" class="space-y-4 animate-fadeIn">
            <?= faq("Bagaimana cara melacak status pesanan saya?",
            "Masuk ke halaman Status. Jika penjual sudah memasukkan nomor resi, klik pada kolom tracking lacak paket.") ?>

            <?= faq("Di mana saya bisa melihat bukti pembayaran?",
            "Buka halaman Status pada kolom Bukti Pembayaran. Klik gambar mini untuk membuka ukuran penuh.") ?>

            <?= faq("Apa yang harus saya lakukan jika pesanan ditolak?",
            "Jika status Approve = Tolak, berarti pesanan direfund. Anda bisa datang ke kantor untuk refund atau hubungi penjual.") ?>

            <?= faq("Bagaimana cara mencetak invoice belanja?",
            "Setelah pemesanan, buka halaman Ringkasan Pesanan (Invoice), klik download/cetak pdf lalu Print.") ?>

            <?= faq("Apakah saya bisa membeli buku dari penjual berbeda?",
            "Ya, sistem mendukung pembelian dari berbagai penjual dalam satu transaksi.") ?>
        </div>
        <?php endif; ?>

        <!-- ================= PENJUAL ================= -->
        <?php if($role === 'penjual'): ?>
        <div id="penjual" class="space-y-4 animate-fadeIn">
            <?= faq("Bagaimana cara melihat keuntungan bersih toko saya?",
            "Masuk menu Laporan. Sistem otomatis menghitung selisih harga jual dan harga modal.") ?>

            <?= faq("Apa yang harus dilakukan jika stok produk habis?",
            "Update stok melalui halaman Produk agar sesuai dengan stok fisik.") ?>

            <?= faq("Bagaimana cara menyetujui (approve) pembayaran pembeli?",
            "Masuk halaman Approve, periksa bukti transfer lalu ubah status menjadi Approve.") ?>

            <?= faq("Di mana saya memasukkan nomor resi pengiriman?",
            "Input nomor resi di halaman Approve pada kolom resi setelah approve.") ?>

            <?= faq("Apakah saya bisa berkomunikasi langsung dengan pembeli?",
            "Ya, gunakan fitur Chat untuk komunikasi langsung.") ?>
        </div>
        <?php endif; ?>

        <!-- ================= SUPER ADMIN ================= -->
        <?php if($role === 'super_admin'): ?>
        <div id="super_admin" class="space-y-4 animate-fadeIn">
            <?= faq("Bagaimana cara memantau seluruh pengguna di sistem?",
            "Masuk menu Manajemen Akun (Penjual & Pembeli) untuk melihat data lengkap pengguna.") ?>

            <?= faq("Apakah Super Admin bisa menonaktifkan akun?",
            "Ya, status akun dapat diubah menjadi Tidak Aktif.") ?>

            <?= faq("Bagaimana cara menambah kategori buku baru secara global?",
            "Masuk halaman Kategori. Kategori otomatis tersedia untuk semua penjual.") ?>

            <?= faq("Bagaimana cara membantu pengguna yang lupa password?",
            "Super Admin dapat membantu reset akun melalui database.") ?>

            <?= faq("Apa fungsi NIK dalam pendaftaran pengguna?",
            "Sebagai validasi identitas untuk meningkatkan keamanan transaksi.") ?>
        </div>
        <?php endif; ?>

    </div>

</div>

<!-- ANIMATION -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn{
    animation: fadeIn .6s ease-out;
}
</style>

<!-- JS -->
<script>
function toggleFaq(id){
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
</script>

</body>
</html>

<?php
// ===== COMPONENT FAQ =====
function faq($q,$a){
    static $i=0; $i++;
    return "
    <div class='bg-white rounded-xl shadow'>
        <button onclick=\"toggleFaq('faq$i')\"
            class='w-full text-left p-4 font-semibold flex justify-between items-center hover:bg-slate-50'>
            <span>❓ $q</span>
            <span class='text-xl'>+</span>
        </button>
        <div id='faq$i' class='hidden px-4 pb-4 text-gray-600'>
            $a
        </div>
    </div>
    ";
}
?>