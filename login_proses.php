<?php
session_start();
$conn = new mysqli("localhost", "root", "", "absensi_db");

if ($conn->connect_error) {
    die("Koneksi gagal");
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username == '' || $password == '') {
    header("Location: login.php");
    exit;
}

$hash = hash('sha256', $password);

$stmt = $conn->prepare("
    SELECT id FROM admin
    WHERE username = ? AND password = ?
");
$stmt->bind_param("ss", $username, $hash);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $_SESSION['admin'] = $username;
    header("Location: dashboard.php");
} else {
    header("Location: login.php?error=1");
}
exit;
