<?php
session_start();
include '../config/koneksi.php';

/* ===== PROSES SIMPAN ===== */
if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama_kategori']);

    if ($nama == '') {
        echo "<script>alert('Nama kategori wajib diisi');</script>";
    } else {

        /* CEK DUPLIKAT */
        $cek = mysqli_query($conn, "SELECT id FROM kategori WHERE nama_kategori='$nama'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Kategori sudah ada');</script>";
        } else {

            /* ===== PROSES GAMBAR ===== */
            $gambar = null;

            if (!empty($_FILES['gambar']['name'])) {
                $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($ext, $allowed)) {
                    echo "<script>alert('Format gambar harus JPG, PNG, atau WEBP');</script>";
                    return;
                }

                $gambar = 'kategori_' . time() . '.' . $ext;
                $folder = '../uploads/kategori/';

                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                move_uploaded_file($_FILES['gambar']['tmp_name'], $folder . $gambar);
            }

            /* SIMPAN DATABASE */
            mysqli_query($conn, "
                INSERT INTO kategori (nama_kategori, gambar)
                VALUES ('$nama', '$gambar')
            ");

            echo "<script>
                alert('Kategori berhasil ditambahkan');
                window.location='kategori.php';
            </script>";
        }
    }
}

/* ===== AMBIL DATA + SEARCH ===== */
$search = $_GET['search'] ?? '';

if ($search != '') {
    $kategori = mysqli_query($conn, "
        SELECT * FROM kategori
        WHERE nama_kategori LIKE '%$search%'
        ORDER BY id DESC
    ");
} else {
    $kategori = mysqli_query($conn, "
        SELECT * FROM kategori
        ORDER BY id DESC
    ");
}

if (!$kategori) {
    die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kategori</title>
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
<form method="GET" class="flex-1 relative">
  <input
    type="text"
    name="search"
    value="<?= htmlspecialchars($search); ?>"
    placeholder="Cari kategori..."
    class="w-full px-12 py-3 rounded-full bg-white shadow focus:outline-none"
  />
  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
</form>

<?php include '../layouts/profil_notifikasi.php'; ?>
</div>

<!-- FORM INPUT -->
<div class="bg-gradient-to-r from-teal-400 to-teal-600 rounded-3xl p-10 text-white mb-10">

<h2 class="text-2xl font-bold text-center mb-8">Kategori</h2>

<form method="POST" enctype="multipart/form-data"
      class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">

    <label class="text-lg">Kategori</label>
    <input type="text"
           name="nama_kategori"
           required
           class="md:col-span-2 px-5 py-3 rounded-full text-gray-700 outline-none"
           placeholder="Nama kategori">

    <label class="text-lg">Gambar</label>
    <input type="file"
           name="gambar"
           accept="image/*"
           class="md:col-span-2 px-5 py-3 rounded-full bg-white text-gray-700">

    <div class="md:col-span-3 flex justify-end">
        <button type="submit"
                name="simpan"
                class="bg-gray-500 text-white px-10 py-3 rounded-full hover:bg-gray-600">
            Simpan
        </button>
    </div>
</form>
</div>

<!-- TABEL -->
<div class="bg-white rounded-xl shadow p-6">
<table class="w-full border border-green-400 text-center">
<thead>
<tr class="border-b border-green-400">
    <th class="border-r border-green-400 py-3">No</th>
    <th class="border-r border-green-400">Gambar</th>
    <th class="border-r border-green-400">Kategori</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php if (mysqli_num_rows($kategori) > 0): ?>
<?php $no = 1; while ($row = mysqli_fetch_assoc($kategori)): ?>
<tr class="border-b border-green-400">
    <td class="border-r border-green-400 py-3"><?= $no++; ?></td>

    <td class="border-r border-green-400 py-2">
        <?php if ($row['gambar']): ?>
            <img src="../uploads/kategori/<?= $row['gambar']; ?>"
                 class="w-16 h-16 object-cover rounded-lg mx-auto">
        <?php else: ?>
            <span class="text-gray-400">-</span>
        <?php endif; ?>
    </td>

    <td class="border-r border-green-400">
        <?= htmlspecialchars($row['nama_kategori']); ?>
    </td>

    <td class="py-3">
        <div class="flex justify-center gap-5">
            <a href="edit_kategori.php?id=<?= $row['id']; ?>"
               class="p-2 rounded-full bg-yellow-400 hover:bg-yellow-500">✏️</a>

            <a href="hapus_kategori.php?id=<?= $row['id']; ?>"
               onclick="return confirm('Yakin ingin menghapus kategori ini?')"
               class="p-2 rounded-full bg-red-500 hover:bg-red-600">🗑️</a>
        </div>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="4" class="py-6 text-gray-500">
        Data kategori belum tersedia
    </td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

<p class="text-center text-sm text-gray-500 mt-10">
© nurhayatulfadilla
</p>

</main>
</div>

</body>
</html>
