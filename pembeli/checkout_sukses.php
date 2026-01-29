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

/* Ambil resi per penjual */
$qResi = mysqli_query($conn, "
    SELECT u.nama AS nama_penjual, tp.resi, tp.status
    FROM transaksi_penjual tp
    JOIN users u ON tp.penjual_id = u.id
    WHERE tp.transaksi_id = $transaksi_id
");
$resi_list = [];
while ($r = mysqli_fetch_assoc($qResi)) {
    $resi_list[] = [
        'nama_penjual' => $r['nama_penjual'],
        'resi' => $r['resi'],
        'status' => $r['status']
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
while ($d = mysqli_fetch_assoc($qDetail)) {
    $items[] = $d;
}

/* Tentukan status global */
$allStatuses = array_column($resi_list, 'status');
$status_global = 'Menunggu';
if (in_array('dikirim', $allStatuses) && !in_array('menunggu_verifikasi', $allStatuses) && !in_array('refund', $allStatuses)) {
    $status_global = 'Dikirim';
} elseif (in_array('refund', $allStatuses) && !in_array('dikirim', $allStatuses)) {
    $status_global = 'Refund';
} elseif (in_array('dikirim', $allStatuses) && in_array('refund', $allStatuses)) {
    $status_global = 'Sebagian Dikirim';
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
                'Menunggu' => 'bg-orange-100 text-orange-700',
                'Dikirim' => 'bg-blue-100 text-blue-700',
                'Sebagian Dikirim' => 'bg-yellow-100 text-yellow-700',
                'Refund' => 'bg-red-100 text-red-700',
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
                        <?php if ($r['status'] === 'dikirim'): ?>
                            <span class="text-green-600">✅ Dikirim</span><br>
                            <span class="font-mono"><?= $r['resi'] ?: '-' ?></span>
                        <?php elseif ($r['status'] === 'refund'): ?>
                            <span class="text-red-600">❌ Refund</span>
                        <?php else: ?>
                            <span class="text-orange-600">⌛ Menunggu</span>
                        <?php endif; ?>
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
    <div class="mb-4 text-gray-700">
        <p class="font-medium mb-2">🧾 Detail Pesanan</p>
        <?php foreach ($invoice['items'] as $item): ?>
            <div class="flex justify-between mb-1">
                <div>
                    <p><?= htmlspecialchars($item['nama_produk']) ?></p>
                    <p class="text-xs text-gray-500">x<?= $item['qty'] ?></p>
                </div>
                <p>Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></p>
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
