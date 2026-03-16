<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$penjual_id = $_SESSION['user_id'];

$kategori = mysqli_query($conn, "
    SELECT * FROM kategori 
    WHERE penjual_id = '$penjual_id' OR penjual_id IS NULL
    ORDER BY nama_kategori ASC
");

if (isset($_POST['simpan'])) {

    $nama_produk   = trim($_POST['nama_produk']);
    $deskripsi     = $_POST['deskripsi'];
    $harga_modal   = $_POST['harga_modal'];
    $harga         = $_POST['harga'];
    $margin        = $_POST['margin'] ?? 0;
    $stok          = $_POST['stok'];
    $kategori_id   = $_POST['kategori_id'];
    $kategori_baru = $_POST['kategori_baru'] ?? null;

    
    $margin_persen = ($harga_modal > 0)
        ? round(($margin / $harga_modal) * 100)
        : 0;


    $cek = mysqli_query($conn, "
        SELECT id FROM produk 
        WHERE penjual_id='$penjual_id'
        AND nama_produk='$nama_produk'
        LIMIT 1
    ");

    if (mysqli_num_rows($cek) > 0) {
        $error = "Nama produk sudah ada. Gunakan nama lain.";
    }

    if (!isset($error) && $kategori_id === 'baru' && !empty($kategori_baru)) {
        mysqli_query($conn, "
            INSERT INTO kategori (nama_kategori)
            VALUES ('$kategori_baru')
        ");
        $kategori_id = mysqli_insert_id($conn);
    }

    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $folder = "../uploads/";
        if (!is_dir($folder)) mkdir($folder);

        $namaFile = time() . "_" . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], $folder . $namaFile);
        $gambar = $namaFile;
    }

    if (!isset($error)) {
       mysqli_query($conn, "
    INSERT INTO produk
    (penjual_id, kategori_id, nama_produk, deskripsi, harga_modal, harga, margin, margin_persen, stok, gambar)
    VALUES
    ('$penjual_id','$kategori_id','$nama_produk','$deskripsi','$harga_modal','$harga','$margin','$margin_persen','$stok','$gambar')
");


        header("Location: produk.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-teal-50 to-sky-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-xl p-10">

        <h2 class="text-3xl font-bold text-center text-teal-600 mb-10">
            Tambah Produk
        </h2>

        <?php if (isset($error)) { ?>
            <div class="mb-6 flex items-center gap-3 bg-red-100 text-red-700 p-4 rounded-xl">
                <span class="text-xl">⚠️</span>
                <span><?= $error ?></span>
            </div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="grid grid-cols-2 gap-10">

                <!-- ================= KIRI ================= -->
                <div class="space-y-6">

                    <div>
                        <label class="font-semibold">Nama Produk</label>
                        <input type="text" name="nama_produk" required
                            class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-teal-400 outline-none">
                    </div>

                    <div>
                        <label class="font-semibold">Deskripsi</label>
                        <textarea name="deskripsi" rows="4"
                            class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-teal-400 outline-none"></textarea>
                    </div>

                    <div>
                        <label class="font-semibold">Kategori</label>

                        <!-- SEARCH KATEGORI -->
                        <input type="text"
                            id="searchKategori"
                            placeholder="Cari kategori..."
                            class="w-full mb-2 p-3 border rounded-xl focus:ring-2 focus:ring-teal-400 outline-none">

                        <select name="kategori_id" id="kategoriSelect"
                            onchange="toggleKategoriBaru()"
                            class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-teal-400 outline-none"
                            required>
                            <option value="">-- Pilih Kategori --</option>

                            <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                                <option value="<?= $k['id'] ?>">
                                    <?= $k['nama_kategori'] ?>
                                </option>
                            <?php } ?>

                            <!-- <option value="baru">+ Tambah Kategori Baru</option> -->
                        </select>


                        <div id="kategoriBaru" class="hidden mt-3">
                            <input type="text" name="kategori_baru"
                                class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-teal-400 outline-none"
                                placeholder="Nama kategori baru">
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold">Gambar Produk</label>
                        <input type="file" name="gambar"
                            class="w-full p-3 border-2 border-dashed rounded-xl bg-gray-50">
                    </div>

                </div>

                <!-- ================= KANAN ================= -->
                <div class="space-y-6">

                    <div>
                        <label class="font-semibold">Harga Modal</label>
                        <input type="number" name="harga_modal" required
                            class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-teal-400 outline-none">
                    </div>

                    <div>
                        <label class="font-semibold">Harga Jual</label>
                        <input type="number" name="harga" required
                            class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-teal-400 outline-none">
                    </div>

                    <div>
                        <label class="font-semibold">Margin (Otomatis)</label>
                        <input type="text" id="margin"
                            class="w-full p-4 border rounded-xl bg-gray-100 font-bold text-green-700"
                            readonly>
                    </div>


                    <div>
                        <label class="font-semibold">Stok</label>
                        <input type="number" name="stok" required
                            class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-teal-400 outline-none">
                    </div>
                    <input type="hidden" name="margin" id="marginHidden">
                    <div class="pt-10 flex gap-4">
                        <a href="produk.php"
                            class="w-1/2 text-center px-6 py-3 bg-gray-400 hover:bg-gray-500 text-white rounded-xl transition">
                            Kembali
                        </a>

                        <button type="submit" name="simpan"
                            class="w-1/2 px-6 py-3 bg-teal-500 hover:bg-teal-600 text-white rounded-xl transition font-semibold">
                            Simpan
                        </button>
                    </div>

                </div>

            </div>

        </form>
    </div>

    <script>
/* ====== KATEGORI BARU ====== */
function toggleKategoriBaru() {
    const select = document.getElementById('kategoriSelect');
    const inputBaru = document.getElementById('kategoriBaru');
    inputBaru.classList.toggle('hidden', select.value !== 'baru');
}

/* ====== SEARCH KATEGORI ====== */
const searchInput = document.getElementById('searchKategori');
const kategoriSelect = document.getElementById('kategoriSelect');

searchInput.addEventListener('input', function () {
    const keyword = this.value.toLowerCase().trim();
    let firstVisible = null;

    Array.from(kategoriSelect.options).forEach(option => {
        if (option.value === "" || option.value === "baru") {
            option.hidden = false;
            return;
        }

        if (option.text.toLowerCase().includes(keyword)) {
            option.hidden = false;
            if (!firstVisible) firstVisible = option;
        } else {
            option.hidden = true;
        }
    });

    kategoriSelect.value = firstVisible ? firstVisible.value : "";
});

/* ====== HITUNG MARGIN ====== */
const hargaModal   = document.querySelector('input[name="harga_modal"]');
const hargaJual    = document.querySelector('input[name="harga"]');
const marginInput  = document.getElementById('margin');
const marginHidden = document.getElementById('marginHidden');

function hitungMargin() {
    const modal = parseInt(hargaModal.value) || 0;
    const jual  = parseInt(hargaJual.value) || 0;
    const margin = jual - modal;

    marginInput.value  = 'Rp ' + margin.toLocaleString('id-ID');
    marginHidden.value = margin;
}

hargaModal.addEventListener('input', hitungMargin);
hargaJual.addEventListener('input', hitungMargin);
</script>


</body>

</html>