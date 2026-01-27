<?php
date_default_timezone_set('Asia/Jakarta');
session_start();
include '../config/koneksi.php';

/* ================= CEK LOGIN PENJUAL ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit;
}

$penjual_id = (int) $_SESSION['user_id'];

/* ================= SEARCH ================= */
$q = mysqli_real_escape_string($conn, $_GET['q'] ?? '');
$search = '';

if ($q !== '') {
    $search = " AND (t.no_rekening = '$q' OR t.resi = '$q')";
}

/* ================= PROSES AKSI ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'], $_POST['id'])) {

    $id   = (int) $_POST['id'];
    $aksi = $_POST['aksi'];

    /* ===== APPROVE ===== */
    if ($aksi === 'approve') {

        $approve = 'setuju';
        $status  = 'dikirim';

        mysqli_query($conn, "
            UPDATE transaksi t
            JOIN transaksi_detail d ON t.id = d.transaksi_id
            JOIN produk p ON d.produk_id = p.id
            SET 
                t.approve = '$approve',
                t.status  = '$status'
            WHERE t.id = $id
            AND p.penjual_id = $penjual_id
        ");

    }

    /* ===== TOLAK ===== */
   elseif ($aksi === 'tolak') {

    $approve = 'ditolak';
    $status  = 'refund';

    mysqli_query($conn, "
        UPDATE transaksi t
        JOIN transaksi_detail d ON t.id = d.transaksi_id
        JOIN produk p ON d.produk_id = p.id
        SET 
            t.approve = '$approve',
            t.status  = '$status',
            t.refunded_at = NOW()
        WHERE t.id = $id
        AND p.penjual_id = $penjual_id
    ");
}


    /* ===== INPUT RESI ===== */
    elseif ($aksi === 'resi' && !empty($_POST['resi'])) {

        $resi = mysqli_real_escape_string($conn, $_POST['resi']);

        mysqli_query($conn, "
            UPDATE transaksi t
            JOIN transaksi_detail d ON t.id = d.transaksi_id
            JOIN produk p ON d.produk_id = p.id
            SET 
                t.resi = '$resi',
                t.status = 'dikirim'
            WHERE t.id = $id
            AND p.penjual_id = $penjual_id
        ");

    }

    /* ===== HAPUS (SETELAH REFUND ≥ 1 MENIT) ===== */
  elseif ($aksi === 'hapus') {

    // pastikan refund sudah ≥ 1 menit
    $cek = mysqli_query($conn, "
        SELECT refunded_at 
        FROM transaksi 
        WHERE id = $id 
        AND status = 'refund'
    ");

    $trx = mysqli_fetch_assoc($cek);
    $refunded = strtotime($trx['refunded_at'] ?? '');
    $selisih_menit = $refunded ? (time() - $refunded) / 60 : 0;

    if ($selisih_menit >= 1) {

        mysqli_query($conn, "
            DELETE t, d
            FROM transaksi t
            JOIN transaksi_detail d ON t.id = d.transaksi_id
            JOIN produk p ON d.produk_id = p.id
            WHERE t.id = $id
            AND p.penjual_id = $penjual_id
        ");

    }
}


    header("Location: approve.php");
    exit;
}


/* ================= AMBIL TRANSAKSI ================= */
$query = mysqli_query($conn, "
SELECT DISTINCT
    t.id,
    t.total,
    t.bank,
    t.no_rekening,
    t.bukti_transfer,
    t.approve,
    t.status,
    t.resi,
    t.created_at,
    t.refunded_at,
    t.no_telepon,
    u.nama AS nama_pembeli,
    u.alamat

    FROM transaksi t
    JOIN transaksi_detail d ON t.id = d.transaksi_id
    JOIN produk p ON d.produk_id = p.id
    JOIN users u ON t.pembeli_id = u.id
    WHERE p.penjual_id = $penjual_id
    $search
    ORDER BY t.created_at DESC
");
    ?>

    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Approve</title>

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Font Awesome -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>

    <body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- ================= SIDEBAR ================= -->
        <?php include '../layouts/sidebar_penjual.php'; ?>

        <!-- ================= CONTENT ================= -->
        <div class="flex-1 px-6 py-6">

            <!-- ===== TOP BAR ===== -->
            <div class="flex items-center gap-4 mb-6">

                <form method="get" class="flex-1 relative">
        <input
            type="text"
            name="q"
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
            placeholder="Cari No Rekening / No Resi"
            class="w-full px-12 py-3 rounded-full bg-white shadow focus:outline-none"
        />
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
            🔍
        </span>
    </form>

                <!-- PROFIL & NOTIFICATION -->
                <?php include '../layouts/profil_notifikasi.php'; ?>
            </div>

            <!-- ===== MAIN CONTENT ===== -->
            <main>

                <h2 class="text-2xl font-semibold mb-4">Approve</h2>

                <div class="bg-white rounded-lg shadow overflow-x-auto">

                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left">Kode</th>
                                <th class="px-4 py-3 text-left">Judul</th>
                                <th class="px-4 py-3">Qty</th>
                                <th class="px-4 py-3">Bukti</th>
                                <th class="px-4 py-3">Pembayaran</th>
                                <th class="px-4 py-3">Approve</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Resi</th>
                                <th class="px-4 py-3">Alamat</th>
                                <th class="px-4 py-3">Pembeli</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

    <tbody>
    <?php while ($row = mysqli_fetch_assoc($query)): ?>

    <?php
    // ===== HITUNG SELISIH MENIT =====
$refunded = strtotime($row['refunded_at'] ?? '');
$selisih_menit = $refunded ? (time() - $refunded) / 60 : 0;
// ===== DETAIL TRANSAKSI =====
    $detailQ = mysqli_query($conn, "
        SELECT 
            d.qty,
            d.harga,
            p.nama_produk,
            p.gambar
        FROM transaksi_detail d
        JOIN produk p ON d.produk_id = p.id
        WHERE d.transaksi_id = {$row['id']}
    ");
    ?>

    <tr class="border-t hover:bg-gray-50">

        <!-- KODE -->
        <td class="px-4 py-3">TRX<?= $row['id'] ?></td>

        <!-- JUDUL PRODUK -->
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

        <?php mysqli_data_seek($detailQ, 0); ?>

        <!-- BUKTI TRANSFER -->
        <td class="px-4 py-3 text-center">
            <?php if ($row['bukti_transfer']): ?>
                <img src="../uploads/bukti/<?= $row['bukti_transfer'] ?>" class="w-12 mx-auto">
            <?php else: ?>
                -
            <?php endif; ?>
        </td>

        <!-- BANK -->
    <td class="px-4 py-3 text-sm">
        <div class="font-semibold">
            <?= strtoupper($row['bank'] ?? '-') ?>
        </div>
        <div class="text-gray-600">
            <?= $row['no_rekening'] ?? '-' ?>
        </div>
    </td>


        <!-- APPROVE -->
      <td class="px-4 py-3 text-center">
    <?php if ($row['status'] === 'menunggu_verifikasi'): ?>
        <form method="post" class="inline">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button name="aksi" value="approve"
                class="px-3 py-1 bg-green-500 text-white rounded text-xs">
                Setujui
            </button>
            <button name="aksi" value="tolak"
                class="px-3 py-1 bg-red-500 text-white rounded text-xs">
                Tolak
            </button>
        </form>
    <?php else: ?>
        <span class="font-semibold text-gray-600">
            <?= ucfirst($row['approve'] ?? '-') ?>
        </span>
    <?php endif; ?>
</td>


        <!-- STATUS -->
     <td class="px-4 py-3">
<?php
$warna = match ($row['status']) {
    'menunggu_verifikasi' => 'text-orange-600',
    'dikirim' => 'text-blue-600',
    'selesai' => 'text-green-600',
    'refund' => 'text-red-600',
    default => 'text-gray-600'
};
?>
<span class="font-semibold <?= $warna ?>">
    <?= ucfirst(str_replace('_', ' ', $row['status'])) ?>
</span>
</td>


    <!-- RESI -->
    <td class="px-4 py-3 text-center">

    <?php if ($row['status'] === 'dikirim' && empty($row['resi'])): ?>

        <form method="post" class="flex gap-1 justify-center">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="text" name="resi"
                placeholder="No Resi"
                required
                class="border px-2 py-1 text-xs rounded w-28">
            <button name="aksi" value="resi"
                    class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                Simpan
            </button>
        </form>

    <?php elseif (!empty($row['resi'])): ?>

        <span class="text-green-600 font-semibold">
            <?= htmlspecialchars($row['resi']) ?>
        </span>

    <?php else: ?>
        -
    <?php endif; ?>

    </td>


        <!-- ALAMAT -->
        <td class="px-4 py-3">
            <?= trim($row['alamat'] ?? '') ?: 'Alamat belum diisi' ?>
        </td>

        <!-- PEMBELI -->
        <td class="px-4 py-3"><?= $row['nama_pembeli'] ?></td>

        <!-- AKSI -->
        <!-- AKSI -->
<td class="px-4 py-3 space-y-1">

    <a href="../pembeli/checkout_sukses.php?transaksi_id=<?= $row['id'] ?>"
       class="block px-3 py-1 bg-gray-500 text-white rounded text-xs text-center">
        Detail
    </a>

    <?php if ($row['status'] === 'refund' && $selisih_menit >= 1): ?>
        <form method="post" onsubmit="return confirm('Yakin hapus transaksi ini?')">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button name="aksi" value="hapus"
                class="w-full px-3 py-1 bg-red-600 text-white rounded text-xs">
                Hapus
            </button>
        </form>
    <?php endif; ?>

</td>


    </tr>

    <?php endwhile; ?>
    </tbody>


                    </table>

                </div>
            </main>

        </div>
    </div>

    </body>
    </html>
