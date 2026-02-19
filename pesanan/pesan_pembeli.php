<?php
session_start();
include '../config/koneksi.php';

/* ================= CEK LOGIN PEMBELI ================= */
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header("Location: /login.php");
    exit;
}

$pembeli_id = $_SESSION['user_id'];

/* ================= TANDAI SEMUA NOTIF SUDAH DIBACA ================= */
mysqli_query($conn, "
    UPDATE transaksi
    SET notif_dibaca_pembeli = 1
    WHERE pembeli_id = '$pembeli_id' AND notif_dibaca_pembeli = 0
");

/* ================= AMBIL TRANSAKSI PER PENJUAL ================= */
$transaksiQuery = mysqli_query($conn, "
    SELECT 
        tp.id AS tp_id,
        tp.penjual_id,
        u.nama AS nama_penjual,
        t.id AS transaksi_id,
        t.created_at,
        tp.status AS status_penjual,
        SUM(td.harga * td.qty) AS total_per_penjual
    FROM transaksi_penjual tp
    JOIN transaksi t ON tp.transaksi_id = t.id
    JOIN users u ON tp.penjual_id = u.id
    JOIN transaksi_detail td ON td.transaksi_id = t.id
    JOIN produk p ON td.produk_id = p.id AND p.penjual_id = tp.penjual_id
    WHERE t.pembeli_id = '$pembeli_id'
    GROUP BY tp.id, tp.penjual_id, t.id
    ORDER BY t.created_at DESC
");

$statusMap = [
    'menunggu_verifikasi' => ['bg'=>'#fef3c7','text'=>'#92400e'],
    'diproses'            => ['bg'=>'#e0f2fe','text'=>'#075985'],
    'dikirim'             => ['bg'=>'#dcfce7','text'=>'#166534'],
    'selesai'             => ['bg'=>'#ede9fe','text'=>'#5b21b6'],
    'refund'              => ['bg'=>'#fee2e2','text'=>'#991b1b'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
</head>
<body style="font-family:system-ui, -apple-system, BlinkMacSystemFont, sans-serif; background:#f1f5f9; padding:32px; color:#0f172a;">

<h2 style="font-size:24px; font-weight:700; margin-bottom:28px;">🛒 Pesanan Saya</h2>

<?php if (mysqli_num_rows($transaksiQuery) == 0): ?>
    <div style="background:#ffffff; padding:24px; border-radius:16px; border:1px solid #e5e7eb; color:#64748b;">
        Belum ada pesanan
    </div>
<?php endif; ?>

<?php while ($row = mysqli_fetch_assoc($transaksiQuery)):

    $statusColor = $statusMap[$row['status_penjual']] ?? ['bg'=>'#e5e7eb','text'=>'#374151'];

    // Ambil produk per penjual
    $produkQuery = mysqli_query($conn, "
        SELECT td.qty, td.harga, p.nama_produk
        FROM transaksi_detail td
        JOIN produk p ON td.produk_id = p.id
        WHERE td.transaksi_id = '{$row['transaksi_id']}' AND p.penjual_id = '{$row['penjual_id']}'
    ");
?>

<!-- CARD PENJUAL -->
<div style="background:#ffffff; border-radius:20px; padding:30px; margin-bottom:30px; border:1px solid #e2e8f0; box-shadow:0 4px 12px rgba(0,0,0,0.03);">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
        <div>
            <div style="font-size:12px; color:#64748b;">Transaksi</div>
            <div style="font-size:22px; font-weight:700;">#<?= $row['transaksi_id'] ?></div>
            <div style="font-size:13px; color:#94a3b8; margin-top:4px;">
                <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
            </div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:13px; color:#64748b;">Status</div>
            <span style="padding:7px 16px; border-radius:999px; font-size:12px; font-weight:600; background:<?= $statusColor['bg'] ?>; color:<?= $statusColor['text'] ?>; display:inline-block; margin-top:4px;">
                <?= strtoupper(str_replace('_',' ',$row['status_penjual'])) ?>
            </span>
        </div>
    </div>

    <!-- PENJUAL -->
    <div style="margin-top:20px; font-weight:700; font-size:15px; color:#0f172a;">
        🏪 <?= $row['nama_penjual'] ?>
    </div>

    <!-- TABEL PRODUK -->
    <table width="100%" style="border-collapse:collapse; font-size:14px; margin-top:12px;">
        <tr style="border-bottom:1px solid #e2e8f0;">
            <th align="left">Produk</th>
            <th width="70" style="text-align:center;">Qty</th>
            <th width="130" align="right">Harga</th>
        </tr>
        <?php while ($prd = mysqli_fetch_assoc($produkQuery)): ?>
        <tr style="border-bottom:1px solid #e5e7eb;">
            <td style="padding:10px 0;"><?= $prd['nama_produk'] ?></td>
            <td style="text-align:center;"><?= $prd['qty'] ?></td>
            <td align="right">Rp<?= number_format($prd['harga']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- TOTAL -->
    <div style="margin-top:20px; padding:14px; background:#f8fafc; border-radius:12px; font-size:15px; display:flex; justify-content:space-between;">
        <div>Total Pembayaran</div>
        <div style="font-weight:700;">Rp<?= number_format($row['total_per_penjual']) ?></div>
    </div>

    <!-- KONFIRMASI -->
    <?php if ($row['status_penjual'] === 'dikirim'): ?>
        <div style="margin-top:20px;">
            <a href="konfirmasi.php?tp_id=<?= $row['tp_id'] ?>"
               style="padding:12px 20px; background:#16a34a; color:#fff; border-radius:12px; text-decoration:none; font-weight:600;">
                ✅ Konfirmasi Barang Diterima
            </a>
        </div>
    <?php endif; ?>

</div>
<?php endwhile; ?>

</body>
</html>
