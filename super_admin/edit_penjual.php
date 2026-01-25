<?php
include '../config/koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID tidak ditemukan');
}

// ambil data penjual
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id' AND role_id = 2");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    die('Data penjual tidak ditemukan');
}

// =================== PROSES UPDATE ===================
if (isset($_POST['update'])) {

    $nik = $_POST['nik'];

    // VALIDASI NIK
    if (!preg_match('/^[0-9]{16}$/', $nik)) {
        echo "<script>
            alert('NIK harus berupa 16 digit angka!');
            history.back();
        </script>";
        exit;
    }

    // ===== CEK NIK DUPLIKAT (KECUALI DIRI SENDIRI) =====
$cekNik = mysqli_query($conn, "
    SELECT id FROM users 
    WHERE nik = '$nik'
    AND id != '$id'
    LIMIT 1
");

if (mysqli_num_rows($cekNik) > 0) {
    echo "<script>
        alert('NIK sudah digunakan oleh penjual lain!');
        history.back();
    </script>";
    exit;
}


    $nama   = $_POST['nama'];
    $email  = $_POST['email'];
    $alamat = $_POST['alamat'];
    $foto_lama = $data['foto'];

    // ===== CEK EMAIL DUPLIKAT (KECUALI DIRI SENDIRI) =====
$cekEmail = mysqli_query($conn, "
    SELECT id FROM users 
    WHERE email = '$email'
    AND id != '$id'
    LIMIT 1
");

if (mysqli_num_rows($cekEmail) > 0) {
    echo "<script>
        alert('Email sudah digunakan oleh penjual lain!');
        history.back();
    </script>";
    exit;
}


    // upload foto baru
    if (!empty($_FILES['foto']['name'])) {

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_file = 'user_' . $id . '_' . time() . '.' . $ext;

        $folder = '../uploads/profile/';
        $tujuan = $folder . $nama_file;

        if (!empty($foto_lama) && file_exists('../' . $foto_lama)) {
            unlink('../' . $foto_lama);
        }

        move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan);
        $foto_db = 'uploads/profile/' . $nama_file;

        mysqli_query($conn, "
            UPDATE users SET
                nik='$nik',
                nama='$nama',
                email='$email',
                alamat='$alamat',
                foto='$foto_db'
            WHERE id='$id'
        ");

    } else {

        mysqli_query($conn, "
            UPDATE users SET
                nik='$nik',
                nama='$nama',
                email='$email',
                alamat='$alamat'
            WHERE id='$id'
        ");
    }

    header("Location: admin.php");
    exit;
}

// FOTO SAAT INI
$path_server = $_SERVER['DOCUMENT_ROOT'] . '/toko_online/' . $data['foto'];
$path_url    = '/toko_online/' . $data['foto'];
$ada_foto    = !empty($data['foto']) && file_exists($path_server);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Penjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">

<div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl overflow-hidden">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-teal-600 to-emerald-500 p-6 text-white">
        <h2 class="text-2xl font-bold">Edit Penjual</h2>
        <p class="text-sm opacity-90">Perbarui data akun penjual</p>
    </div>

    <div class="p-8">

        <!-- FOTO -->
        <div class="flex justify-center -mt-20 mb-8">
            <div class="w-32 h-32 rounded-full bg-white p-1 shadow-lg">
                <div class="w-full h-full rounded-full overflow-hidden border-4 border-teal-500">
                    <?php if ($ada_foto): ?>
                        <img src="<?= $path_url ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-500">NIK</label>
                <input type="text" name="nik"
                       value="<?= $data['nik'] ?>"
                       class="w-full mt-1 border rounded-xl p-3"
                       maxlength="16"
                       pattern="[0-9]{16}"
                       inputmode="numeric"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                       required>
            </div>

            <div>
                <label class="text-sm text-gray-500">Nama</label>
                <input type="text" name="nama"
                       value="<?= $data['nama'] ?>"
                       class="w-full mt-1 border rounded-xl p-3"
                       required>
            </div>

            <div>
                <label class="text-sm text-gray-500">Email</label>
                <input type="email" name="email"
                       value="<?= $data['email'] ?>"
                       class="w-full mt-1 border rounded-xl p-3"
                       required>
            </div>

            <div>
                <label class="text-sm text-gray-500">Foto Profil</label>
                <input type="file" name="foto"
                       class="w-full mt-1 border rounded-xl p-3"
                       accept="image/*">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-gray-500">Alamat</label>
                <textarea name="alamat"
                          class="w-full mt-1 border rounded-xl p-3"
                          rows="3"><?= $data['alamat'] ?></textarea>
            </div>

            <!-- ACTION -->
            <div class="md:col-span-2 flex justify-between mt-6">
                <a href="admin.php"
                   class="px-6 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 transition">
                    Batal
                </a>

                <button name="update"
                        class="px-6 py-2 rounded-xl bg-teal-600 text-white hover:bg-teal-700 transition shadow">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
