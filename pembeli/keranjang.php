<?php
session_start();
include '../config/koneksi.php';

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);
$total = 0;

/* ================= UPDATE QTY (TAMBAH / KURANG) ================= */
if (isset($_GET['aksi'], $_GET['id'])) {
    $aksi = $_GET['aksi'];
    $keranjang_id = intval($_GET['id']);

    if (in_array($aksi, ['tambah', 'kurang']) && $keranjang_id > 0) {

      $data = mysqli_query($conn, "
    SELECT k.qty, k.produk_id, p.stok
    FROM keranjang k
    JOIN produk p ON k.produk_id = p.id
    WHERE k.id = $keranjang_id 
      AND k.pembeli_id = $pembeli_id
");

if ($rowQty = mysqli_fetch_assoc($data)) {
    $qty = (int)$rowQty['qty'];
    $stok = (int)$rowQty['stok'];
    $produk_id = (int)$rowQty['produk_id'];
if ($aksi === 'tambah') {
    if ($qty < $stok) {
        $qty++;
    }
}

elseif ($aksi === 'kurang') {
    $qty--;
}

// kalau qty <= 0 → hapus dari keranjang
if ($qty <= 0) {
    mysqli_query($conn, "
        DELETE FROM keranjang
        WHERE id = $keranjang_id
          AND pembeli_id = $pembeli_id
    ");
} else {
    mysqli_query($conn, "
        UPDATE keranjang
        SET qty = $qty
        WHERE id = $keranjang_id
          AND pembeli_id = $pembeli_id
    ");
}


    if ($qty <= 0) {
        mysqli_query($conn, "
            DELETE FROM keranjang 
            WHERE id = $keranjang_id
        ");
    } else {
        mysqli_query($conn, "
            UPDATE keranjang 
            SET qty = $qty 
            WHERE id = $keranjang_id
        ");
    }
}

    }

    // cegah double submit saat refresh
    header("Location: keranjang.php");
    exit;
}


/* ================= AMBIL ISI KERANJANG ================= */
/* ================= AMBIL ISI KERANJANG ================= */
$items = [];

$query = mysqli_query($conn, "
    SELECT 
        k.id AS keranjang_id,
        k.qty,
        p.nama_produk,
        p.harga,
        p.gambar
    FROM keranjang k
    JOIN produk p ON k.produk_id = p.id
    WHERE k.pembeli_id = $pembeli_id
");

if (!$query) {
    die('Query keranjang error: ' . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($query)) {
    $items[] = $row;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

<div class="max-w-6xl mx-auto px-6 py-10">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-10">
        <h1 class="text-3xl font-extrabold tracking-wide">🛒 Keranjang Belanja</h1>
        <a href="produk.php" class="text-sm font-semibold text-teal-600 hover:underline">
            ← Lanjut Belanja
        </a>
    </div>

<?php if (count($items) === 0) { ?>


    <!-- EMPTY -->
    <div class="bg-white rounded-3xl p-16 shadow text-center">
        <p class="text-lg text-gray-500 mb-6">
            Keranjang belanja kamu masih kosong
        </p>
        <a href="produk.php"
           class="inline-block px-8 py-3 rounded-full bg-teal-500 text-white font-semibold hover:bg-teal-600">
            Mulai Belanja
        </a>
    </div>

<?php } else { ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[80vh]">


    <!-- LIST -->
   <div class="lg:col-span-2 space-y-4 overflow-y-auto pr-2">


   <?php foreach ($items as $row) { 

        $subtotal = $row['harga'] * $row['qty'];
        $total += $subtotal;
        $gambar = $row['gambar']
            ? '../uploads/'.$row['gambar']
            : 'https://cdn-icons-png.flaticon.com/512/2847/2847978.png';
    ?>

        <div class="flex items-center bg-white p-6 rounded-3xl shadow gap-6">
            <img src="<?= $gambar ?>" class="w-24 h-24 object-contain">

            <div class="flex-1">
                <h3 class="font-semibold text-lg"><?= htmlspecialchars($row['nama_produk']) ?></h3>
                <p class="text-sm text-gray-500">Rp <?= number_format($row['harga']) ?></p>
               <div class="flex items-center gap-3 mt-2">
    <a href="keranjang.php?id=<?= $row['keranjang_id'] ?>&aksi=kurang"
       class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 font-bold">
        −
    </a>

    <span class="px-4 py-1 border rounded-lg font-semibold">
        <?= $row['qty'] ?>
    </span>

    <a href="keranjang.php?id=<?= $row['keranjang_id'] ?>&aksi=tambah"
       class="w-8 h-8 flex items-center justify-center rounded-full bg-teal-500 text-white hover:bg-teal-600 font-bold">
        +
    </a>
</div>


            </div>

            <div class="text-right">
                <p class="font-bold text-teal-600">Rp <?= number_format($subtotal) ?></p>
                <a href="keranjang_hapus.php?id=<?= $row['keranjang_id'] ?>"
                   onclick="return confirm('Hapus produk ini?')"
                   class="text-xs text-red-500 hover:underline">
                   Hapus
                </a>
            </div>
        </div>

    <?php } ?>

    </div>

    <!-- SUMMARY -->
    <!-- SUMMARY -->
<div class="bg-white rounded-3xl p-8 shadow h-full overflow-y-auto">

    <h2 class="text-xl font-bold mb-6">Ringkasan</h2>

    <!-- LIST PRODUK -->
    <div class="space-y-3 mb-6">
        <?php foreach ($items as $row) { ?>
            <div class="flex justify-between text-sm text-gray-700">
                <div>
                    <p class="font-medium">
                        <?= htmlspecialchars($row['nama_produk']) ?>
                    </p>
                    <p class="text-xs text-gray-500">
                        Qty: <?= $row['qty'] ?>
                    </p>
                </div>
                <div class="font-semibold">
                    Rp <?= number_format($row['harga'] * $row['qty']) ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- GARIS -->
    <hr class="my-4">

    <!-- TOTAL -->
    <div class="flex justify-between text-lg font-extrabold text-teal-600">
        <span>Total</span>
        <span>Rp <?= number_format($total) ?></span>
    </div>

    <a href="checkout.php"
       class="block mt-8 text-center px-6 py-4 rounded-full bg-teal-500 text-white font-bold hover:bg-teal-600">
        lanjutkan ke Checkout
    </a>
</div>


</div>

<?php } ?>

</div>
</body>
</html>
