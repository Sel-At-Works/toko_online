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
    // search berdasarkan kode invoice (INV-YYYYMMDD-ID) atau nomor resi di transaksi_penjual
    $search = " AND (
        CONCAT('INV-', DATE_FORMAT(t.created_at, '%Y%m%d'), '-', t.id) LIKE '%$q%'
        OR EXISTS (
            SELECT 1
            FROM transaksi_penjual tp
            WHERE tp.transaksi_id = t.id
            AND tp.resi LIKE '%$q%'
        )
    )";
}


/* ================= AMBIL TRANSAKSI PEMBELI ================= */
$query = mysqli_query($conn, "
    SELECT t.*
    FROM transaksi t
    WHERE t.pembeli_id = $pembeli_id
    AND EXISTS (
        SELECT 1 
        FROM transaksi_penjual tp 
        WHERE tp.transaksi_id = t.id
    )
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

    <div class="flex h-screen overflow-hidden">

        <!-- ================= SIDEBAR ================= -->
      <div class="w-64 flex-shrink-0 h-screen sticky top-0">
        <?php include '../layouts/sidebar_pembeli.php'; ?>
    </div>

        <!-- ================= CONTENT ================= -->
        <div class="flex-1 overflow-y-auto px-6 py-6">

            <!-- ===== TOP BAR ===== -->
            <div class="flex items-center gap-4 mb-6">

                <!-- SEARCH -->
                <form method="get" class="flex-1 relative">
                    <input
                        type="text"
                        name="q"
                        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                        placeholder="Cari Kode atau Resi"
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
                            <th class="px-4 py-3">Tracking</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

                            <?php
                            $kode = 'INV-' . date('Ymd', strtotime($row['created_at'])) . '-' . $row['id'];
                            $detailQ = mysqli_query($conn, "
    SELECT d.qty, p.nama_produk
    FROM transaksi_detail d
    JOIN produk p ON d.produk_id = p.id
    WHERE d.transaksi_id = {$row['id']}
      AND EXISTS (
          SELECT 1 
          FROM transaksi_penjual tp
          WHERE tp.transaksi_id = d.transaksi_id
            AND tp.penjual_id = p.penjual_id
      )
");

                            ?>

                            <tr class="border-t hover:bg-gray-50">

                                <!-- KODE -->
                                <td class="px-4 py-3 font-semibold">
                                    <?= $kode ?>
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
                                    <?php
                                    $tpStatusQ = mysqli_query($conn, "
                                        SELECT tp.status, tp.alasan_tolak, tp.penjual_id
                                        FROM transaksi_penjual tp
                                        WHERE tp.transaksi_id = {$row['id']}
                                    ");

                                    $alasan_tolak = [];
                                    $allStatuses = [];

                                    while ($s = mysqli_fetch_assoc($tpStatusQ)) {
                                        $allStatuses[] = $s['status'];

                                        // Simpan alasan tolak jika status = 'refund' atau 'ditolak'
                                        if (($s['status'] === 'refund' || $s['status'] === 'ditolak') && !empty($s['alasan_tolak'])) {
                                            $alasan_tolak[] = [
                                                'penjual_id' => $s['penjual_id'],
                                                'pesan' => $s['alasan_tolak']
                                            ];
                                        }
                                    }

                                    $total      = count($allStatuses);
                                    $dikirim    = count(array_filter($allStatuses, fn($s) => $s === 'dikirim'));
                                    $selesai    = count(array_filter($allStatuses, fn($s) => $s === 'selesai'));
                                    $refund     = count(array_filter($allStatuses, fn($s) => $s === 'refund'));
                                    $diproses   = count(array_filter($allStatuses, fn($s) => $s === 'diproses'));
                                    $menunggu   = count(array_filter($allStatuses, fn($s) => $s === 'menunggu'));

                                    // ==== LOGIKA KOMBINASI LENGKAP ====
                                    if ($refund == $total && $total > 0) {
                                        $status_global = 'refund';
                                    } elseif ($selesai == $total && $total > 0) {
                                        $status_global = 'selesai';
                                    } elseif ($dikirim == $total && $total > 0) {
                                        $status_global = 'dikirim';
                                    } elseif ($selesai > 0 && $dikirim > 0 && $refund == 0) {
                                        $status_global = 'sebagian_selesai';
                                    } elseif ($selesai > 0 && $refund > 0 && $dikirim == 0) {
                                        $status_global = 'sebagian_selesai_refund';
                                    } elseif ($dikirim > 0 && $refund > 0 && $selesai == 0) {
                                        $status_global = 'sebagian_dikirim_refund';
                                    } elseif ($selesai > 0 && $dikirim > 0 && $refund > 0) {
                                        $status_global = 'sebagian_selesai_dikirim_refund';
                                    } elseif ($diproses > 0 || $menunggu > 0) {
                                        $status_global = 'diproses';
                                    } else {
                                        $status_global = 'menunggu_verifikasi';
                                    }

                                    // Badge warna
                                    $badge = match ($status_global) {
                                        'menunggu_verifikasi' => 'bg-orange-100 text-orange-700',
                                        'dikirim' => 'bg-blue-100 text-blue-700',
                                        'sebagian_dikirim' => 'bg-yellow-100 text-yellow-700',
                                        'sebagian_selesai' => 'bg-yellow-200 text-green-800',
                                        'sebagian_dikirim_refund' => 'bg-pink-100 text-pink-700',
                                        'sebagian_selesai_refund' => 'bg-purple-100 text-purple-800',
                                        'sebagian_selesai_dikirim_refund' => 'bg-red-200 text-red-800',
                                        'refund' => 'bg-red-100 text-red-700',
                                        'selesai' => 'bg-green-100 text-green-700',
                                        'diproses' => 'bg-gray-100 text-gray-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                    ?>

                                    <span class="px-2 py-1 text-xs rounded-full font-semibold <?= $badge ?>">
                                        <?= ucfirst(str_replace('_', ' ', $status_global)) ?>
                                    </span>

                                    <?php if (!empty($alasan_tolak)): ?>
                                        <?php foreach ($alasan_tolak as $at): ?>
                                            <div class="mt-2 p-2 bg-yellow-50 border border-yellow-300 rounded text-xs text-yellow-800">
                                                ⚠️ <?= htmlspecialchars($at['pesan']) ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php elseif (in_array('refund', $allStatuses) || in_array('ditolak', $allStatuses)): ?>
                                        <div class="text-xs text-red-600 mt-1">
                                            Silahkan datang ke toko yang bersangkutan untuk proses refund.
                                        </div>
                                    <?php endif; ?>
                                </td>


                                <!-- RESI -->
                                <td class="px-4 py-3">
                                    <?php
                                     $tpResi = mysqli_query($conn, "
                                    SELECT 
                                        u.nama AS nama_penjual,
                                        MAX(tp.resi) AS resi
                                    FROM transaksi_penjual tp
                                    JOIN produk p ON p.penjual_id = tp.penjual_id
                                    JOIN users u ON p.penjual_id = u.id
                                    JOIN transaksi_detail d ON d.produk_id = p.id AND d.transaksi_id = tp.transaksi_id
                                    WHERE tp.transaksi_id = {$row['id']}
                                    GROUP BY tp.penjual_id, u.nama
                                ");
                                        if (mysqli_num_rows($tpResi) > 0):
                                        while ($r = mysqli_fetch_assoc($tpResi)):
                                    ?>
                                            <div class="text-xs">
                                                <strong><?= $r['nama_penjual'] ?>:</strong>
                                                <?= $r['resi'] ?: '-' ?>
                                            </div>
                                    <?php
                                        endwhile;
                                    else:
                                        echo '-';
                                    endif;
                                    ?>
                                </td>
                            
                                 <!-- TRACKING -->
                                    <td class="px-4 py-3">
                                    <?php
                                    $tpTracking = mysqli_query($conn, "
                                        SELECT 
                                            u.nama AS nama_penjual,
                                            tp.link_lacak
                                        FROM transaksi_penjual tp
                                        JOIN users u ON tp.penjual_id = u.id
                                        WHERE tp.transaksi_id = {$row['id']}
                                        GROUP BY tp.penjual_id, u.nama, tp.link_lacak
                                    ");

                                    if (mysqli_num_rows($tpTracking) > 0):
                                        while ($t = mysqli_fetch_assoc($tpTracking)):
                                    ?>
                                            <div class="text-xs flex flex-col gap-1">
                                                <span class="font-semibold"><?= $t['nama_penjual'] ?>:</span>

                                                <?php if (!empty($t['link_lacak'])): ?>
                                                    <a href="<?= htmlspecialchars($t['link_lacak']) ?>" 
                                                    target="_blank"
                                                    class="inline-block text-blue-600 underline font-semibold">
                                                    📦 Lacak Paket
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                            </div>
                                    <?php
                                        endwhile;
                                    else:
                                        echo '-';
                                    endif;
                                    ?>
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