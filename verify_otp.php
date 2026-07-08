<?php
session_start();
header('Content-Type: application/json');

$otpInput = trim($_POST['otp'] ?? '');

// ✅ Debug: cek isi session saat verify
error_log("VERIFY - Input: $otpInput | Session OTP: " . ($_SESSION['otp'] ?? 'NULL')
    . " | Expired: " . ($_SESSION['otp_expired'] ?? 'NULL')
    . " | Now: " . time());

if ($otpInput === '') {
    echo json_encode(['status' => 'error', 'msg' => 'OTP kosong']);
    exit;
}

if (!isset($_SESSION['otp'], $_SESSION['otp_expired'], $_SESSION['otp_email'])) {
    echo json_encode(['status' => 'error', 'msg' => 'OTP tidak ditemukan — session hilang']);
    exit;
}

if (time() > $_SESSION['otp_expired']) {
    $selisih = time() - $_SESSION['otp_expired'];
    error_log("OTP EXPIRED: terlambat $selisih detik");
    unset($_SESSION['otp'], $_SESSION['otp_expired'], $_SESSION['otp_email']);
    echo json_encode(['status' => 'error', 'msg' => 'OTP kadaluarsa']);
    exit;
}

if ($otpInput !== (string) $_SESSION['otp']) {
    echo json_encode(['status' => 'error', 'msg' => 'OTP salah']);
    exit;
}

$_SESSION['verified_email'] = $_SESSION['otp_email'];
unset($_SESSION['otp'], $_SESSION['otp_expired'], $_SESSION['otp_email']);

echo json_encode(['status' => 'success', 'msg' => 'OTP valid']);
