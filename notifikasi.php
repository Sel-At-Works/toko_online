<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user    = $_SESSION['user'];
$role    = $user['role'];
$user_id = $user['id'];

if ($role === 'super_admin') {
    echo "<div style='margin-top:100px;text-align:center;color:#777'>
            Super Admin tidak memiliki notifikasi
          </div>";
    exit;
}

$notifikasi = [];

/* ==========================
   NOTIFIKASI PEMBELI
========================== */
if ($role === 'pembeli') {

    // 💬 Pesan dari penjual
    $qChat = mysqli_query($conn, "
        SELECT c.created_at, u.nama, u.id AS lawan_id
        FROM chat c
        JOIN users u ON c.pengirim_id = u.id
        WHERE c.penerima_id = $user_id
          AND c.dibaca = 0
          AND u.role = 'penjual'
        ORDER BY c.created_at DESC
    ");

    while ($c = mysqli_fetch_assoc($qChat)) {
        $notifikasi[] = [
            'icon' => '💬',
            'text' => 'Pesan baru dari ' . $c['nama'],
            'link' => '../chat_app.php?lawan_id=' . $c['lawan_id'],
            'time' => $c['created_at']
        ];
    }

    // 📦 Pesanan diproses
    $qOrder = mysqli_query($conn, "
        SELECT created_at
        FROM transaksi
        WHERE pembeli_id = $user_id
          AND status = 'diproses'
         ORDER BY created_at DESC
    ");

    while ($o = mysqli_fetch_assoc($qOrder)) {
        $notifikasi[] = [
            'icon' => '📦',
            'text' => 'Pesanan sedang diproses',
            'link' => '../pembeli/pesanan_saya.php',
            'time' => $o['created_at']
        ];
    }
}

/* ==========================
   NOTIFIKASI PENJUAL
========================== */
if ($role === 'penjual') {

    // 💬 Pesan dari pembeli
 $qChat = mysqli_query($conn, "
    SELECT c.created_at, u.nama, u.id AS lawan_id
    FROM chat c
    JOIN users u ON c.pengirim_id = u.id
    WHERE c.penerima_id = $user_id
      AND c.dibaca = 0
      AND u.role = 'pembeli'
    ORDER BY c.created_at DESC
");


    while ($c = mysqli_fetch_assoc($qChat)) {
        $notifikasi[] = [
            'icon' => '💬',
            'text' => 'Pesan baru dari ' . $c['nama'],
            'link' => 'chat_app.php?lawan_id=' . $c['lawan_id'],
            'time' => $c['created_at']
        ];
    }

    // 📦 Pesanan baru masuk
    $notif_pesanan = mysqli_query($conn, "
        SELECT t.created_at
        FROM transaksi t
        JOIN transaksi_penjual tp ON tp.transaksi_id = t.id
        WHERE tp.penjual_id = $user_id
          AND t.status = 'diproses'
        ORDER BY t.created_at DESC
    ");

    while ($o = mysqli_fetch_assoc($notif_pesanan)) {
        $notifikasi[] = [
            'icon' => '📦',
            'text' => 'Pesanan baru masuk',
            'link' => 'penjual/pesanan_masuk.php',
            'time' => $o['created_at']
        ];
    }
}
?>
