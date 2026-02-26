<?php
session_start();
include '../config/koneksi.php';

// Cek login pembeli
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);
$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

$filter = "WHERE t.pembeli_id = $pembeli_id";

if ($start && $end) {
    $filter .= " AND t.created_at BETWEEN '$start 00:00:00' AND '$end 23:59:59'";
}

// Ambil transaksi pembeli
$transaksiQ = mysqli_query($conn, "
    SELECT t.*, CONCAT('INV-', DATE_FORMAT(t.created_at,'%Y%m%d'), '-', t.id) as kode_invoice
    FROM transaksi t
    $filter
    ORDER BY t.created_at DESC
");

$totalBelanja = 0;
$jumlahTransaksi = 0;
$transaksiList = [];
$grafikData = [];

while ($t = mysqli_fetch_assoc($transaksiQ)) {
    // Ambil produk non-refund
    $detailQ = mysqli_query($conn, "
        SELECT d.qty, p.nama_produk, d.harga
        FROM transaksi_detail d
        JOIN produk p ON d.produk_id = p.id
        LEFT JOIN transaksi_penjual tp 
          ON tp.transaksi_id = d.transaksi_id
          AND tp.penjual_id = p.penjual_id
        WHERE d.transaksi_id = {$t['id']}
        AND (tp.status IS NULL OR tp.status != 'refund')
    ");

    $produk = [];
    $totalTransaksi = 0;
    while($d = mysqli_fetch_assoc($detailQ)){
        $produk[] = $d;
        $totalTransaksi += $d['qty'] * $d['harga'];
    }

    // Lewati transaksi jika semua produk di-refund
    if (count($produk) === 0) continue;

    $jumlahTransaksi++;
    $totalBelanja += $totalTransaksi;

    $transaksiList[] = [
        'kode' => $t['kode_invoice'],
        'tanggal' => $t['created_at'],
        'total' => $totalTransaksi,
        'produk' => $produk
    ];

    $tgl = date('Y-m-d', strtotime($t['created_at']));
    if (!isset($grafikData[$tgl])) $grafikData[$tgl] = 0;
    $grafikData[$tgl] += $totalTransaksi;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pembeli</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-100 to-slate-200 font-sans">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md flex-shrink-0">
        <?php include '../layouts/sidebar_pembeli.php'; ?>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-6 overflow-y-auto">

        <!-- HEADER -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6 mb-6 border border-white">
            <div class="flex flex-wrap gap-4 items-center justify-between">

                <!-- TITLE -->
                <div>
                    <h1 class="text-3xl font-extrabold text-indigo-700 tracking-wide">
                        🛒 Laporan Pembelian Saya
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Riwayat transaksi & aktivitas belanja
                    </p>
                </div>

                <!-- FILTER -->
                <form method="GET" class="flex gap-2 items-center">
                    <input type="date" name="start" value="<?= htmlspecialchars($start) ?>" 
                        class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none">
                    <span class="text-gray-500">—</span>
                    <input type="date" name="end" value="<?= htmlspecialchars($end) ?>" 
                        class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none">
                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold shadow-lg hover:scale-105 transition">
                        Filter
                    </button>
                </form>
                <!-- PROFIL & NOTIFIKASI -->
                  <?php include '../layouts/profil_notifikasi.php'; ?>

                <!-- TOMBOL PDF -->
                <button id="btnPdf"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg hover:scale-105 transition">
                    ⬇ Unduh PDF
                </button>
            </div>
        </div>

        <!-- SUMMARY -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-indigo-500">
                <p class="text-sm text-gray-500">Total Belanja</p>
                <h2 class="text-2xl font-extrabold text-indigo-700 mt-2">
                    Rp<?= number_format($totalBelanja,0,',','.') ?>
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-purple-500">
                <p class="text-sm text-gray-500">Jumlah Transaksi</p>
                <h2 class="text-2xl font-extrabold text-purple-600 mt-2">
                    <?= $jumlahTransaksi ?>
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-blue-500">
                <p class="text-sm text-gray-500">Periode</p>
                <h2 class="text-lg font-bold text-blue-600 mt-2">
                    <?= $start && $end ? date('d M Y', strtotime($start))." - ".date('d M Y', strtotime($end)) : 'Semua Data' ?>
                </h2>
            </div>

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h2 class="text-xl font-bold text-indigo-700 mb-4">📋 Detail Pembelian</h2>

            <div class="overflow-auto max-h-[450px] rounded-xl border">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksiList as $t): ?>
                        <tr class="hover:bg-indigo-50 transition border-b align-top">
                            <td class="px-4 py-2 font-semibold text-gray-700"><?= $t['kode'] ?></td>

                            <td class="px-4 py-2">
                                <?php foreach($t['produk'] as $p): ?>
                                    <div>• <?= htmlspecialchars($p['nama_produk']) ?></div>
                                <?php endforeach; ?>
                            </td>

                            <td class="px-4 py-2 text-center font-bold">
                                <?php foreach($t['produk'] as $p): ?>
                                    <div><?= $p['qty'] ?></div>
                                <?php endforeach; ?>
                            </td>

                            <td class="px-4 py-2">
                                <?php foreach($t['produk'] as $p): ?>
                                    <div>Rp<?= number_format($p['harga'],0,',','.') ?></div>
                                <?php endforeach; ?>
                            </td>

                            <td class="px-4 py-2 font-bold text-emerald-600">
                                Rp<?= number_format($t['total'],0,',','.') ?>
                            </td>

                            <td class="px-4 py-2 text-gray-600">
                                <?= date('d M Y', strtotime($t['tanggal'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <!-- GRAFIK CARD -->
    <!-- <div class="bg-white rounded-2xl shadow-xl p-6 mt-8">
        <h2 class="text-xl font-bold text-indigo-700 mb-4">📈 Grafik Belanja</h2>
        <div class="bg-slate-50 rounded-xl p-4">
            <canvas id="grafikBelanja"></canvas>
        </div>
    </div> -->
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- <script>
const labels = <?= json_encode(array_keys($grafikData)) ?>;
const data = <?= json_encode(array_values($grafikData)) ?>;

const ctx = document.getElementById('grafikBelanja').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Belanja (Rp)',
            data: data,
            backgroundColor: 'rgba(99,102,241,0.2)',
            borderColor: 'rgba(99,102,241,1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgba(99,102,241,1)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp' + v.toLocaleString() }
            }
        }
    }
});
</script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.getElementById('btnPdf').addEventListener('click', () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a4');

    // ===== HEADER =====
    doc.setFontSize(16);
    doc.text("Laporan Pembelian Saya", 40, 40);

    doc.setFontSize(11);
    doc.text("Tanggal Cetak: " + new Date().toLocaleDateString('id-ID'), 40, 60);

    // ===== TABLE =====
    const table = document.querySelector('table');

    doc.autoTable({
        html: table,
        startY: 80,
        theme: 'grid',
        styles: { fontSize: 9 },
        headStyles: { fillColor: [99,102,241] }
    });

    // ===== SUMMARY =====
    const finalY = doc.lastAutoTable.finalY || 80;
    doc.setFontSize(12);
    doc.setTextColor(99,102,241);

    doc.text(`Total Belanja   : Rp<?= number_format($totalBelanja,0,',','.') ?>`, 40, finalY + 25);
    doc.text(`Jumlah Transaksi: <?= $jumlahTransaksi ?>`, 40, finalY + 45);
    doc.text(`Periode         : <?= $start && $end ? date('d M Y', strtotime($start))." - ".date('d M Y', strtotime($end)) : 'Semua Data' ?>`, 40, finalY + 65);

    // ===== GRAFIK (HALAMAN 2) =====
    // const canvas = document.getElementById('grafikBelanja');
    // const chartImg = canvas.toDataURL('image/png', 1.0);

    // doc.addPage();
    // doc.setFontSize(16);
    // doc.setTextColor(0,0,0);
    // doc.text("Grafik Belanja per Tanggal", 40, 40);
    // doc.addImage(chartImg, 'PNG', 40, 70, 700, 300);

    // ===== SAVE =====
    doc.save('laporan_pembeli.pdf');
});
</script>
</body>
</html>