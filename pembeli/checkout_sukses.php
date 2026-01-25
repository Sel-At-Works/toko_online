<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_SESSION['invoice'])) {
    header("Location: dashboard.php");
    exit;
}

$invoice = $_SESSION['invoice'];

$alamat = $invoice['alamat'] ?? [
    'nama' => '-',
    'telp' => '-',
    'alamat' => 'Alamat belum tersedia',
    'kota' => '',
    'provinsi' => ''
];


?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice Pesanan</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
        @media print {
    @page {
        size: A4;
        margin: 15mm;
    }

    body {
        background: white;
        margin: 0;
        padding: 0;
    }

    .no-print {
        display: none !important;
    }

    .print-area {
        width: 100%;
        max-width: 180mm;
        margin: 0 auto;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
}

    </style>
</head>

<body class="bg-gray-100 min-h-screen flex justify-center p-4">

<div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 text-sm font-sans print-area">



    <!-- HEADER -->
    <div class="text-center border-b pb-3 mb-4">
        <h1 class="text-lg font-bold tracking-wide">TOKO ONLINE</h1>
        <p class="text-xs text-gray-500">Bukti Pembayaran / Invoice</p>
    </div>

    <!-- INFO -->
    <div class="mb-4 space-y-1">
        <div class="flex justify-between">
            <span>No Invoice</span>
            <span><?= $invoice['kode'] ?></span>
        </div>
        <div class="flex justify-between">
            <span>Tanggal</span>
            <span><?= $invoice['tanggal'] ?></span>
        </div>
        <div class="flex justify-between">
            <span>Status</span>
            <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-700 font-semibold">
    <?= $invoice['status'] ?>
</span>

        </div>
    </div>

    <!-- ALAMAT -->
    <div class="border-t border-b py-3 mb-4">
        <p class="font-bold mb-1">📦 Alamat Pengiriman</p>
        <p><?= htmlspecialchars($alamat['nama']) ?></p>
        <p><?= htmlspecialchars($alamat['telp']) ?></p>
        <p><?= htmlspecialchars($alamat['alamat']) ?></p>
        <?php if (!empty($alamat['kota']) && !empty($alamat['provinsi']) && $alamat['kota'] !== '-'): ?>
    <p><?= htmlspecialchars($alamat['kota']) ?>, <?= htmlspecialchars($alamat['provinsi']) ?></p>
<?php endif; ?>

    </div>

    <!-- PRODUK -->
    <div class="mb-4">
        <p class="font-bold mb-2">🧾 Detail Pesanan</p>

        <?php foreach ($invoice['items'] as $item): ?>
        <div class="flex justify-between mb-1">
            <div>
                <p><?= htmlspecialchars($item['nama_produk']) ?></p>
                <p class="text-xs text-gray-500">x<?= $item['qty'] ?></p>
            </div>
            <p>Rp <?= number_format($item['harga'] * $item['qty']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- TOTAL -->
    <div class="border-t pt-3 mb-4">
        <div class="flex justify-between font-bold text-lg text-gray-800">
            <span>TOTAL</span>
            <span>Rp <?= number_format($invoice['total']) ?></span>
        </div>
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
       <a href="cetak_invoice.php" target="_blank"
   class="border py-2 rounded text-center font-bold">
    📄 Download / Cetak PDF
</a>

    </div>

</div>

</body>
</html>

