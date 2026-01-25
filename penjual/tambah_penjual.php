
<?php
include '../config/koneksi.php';

/* ================= PROSES SIMPAN ================= */
if (isset($_POST['simpan'])) {

    $nik    = $_POST['nik'];
    $nama   = $_POST['nama'];
    $email  = $_POST['email'];
    $alamat = $_POST['alamat'];

  // ===== VALIDASI FORMAT NIK =====
if (!preg_match('/^[0-9]{16}$/', $nik)) {
    echo "<script>
        alert('NIK harus 16 digit angka');
        history.back();
    </script>";
    exit;
}

// ===== CEK EMAIL DUPLIKAT =====
$cekEmail = mysqli_query($conn, "
    SELECT id FROM users 
    WHERE email = '$email' 
    LIMIT 1
");

if (mysqli_num_rows($cekEmail) > 0) {
    echo "<script>
        alert('Email sudah digunakan, silakan gunakan email lain!');
        history.back();
    </script>";
    exit;
}


    // password default
    $password_plain = '123456';
    $password = password_hash($password_plain, PASSWORD_DEFAULT);

    // upload foto
    $foto_db = null;
    if (!empty($_FILES['foto']['name'])) {

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_file = 'user_' . time() . '.' . $ext;

        $folder = '../uploads/profile/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $nama_file);
        $foto_db = 'uploads/profile/' . $nama_file;
    }

    mysqli_query($conn, "
        INSERT INTO users (role_id, nik, nama, email, password, alamat, foto)
        VALUES (2, '$nik', '$nama', '$email', '$password', '$alamat', '$foto_db')
    ");

    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">

<div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl overflow-hidden">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-teal-600 to-emerald-500 p-6 text-white">
        <h2 class="text-2xl font-bold">Tambah Penjual</h2>
        <p class="text-sm opacity-90">Buat akun penjual baru</p>
    </div>

    <div class="p-8">

        <!-- AVATAR -->
        <div class="flex justify-center -mt-20 mb-8">
            <div class="w-32 h-32 rounded-full bg-white p-1 shadow-lg">
                <div class="w-full h-full rounded-full border-4 border-teal-500
                            flex items-center justify-center bg-gray-200 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <form method="POST" enctype="multipart/form-data"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-500">NIK</label>
                <input type="text" name="nik"
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
                       class="w-full mt-1 border rounded-xl p-3"
                       required>
            </div>

            <div>
                <label class="text-sm text-gray-500">Email</label>
                <input
    type="email"
    name="email"
    class="w-full mt-1 border rounded-xl p-3"
    placeholder="contoh@email.com"
    required
>

            </div>

            <div>
                <label class="text-sm text-gray-500">Foto Profil (opsional)</label>
                <input type="file" name="foto"
                       class="w-full mt-1 border rounded-xl p-3"
                       accept="image/*">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-gray-500">Alamat</label>
                <textarea name="alamat"
                          class="w-full mt-1 border rounded-xl p-3"
                          rows="3"></textarea>
            </div>

            <!-- INFO -->
            <div class="md:col-span-2 text-sm text-gray-500 bg-gray-100 p-4 rounded-xl">
                Password default penjual:  
                <span class="font-semibold text-gray-700">123456</span>
            </div>

            <!-- ACTION -->
            <div class="md:col-span-2 flex justify-between mt-6">
                <a href="admin.php"
                   class="px-6 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 transition">
                    Batal
                </a>

                <button name="simpan"
                        class="px-6 py-2 rounded-xl bg-teal-600 text-white
                               hover:bg-teal-700 transition shadow">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
