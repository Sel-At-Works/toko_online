<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit;
}

$penjual_id = intval($_SESSION['user_id']);
$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

$filter = "WHERE tp.penjual_id = $penjual_id 
           AND tp.approve = 'setuju' "; // hanya setuju
if ($start && $end) {
    $filter .= " AND tp.updated_at BETWEEN '$start 00:00:00' AND '$end 23:59:59' ";
}

$transaksiQ = mysqli_query($conn, "
    SELECT tp.id as tp_id, tp.transaksi_id, tp.total, tp.updated_at, t.pembeli_id
    FROM transaksi_penjual tp
    JOIN transaksi t ON t.id = tp.transaksi_id
    $filter
    ORDER BY tp.updated_at DESC
");

$transaksiList = [];
$grafikData = [];
$totalKeuntungan = 0;

while ($t = mysqli_fetch_assoc($transaksiQ)) {
    // Gunakan updated_at dan tp_id / transaksi_id
    $kode = 'INV-' . date('Ymd', strtotime($t['updated_at'])) . '-' . $t['transaksi_id'];

$detailQ = mysqli_query($conn, "
    SELECT d.qty, p.nama_produk, p.harga_modal, p.harga
    FROM transaksi_detail d
    JOIN produk p ON d.produk_id = p.id
    WHERE d.transaksi_id = {$t['transaksi_id']}
      AND p.penjual_id = $penjual_id
");

    $transaksiProduk = [];
    while ($d = mysqli_fetch_assoc($detailQ)) {
        $keuntungan = $d['qty'] * ($d['harga'] - $d['harga_modal']);
        $totalKeuntungan += $keuntungan;
        $transaksiProduk[] = [
            'nama_produk' => $d['nama_produk'],
            'qty' => $d['qty'],
            'harga_modal' => $d['harga_modal'],
            'harga' => $d['harga'],
            'keuntungan' => $keuntungan
        ];

        $tgl = date('Y-m-d', strtotime($t['updated_at']));
        if (!isset($grafikData[$tgl])) $grafikData[$tgl] = 0;
        $grafikData[$tgl] += $keuntungan;
    }

    $transaksiList[] = [
        'tanggal' => $t['updated_at'],
        'kode' => $kode,
        'produk' => $transaksiProduk
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Transaksi Penjual</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md flex-shrink-0">
        <?php include '../layouts/sidebar_penjual.php'; ?>
    </aside>

    <!-- MAIN -->
<main class="flex-1 p-6 overflow-y-auto bg-gradient-to-br from-slate-100 to-slate-200">

    <!-- HEADER -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6 mb-6 border border-white">
        <div class="flex flex-wrap gap-4 items-center justify-between">

            <!-- TITLE -->
            <div>
                <h1 class="text-3xl font-extrabold text-teal-700 tracking-wide">
                    📊 Laporan Transaksi Penjual
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Analisis performa penjualan & keuntungan
                </p>
            </div>

            <!-- profile_notifikasi -->
            <?php include '../layouts/profil_notifikasi.php'; ?>
             <!-- FILTER -->
            <form method="GET" class="flex gap-2 items-center">
                <input type="date" name="start" value="<?= htmlspecialchars($start) ?>" 
                    class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-400 outline-none">
                <span class="text-gray-500">—</span>
                <input type="date" name="end" value="<?= htmlspecialchars($end) ?>" 
                    class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-400 outline-none">
                <button type="submit"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold shadow-lg hover:scale-105 transition">
                    Filter
                </button>
            </form>
            <!-- dowload pdf -->
            <div class="flex items-center gap-3">
                <button id="downloadPdf"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold shadow-lg hover:scale-105 transition">
                    ⬇ Download PDF
                </button>
            </div>

        </div>
    </div>

    <!-- SUMMARY CARD -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-teal-500">
            <p class="text-sm text-gray-500">Total Keuntungan</p>
            <h2 class="text-2xl font-extrabold text-teal-700 mt-2">
                Rp<?= number_format($totalKeuntungan,0,',','.') ?>
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-emerald-500">
            <p class="text-sm text-gray-500">Jumlah Transaksi</p>
            <h2 class="text-2xl font-extrabold text-emerald-600 mt-2">
                <?= count($transaksiList) ?>
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-blue-500">
            <p class="text-sm text-gray-500">Periode</p>
            <h2 class="text-lg font-bold text-blue-600 mt-2">
                <?= $start && $end ? date('d M Y', strtotime($start))." - ".date('d M Y', strtotime($end)) : 'Semua Data' ?>
            </h2>
        </div>

    </div>

    <!-- TABLE CARD -->
    <div id="laporanTable" class="bg-white rounded-2xl shadow-xl p-6 mb-8">
        <h2 class="text-xl font-bold text-teal-700 mb-4">📋 Detail Transaksi</h2>

        <div class="overflow-auto max-h-[420px] rounded-xl border">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-teal-500 to-emerald-500 text-white sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                        <th class="px-4 py-3">Modal</th>
                        <th class="px-4 py-3">Jual</th>
                        <th class="px-4 py-3">Keuntungan</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=0; foreach ($transaksiList as $t): ?>
                    <?php foreach ($t['produk'] as $d): ?>
                        <tr class="<?= $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?> hover:bg-teal-50 transition">
                            <td class="px-4 py-2"><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                            <td class="px-4 py-2 font-semibold text-gray-700"><?= $t['kode'] ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($d['nama_produk']) ?></td>
                            <td class="px-4 py-2 text-center font-bold"><?= $d['qty'] ?></td>
                            <td class="px-4 py-2">Rp<?= number_format($d['harga_modal'],0,',','.') ?></td>
                            <td class="px-4 py-2">Rp<?= number_format($d['harga'],0,',','.') ?></td>
                            <td class="px-4 py-2 font-bold text-emerald-600">
                                Rp<?= number_format($d['keuntungan'],0,',','.') ?>
                            </td>
                        </tr>
                    <?php $i++; endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- GRAFIK CARD -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <h2 class="text-xl font-bold text-teal-700 mb-4">📈 Grafik Keuntungan</h2>
        <div class="bg-slate-50 rounded-xl p-4">
            <canvas id="grafikKeuntungan"></canvas>
        </div>
    </div>

</main>

<script>
    // DATA GRAFIK
    const labels = <?= json_encode(array_keys($grafikData)) ?>;
    const data = <?= json_encode(array_values($grafikData)) ?>;

    const ctx = document.getElementById('grafikKeuntungan').getContext('2d');
    const chartKeuntungan = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Keuntungan (Rp)',
                data: data,
                backgroundColor: 'rgba(22,163,74,0.2)',
                borderColor: 'rgba(22,163,74,1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(22,163,74,1)'
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

    // DOWNLOAD PDF termasuk total & grafik
    document.getElementById('downloadPdf').addEventListener('click', () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a4');
        doc.setFontSize(16);
        doc.text("Laporan Transaksi Penjual", 40, 40);

        const table = document.querySelector('#laporanTable table');
        doc.autoTable({ html: table, startY: 60, theme: 'grid', headStyles: { fillColor: [22,163,74] } });

        const total = <?= $totalKeuntungan ?>;
        const finalY = doc.lastAutoTable.finalY || 60;
        doc.setFontSize(14);
        doc.setTextColor(22,163,74);
        doc.text(`Total Keuntungan: Rp${total.toLocaleString()}`, 40, finalY + 20);

        // Grafik
        const canvas = document.getElementById('grafikKeuntungan');
        const chartImg = canvas.toDataURL('image/png',1.0);
        doc.addPage();
        doc.text("Grafik Keuntungan per Tanggal", 40, 40);
        doc.addImage(chartImg, 'PNG', 40, 60, 700, 300);

        doc.save('laporan_penjual.pdf');
    });
</script>