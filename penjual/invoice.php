<?php
session_start();
include '../config/koneksi.php';

// pastikan yang login adalah penjual
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'penjual') {
    die('Akses ditolak');
}

$penjual_id = $_SESSION['user_id']; // id penjual dari session
$transaksi_id = intval($_GET['transaksi_id'] ?? 0);

if ($transaksi_id <= 0) {
    die('Transaksi tidak valid');
}

/* === AMBIL TRANSAKSI === */
$q = mysqli_query($conn, "
    SELECT 
        t.id,
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

/* === AMBIL STATUS PENJUAL === */
$qStatus = mysqli_query($conn, "
    SELECT status
    FROM transaksi_penjual
    WHERE transaksi_id = $transaksi_id
    AND penjual_id = $penjual_id
    LIMIT 1
");
$status_row = mysqli_fetch_assoc($qStatus);
$status_penjual = $status_row['status'] ?? 'menunggu';

/* === AMBIL NOMOR RESI === */
$qResi = mysqli_query($conn, "
    SELECT resi
    FROM transaksi_penjual
    WHERE transaksi_id = $transaksi_id
    AND penjual_id = $penjual_id
    LIMIT 1
");
$resi_row = mysqli_fetch_assoc($qResi);
$resi = $resi_row['resi'] ?? ''; // <-- ini

/* === AMBIL PRODUK HANYA MILIK PENJUAL INI === */
$qDetail = mysqli_query($conn, "
    SELECT 
        d.qty,
        d.harga,
        p.nama_produk
    FROM transaksi_detail d
    JOIN produk p ON d.produk_id = p.id
    WHERE d.transaksi_id = $transaksi_id
    AND p.penjual_id = $penjual_id
");

$items = [];
$total = 0;
while ($d = mysqli_fetch_assoc($qDetail)) {
    $items[] = $d;
    $total += $d['harga'] * $d['qty'];
}

/* === INVOICE PENJUAL === */
$invoice = [
    'kode'    => 'INV-' . date('Ymd', strtotime($transaksi['created_at'])) . '-' . $transaksi_id,
    'tanggal' => date('d M Y H:i', strtotime($transaksi['created_at'])),
    'total'   => $total,
    'status'  => $status_penjual,
     'resi'    => $resi, 
    'alamat'  => [
        'nama'   => $transaksi['nama'],
        'telp'   => $transaksi['no_telepon'],
        'alamat' => $transaksi['alamat']
    ],
    'items' => $items
];

$alamat = $invoice['alamat'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Penjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: A4; margin: 15mm; }
            body { background: white; margin:0; padding:0; }
            .no-print { display: none !important; }
            .print-area { width:100%; max-width:180mm; margin:0 auto; box-shadow:none !important; border-radius:0 !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center p-4">

<div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 text-sm font-sans print-area">

    <!-- HEADER -->
    <div class="text-center border-b pb-3 mb-4">
        <h1 class="text-lg font-bold tracking-wide">TOKO ONLINE</h1>
        <p class="text-xs text-gray-500">Invoice Penjual</p>
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
 <div class="flex justify-between items-center">
    <span>Status</span>
    <?php
    $badge = match ($invoice['status']) {
        'menunggu' => 'bg-orange-100 text-orange-700',
        'diproses' => 'bg-blue-100 text-blue-700',
        'dikirim' => 'bg-blue-200 text-blue-800',
        'selesai'  => 'bg-green-100 text-green-700',
        'refund'   => 'bg-red-100 text-red-700',
        default    => 'bg-gray-100 text-gray-700'
    };
    ?>
    <span class="px-2 py-1 text-xs rounded-full font-semibold <?= $badge ?>">
        <?= ucfirst($invoice['status']) ?>
    </span>
</div>

        <div class="flex justify-between">
    <span>Nomor Resi</span>
    <span><?= $invoice['resi'] ?: '-' ?></span>
</div>

    </div>

    <!-- ALAMAT -->
    <div class="border-t border-b py-3 mb-4">
        <p class="font-bold mb-1">📦 Alamat Pembeli</p>
        <p><?= htmlspecialchars($alamat['nama']) ?></p>
        <p><?= htmlspecialchars($alamat['telp']) ?></p>
        <p><?= htmlspecialchars($alamat['alamat']) ?></p>
    </div>

    <!-- PRODUK -->
    <div class="mb-4">
        <p class="font-bold mb-2">🧾 Detail Produk (Milik Penjual)</p>
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
        Terima kasih telah berjualan 🙏<br>
        Simpan invoice ini sebagai bukti transaksi
    </div>

    <!-- BUTTON -->
    <div class="mt-4 flex flex-col gap-2 no-print">
        <a href="../penjual/dashboard.php"
           class="bg-teal-600 text-white py-2 rounded text-center font-bold">
            Kembali ke Dashboard Penjual
        </a>
        <button onclick="window.print()"
           class="border py-2 rounded text-center font-bold">
           📄 Cetak Invoice
        </button>
    </div>

</div>
</body>
</html>
