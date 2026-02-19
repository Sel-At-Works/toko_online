<?php
session_start();
include '../config/koneksi.php';

$transaksi_id = intval($_GET['transaksi_id'] ?? 0);
if ($transaksi_id <= 0) die('Transaksi tidak valid');

/* Ambil transaksi */
$q = mysqli_query($conn, "
    SELECT t.id, t.total, t.created_at, u.nama, u.alamat, t.no_telepon
    FROM transaksi t
    JOIN users u ON t.pembeli_id = u.id
    WHERE t.id = $transaksi_id
");
$transaksi = mysqli_fetch_assoc($q);
if (!$transaksi) die('Transaksi tidak ditemukan');

$qResi = mysqli_query($conn, "
    SELECT tp.id AS tp_id, u.nama AS nama_penjual, tp.resi, LOWER(tp.status) AS status
    FROM transaksi_penjual tp
    JOIN users u ON tp.penjual_id = u.id
    WHERE tp.transaksi_id = $transaksi_id
");

$resi_list = [];
while ($r = mysqli_fetch_assoc($qResi)) {
    $resi_list[$r['tp_id']] = [
        'tp_id' => $r['tp_id'],
        'nama_penjual' => $r['nama_penjual'],
        'resi' => $r['resi'] ?: '-',   // tampilkan resi jika ada
        'status' => $r['status'] ?: 'menunggu'
    ];
}

/* Ambil detail produk */
$qDetail = mysqli_query($conn, "
    SELECT d.qty, d.harga, p.nama_produk
    FROM transaksi_detail d
    JOIN produk p ON d.produk_id = p.id
    WHERE d.transaksi_id = $transaksi_id
      AND EXISTS (
          SELECT 1
          FROM transaksi_penjual tp
          WHERE tp.transaksi_id = d.transaksi_id
            AND tp.penjual_id = p.penjual_id
      )
");
$items = [];
$qDetail = mysqli_query($conn, "
    SELECT td.qty, td.harga, p.nama_produk, tp.id AS tp_id
    FROM transaksi_detail td
    JOIN produk p ON td.produk_id = p.id
    JOIN transaksi_penjual tp ON tp.transaksi_id = td.transaksi_id AND tp.penjual_id = p.penjual_id
    WHERE td.transaksi_id = $transaksi_id
");
while ($d = mysqli_fetch_assoc($qDetail)) {
    $items[$d['tp_id']][] = $d; // kelompokkan per tp_id
}

/* Tentukan status global */
$allStatuses = [];
foreach ($resi_list as $r) {
    $allStatuses[] = $r['status'] ?: 'menunggu';
}

$total      = count($allStatuses);
$dikirim    = count(array_filter($allStatuses, fn($s) => $s === 'dikirim'));
$selesai    = count(array_filter($allStatuses, fn($s) => $s === 'selesai'));
$refund     = count(array_filter($allStatuses, fn($s) => $s === 'refund'));
$diproses   = count(array_filter($allStatuses, fn($s) => $s === 'diproses'));
$menunggu   = count(array_filter($allStatuses, fn($s) => $s === 'menunggu'));

/* Prioritas kombinasi */
if ($refund == $total && $total > 0) {
    $status_global = 'refund';
} elseif ($selesai == $total && $total > 0) {
    $status_global = 'selesai';
} elseif ($dikirim == $total && $total > 0) {
    $status_global = 'dikirim';
}
/* Kombinasi selesai + dikirim */
elseif ($selesai > 0 && $dikirim > 0 && $refund == 0) {
    $status_global = 'sebagian_selesai';
}
/* Kombinasi selesai + refund */
elseif ($selesai > 0 && $refund > 0 && $dikirim == 0) {
    $status_global = 'sebagian_selesai_refund';
}
/* Kombinasi dikirim + refund */
elseif ($dikirim > 0 && $refund > 0 && $selesai == 0) {
    $status_global = 'sebagian_dikirim_refund';
}
/* Kombinasi semua: selesai + dikirim + refund */
elseif ($selesai > 0 && $dikirim > 0 && $refund > 0) {
    $status_global = 'sebagian_selesai_dikirim_refund';
}
/* Masih diproses atau menunggu */
elseif ($diproses > 0 || $menunggu > 0) {
    $status_global = 'diproses';
} else {
    $status_global = 'menunggu_verifikasi';
}


$invoice = [
    'kode' => 'INV-' . date('Ymd', strtotime($transaksi['created_at'])) . '-' . $transaksi_id,
    'tanggal' => date('d M Y H:i', strtotime($transaksi['created_at'])),
    'status' => $status_global,
    'total' => $transaksi['total'],
    'resi_list' => $resi_list,
    'alamat' => [
        'nama' => $transaksi['nama'],
        'telp' => $transaksi['no_telepon'],
        'alamat' => $transaksi['alamat'],
        'kota' => '',
        'provinsi' => ''
    ],
    'items' => $items
];

$alamat = $invoice['alamat'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice Pesanan</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@media print {
    body { background: white; margin: 0; padding: 0; }
    .no-print { display: none !important; }
    .print-area { max-width: 180mm; margin: 0 auto; }
}
</style>
</head>
<body class="bg-gray-100 flex justify-center p-4 min-h-screen">

<div class="bg-white w-full max-w-md rounded-xl shadow-xl p-6 text-sm font-sans print-area">

    <!-- HEADER -->
    <div class="text-center border-b pb-3 mb-4">
        <h1 class="text-xl font-bold tracking-wide">TOKO ONLINE</h1>
        <p class="text-xs text-gray-500">Bukti Pembayaran / Invoice</p>
    </div>

    <!-- INFO INVOICE -->
    <div class="mb-4 space-y-2 text-gray-700">
        <div class="flex justify-between">
            <span class="font-medium">No Invoice</span>
            <span><?= $invoice['kode'] ?></span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium">Tanggal</span>
            <span><?= $invoice['tanggal'] ?></span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium">Status</span>
            <?php
        $badgeColor = match ($status_global) {
                'menunggu_verifikasi' => 'bg-orange-100 text-orange-700',
                'diproses' => 'bg-gray-100 text-gray-700',
                'dikirim' => 'bg-blue-100 text-blue-700',
                'sebagian_dikirim' => 'bg-yellow-100 text-yellow-700',
                'selesai' => 'bg-green-100 text-green-700',
                'sebagian_selesai' => 'bg-yellow-200 text-green-800',
                'refund' => 'bg-red-100 text-red-700',
                'sebagian_selesai_refund' => 'bg-purple-100 text-purple-800',
                'sebagian_dikirim_refund' => 'bg-pink-100 text-pink-700',
                'sebagian_selesai_dikirim_refund' => 'bg-red-200 text-red-800',
                default => 'bg-gray-100 text-gray-700'
            };

            ?>
            <span class="px-2 py-1 text-xs rounded-full font-semibold <?= $badgeColor ?>">
                <?= $status_global ?>
            </span>
        </div>
     <div class="mb-4">
    <p class="font-medium mb-2">Nomor Resi</p>
    <div class="space-y-2">
        <?php if (!empty($invoice['resi_list'])): ?>
            <?php foreach ($invoice['resi_list'] as $r): ?>
                <div class="border rounded p-2 bg-gray-50 flex justify-between items-center">
                    <div class="text-gray-700 font-semibold"><?= htmlspecialchars($r['nama_penjual']) ?></div>
                    <div class="text-right text-gray-600 text-xs">
                    <?php
                    if ($r['status'] === 'selesai') {
                        echo '<span class="text-green-600">✅ Selesai</span><br>';
                        echo '<span class="font-mono">' . ($r['resi'] ?: '-') . '</span>';
                    } elseif ($r['status'] === 'dikirim') {
                        echo '<span class="text-blue-600">🚚 Dikirim</span><br>';
                        echo '<span class="font-mono">' . ($r['resi'] ?: '-') . '</span>';
                    } elseif ($r['status'] === 'refund') {
                        echo '<span class="text-red-600">❌ Refund</span>';
                    } else {
                        echo '<span class="text-orange-600">⌛ Menunggu</span>';
                    }
                    ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-gray-500">-</div>
        <?php endif; ?>
    </div>
</div>

    </div>

    <!-- ALAMAT -->
    <div class="border-t border-b py-3 mb-4 text-gray-700">
        <p class="font-medium mb-1">📦 Alamat Pengiriman</p>
        <p><?= htmlspecialchars($alamat['nama']) ?></p>
        <p><?= htmlspecialchars($alamat['telp']) ?></p>
        <p><?= htmlspecialchars($alamat['alamat']) ?></p>
    </div>

    <!-- PRODUK -->
 <!-- PRODUK PER PENJUAL -->
<div class="mb-4 text-gray-700">
    <p class="font-medium mb-2">🧾 Detail Pesanan</p>

    <?php foreach($resi_list as $r): ?>
        <div class="mb-2 p-2 border rounded bg-gray-50">
            <div class="font-semibold mb-1"><?= htmlspecialchars($r['nama_penjual']) ?></div>
            <ul class="ml-2">
                <?php foreach($items[$r['tp_id']] ?? [] as $item): ?>
                    <li><?= htmlspecialchars($item['nama_produk']) ?> x<?= $item['qty'] ?> - Rp<?= number_format($item['harga'] * $item['qty'],0,',','.') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>

</div>


    <!-- TOTAL -->
    <div class="border-t pt-3 mb-4 flex justify-between font-bold text-gray-800 text-lg">
        <span>TOTAL</span>
        <span>Rp <?= number_format($invoice['total'], 0, ',', '.') ?></span>
    </div>

    <!-- FOOTER -->
    <div class="text-center text-xs text-gray-500 border-t pt-3">
        Terima kasih telah berbelanja 🙏<br>
        Simpan invoice ini sebagai bukti pembayaran
    </div>

    <!-- BUTTON -->
    <div class="mt-4 flex flex-col gap-2 no-print">
        <a href="dashboard.php" class="bg-teal-600 text-white py-2 rounded text-center font-bold">
            Kembali ke Dashboard
        </a>
        <a href="cetak_invoice.php" target="_blank" class="border py-2 rounded text-center font-bold">
            📄 Download / Cetak PDF
        </a>
    </div>

</div>

</body>
</html>
