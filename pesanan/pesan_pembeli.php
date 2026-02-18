<?php
session_start();

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role_id']) ||
    $_SESSION['role_id'] != 3
) {
    header("Location: /login.php");
    exit;
}

include '../config/koneksi.php';

$pembeli_id = $_SESSION['user_id'];

/* 🔥 TANDAI SEMUA NOTIF SUDAH DIBACA */
mysqli_query($conn, "
    UPDATE transaksi
    SET notif_dibaca_pembeli = 1
    WHERE pembeli_id = '$pembeli_id'
    AND notif_dibaca_pembeli = 0
");

// tandai refund sudah dibaca
mysqli_query($conn, "
    UPDATE transaksi
    SET notif_dibaca_pembeli = 1
    WHERE pembeli_id = '$pembeli_id'
    AND status = 'refund'
");

$query = mysqli_query($conn, "
    SELECT 
    tp.id AS tp_id,
    tp.transaksi_id,
    tp.status,
    tp.resi,
    tp.penjual_id,
    t.total,
    t.created_at,
    u.nama AS nama_penjual
FROM transaksi_penjual tp
JOIN transaksi t ON tp.transaksi_id = t.id
JOIN users u ON tp.penjual_id = u.id
WHERE t.pembeli_id = '$pembeli_id'
ORDER BY t.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
</head>
<body style="font-family:system-ui, -apple-system, BlinkMacSystemFont, sans-serif; background:#f1f5f9; padding:32px; color:#0f172a;">

    <?php if (isset($_SESSION['alert'])): 
    $alert = $_SESSION['alert'];
    $bg = '#dcfce7';
    $textColor = '#166534';

    if ($alert['icon'] === 'error') {
        $bg = '#fee2e2';
        $textColor = '#991b1b';
    }

    if ($alert['icon'] === 'warning') {
        $bg = '#fef9c3';
        $textColor = '#854d0e';
    }
?>

<div id="alertBox" style="
    background: <?= $bg ?>;
    color: <?= $textColor ?>;
    padding:16px;
    border-radius:14px;
    margin-bottom:20px;
    font-size:14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
">
    <div>
        <strong><?= $alert['title'] ?></strong><br>
        <?= $alert['text'] ?>
    </div>
    <button onclick="closeAlert()" style="background:none;border:none;font-size:16px;cursor:pointer;">✖</button>
</div>

<script>
function closeAlert() {
    document.getElementById('alertBox').style.display = 'none';
}
setTimeout(closeAlert, 3000);
</script>

<?php unset($_SESSION['alert']); endif; ?>

<h2 style="font-size:24px; font-weight:700; margin-bottom:28px;">🛒 Pesanan Saya</h2>

<?php if (mysqli_num_rows($query) == 0) { ?>
    <div style="background:#ffffff; padding:24px; border-radius:16px; border:1px solid #e5e7eb; color:#64748b;">
        Belum ada pesanan
    </div>
<?php } ?>

<?php while ($row = mysqli_fetch_assoc($query)) { ?>

<?php
// 🔹 QUERY PENJUAL MASUK DI SINI
$penjual = mysqli_query($conn, "
    SELECT DISTINCT u.nama
    FROM transaksi_penjual tp
    JOIN users u ON tp.penjual_id = u.id
    WHERE tp.transaksi_id = '{$row['transaksi_id']}'
");
?>
<?php
// 🔥 STATUS GLOBAL = SUMBER UTAMA DARI TABEL transaksi
$status_global = $row['status'];

$statusMap = [
    'menunggu_verifikasi' => ['bg'=>'#fef3c7','text'=>'#92400e'],
    'diproses'            => ['bg'=>'#e0f2fe','text'=>'#075985'],
    'dikirim'             => ['bg'=>'#dcfce7','text'=>'#166534'],
    'selesai'             => ['bg'=>'#ede9fe','text'=>'#5b21b6'],
    'refund'              => ['bg'=>'#fee2e2','text'=>'#991b1b'],
];

$statusColor = $statusMap[$status_global] ?? ['bg'=>'#e5e7eb','text'=>'#374151'];
?>

<?php
$produkPerPenjual = mysqli_query($conn, "
    SELECT 
        td.qty,
        td.harga,
        p.nama_produk,
        u.nama AS nama_penjual,
        u.id AS penjual_id
    FROM transaksi_detail td
    JOIN produk p ON td.produk_id = p.id
    JOIN users u ON p.penjual_id = u.id
    WHERE td.transaksi_id = '{$row['transaksi_id']}'
    ORDER BY u.nama ASC
");
?>

<?php
$detail = mysqli_query($conn, "
    SELECT td.qty, td.harga, p.nama_produk
    FROM transaksi_detail td
    JOIN produk p ON td.produk_id = p.id
    WHERE td.transaksi_id = '{$row['transaksi_id']}'
");
?>

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
            <span style="
                padding:7px 16px;
                border-radius:999px;
                font-size:12px;
                font-weight:600;
                background:<?= $statusColor['bg'] ?>;
                color:<?= $statusColor['text'] ?>;
                display:inline-block;
                margin-top:4px;
            ">
                <?= strtoupper(str_replace('_',' ',$status_global)) ?>
            </span>
        </div>

    </div>

    <!-- PENJUAL -->
    <div style="margin-top:18px; font-size:14px; color:#475569;">
        <strong>Penjual:</strong>
        <?php
        $listPenjual = [];
        mysqli_data_seek($penjual, 0);
        while ($p = mysqli_fetch_assoc($penjual)) {
            $listPenjual[] = $p['nama'];
        }
        echo implode(', ', $listPenjual);
        ?>
    </div>

    <!-- TOTAL -->
    <div style="margin-top:22px; padding:16px; background:#f8fafc; border-radius:14px; font-size:15px; display:flex; justify-content:space-between;">
        <div>Total Pembayaran</div>
        <div style="font-weight:700; font-size:16px;">Rp<?= number_format($row['total']) ?></div>
    </div>

<!-- DETAIL PRODUK PER PENJUAL -->
<div style="margin-top:26px;">
    <div style="font-size:15px; font-weight:600; margin-bottom:14px;">Detail Produk</div>

    <?php
    $produkPerPenjual = mysqli_query($conn, "
        SELECT 
            tp.penjual_id,
            u.nama AS nama_penjual,
            p.nama_produk,
            td.qty,
            td.harga
        FROM transaksi_detail td
        JOIN produk p ON td.produk_id = p.id
        JOIN transaksi_penjual tp ON td.transaksi_id = tp.transaksi_id 
        JOIN users u ON tp.penjual_id = u.id
        WHERE td.transaksi_id = '{$row['transaksi_id']}'
        ORDER BY tp.penjual_id
    ");

    $grouped = [];

    while ($prd = mysqli_fetch_assoc($produkPerPenjual)) {
        $grouped[$prd['penjual_id']]['nama_penjual'] = $prd['nama_penjual'];
        $grouped[$prd['penjual_id']]['produk'][] = $prd;
    }
    ?>

    <?php foreach ($grouped as $penjualData): ?>

    <div style="margin-top:22px; padding:16px; background:#f1f5f9; border-radius:14px;">

        <!-- HEADER PENJUAL -->
        <div style="font-weight:700; margin-bottom:10px; color:#0f172a;">
            🏪 <?= $penjualData['nama_penjual'] ?>
        </div>

        <!-- TABEL PRODUK -->
        <table width="100%" style="border-collapse:collapse; font-size:14px;">
            <tr style="border-bottom:1px solid #e2e8f0;">
                <th align="left">Produk</th>
                <th width="70" style="text-align:center;">Qty</th>
                <th width="130" align="right">Harga</th>
            </tr>

            <?php foreach ($penjualData['produk'] as $prd): ?>
            <tr style="border-bottom:1px solid #e5e7eb;">
                <td style="padding:10px 0;"><?= $prd['nama_produk'] ?></td>
                <td style="text-align:center;"><?= $prd['qty'] ?></td>
                <td align="right">Rp<?= number_format($prd['harga']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

    </div>

    <?php endforeach; ?>
</div>


    <!-- ACTION -->
    <?php if ($status_global === 'dikirim') { ?>
        <div style="margin-top:26px;">
            <a href="konfirmasi.php?id=<?= $row['transaksi_id'] ?>"
               style="
                    padding:13px 20px;
                    background:#16a34a;
                    color:#ffffff;
                    border-radius:12px;
                    text-decoration:none;
                    font-size:14px;
                    font-weight:600;
                    display:inline-block;
                    transition:0.2s;
               "
               onmouseover="this.style.opacity='0.85'"
               onmouseout="this.style.opacity='1'">
                ✅ Konfirmasi Barang Diterima
            </a>
        </div>
    <?php } ?>

</div>


<?php } ?>

</body>
</html>
