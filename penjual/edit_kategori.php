<?php
session_start();
include '../config/koneksi.php';

/* ================= AMBIL ID ================= */
$id = $_GET['id'] ?? '';

if ($id == '') {
    echo "<script>
        alert('ID kategori tidak ditemukan');
        window.location='kategori.php';
    </script>";
    exit;
}

/* ================= AMBIL DATA ================= */
$data = mysqli_query($conn, "SELECT * FROM kategori WHERE id='$id'");
$kategori = mysqli_fetch_assoc($data);

if (!$kategori) {
    echo "<script>
        alert('Data kategori tidak ditemukan');
        window.location='kategori.php';
    </script>";
    exit;
}

/* ================= PROSES UPDATE ================= */
if (isset($_POST['update'])) {
    $nama = trim($_POST['nama_kategori']);
    $gambar_lama = $kategori['gambar'];

    if ($nama == '') {
        echo "<script>alert('Nama kategori wajib diisi');</script>";
    } else {

        /* CEK DUPLIKAT */
        $cek = mysqli_query($conn, "
            SELECT id FROM kategori 
            WHERE nama_kategori='$nama' AND id != '$id'
        ");

        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Nama kategori sudah digunakan');</script>";
        } else {

            /* ===== PROSES GAMBAR ===== */
            $gambar_baru = $gambar_lama;

            if (!empty($_FILES['gambar']['name'])) {
                $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($ext, $allowed)) {
                    echo "<script>alert('Format gambar tidak valid');</script>";
                    return;
                }

                $gambar_baru = 'kategori_' . time() . '.' . $ext;
                $folder = '../uploads/kategori/';

                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                move_uploaded_file($_FILES['gambar']['tmp_name'], $folder . $gambar_baru);

                /* HAPUS GAMBAR LAMA */
                if ($gambar_lama && file_exists($folder . $gambar_lama)) {
                    unlink($folder . $gambar_lama);
                }
            }

            /* UPDATE DATABASE */
            mysqli_query($conn, "
                UPDATE kategori 
                SET nama_kategori='$nama', gambar='$gambar_baru'
                WHERE id='$id'
            ");

            echo "<script>
                alert('Kategori berhasil diperbarui');
                window.location='kategori.php';
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">

<!-- SIDEBAR -->
<?php include '../layouts/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8">

<!-- TOP BAR -->
<div class="flex items-center gap-4 mb-8">
    <h1 class="text-2xl font-bold text-gray-700">Edit Kategori</h1>
    <div class="ml-auto">
        <?php include '../layouts/profil_notifikasi.php'; ?>
    </div>
</div>

<!-- FORM -->
<div class="bg-gradient-to-r from-teal-400 to-teal-600 rounded-3xl p-10 text-white max-w-3xl mx-auto">

<h2 class="text-2xl font-bold text-center mb-8">Edit Kategori</h2>

<form method="POST" enctype="multipart/form-data"
      class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">

    <label class="text-lg">Nama Kategori</label>
    <input type="text"
           name="nama_kategori"
           value="<?= htmlspecialchars($kategori['nama_kategori']); ?>"
           required
           class="md:col-span-2 px-5 py-3 rounded-full text-gray-700 outline-none">

    <label class="text-lg">Gambar</label>
    <div class="md:col-span-2 space-y-3">

        <?php if ($kategori['gambar']): ?>
            <img src="../uploads/kategori/<?= $kategori['gambar']; ?>"
                 class="w-32 h-32 object-cover rounded-xl">
        <?php endif; ?>

        <input type="file"
               name="gambar"
               accept="image/*"
               class="px-5 py-3 rounded-full bg-white text-gray-700 w-full">
        <small class="text-sm text-gray-200">
            Kosongkan jika tidak ingin mengganti gambar
        </small>
    </div>

    <div class="md:col-span-3 flex justify-end gap-4 mt-4">
        <a href="kategori.php"
           class="bg-gray-400 text-white px-8 py-3 rounded-full hover:bg-gray-500">
            Batal
        </a>

        <button type="submit"
                name="update"
                class="bg-green-600 text-white px-10 py-3 rounded-full hover:bg-green-700">
            Update
        </button>
    </div>

</form>
</div>

</main>
</div>

</body>
</html>
