<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user'])) exit;

$user_id  = (int)$_SESSION['user']['id'];
$lawan_id = (int)($_POST['lawan_id'] ?? 0);
$pesan    = trim($_POST['pesan'] ?? '');
$transaksi_id = isset($_POST['transaksi_id']) ? (int)$_POST['transaksi_id'] : 'NULL';

if ($lawan_id <= 0 || $pesan === '') exit;

// escape pesan
$pesan = mysqli_real_escape_string($conn, $pesan);

// tandai pesan lawan sebagai SUDAH DIBALAS
mysqli_query($conn, "
    UPDATE chat
    SET dibalas = 1
    WHERE pengirim_id = $lawan_id
      AND penerima_id = $user_id
      AND dibalas = 0
"); 

// insert chat
mysqli_query($conn, "
    INSERT INTO chat (pengirim_id, penerima_id, pesan, dibaca, transaksi_id, created_at)
    VALUES ($user_id, $lawan_id, '$pesan', 0, $transaksi_id, NOW())
");
