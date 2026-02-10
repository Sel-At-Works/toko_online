<?php
session_start();

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role_id']) ||
    $_SESSION['role_id'] != 2 // 2 = penjual
) {
    header("Location: /login.php");
    exit;
}

include '../config/koneksi.php';

$penjual_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT 
        tp.transaksi_id,
        tp.total,
        tp.status,
        tp.approve,
        tp.updated_at,
        u.nama AS pembeli
    FROM transaksi_penjual tp
    JOIN transaksi t ON tp.transaksi_id = t.id
    JOIN users u ON t.pembeli_id = u.id
    WHERE tp.penjual_id = '$penjual_id'
    ORDER BY tp.updated_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Masuk</title>
</head>
<body style="font-family:system-ui, -apple-system, BlinkMacSystemFont, sans-serif; background:#f1f5f9; padding:32px; color:#0f172a;">

<h2 style="font-size:24px; font-weight:700; margin-bottom:28px;">📦 Pesanan Masuk</h2>

<?php if (mysqli_num_rows($query) == 0) { ?>
    <div style="background:#ffffff; padding:24px; border-radius:16px; border:1px solid #e5e7eb; color:#64748b;">
        Belum ada pesanan
    </div>
<?php } ?>

<?php while ($row = mysqli_fetch_assoc($query)) { ?>

<?php
    $approveColor = $row['approve'] === 'setuju' ? '#dcfce7' : '#fee2e2';
    $statusColor = match ($row['status']) {
        'MENUNGGU', 'MENUNGGU_VERIFIKASI' => '#fef3c7',
        'diproses' => '#ffedd5',
        'dikirim'  => '#dbeafe',
        default    => '#e5e7eb',
    };

    $detail = mysqli_query($conn, "
        SELECT td.qty, td.harga, p.nama_produk
        FROM transaksi_detail td
        JOIN produk p ON td.produk_id = p.id
        WHERE td.transaksi_id = '{$row['transaksi_id']}'
    ");
?>

<div style="background:#ffffff; border-radius:20px; padding:28px; margin-bottom:28px; border:1px solid #e5e7eb;">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div>
            <div style="font-size:13px; color:#64748b;">Transaksi</div>
            <div style="font-size:20px; font-weight:700;">#<?= $row['transaksi_id'] ?></div>
            <div style="font-size:13px; color:#94a3b8; margin-top:4px;"><?= date('d M Y H:i', strtotime($row['updated_at'])) ?></div>
        </div>

        <div style="display:flex; gap:6px;">
            <span style="padding:6px 14px; border-radius:999px; font-size:12px; font-weight:600; background:<?= $approveColor ?>; color:#166534;">
                <?= strtoupper($row['approve']) ?>
            </span>
            <span style="padding:6px 14px; border-radius:999px; font-size:12px; font-weight:600; background:<?= $statusColor ?>; color:#1e40af;">
                <?= strtoupper($row['status']) ?>
            </span>
        </div>
    </div>

    <!-- INFO -->
    <div style="margin-top:22px; display:grid; grid-template-columns:120px 1fr; row-gap:10px; font-size:14px;">
        <div>Pembeli</div>
        <div style="font-weight:600;"><?= $row['pembeli'] ?></div>

        <div>Total</div>
        <div style="font-weight:600;">Rp<?= number_format($row['total']) ?></div>
    </div>

    <!-- DETAIL PRODUK -->
    <div style="margin-top:26px; border-top:1px solid #e5e7eb; padding-top:18px;">
        <div style="font-size:14px; font-weight:600; margin-bottom:12px;">Detail Produk</div>

        <table width="100%" style="border-collapse:collapse; font-size:13px;">
            <tr style="border-bottom:1px solid #e5e7eb;">
                <th align="left" style="padding-bottom:8px;">Produk</th>
                <th width="60" style="text-align:center;">Qty</th>
                <th width="120" align="right">Harga</th>
            </tr>

            <?php while ($d = mysqli_fetch_assoc($detail)) { ?>
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:10px 0;"><?= $d['nama_produk'] ?></td>
                <td style="text-align:center;"><?= $d['qty'] ?></td>
                <td align="right">Rp<?= number_format($d['harga']) ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

<!-- ACTION -->
<?php
$status  = strtoupper($row['status']);
$approve = strtolower($row['approve'] ?? '');
?>

<div style="margin-top:26px; display:flex; gap:12px; flex-wrap:wrap;">
    <?php if ($status === 'MENUNGGU' || $status === 'MENUNGGU_VERIFIKASI') { ?>
        <a href="/penjual/approve.php?id=<?= $row['transaksi_id'] ?>"
           style="padding:12px 18px; background:#f59e0b; color:#fff; border-radius:12px; text-decoration:none; font-size:14px; font-weight:600;">
            ✅ Approve Pesanan
        </a>
    <?php } ?>

    <?php if ($approve === 'setuju' && $status === 'DIPROSES') { ?>
        <form method="post" action="update_status.php" style="display:flex; gap:12px; flex:1;">
            <input type="hidden" name="transaksi_id" value="<?= $row['transaksi_id'] ?>">
            <input type="text" name="resi" placeholder="Nomor resi pengiriman" required style="flex:1; padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
            <button type="submit" style="background:#16a34a; color:#fff; padding:12px 20px; border-radius:12px; border:none; font-weight:600;">
                🚚 Kirim
            </button>
        </form>
    <?php } ?>
</div>

</div>

<?php } ?>

</body>
</html>
