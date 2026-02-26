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
<div class="bg-white rounded-2xl shadow-xl p-6 space-y-6">

    <h2 class="text-xl font-bold text-teal-700">📊 Dashboard Grafik Penjualan</h2>

    <!-- TOMBOL SWITCH -->
    <div class="flex flex-wrap gap-3">
        <button onclick="showGrafik('garis')" id="btn-garis"
            class="grafik-btn bg-teal-600 text-white px-5 py-2 rounded-xl font-semibold shadow">
            📈 Garis
        </button>

        <button onclick="showGrafik('batang')" id="btn-batang"
            class="grafik-btn bg-gray-200 text-gray-700 px-5 py-2 rounded-xl font-semibold">
            📊 Batang
        </button>

        <button onclick="showGrafik('pie')" id="btn-pie"
            class="grafik-btn bg-gray-200 text-gray-700 px-5 py-2 rounded-xl font-semibold">
            🥧 Diagram
        </button>
    </div>

    <!-- CONTAINER GRAFIK -->
    <div class="bg-slate-50 rounded-xl p-4 shadow-inner">

        <!-- GARIS -->
        <div id="wrap-garis">
            <canvas id="grafikGaris" height="280"></canvas>
        </div>

        <!-- BATANG -->
        <div id="wrap-batang" class="hidden">
            <canvas id="grafikBatang" height="280"></canvas>
        </div>

        <!-- PIE -->
        <div id="wrap-pie" class="hidden max-w-md mx-auto">
            <canvas id="grafikPie"></canvas>
        </div>

    </div>

</div>

</main>

<script>
/* ======================
   DATA
====================== */
const labels = <?= json_encode(array_keys($grafikData)) ?>;
const dataKeuntungan = <?= json_encode(array_values($grafikData)) ?>;

// hitung total modal & penjualan dari keuntungan
let totalKeuntungan = <?= $totalKeuntungan ?>;
let totalModal = 0;
let totalPenjualan = 0;

<?php
// hitung modal & penjualan ulang dari data
mysqli_data_seek($transaksiQ, 0);
$totalModal = 0;
$totalPenjualan = 0;

$transaksiQ2 = mysqli_query($conn, "
    SELECT d.qty, p.harga_modal, p.harga
    FROM transaksi_penjual tp
    JOIN transaksi_detail d ON d.transaksi_id = tp.transaksi_id
    JOIN produk p ON d.produk_id = p.id
    WHERE tp.penjual_id = $penjual_id
      AND tp.approve = 'setuju'
");

while($x = mysqli_fetch_assoc($transaksiQ2)){
    $totalModal += $x['qty'] * $x['harga_modal'];
    $totalPenjualan += $x['qty'] * $x['harga'];
}
?>

totalModal = <?= $totalModal ?>;
totalPenjualan = <?= $totalPenjualan ?>;

/* ======================
   GRAFIK GARIS
====================== */
new Chart(document.getElementById('grafikGaris'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Keuntungan (Rp)',
            data: dataKeuntungan,
            borderColor: 'rgba(22,163,74,1)',
            backgroundColor: 'rgba(22,163,74,0.2)',
            fill: true,
            tension: 0.4,
            borderWidth: 2,
            pointBackgroundColor: 'rgba(22,163,74,1)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,   // ⬅️ WAJIB
        plugins: { legend: { position: 'top' }},
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp' + v.toLocaleString() }
            }
        }
    }
});

/* ======================
   GRAFIK BATANG
====================== */
new Chart(document.getElementById('grafikBatang'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Keuntungan (Rp)',
            data: dataKeuntungan,
            backgroundColor: 'rgba(16,185,129,0.7)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,   // ⬅️ WAJIB
        plugins: { legend: { position: 'top' }},
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp' + v.toLocaleString() }
            }
        }
    }
});

