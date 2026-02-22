<?php
session_start();
include '../config/koneksi.php';

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);

/* ================= QUERY KERANJANG ================= */
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

/* ================= KELOMPOK PER PENJUAL ================= */
$penjuals = [];

while ($row = mysqli_fetch_assoc($query)) {
    $pid = $row['penjual_id'];

    if (!isset($penjuals[$pid])) {
        $penjuals[$pid] = [
            'nama'         => $row['nama_penjual'],
            'bank'         => $row['bank'],
            'no_rekening'  => $row['no_rekening'],
            'qris'         => $row['qris'],
            'items'        => [],
            'total'        => 0
        ];
    }

    $penjuals[$pid]['items'][] = $row;
    $penjuals[$pid]['total'] += $row['harga'] * $row['qty'];
}

/* ================= TOTAL KESELURUHAN ================= */
$total = 0;
foreach ($penjuals as $p) {
    $total += $p['total'];
}

/* ================= CEK KERANJANG KOSONG ================= */
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
input[type=radio] { accent-color: #14b8a6; }
</style>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

<div class="max-w-7xl mx-auto py-10 px-6">

<form action="checkout_proses.php" method="POST" enctype="multipart/form-data">

<div class="bg-white rounded-3xl shadow-xl p-10 grid grid-cols-1 lg:grid-cols-[1.4fr_1fr] gap-12">

<!-- ================= LEFT : RINGKASAN ================= -->
<div class="bg-gray-50 rounded-2xl p-6 flex flex-col h-[600px]">

    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 sticky top-0 bg-gray-50 z-10 pb-3">
        🧾 Ringkasan Pesanan
    </h2>

    <div class="space-y-3 overflow-y-auto pr-2 flex-1">
        <?php foreach ($penjuals as $p): ?>
            <?php foreach ($p['items'] as $item): ?>
                <div class="bg-white rounded-2xl p-4 shadow flex justify-between items-center">
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

    <div class="sticky bottom-0 bg-gradient-to-r from-teal-500 to-emerald-500
                text-white rounded-xl px-5 py-4 mt-4 flex justify-between
                text-lg font-bold shadow-lg">
        <span>Total Bayar</span>
        <span>Rp <?= number_format($total) ?></span>
    </div>

</div>

<!-- ================= RIGHT : PEMBAYARAN ================= -->
<div class="bg-white rounded-2xl p-6 h-[600px] flex flex-col">

    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 sticky top-0 bg-white z-10 pb-3">
        💳 Informasi Pembayaran
    </h2>

    <div class="overflow-y-auto pr-2 flex-1 space-y-6">

        <?php foreach ($penjuals as $penjual_id => $p): ?>
        <div class="border rounded-2xl p-5 bg-gray-50 shadow">

            <!-- DATA WAJIB -->
            <input type="hidden" name="penjual_id[]" value="<?= $penjual_id ?>">
            <input type="hidden" name="total_penjual[<?= $penjual_id ?>]" value="<?= $p['total'] ?>">

            <div class="flex justify-between mb-3">
                <h3 class="font-bold text-lg">🏪 <?= htmlspecialchars($p['nama']) ?></h3>
                <span class="font-semibold text-teal-600">
                    Rp <?= number_format($p['total']) ?>
                </span>
            </div>

            <!-- METODE -->
            <div class="flex gap-4 text-sm">
                <label class="flex-1 border rounded-xl px-4 py-3 flex gap-3 cursor-pointer">
                    <input type="radio"
                           name="metode[<?= $penjual_id ?>]"
                           value="transfer"
                           checked
                           onchange="toggleMetode(<?= $penjual_id ?>)">
                    🏦 Transfer
                </label>

                <label class="flex-1 border rounded-xl px-4 py-3 flex gap-3 cursor-pointer">
                    <input type="radio"
                           name="metode[<?= $penjual_id ?>]"
                           value="qris"
                           onchange="toggleMetode(<?= $penjual_id ?>)">
                    📱 QRIS
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
                <img src="../<?= $p['qris'] ?>" class="w-48 rounded-xl border">
            </div>
            <?php endif; ?>

            <!-- UPLOAD -->
            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">
                    📎 Upload Bukti
                </label>
                <input type="file"
                       name="bukti[<?= $penjual_id ?>]"
                       
                       class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

        </div>
        <?php endforeach; ?>

    </div>

    <div class="sticky bottom-0 bg-white pt-4">
        <button type="submit"
                class="w-full bg-gradient-to-r from-teal-500 to-emerald-500
                       text-white px-6 py-4 rounded-2xl font-bold text-lg shadow-xl">
            ✅ Selesaikan Pembayaran
        </button>
    </div>

</div>
</div>

</form>
</div>

<script>
function toggleMetode(id) {
    const transfer = document.querySelector(
        `input[name="metode[${id}]"][value="transfer"]`
    ).checked;

    document.getElementById('bank-box-' + id).style.display =
        transfer ? 'block' : 'none';

    const qris = document.getElementById('qris-box-' + id);
    if (qris) qris.style.display = transfer ? 'none' : 'block';
}
/* ================= VALIDASI UPLOAD ================= */
document.querySelector('form').addEventListener('submit', function(e) {
    const inputs = document.querySelectorAll('input[type="file"]');
    let belumUpload = false;

    inputs.forEach(input => {
        if (!input.files || input.files.length === 0) {
            belumUpload = true;
        }
    });

    if (belumUpload) {
        e.preventDefault(); // stop submit
        alert("⚠️ Silakan upload bukti pembayaran untuk semua penjual sebelum melanjutkan checkout!");
    }
});
</script>

</body>
</html>
