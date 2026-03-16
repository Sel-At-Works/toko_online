<?php
session_start();
include '../config/koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$penjual_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? '';

if ($id == '') {
    header("Location: produk.php");
    exit;
}

/* ================= AMBIL PRODUK SESUAI ID ================= */
$query = mysqli_query($conn, "
    SELECT p.*, k.nama_kategori
    FROM produk p
    LEFT JOIN kategori k ON p.kategori_id = k.id
    WHERE p.id = '$id' AND p.penjual_id = '$penjual_id'
    LIMIT 1
");

$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    echo "<h2>Produk tidak ditemukan atau bukan milik Anda</h2>";
    exit;
}

/* ================= AMBIL KATEGORI ================= */
$kategori = mysqli_query($conn, "
    SELECT * FROM kategori 
    WHERE penjual_id = '$penjual_id' OR penjual_id IS NULL
    ORDER BY nama_kategori ASC
");

/* ================= PROSES UPDATE ================= */
if (isset($_POST['update'])) {

    $nama_produk = trim($_POST['nama_produk']);
    $deskripsi   = $_POST['deskripsi'];
    $harga_modal = $_POST['harga_modal'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $kategori_id = $_POST['kategori_id'];
    $margin = $_POST['margin'] ?? 0;

    $margin_persen = ($harga_modal > 0)
    ? round(($margin / $harga_modal) * 100)
    : 0;


     // 🔒 VALIDASI NAMA PRODUK
    $cekNama = mysqli_query($conn, "
        SELECT id FROM produk 
        WHERE nama_produk = '$nama_produk'
          AND penjual_id = '$penjual_id'
          AND id != '$id'
        LIMIT 1
    ");

    if (mysqli_num_rows($cekNama) > 0) {
        echo "
        <script>
            alert('Nama produk sudah digunakan. Silakan gunakan nama lain.');
            history.back();
        </script>
        ";
        exit;
    }
    

    /* UPLOAD GAMBAR */
    $gambar = $produk['gambar'];
    if (!empty($_FILES['gambar']['name'])) {
        $folder = "../uploads/";
        if (!is_dir($folder)) mkdir($folder);

        $namaFile = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], $folder . $namaFile);
        $gambar = $namaFile;
    }

    /* UPDATE PRODUK SESUAI ID */
    mysqli_query($conn, "
        UPDATE produk SET
    kategori_id   = '$kategori_id',
    nama_produk   = '$nama_produk',
    deskripsi     = '$deskripsi',
    harga_modal   = '$harga_modal',
    harga         = '$harga',
    margin        = '$margin',
    margin_persen = '$margin_persen',
    stok          = '$stok',
    gambar        = '$gambar'
WHERE id = '$id' AND penjual_id = '$penjual_id'
    ");

    header("Location: detail_produk.php?id=$id&updated=1");
    exit;
}

/* ================= GAMBAR PREVIEW ================= */
$gambarPreview = $produk['gambar']
    ? "../uploads/" . $produk['gambar']
    : "https://cdn-icons-png.flaticon.com/512/2847/2847978.png";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-8">

<div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6 text-teal-600">Edit Produk</h1>

    <form method="POST" enctype="multipart/form-data">

    <div class="grid grid-cols-3 gap-8">

        <!-- ================= KIRI (FORM UTAMA) ================= -->
        <div class="col-span-2 space-y-5">

            <div>
                <label class="font-semibold">Nama Produk</label>
                <input type="text" name="nama_produk"
                       value="<?= htmlspecialchars($produk['nama_produk']) ?>"
                       required
                       class="w-full p-3 border rounded-lg">
            </div>

            <div>
                <label class="font-semibold">Kategori</label>
                <select name="kategori_id" class="w-full p-3 border rounded-lg">
                    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                        <option value="<?= $k['id'] ?>"
                            <?= $k['id'] == $produk['kategori_id'] ? 'selected' : '' ?>>
                            <?= $k['nama_kategori'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label class="font-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full p-3 border rounded-lg"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold">Harga Modal</label>
                    <input type="number" name="harga_modal"
                           value="<?= $produk['harga_modal'] ?>"
                           required
                           class="w-full p-3 border rounded-lg">
                </div>

                <div>
                    <label class="font-semibold">Harga Jual</label>
                    <input type="number" name="harga"
                           value="<?= $produk['harga'] ?>"
                           required
                           class="w-full p-3 border rounded-lg">
                </div>
            </div>

            <div>
                <label class="font-semibold">Margin (Otomatis)</label>
                <input type="text" id="margin"
                       class="w-full p-3 border rounded-lg bg-gray-100 font-bold text-green-700"
                       readonly>
            </div>

            <input type="hidden" name="margin" id="marginHidden">

            <div>
                <label class="font-semibold">Stok</label>
                <input type="number" name="stok"
                       value="<?= $produk['stok'] ?>"
                       required
                       class="w-full p-3 border rounded-lg">
            </div>

        </div>

        <!-- ================= KANAN (GAMBAR) ================= -->
        <div class="space-y-4">

            <label class="font-semibold">Gambar Produk</label>

            <div class="border rounded-xl p-4 flex flex-col items-center bg-gray-50">
                <img src="<?= $gambarPreview ?>"
                     class="w-full max-h-60 object-contain rounded mb-4">
                <input type="file" name="gambar"
                       class="w-full p-2 border rounded-lg bg-white">
            </div>

        </div>

    </div>

    <!-- ================= TOMBOL ================= -->
    <div class="flex justify-end gap-4 mt-8">
        <a href="detail_produk.php?id=<?= $id ?>"
           class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg">
            Batal
        </a>

        <button type="submit" name="update"
                class="px-6 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-semibold">
            Simpan Perubahan
        </button>
    </div>

</form>

</div>
<script>
const hargaModal   = document.querySelector('input[name="harga_modal"]');
const hargaJual    = document.querySelector('input[name="harga"]');
const marginInput  = document.getElementById('margin');
const marginHidden = document.getElementById('marginHidden');

function hitungMargin() {
    const modal  = parseInt(hargaModal.value) || 0;
    const jual   = parseInt(hargaJual.value) || 0;
    const margin = jual - modal;

    marginInput.value  = 'Rp ' + margin.toLocaleString('id-ID');
    marginHidden.value = margin;
}

// hitung saat load halaman
hitungMargin();

hargaModal.addEventListener('input', hitungMargin);
hargaJual.addEventListener('input', hitungMargin);
</script>

</body>
</html>
