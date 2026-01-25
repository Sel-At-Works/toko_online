<?php
session_start();
include '../config/koneksi.php';

// ambil transaksi yang perlu di-approve
$query = mysqli_query($conn, "
    SELECT 
        t.id,
        t.total,
        t.bank,
        t.bukti_transfer,
        t.status,
        t.created_at,

        u.nama AS nama_pembeli,
        u.alamat,
        t.no_telepon


    FROM transaksi t
    JOIN users u ON t.pembeli_id = u.id
    ORDER BY t.created_at DESC
");
/* ================= PROSES APPROVE / TOLAK ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'], $_POST['id'])) {
    $id   = intval($_POST['id']);
    $aksi = $_POST['aksi'];

    if ($aksi === 'approve') {
        $status = 'selesai';
    } elseif ($aksi === 'tolak') {
        $status = 'ditolak';
    } else {
        $status = null;
    }

    if ($status) {
        mysqli_query($conn, "
            UPDATE transaksi 
            SET status = '$status'
            WHERE id = $id
        ");
    }

    // refresh halaman supaya data update
    header("Location: approve.php");
    exit;
}
$warna = match($row['status']) {
    'menunggu_verifikasi' => 'text-orange-600',
    'selesai' => 'text-green-600',
    'ditolak' => 'text-red-600',
    default => 'text-gray-600'
};
?>
<span class="font-semibold <?= $warna ?>">
    <?= ucfirst($row['status']) ?>
</span>

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

            <!-- SEARCH -->
            <div class="flex-1 relative">
                <input
                    type="text"
                    placeholder="Search Here"
                    class="w-full px-12 py-3 rounded-full bg-white shadow focus:outline-none"
                />
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    🔍
                </span>
            </div>

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
<tr class="border-t hover:bg-gray-50">

    <td class="px-4 py-3">TRX<?= $row['id'] ?></td>

    <td class="px-4 py-3">-</td>

    <td class="px-4 py-3 text-center">-</td>

    <td class="px-4 py-3 text-center">
        <?php if ($row['bukti_transfer']): ?>
            <img src="../uploads/<?= $row['bukti_transfer'] ?>" class="w-12 mx-auto">
        <?php else: ?>
            -
        <?php endif; ?>
    </td>

    <td class="px-4 py-3"><?= strtoupper($row['bank'] ?? '-') ?></td>

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
                <?= ucfirst($row['status']) ?>
            </span>
        <?php endif; ?>
    </td>

    <td class="px-4 py-3"><?= ucfirst($row['status']) ?></td>

    <td class="px-4 py-3">-</td>

      <!-- KOLOM ALAMAT -->
    <td class="px-4 py-3">
        <?php
        $alamat = trim($row['alamat'] ?? '');
        echo $alamat !== '' ? $alamat : 'Alamat belum diisi';
        ?>
    </td>

    <td class="px-4 py-3"><?= $row['nama_pembeli'] ?></td>

    <td class="px-4 py-3">
        <a href="detail_transaksi.php?id=<?= $row['id'] ?>"
           class="px-3 py-1 bg-gray-500 text-white rounded text-xs">
            Detail
        </a>
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
