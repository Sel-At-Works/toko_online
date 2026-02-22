<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/config/koneksi.php';

if (!isset($_SESSION['user'])) {
  echo json_encode(['total' => 0]);
  exit;
}

$user_id = $_SESSION['user']['id'];
$role    = $_SESSION['user']['role'];

$notif_chat = 0;
$notif_pesanan = 0;

if ($role === 'pembeli') {

  $q = mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM chat
    WHERE penerima_id = '$user_id'
    AND dibaca = 0
  ");
  $notif_chat = mysqli_fetch_assoc($q)['total'];

  $q = mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM transaksi
    WHERE pembeli_id = '$user_id'
    AND notif_dibaca_pembeli = 0
    AND status IN ('dikirim','refund')
  ");
  $notif_pesanan = mysqli_fetch_assoc($q)['total'];

} elseif ($role === 'penjual') {

  $q = mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM chat
    WHERE penerima_id = '$user_id'
    AND dibaca = 0
  ");
  $notif_chat = mysqli_fetch_assoc($q)['total'];

  $q = mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM transaksi_penjual
    WHERE penjual_id = '$user_id'
    AND status IN ('MENUNGGU','MENUNGGU_VERIFIKASI')
  ");
  $notif_pesanan = mysqli_fetch_assoc($q)['total'];
}

echo json_encode([
  'chat'     => (int)$notif_chat,
  'pesanan' => (int)$notif_pesanan,
  'total'   => (int)($notif_chat + $notif_pesanan)
]);
