<?php
session_start();
include '../config/koneksi.php';

$transaksi_id = intval($_GET['transaksi_id'] ?? 0);

if ($transaksi_id <= 0) {
    die('Transaksi tidak valid');
}

/* === AMBIL TRANSAKSI === */
$q = mysqli_query($conn, "
    SELECT 
        t.id,
        t.total,
        t.status,
        t.created_at,
        u.nama,
        u.alamat,
        t.no_telepon
    FROM transaksi t
    JOIN users u ON t.pembeli_id = u.id
    WHERE t.id = $transaksi_id
");

$transaksi = mysqli_fetch_assoc($q);

if (!$transaksi) {
    die('Transaksi tidak ditemukan');
}

/* === DETAIL PRODUK === */
$qDetail = mysqli_query($conn, "
    SELECT 
        d.qty,
        d.harga,
        p.nama_produk
    FROM transaksi_detail d
    JOIN produk p ON d.produk_id = p.id
    WHERE d.transaksi_id = $transaksi_id
");

$items = [];
while ($d = mysqli_fetch_assoc($qDetail)) {
    $items[] = $d;
}

/* === INVOICE === */
$invoice = [
    'kode'    => 'INV-' . date('Ymd', strtotime($transaksi['created_at'])) . '-' . $transaksi_id,
    'tanggal' => date('d M Y H:i', strtotime($transaksi['created_at'])),
    'status'  => ucfirst($transaksi['status']),
    'total'   => $transaksi['total'],
    'alamat'  => [
        'nama'   => $transaksi['nama'],
        'telp'   => $transaksi['no_telepon'],
        'alamat' => $transaksi['alamat'],
        'kota'   => '',
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
                <?php
                $badge = match ($transaksi['status']) {
                    'menunggu_verifikasi' => 'bg-orange-100 text-orange-700',
                    'dikirim' => 'bg-blue-100 text-blue-700',
                    'selesai' => 'bg-green-100 text-green-700',
                    'refund' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700'
                };
                ?>
                <span class="px-2 py-1 text-xs rounded-full font-semibold <?= $badge ?>">
                    <?= ucfirst($transaksi['status']) ?>
                </span>

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

            <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'penjual'): ?>
                <a href="../penjual/dashboard.php"
                    class="bg-teal-600 text-white py-2 rounded text-center font-bold">
                    Kembali ke Dashboard Penjual
                </a>
            <?php else: ?>
                <a href="dashboard.php"
                    class="bg-teal-600 text-white py-2 rounded text-center font-bold">
                    Kembali ke Dashboard
                </a>
            <?php endif; ?>

            <a href="cetak_invoice.php" target="_blank"
                class="border py-2 rounded text-center font-bold">
                📄 Download / Cetak PDF
            </a>

        </div>

    </div>

</body>

</html>