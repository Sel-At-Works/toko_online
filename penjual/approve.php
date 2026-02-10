        <?php
        date_default_timezone_set('Asia/Jakarta');
        session_start();
        include '../config/koneksi.php';

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        /* ================= CEK LOGIN PENJUAL ================= */
        if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'penjual') {
            header("Location: ../login.php");
            exit;
        }

        $penjual_id = (int) $_SESSION['user_id'];

        /* ================= PROSES AKSI ================= */
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'], $_POST['tp_id'])) {

            $tp_id = (int) $_POST['tp_id'];
            $aksi  = $_POST['aksi'];

        if ($aksi === 'approve') {

            // ambil detail produk milik penjual ini
            $detail = mysqli_query($conn, "
                SELECT d.produk_id, d.qty
                FROM transaksi_detail d
                JOIN produk p ON d.produk_id = p.id
                WHERE d.transaksi_id = (
                    SELECT transaksi_id FROM transaksi_penjual WHERE id = $tp_id
                )
                AND p.penjual_id = $penjual_id
            ");

            // kurangi stok
            while ($d = mysqli_fetch_assoc($detail)) {
                $produk_id = (int) $d['produk_id'];
                $qty       = (int) $d['qty'];

                mysqli_query($conn, "
                    UPDATE produk
                    SET stok = stok - $qty
                    WHERE id = $produk_id
                    AND stok >= $qty
                ");
            }

            // update status transaksi_penjual
            mysqli_query($conn, "
                UPDATE transaksi_penjual
                SET approve = 'setuju',
                    status = 'diproses',
                    approved_at = NOW()
                WHERE id = $tp_id
                AND penjual_id = $penjual_id
            ");
        }

            /* ===== TOLAK ===== */
        elseif ($aksi === 'tolak') {
            mysqli_query($conn, "
                UPDATE transaksi_penjual
                SET approve = 'ditolak',
                    status = 'refund'
                WHERE id = $tp_id
                AND penjual_id = $penjual_id
            ");
        }

        /* ===== INPUT RESI (PER PENJUAL) ===== */
        elseif ($aksi === 'resi' && !empty($_POST['resi']) && isset($_POST['transaksi_id'])) {

            $resi         = mysqli_real_escape_string($conn, $_POST['resi']);
            $transaksi_id = (int) $_POST['transaksi_id'];

            // Update hanya untuk penjual yang login
            mysqli_query($conn, "
                UPDATE transaksi_penjual
                SET resi = '$resi', status = 'dikirim'
                WHERE transaksi_id = $transaksi_id
                AND penjual_id = $penjual_id
            ");
        }

     elseif ($aksi === 'delete') {

    $tp_id = (int) $_POST['tp_id'];
    $transaksi_id = (int) $_POST['transaksi_id'];

    // 1. sembunyikan dari penjual
    mysqli_query($conn, "
        UPDATE transaksi_penjual
        SET is_hidden = 1
        WHERE id = $tp_id
        AND penjual_id = $penjual_id
    ");

    // 2. ubah status transaksi jadi refund
    mysqli_query($conn, "
        UPDATE transaksi
        SET status = 'refund',
            pesan_refund = 'Silahkan datang ke toko'
        WHERE id = $transaksi_id
    ");

    header("Location: approve.php");
    exit;
}

        }

        /* ================= SEARCH ================= */
        $q = mysqli_real_escape_string($conn, $_GET['q'] ?? '');
        $search = '';

        if ($q !== '') {
            // search berdasarkan kode invoice, nama pembeli, atau resi
            $search = " AND (
                CONCAT('INV-', DATE_FORMAT(t.created_at, '%Y%m%d'), '-', t.id) LIKE '%$q%'
                OR u.nama LIKE '%$q%'
                OR tp.resi LIKE '%$q%'
            )";
        }


        /* ================= AMBIL TRANSAKSI PENJUAL ================= */
        $query = mysqli_query($conn, "
            SELECT
            tp.id AS tp_id,
            tp.transaksi_id,
            tp.status,
            tp.approve,
            tp.resi,
            tp.metode_pembayaran,
            tp.bukti_transfer,
            tp.approved_at,
            t.created_at,
            u.nama AS nama_pembeli,
            u.alamat
            FROM transaksi_penjual tp
            JOIN transaksi t ON tp.transaksi_id = t.id
            JOIN users u ON t.pembeli_id = u.id
            WHERE tp.penjual_id = $penjual_id
            AND tp.is_hidden = 0
            $search
            ORDER BY t.created_at DESC
        ");
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Approve Pesanan</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>

        <body class="bg-gray-100">
        <div class="flex min-h-screen">

        <?php include '../layouts/sidebar_penjual.php'; ?>

        <div class="flex-1 p-6">
        <h2 class="text-2xl font-semibold mb-4">Approve Pesanan</h2>

            <!-- ===== TOP BAR ===== -->
            <div class="flex items-center gap-4 mb-6">

                <!-- SEARCH -->
                <form method="get" class="flex-1 relative">
            <input
            type="text"
            name="q"
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
            placeholder="Cari Kode, Nama Pembeli, atau Resi"
            class="w-full px-12 py-3 rounded-full bg-white shadow focus:outline-none" />
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        🔍
                    </span>
                </form>

                <!-- PROFIL & NOTIFICATION -->
                <?php include '../layouts/profil_notifikasi.php'; ?>

            </div>

        <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
        <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2">Kode</th>
            <th class="px-4 py-2">Produk</th>
            <th class="px-4 py-2">Qty</th>
            <th class="px-4 py-2">Metode Bayar</th>
            <th class="px-4 py-2">Bukti Transfer</th>
            <th class="px-4 py-2">Approve</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Resi</th>
            <th class="px-4 py-2">Pembeli</th>
            <th class="px-4 py-2">Invoice</th>
        </tr>
        </thead>

        <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>

        <?php
        // ambil produk KHUSUS penjual ini
        $detail = mysqli_query($conn, "
            SELECT d.qty, p.nama_produk
            FROM transaksi_detail d
            JOIN produk p ON d.produk_id = p.id
            WHERE d.transaksi_id = {$row['transaksi_id']}
            AND p.penjual_id = $penjual_id
        ");
        ?>

        <tr class="border-t">
        <td class="px-4 py-2">
            <?php 
            $kode_invoice = 'INV-' . date('Ymd', strtotime($row['created_at'])) . '-' . $row['transaksi_id'];
            echo $kode_invoice;
            ?>
        </td>


        <td class="px-4 py-2">
        <?php while ($d = mysqli_fetch_assoc($detail)): ?>
            <div><?= $d['nama_produk'] ?></div>
        <?php endwhile; ?>
        </td>

        <?php mysqli_data_seek($detail, 0); ?>

        <td class="px-4 py-2 text-center">
        <?php while ($d = mysqli_fetch_assoc($detail)): ?>
            <div><?= $d['qty'] ?></div>
        <?php endwhile; ?>
        </td>

        <td class="px-4 py-2 text-center">
            <?= ucfirst($row['metode_pembayaran'] ?? '-') ?>
        </td>


        <!-- bukti transfer -->
        <td class="px-4 py-2 text-center">
        <?php if (!empty($row['bukti_transfer']) && file_exists("../uploads/bukti/".$row['bukti_transfer'])): ?>
            <a href="../uploads/bukti/<?= htmlspecialchars($row['bukti_transfer']) ?>" 
            onclick="openModal(event, this.href)" 
            class="inline-block border rounded overflow-hidden">
                <img src="../uploads/bukti/<?= htmlspecialchars($row['bukti_transfer']) ?>" 
                    class="w-12 h-12 object-cover"
                    alt="Bukti Transfer">
            </a>
        <?php else: ?>
            -
        <?php endif; ?>
        </td>


        <!-- tombol approve 1 menit -->
        <td class="px-4 py-2 text-center">

        <?php if ($row['approve'] === 'menunggu'): ?>

        <form method="post" class="inline">
            <input type="hidden" name="tp_id" value="<?= $row['tp_id'] ?>">
            <button name="aksi" value="approve"
                class="px-2 py-1 bg-green-500 text-white text-xs rounded">
                Setujui
            </button>
            <button name="aksi" value="tolak"
                class="px-2 py-1 bg-red-500 text-white text-xs rounded">
                Tolak
            </button>
        </form>

        <?php elseif ($row['status'] === 'refund'): ?>

        <?php
        $updated_at = $row['updated_at'] ?? $row['created_at']; // pakai updated_at kalau ada
        $refund_time = strtotime($updated_at);
        $now = time();
        ?>

        <?php if ($row['approve'] === 'menunggu'): ?>
            <form method="post" class="inline">
                <input type="hidden" name="tp_id" value="<?= $row['tp_id'] ?>">
                <button name="aksi" value="approve"
                    class="px-2 py-1 bg-green-500 text-white text-xs rounded">
                    Setujui
                </button>
                <button name="aksi" value="tolak"
                    class="px-2 py-1 bg-red-500 text-white text-xs rounded">
                    Tolak
                </button>
            </form>

        <?php elseif ($row['status'] === 'refund'): ?>

            <?php if ($refund_time && ($now - $refund_time >= 60)): ?>
                <form method="post" onsubmit="return confirm('Hapus transaksi ini?')">
                    <input type="hidden" name="tp_id" value="<?= $row['tp_id'] ?>">
                    <button name="aksi" value="delete"
                        class="px-2 py-1 bg-gray-700 text-white text-xs rounded">
                        Delete
                    </button>
                </form>
            <?php else: ?>
                <span class="text-xs text-gray-500">Menunggu...</span>
            <?php endif; ?>

        <?php else: ?>
            <span class="font-semibold"><?= ucfirst($row['approve']) ?></span>
        <?php endif; ?>


        <?php else: ?>


        <span class="font-semibold"><?= ucfirst($row['approve']) ?></span>

        <?php endif; ?>

        </td>


        <td class="px-4 py-2 text-center"><?= ucfirst($row['status']) ?></td>

        <td class="px-4 py-2 text-center">
        <?php if ($row['approve'] === 'setuju' && empty($row['resi'])): ?>
        <form method="post" class="flex gap-1 justify-center">
            <input type="hidden" name="tp_id" value="<?= $row['tp_id'] ?>">
            <input type="hidden" name="transaksi_id" value="<?= $row['transaksi_id'] ?>">
            <input type="text" name="resi" required class="border px-2 py-1 text-xs rounded w-24">
            <button name="aksi" value="resi" class="bg-blue-500 text-white text-xs px-2 py-1 rounded">
                Simpan
            </button>
        </form>
        <?php elseif ($row['resi']): ?>
        <span class="text-green-600 font-semibold"><?= $row['resi'] ?></span>
        <?php else: ?>
        -
        <?php endif; ?>
        </td>

        <td class="px-4 py-2"><?= $row['nama_pembeli'] ?></td>


        <td class="px-4 py-2 text-center">
            <a href="invoice.php?transaksi_id=<?= $row['transaksi_id'] ?>" 
            target="_blank"
            class="px-3 py-1 bg-blue-500 text-white rounded text-xs font-semibold hover:bg-blue-600 transition">
            Detail
            </a>
        </td>

        </tr>

        <?php endwhile; ?>
        </tbody>
        </table>
        <!-- Modal -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
            <span class="absolute top-4 right-6 text-white text-3xl cursor-pointer" onclick="closeModal()">&times;</span>
            <img id="modalImg" class="max-h-[90vh] max-w-[90vw] rounded shadow-lg">
        </div>

        <script>
        function openModal(e, src) {
            e.preventDefault();
            document.getElementById('modalImg').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');      
            document.getElementById('imageModal').classList.remove('flex');
        }

        // tutup modal saat klik di luar gambar
        document.getElementById('imageModal').addEventListener('click', function(e){
            if(e.target.id === 'imageModal') closeModal();
        });
        </script>

        </div>
        </div>
        </div>
        </body>
        </html>
