<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
include '../config/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/* CEK LOGIN PENJUAL */
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'penjual') {
    die('Akses ditolak');
}

$penjual_id = $_SESSION['user_id'];
$transaksi_id = intval($_GET['transaksi_id'] ?? 0);

if ($transaksi_id <= 0) {
    die('Transaksi tidak valid');
}

/* AMBIL DATA TRANSAKSI */
$q = mysqli_query($conn,"
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

/* AMBIL PRODUK MILIK PENJUAL */
$qDetail = mysqli_query($conn,"
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

/* DATA INVOICE */
$invoice = [
    'kode' => 'INV-' . date('Ymd', strtotime($transaksi['created_at'])) . '-' . $transaksi_id,
    'tanggal' => date('d M Y H:i', strtotime($transaksi['created_at'])),
    'total' => $total,
    'alamat' => [
        'nama' => $transaksi['nama'],
        'telp' => $transaksi['no_telepon'],
        'alamat' => $transaksi['alamat']
    ],
    'items' => $items
];

$alamat  = $invoice['alamat'] ?? [];

/* ===== RAPIKAN DATA ALAMAT ===== */

$nama = trim($alamat['nama'] ?? '');
$telp = trim($alamat['telp'] ?? '');
$alamatText = trim($alamat['alamat'] ?? '');

if ($nama === '' || $nama === '-') {
    $nama = '-';
}

if ($telp === '' || $telp === '-') {
    $telp = '-';
}

if ($alamatText === '' || $alamatText === '-') {
    $alamatText = '-';
}

/* ===== HTML ===== */

ob_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    margin:0;
    padding:0;
}

.container{
    width:80mm;
    margin:0 auto;
}

h1{
    text-align:center;
    font-size:14px;
    margin:4px 0;
}

.center{ text-align:center; }
.right{ text-align:right; }

hr{
    border:none;
    border-top:1px dashed #000;
    margin:6px 0;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    vertical-align:top;
    padding:2px 0;
}

</style>
</head>

<body>

<div class="container">

<h1>TOKO ONLINE</h1>
<p class="center">Invoice Penjual</p>

<hr>

<table>
<tr>
<td>No Invoice</td>
<td class="right"><?= $invoice['kode'] ?></td>
</tr>

<tr>
<td>Tanggal</td>
<td class="right"><?= $invoice['tanggal'] ?></td>
</tr>
</table>

<hr>

<strong>Alamat Pembeli</strong><br>
<?= htmlspecialchars($nama) ?><br>
<?= htmlspecialchars($telp) ?><br>
<?= htmlspecialchars($alamatText) ?><br>

<hr>

<?php foreach ($invoice['items'] as $item): ?>

<table>
<tr>
<td>
<?= $item['nama_produk'] ?><br>
x<?= $item['qty'] ?>
</td>

<td class="right">
Rp <?= number_format($item['harga'] * $item['qty']) ?>
</td>
</tr>
</table>

<?php endforeach; ?>

<hr>

<table>
<tr>
<td><strong>TOTAL</strong></td>
<td class="right"><strong>Rp <?= number_format($invoice['total']) ?></strong></td>
</tr>
</table>

<hr>

<p class="center">Terima kasih telah berjualan 🙏</p>

</div>

</body>
</html>

<?php
$html = ob_get_clean();

/* ===== DOMPDF ===== */

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

/* ukuran aman untuk thermal */
$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream("invoice-penjual-{$invoice['kode']}.pdf",[
    'Attachment' => true
]);

exit;