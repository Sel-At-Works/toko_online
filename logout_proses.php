<?php
session_start();
include 'config/koneksi.php';

/* ================== UPDATE STATUS LOGIN ================== */
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];

    mysqli_query($conn, "
        UPDATE users 
        SET status_login = 'offline'
        WHERE id = $id
    ");
}

/* ================== HAPUS SESSION ================== */
$_SESSION = [];

// hapus cookie session
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

// destroy session
session_destroy();

/* ================== REDIRECT LANGSUNG ================== */
header("Location: login.php");
exit;