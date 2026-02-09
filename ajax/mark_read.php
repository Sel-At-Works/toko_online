<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user'])) exit;

$user_id  = $_SESSION['user']['id'];
$lawan_id = intval($_POST['lawan_id'] ?? 0);

if ($lawan_id > 0) {
    // Tandai pesan yang diterima dari lawan sebagai dibaca
    mysqli_query($conn, "
        UPDATE chat 
        SET dibaca = 1
        WHERE pengirim_id = $lawan_id
          AND penerima_id = $user_id
          AND dibaca = 0
    ");
}
?>
