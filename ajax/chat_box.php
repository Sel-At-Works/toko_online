<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user'])) exit;

$user_id = $_SESSION['user']['id'];
$lawan   = (int)($_GET['lawan_id'] ?? 0);

$q = mysqli_query($conn, "
    SELECT * FROM chat
    WHERE
      (pengirim_id = $user_id AND penerima_id = $lawan)
      OR
      (pengirim_id = $lawan AND penerima_id = $user_id)
    ORDER BY created_at ASC
");

if (mysqli_num_rows($q) == 0) {
    echo '<div class="text-center text-gray-400 text-sm mt-6">
            Belum ada pesan. Kirim pesan pertama 👋
          </div>';
}
?>

<?php while ($c = mysqli_fetch_assoc($q)): 
    $me = $c['pengirim_id'] == $user_id;
?>
<div class="flex <?= $me ? 'justify-end' : 'justify-start' ?> mb-2">

    <div class="
        max-w-[65%]
        px-4 py-2
        rounded-2xl
        text-sm
        break-words
        <?= $me ? 'bg-blue-500 text-white rounded-br-md' : 'bg-white border rounded-bl-md' ?>
    ">

        <!-- PESAN -->
        <div><?= nl2br(htmlspecialchars($c['pesan'])) ?></div>

        <!-- WAKTU + STATUS -->
        <div class="flex items-center justify-end gap-1 mt-1 text-[11px] opacity-70">
            <span><?= date('H:i', strtotime($c['created_at'])) ?></span>

            <?php if ($me): ?>
                <?php if ($c['dibaca']): ?>
                    <!-- ✓✓ dibaca -->
                    <span class="text-blue-200">✓✓</span>
                <?php else: ?>
                    <!-- ✓ terkirim -->
                    <span>✓</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>

</div>
<?php endwhile; ?>
