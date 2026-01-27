<?php
session_start();
include '../config/koneksi.php';

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);

/* ================= QUERY ================= */
$query = mysqli_query($conn, "
    SELECT
        k.produk_id,
        k.qty,
        p.nama_produk,
        p.harga,
        p.penjual_id,
        u.nama AS nama_penjual,
        pp.bank,
        pp.no_rekening,
        pp.qris
    FROM keranjang k
    JOIN produk p ON k.produk_id = p.id
    JOIN users u ON p.penjual_id = u.id
    LEFT JOIN penjual_profile pp ON pp.user_id = u.id
    WHERE k.pembeli_id = $pembeli_id
");

/* ================= KELOMPOK PENJUAL ================= */
$penjuals = [];

while ($row = mysqli_fetch_assoc($query)) {
    $pid = $row['penjual_id'];

    if (!isset($penjuals[$pid])) {
        $penjuals[$pid] = [
            'nama' => $row['nama_penjual'],
            'bank' => $row['bank'],
            'no_rekening' => $row['no_rekening'],
            'qris' => $row['qris'],
            'items' => [],
            'total' => 0
        ];
    }

    $penjuals[$pid]['items'][] = $row;
    $penjuals[$pid]['total'] += $row['harga'] * $row['qty'];
}

/* ================= TOTAL SEMUA ================= */
$total = 0;
foreach ($penjuals as $p) {
    $total += $p['total'];
}

/* ================= CEK KOSONG ================= */
if (count($penjuals) === 0) {
    header("Location: keranjang.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Checkout</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
input[type=radio] { accent-color:#14b8a6; }
</style>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

<div class="max-w-7xl mx-auto py-10 px-6">

<form action="checkout_proses.php"
      method="POST"
      enctype="multipart/form-data">

<div class="bg-white rounded-3xl shadow-xl p-10 grid grid-cols-1 lg:grid-cols-[1.4fr_1fr] gap-12">

<!-- ================= LEFT ================= -->
<div class="bg-gray-50 rounded-2xl p-6 flex flex-col h-[600px]">

    <!-- HEADER -->
    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 sticky top-0 bg-gray-50 z-10 pb-3">
        🧾 Ringkasan Pesanan
    </h2>

    <!-- LIST (SCROLL) -->
    <div class="space-y-3 overflow-y-auto pr-2 flex-1">

        <?php foreach ($penjuals as $p): ?>
            <?php foreach ($p['items'] as $item): ?>

            <div class="bg-white rounded-2xl p-4 shadow hover:shadow-md transition flex justify-between items-center">
                <div class="max-w-[65%]">
                    <p class="font-semibold text-gray-800 truncate">
                        <?= htmlspecialchars($item['nama_produk']) ?>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Qty <?= $item['qty'] ?>
                    </p>
                </div>

                <p class="font-bold text-sm text-teal-600">
                    Rp <?= number_format($item['harga'] * $item['qty']) ?>
                </p>
            </div>

            <?php endforeach; ?>
        <?php endforeach; ?>

    </div>

    <!-- TOTAL (STICKY BOTTOM) -->
    <div class="sticky bottom-0 bg-gradient-to-r from-teal-500 to-emerald-500 text-white rounded-xl px-5 py-4 mt-4 flex justify-between text-lg font-bold shadow-lg">
        <span>Total Bayar</span>
        <span>Rp <?= number_format($total) ?></span>
    </div>

</div>


<!-- ================= RIGHT ================= -->
<div class="bg-white rounded-2xl p-6 h-[600px] flex flex-col">

    <!-- HEADER -->
    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 sticky top-0 bg-white z-10 pb-3">
        💳 Informasi Pembayaran
    </h2>

    <!-- ISI (SCROLL) -->
    <div class="overflow-y-auto pr-2 flex-1 space-y-6">

        <?php foreach ($penjuals as $penjual_id => $p): ?>
        <div class="border border-gray-100 rounded-2xl p-5 bg-gradient-to-br from-white to-gray-50 shadow hover:shadow-lg transition">

        <!-- 🔒 DATA PENTING (WAJIB ADA) -->
    <input type="hidden" name="penjual_id[]" value="<?= $penjual_id ?>">
    <input type="hidden" name="total_penjual[<?= $penjual_id ?>]" value="<?= $p['total'] ?>">


            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-lg text-gray-800">
                    🏪 <?= htmlspecialchars($p['nama']) ?>
                </h3>
                <span class="font-semibold text-teal-600">
                    Rp <?= number_format($p['total']) ?>
                </span>
            </div>

            <!-- METODE -->
            <div class="mt-3 flex gap-4 text-sm">
                <label class="flex-1 border rounded-xl px-4 py-3 flex items-center gap-3 cursor-pointer hover:border-teal-500 transition">
                    <input type="radio"
                           name="metode[<?= $penjual_id ?>]"
                           value="transfer"
                           checked
                           onchange="toggleMetode(<?= $penjual_id ?>)">
                    <span class="font-semibold">🏦 Transfer</span>
                </label>

                <label class="flex-1 border rounded-xl px-4 py-3 flex items-center gap-3 cursor-pointer hover:border-teal-500 transition">
                    <input type="radio"
                           name="metode[<?= $penjual_id ?>]"
                           value="qris"
                           onchange="toggleMetode(<?= $penjual_id ?>)">
                    <span class="font-semibold">📱 QRIS</span>
                </label>
            </div>

            <!-- BANK -->
            <div id="bank-box-<?= $penjual_id ?>" class="mt-4 bg-gray-100 p-4 rounded-xl">
                <p class="text-xs text-gray-500">Rekening Tujuan</p>
                <p class="font-bold">
                    <?= $p['bank'] ?> - <?= $p['no_rekening'] ?>
                </p>
            </div>

            <!-- QRIS -->
            <?php if ($p['qris']): ?>
            <div id="qris-box-<?= $penjual_id ?>" class="mt-4 hidden flex justify-center">
                <img src="../<?= $p['qris'] ?>"
                     class="w-48 rounded-2xl border shadow hover:scale-105 transition">
            </div>
            <?php endif; ?>

            <!-- UPLOAD -->
            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">
                    📎 Upload Bukti
                </label>
                <input type="file"
                       name="bukti[<?= $penjual_id ?>]"
                       required
                       class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

        </div>
        <?php endforeach; ?>
    </div>
    <!-- tombol selesaikan pembayaran -->
        <div class="sticky bottom-0 bg-white pt-4">
        <button type="submit"
            class="w-full bg-gradient-to-r from-teal-500 to-emerald-500
                   text-white px-6 py-4 rounded-2xl shadow-xl
                   font-bold text-lg hover:scale-105 transition">
            ✅ Selesaikan Pembayaran
        </button>
    </div>
</div>

</div>

<script>
function toggleMetode(id) {
    const transfer = document.querySelector(
        `input[name="metode[${id}]"][value="transfer"]`
    ).checked;

    document.getElementById('bank-box-' + id).style.display =
        transfer ? 'flex' : 'none';

    const qris = document.getElementById('qris-box-' + id);
    if (qris) qris.style.display = transfer ? 'none' : 'block';
}
</script>
</form>
</body>
</html>
