<?php
session_start();
if ($_SESSION['role'] !== 'pembeli') {
    header("Location: /login.php");
    exit;
}

include '../config/koneksi.php';

$pembeli_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT *
    FROM transaksi
    WHERE pembeli_id = '$pembeli_id'
    ORDER BY created_at DESC
");
?>

<h2>Pesanan Saya</h2>

<?php while ($row = mysqli_fetch_assoc($query)) { ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px">
        <p><b>Transaksi #<?= $row['id'] ?></b></p>
        <p>Status: <?= $row['status'] ?></p>
        <p>Total: Rp<?= number_format($row['total']) ?></p>

        <a href="detail.php?id=<?= $row['id'] ?>">Detail</a>
    </div>
<?php } ?>
