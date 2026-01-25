<?php
session_start();
include 'config/koneksi.php';

/* ================== UPDATE STATUS LOGIN ================== */
if (isset($_SESSION['user_id'])) {
    mysqli_query($conn, "
        UPDATE users 
        SET status_login = 'offline'
        WHERE id = {$_SESSION['user_id']}
    ");
}

/* ================== HAPUS SESSION ================== */
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil Logout',
        text: 'Anda telah keluar dari sistem',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        window.location.href = 'login.php';
    });
</script>

</body>
</html>
