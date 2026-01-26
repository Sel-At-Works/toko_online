<?php
session_start();
include '../config/koneksi.php';

/* ================= CEK LOGIN PEMBELI ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);

/* ================= SEARCH ================= */
$q = mysqli_real_escape_string($conn, $_GET['q'] ?? '');
$search = '';

if ($q !== '') {
    $search = " AND t.resi LIKE '%$q%'";
}



/* ================= AMBIL TRANSAKSI PEMBELI ================= */
$query = mysqli_query($conn, "
    SELECT
        t.id,
        t.total,
        t.bank,
        t.no_rekening,
        t.status,
        t.resi,
        t.created_at
    FROM transaksi t
    WHERE t.pembeli_id = $pembeli_id
    $search
    ORDER BY t.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Status Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- ================= SIDEBAR ================= -->
        <?php include '../layouts/sidebar_pembeli.php'; ?>

        <!-- ================= CONTENT ================= -->
        <div class="flex-1 px-6 py-6">

            <!-- ===== TOP BAR ===== -->
            <div class="flex items-center gap-4 mb-6">

                <!-- SEARCH -->
                <form method="get" class="flex-1 relative">
                    <input
                        type="text"
                        name="q"
                        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                        placeholder="Cari No Resi"
                        class="w-full px-12 py-3 rounded-full bg-white shadow focus:outline-none" />
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        🔍
                    </span>
                </form>

                <!-- PROFIL & NOTIFICATION -->
                <?php include '../layouts/profil_notifikasi.php'; ?>

            </div>


            <div class="bg-white rounded-lg shadow overflow-x-auto">

                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Resi</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

                            <?php
                            $detailQ = mysqli_query($conn, "
                SELECT d.qty, p.nama_produk
                FROM transaksi_detail d
                JOIN produk p ON d.produk_id = p.id
                WHERE d.transaksi_id = {$row['id']}
            ");
                            ?>

                            <tr class="border-t hover:bg-gray-50">

                                <!-- KODE -->
                                <td class="px-4 py-3 font-semibold">
                                    TRX<?= $row['id'] ?>
                                </td>

                                <!-- PRODUK -->
                                <td class="px-4 py-3">
                                    <?php while ($d = mysqli_fetch_assoc($detailQ)): ?>
                                        <div><?= $d['nama_produk'] ?></div>
                                    <?php endwhile; ?>
                                </td>

                                <?php mysqli_data_seek($detailQ, 0); ?>

                                <!-- QTY -->
                                <td class="px-4 py-3 text-center">
                                    <?php while ($d = mysqli_fetch_assoc($detailQ)): ?>
                                        <div><?= $d['qty'] ?></div>
                                    <?php endwhile; ?>
                                </td>

                                <!-- TOTAL -->
                                <td class="px-4 py-3">
                                    Rp<?= number_format($row['total'], 0, ',', '.') ?>
                                </td>

                                <!-- STATUS -->
                                <td class="px-4 py-3">
                                    <?php if ($row['status'] === 'ditolak'): ?>
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">
                                            Ditolak
                                        </span>
                                        <div class="text-xs text-red-600 mt-1">
                                            Silahkan datang ke toko
                                        </div>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-semibold">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>


                                <!-- RESI -->
                                <td class="px-4 py-3">
                                    <?= $row['resi'] ?: '-' ?>
                                </td>

                                <!-- TANGGAL -->
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    <?= date('d M Y', strtotime($row['created_at'])) ?>
                                </td>

                                <!-- AKSI -->
                                <td class="px-4 py-3 text-center">
                                    <a href=checkout_sukses.php?transaksi_id=<?= $row['id'] ?>
                                        class="px-3 py-1 bg-blue-500 text-white rounded text-xs">
                                        Detail
                                    </a>

                                </td>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>

            </div>

        </div>

</body>

</html>