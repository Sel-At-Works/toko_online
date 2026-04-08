<?php
session_start();
include '../config/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$penjual_id = (int) $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT 
        id AS tp_id,
        status,
        approve,
        resi
    FROM transaksi_penjual
    WHERE penjual_id = $penjual_id
    AND is_hidden = 0
");

$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    $data[$row['tp_id']] = [
        'status'  => $row['status'],
        'approve' => $row['approve'],
        'resi'    => $row['resi']
    ];
}

echo json_encode($data);