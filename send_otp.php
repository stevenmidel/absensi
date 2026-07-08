<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/config/mail.php';

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo json_encode(['status' => 'error', 'msg' => 'Email kosong']);
    exit;
}

$otp = random_int(100000, 999999);

// ✅ Ganti dari 20 detik → 5 menit (300 detik)
$_SESSION['otp']         = $otp;
$_SESSION['otp_expired'] = time() + 300;
$_SESSION['otp_email']   = $email;

// ✅ Debug: log waktu session tersimpan
error_log("OTP SET: $otp | Expired at: " . $_SESSION['otp_expired'] . " | Now: " . time());

try {
    $sent = sendMail(
        $email,
        'Kode OTP Absensi',
        "Kode OTP Anda: $otp\n\nBerlaku selama 5 menit."
    );

    if (!$sent) {
        throw new Exception("sendMail returned false");
    }

    echo json_encode(['status' => 'success', 'msg' => 'OTP berhasil dikirim']);
} catch (Exception $e) {
    // ✅ Hapus session jika email gagal terkirim
    unset($_SESSION['otp'], $_SESSION['otp_expired'], $_SESSION['otp_email']);
    error_log("Send OTP Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'msg' => 'Gagal kirim OTP']);
}
