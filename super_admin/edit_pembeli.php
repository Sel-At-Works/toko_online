<?php
session_start();
include '../config/koneksi.php';

/* ================= VALIDASI ID ================= */
$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID pembeli tidak ditemukan di URL');
}

/* ================= AMBIL DATA ================= */
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

/* ================= PROSES UPDATE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nik    = $_POST['nik'];
    $nama   = $_POST['nama'];
    $alamat = $_POST['alamat'];

    // upload foto baru
    $foto_sql = '';
    if (!empty($_FILES['foto']['name'])) {

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_foto = 'pembeli_' . time() . '.' . $ext;
        $path_upload = $_SERVER['DOCUMENT_ROOT'] . '/toko_online/uploads/' . $nama_foto;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $path_upload)) {

            // hapus foto lama
            if (!empty($data['foto'])) {
                $old = $_SERVER['DOCUMENT_ROOT'] . '/toko_online/' . $data['foto'];
                if (file_exists($old)) unlink($old);
            }

            $foto_sql = ", foto='uploads/$nama_foto'";
        }
    }

    mysqli_query($conn, "
        UPDATE users SET
            nik='$nik',
            nama='$nama',
            alamat='$alamat'
            $foto_sql
        WHERE id='$id' AND role_id=3
    ");

    header("Location: detail_pembeli.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pembeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">

<div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl overflow-hidden">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 p-6 text-white">
        <h2 class="text-2xl font-bold">Edit Pembeli</h2>
        <p class="text-sm opacity-90">Perbarui informasi akun pembeli</p>
    </div>

    <!-- CONTENT -->
    <form method="POST" enctype="multipart/form-data" class="p-8">

        <!-- FOTO -->
        <div class="flex justify-center -mt-20 mb-6">
            <div class="w-32 h-32 rounded-full bg-white p-1 shadow-lg">
                <div class="w-full h-full rounded-full overflow-hidden border-4 border-blue-500">
                    <?php if ($ada_foto): ?>
                        <img src="<?= $path_url ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500">
                            <span>No Photo</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="text-center mb-6">
            <input type="file" name="foto" class="text-sm">
        </div>

        <!-- FORM -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

            <div>
                <label class="block text-gray-500 mb-1">NIK</label>
                <input type="text" name="nik" value="<?= $data['nik'] ?>"
                       class="w-full border rounded-xl px-4 py-2">
            </div>

            <div>
                <label class="block text-gray-500 mb-1">Nama</label>
                <input type="text" name="nama" value="<?= $data['nama'] ?>"
                       class="w-full border rounded-xl px-4 py-2">
            </div>

            <div>
                <label class="block text-gray-500 mb-1">Email</label>
                <input type="text" value="<?= $data['email'] ?>"
                       class="w-full border rounded-xl px-4 py-2 bg-gray-100" disabled>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-500 mb-1">Alamat</label>
                <textarea name="alamat"
                          class="w-full border rounded-xl px-4 py-2"
                          rows="3"><?= $data['alamat'] ?></textarea>
            </div>

        </div>

        <!-- ACTION -->
        <div class="flex justify-between mt-10">
            <a href="detail_pembeli.php?id=<?= $data['id'] ?>"
               class="px-6 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 transition">
                ← Kembali
            </a>

            <button type="submit"
                    class="px-6 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition shadow">
                💾 Simpan Perubahan
            </button>
        </div>

    </form>

</div>

</body>
</html>
