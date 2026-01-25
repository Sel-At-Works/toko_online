<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['invoice'])) {
    die('Invoice tidak ditemukan');
}

$invoice = $_SESSION['invoice'];
$alamat  = $invoice['alamat'] ?? [];
// ===== RAPIKAN DATA ALAMAT (ANTI "- -") =====
$nama = trim($alamat['nama'] ?? '');
$telp = trim($alamat['telp'] ?? '');
$alamatText = trim($alamat['alamat'] ?? '');

if ($nama === '' || $nama === '-') {
    $nama = 'agoy';
}

if ($telp === '' || $telp === '-') {
    $telp = '0874648494940';
}

if ($alamatText === '' || $alamatText === '-') {
    $alamatText = 'gdhhdhhduud';
}

$kota = trim($alamat['kota'] ?? '');
$provinsi = trim($alamat['provinsi'] ?? '');

$kotaProvinsi = '';
if (
    $kota !== '' && $kota !== '-' &&
    $provinsi !== '' && $provinsi !== '-'
) {
    $kotaProvinsi = $kota . ', ' . $provinsi;
}


/* ===== HTML ===== */
ob_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    margin: 0;
    padding: 0;
}

.container {
    width: 80mm;
    margin: 0 auto;
}

h1 {
    text-align: center;
    font-size: 14px;
    margin: 4px 0;
}

.center { text-align: center; }
.right { text-align: right; }

hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 6px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    vertical-align: top;
    padding: 2px 0;
}
</style>
</head>
<body>

<div class="container">

<h1>TOKO ONLINE</h1>
<p class="center">Bukti Pembayaran</p>

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

<strong>Alamat Pengiriman</strong><br>
<?= htmlspecialchars($nama) ?><br>
<?= htmlspecialchars($telp) ?><br>
<?= htmlspecialchars($alamatText) ?><br>

<?php if ($kotaProvinsi !== ''): ?>
<?= htmlspecialchars($kotaProvinsi) ?><br>
<?php endif; ?>


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

<p class="center">Terima kasih 🙏</p>

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

/**
 * 🔥 PENTING:
 * Pakai A4 → Dompdf aman
 * Printer thermal akan cetak memanjang otomatis
 */
$dompdf->setPaper('A4', 'portrait');

$dompdf->render();
$dompdf->stream("invoice-{$invoice['kode']}.pdf", [
    'Attachment' => true
]);
exit;
