<?php
session_start();
include 'config/koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$email = $_POST['email'] ?? '';

// VALIDASI KOSONG
if ($email === '') {
    $_SESSION['alert'] = [
        'title' => 'Gagal',
        'text'  => 'Email wajib diisi',
        'icon'  => 'warning'
    ];
    header("Location: forget_email.php");
    exit;
}

// CEK EMAIL
$cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");
if (!$cek || mysqli_num_rows($cek) === 0) {
    $_SESSION['alert'] = [
        'title' => 'Gagal',
        'text'  => 'Email tidak terdaftar',
        'icon'  => 'error'
    ];
    header("Location: forget_email.php");
    exit;
}

// GENERATE TOKEN
$token   = bin2hex(random_bytes(32));
$expired = date("Y-m-d H:i:s", strtotime("+30 minutes"));

mysqli_query($conn, "
    UPDATE users 
    SET reset_token='$token', reset_expired='$expired'
    WHERE email='$email'
");

// LINK RESET
$link = "http://localhost/toko_online/reset_password.php?token=$token";

// KIRIM EMAIL
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'c6e92c53cc1737';
    $mail->Password   = '7afcf011e93e89';
    $mail->Port       = 2525;

    $mail->setFrom('no-reply@cahayanusantara.com', 'Cahaya Nusantara');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Reset Password';
    $mail->Body = "
        <h3>Reset Password</h3>
        <p>Klik link di bawah ini:</p>
        <a href='$link'>$link</a>
        <p>Link berlaku selama 30 menit.</p>
    ";

    $mail->send();

    $_SESSION['alert'] = [
        'title' => 'Berhasil',
        'text'  => 'Link reset password telah dikirim. Silakan cek email Anda.',
        'icon'  => 'success'
    ];
    header("Location: forget_email.php");
    exit;

} catch (Exception $e) {
    $_SESSION['alert'] = [
        'title' => 'Gagal',
        'text'  => 'Email gagal dikirim. Silakan coba lagi.',
        'icon'  => 'error'
    ];
    header("Location: forget_email.php");
    exit;
}
