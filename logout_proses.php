<?php
session_start();
include 'config/koneksi.php';

/* update status login */
if (isset($_SESSION['user_id'])) {

    $id = intval($_SESSION['user_id']);

    mysqli_query($conn,"
        UPDATE users 
        SET status_login='offline'
        WHERE id=$id
    ");
}

/* hapus semua session */
$_SESSION = [];

/* hapus cookie session */
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* destroy session */
session_destroy();

/* redirect */
header("Location: login.php");
exit;