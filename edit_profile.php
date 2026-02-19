<?php
session_start();
include 'config/koneksi.php';

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

/* ================= DEFAULT NO TELEPON ================= */
$profilPembeli = [
    'no_telepon' => ''
];

/* ================= AMBIL NO TELEPON DARI DATABASE ================= */
if ($user['role'] === 'pembeli') {

    $uid = intval($user['id']);

    $q = mysqli_query($conn, "
        SELECT no_telepon
        FROM pembeli_profile
        WHERE user_id = $uid
        LIMIT 1
    ");

    if ($q && mysqli_num_rows($q) > 0) {
        $profilPembeli = mysqli_fetch_assoc($q);
    }
}

/* ================= FOTO ================= */
$foto = !empty($user['foto'])
    ? $user['foto']
    : 'uploads/profile/default.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen flex items-center justify-center px-4">

<?php if(isset($_SESSION['alert'])): ?>
<script>
    alert("<?= addslashes($_SESSION['alert']); ?>");
</script>
<?php unset($_SESSION['alert']); endif; ?>

<div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden grid grid-cols-1 md:grid-cols-3">

<!-- ================= LEFT : FOTO ================= -->
<div class="bg-gradient-to-b from-teal-600 to-emerald-500 p-8 text-white
            flex flex-col items-center justify-center gap-4 text-center">

    <div class="relative group">
        <img src="<?= htmlspecialchars($foto); ?>"
             class="w-32 h-32 rounded-full object-cover
                    border-4 border-white shadow-lg bg-white"
             alt="Foto Profile">

        <label class="absolute inset-0 rounded-full bg-black/40
                      opacity-0 group-hover:opacity-100
                      flex items-center justify-center
                      text-sm transition cursor-pointer">
            Ganti Foto
        </label>
    </div>

    <h2 class="text-xl font-bold">
        <?= htmlspecialchars($user['nama']); ?>
    </h2>

    <p class="text-teal-100 text-sm">
        <?= htmlspecialchars($user['email']); ?>
    </p>

    <p class="text-xs text-teal-100 opacity-90">
        Edit informasi akun Anda
    </p>

</div>

<!-- ================= RIGHT : FORM ================= -->
<div class="md:col-span-2 p-10">

    <h3 class="text-2xl font-bold text-gray-800 mb-6">
        Edit Profile
    </h3>

    <form action="update_profile.php"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-5">

        <input type="hidden" name="id" value="<?= $user['id']; ?>">

        <!-- FOTO -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Foto Profile
            </label>
            <input type="file" name="foto" accept="image/*"
                   class="w-full text-sm file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:bg-teal-50 file:text-teal-600
                          hover:file:bg-teal-100 cursor-pointer">
        </div>

        <!-- NAMA -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Nama
            </label>
            <input type="text" name="nama"
                   value="<?= htmlspecialchars($user['nama']); ?>"
                   class="w-full rounded-xl border px-4 py-2
                          focus:ring-2 focus:ring-teal-500 focus:outline-none"
                   required>
        </div>

        <!-- EMAIL -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Email
            </label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($user['email']); ?>"
                   class="w-full rounded-xl border px-4 py-2
                          focus:ring-2 focus:ring-teal-500 focus:outline-none"
                   required>
        </div>

        <!-- NOMOR TELEPON (KHUSUS PEMBELI) -->
        <?php if ($user['role'] === 'pembeli'): ?>
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Nomor Telepon
            </label>

            <input type="text"
                   name="no_telepon"
                   value="<?= htmlspecialchars($profilPembeli['no_telepon']); ?>"
                   placeholder="08xxxxxxxxxx"
                   maxlength="13"
                   inputmode="numeric"
                   pattern="[0-9]{10,13}"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                   class="w-full border rounded-xl px-4 py-2
                          focus:ring-2 focus:ring-teal-500 focus:outline-none"
                   required>

            <p class="text-xs text-gray-500 mt-1">
                Nomor harus 10–13 digit dan hanya angka
            </p>
        </div>
        <?php endif; ?>

        <!-- ALAMAT -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Alamat
            </label>
            <textarea name="alamat" rows="3"
                      class="w-full rounded-xl border px-4 py-2
                             focus:ring-2 focus:ring-teal-500 focus:outline-none"
                      placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
        </div>

        <!-- ACTION -->
        <div class="flex items-center justify-between pt-6">
            <a href="profile.php"
               class="text-gray-500 hover:text-teal-600 font-medium">
                ← Batal
            </a>

            <button type="submit"
                    class="bg-teal-600 text-white px-8 py-2 rounded-full
                           font-semibold shadow-lg
                           hover:bg-teal-700 transition">
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>
</div>

</body>
</html>
