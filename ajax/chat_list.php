<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user'])) {
    exit;
}

$user_id = (int) $_SESSION['user']['id'];

/* ================= QUERY CHAT LIST ================= */
$q = mysqli_query($conn, "
SELECT 
  u.id AS lawan_id,
  u.nama,
  u.foto,

  (
    SELECT c2.created_at
    FROM chat c2
    WHERE 
      (c2.pengirim_id = u.id AND c2.penerima_id = $user_id)
      OR
      (c2.pengirim_id = $user_id AND c2.penerima_id = u.id)
    ORDER BY c2.created_at DESC
    LIMIT 1
  ) AS terakhir,

  (
    SELECT c2.pesan
    FROM chat c2
    WHERE 
      (c2.pengirim_id = u.id AND c2.penerima_id = $user_id)
      OR
      (c2.pengirim_id = $user_id AND c2.penerima_id = u.id)
    ORDER BY c2.created_at DESC
    LIMIT 1
  ) AS pesan_terakhir,

  (
    SELECT COUNT(*)
    FROM chat c3
    WHERE c3.pengirim_id = u.id
      AND c3.penerima_id = $user_id
      AND c3.dibaca = 0
  ) AS unread

FROM users u

WHERE u.id != $user_id
AND (
    -- sudah ada chat
    EXISTS (
        SELECT 1 FROM chat c
        WHERE 
          (c.pengirim_id = u.id AND c.penerima_id = $user_id)
          OR
          (c.pengirim_id = $user_id AND c.penerima_id = u.id)
    )
    OR
    -- belum ada chat tapi ada transaksi
    EXISTS (
        SELECT 1
        FROM transaksi_penjual tp
        JOIN transaksi t ON tp.transaksi_id = t.id
        WHERE
            (t.pembeli_id = u.id AND tp.penjual_id = $user_id)
         OR (t.pembeli_id = $user_id AND tp.penjual_id = u.id)
    )
)

ORDER BY terakhir DESC
");



if (!$q || mysqli_num_rows($q) == 0) {
    echo '<div class="p-4 text-center text-gray-500">Belum ada chat</div>';
    exit;
}

/* ================= TAMPILAN ================= */
while ($row = mysqli_fetch_assoc($q)):

    $inisial = strtoupper(substr($row['nama'], 0, 1));
    $foto    = $row['foto'];

$path_foto = $_SERVER['DOCUMENT_ROOT'] . '/' . $foto;

?>
<div
  onclick="openChat(<?= $row['lawan_id'] ?>, '<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>'); hideUnread(<?= $row['lawan_id'] ?>)"
  class="flex items-center gap-3 p-4 border-b hover:bg-gray-50 cursor-pointer">

    <!-- FOTO / INISIAL -->
<div class="w-11 h-11 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
    <?php 
    if (!empty($foto) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $foto)): 
    ?>
        <img src="/<?= htmlspecialchars($foto) ?>" class="w-full h-full object-cover">
    <?php else: ?>
        <span class="font-semibold text-gray-600">
            <?= $inisial ?>
        </span>
    <?php endif; ?>
</div>

    <!-- NAMA & PESAN -->
    <div class="flex-1 min-w-0">
        <div class="font-semibold truncate">
            <?= htmlspecialchars($row['nama']) ?>
        </div>

        <div class="text-sm text-gray-500 truncate">
            <?= $row['pesan_terakhir']
    ? htmlspecialchars($row['pesan_terakhir'])
    : '<span class="italic text-gray-400">Belum ada pesan</span>' ?>
        </div>
    </div>

    <!-- WAKTU & UNREAD -->
    <div class="text-right ml-3">
        <div class="text-xs text-gray-400">
            <?= $row['terakhir'] 
    ? date('H:i', strtotime($row['terakhir'])) 
    : '-' ?>
        </div>

    <!-- Badge unread: tampilkan jika ada pesan belum dibaca -->
        <?php if ($row['unread'] > 0): ?>
            <span 
                class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full inline-block mt-1 unread-badge" 
                data-lawan="<?= $row['lawan_id'] ?>">
                <?= $row['unread'] ?>
            </span>
        <?php endif; ?>
    </div>
</div>
<?php endwhile; ?>