/* ======================
   DIAGRAM BULAT (PIE)
====================== */
new Chart(document.getElementById('grafikPie'), {
    type: 'pie',
    data: {
        labels: ['Modal', 'Total Penjualan', 'Keuntungan'],
        datasets: [{
            data: [totalModal, totalPenjualan, totalKeuntungan],
            backgroundColor: [
                'rgba(59,130,246,0.8)',   // biru modal
                'rgba(16,185,129,0.8)',   // hijau penjualan
                'rgba(234,179,8,0.8)'     // kuning keuntungan
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(ctx){
                        return ctx.label + ': Rp' + ctx.raw.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

<script>
/* ======================
   SWITCH GRAFIK
====================== */
function showGrafik(type){

    const gGaris  = document.getElementById('wrap-garis');
    const gBatang = document.getElementById('wrap-batang');
    const gPie    = document.getElementById('wrap-pie');

    const bGaris  = document.getElementById('btn-garis');
    const bBatang = document.getElementById('btn-batang');
    const bPie    = document.getElementById('btn-pie');

    // hide semua
    gGaris.classList.add('hidden');
    gBatang.classList.add('hidden');
    gPie.classList.add('hidden');

    // reset tombol
    document.querySelectorAll('.grafik-btn').forEach(btn=>{
        btn.classList.remove('bg-teal-600','text-white','shadow');
        btn.classList.add('bg-gray-200','text-gray-700');
    });

    // show sesuai pilihan
    if(type === 'garis'){
        gGaris.classList.remove('hidden');
        bGaris.classList.add('bg-teal-600','text-white','shadow');
    }

    if(type === 'batang'){
        gBatang.classList.remove('hidden');
        bBatang.classList.add('bg-teal-600','text-white','shadow');
    }

    if(type === 'pie'){
        gPie.classList.remove('hidden');
        bPie.classList.add('bg-teal-600','text-white','shadow');
    }
}
</script>

<script>
/* ======================
   DOWNLOAD PDF (GRAFIK DI HALAMAN PERTAMA)
====================== */
document.getElementById('downloadPdf').addEventListener('click', async () => {

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a4'); // landscape

    /* ===== DETEKSI GRAFIK AKTIF ===== */
    let activeCanvas = null;

    if(!document.getElementById('wrap-garis').classList.contains('hidden')){
        activeCanvas = document.getElementById('grafikGaris');
    }

    if(!document.getElementById('wrap-batang').classList.contains('hidden')){
        activeCanvas = document.getElementById('grafikBatang');
    }

    if(!document.getElementById('wrap-pie').classList.contains('hidden')){
        activeCanvas = document.getElementById('grafikPie');
    }

    /* ======================
       HALAMAN 1 → GRAFIK
    ====================== */
    if(activeCanvas){
        const imgData = activeCanvas.toDataURL('image/png', 1.0);

        doc.setFontSize(18);
        doc.text("Grafik Penjualan", 40, 40);

        // ukuran canvas
        const canvasWidth  = activeCanvas.width;
        const canvasHeight = activeCanvas.height;

        const ratio = canvasWidth / canvasHeight;

        const maxWidth = 720;
        const maxHeight = 360;

        let pdfWidth = maxWidth;
        let pdfHeight = pdfWidth / ratio;

        if (pdfHeight > maxHeight) {
            pdfHeight = maxHeight;
            pdfWidth = pdfHeight * ratio;
        }

        const pageWidth  = doc.internal.pageSize.getWidth();
        const centerX = (pageWidth - pdfWidth) / 2;

        const startY = 80;

        doc.addImage(imgData, 'PNG', centerX, startY, pdfWidth, pdfHeight);
    }

    /* ======================
       HALAMAN 2 → LAPORAN
    ====================== */
    doc.addPage();

    /* ===== TITLE ===== */
    doc.setFontSize(18);
    doc.text("Laporan Transaksi Penjual", 40, 40);

    /* ===== SUBTITLE ===== */
    doc.setFontSize(11);
    doc.setTextColor(100);
    doc.text(
        "Periode: <?= $start && $end ? date('d M Y', strtotime($start)).' - '.date('d M Y', strtotime($end)) : 'Semua Data' ?>",
        40,
        60
    );

    /* ===== TABLE ===== */
    const table = document.querySelector('#laporanTable table');

    doc.autoTable({
        html: table,
        startY: 80,
        theme: 'grid',
        headStyles: { fillColor: [22,163,74] },
        styles: { fontSize: 9 },
        margin: { left: 40, right: 40 }
    });

    /* ===== TOTAL ===== */
    const finalY = doc.lastAutoTable.finalY || 80;

    doc.setFontSize(14);
    doc.setTextColor(22,163,74);
    doc.text(
        `Total Keuntungan: Rp<?= number_format($totalKeuntungan,0,',','.') ?>`,
        40,
        finalY + 25
    );

    /* ===== SAVE ===== */
    doc.save('laporan_penjual.pdf');

});
</script>