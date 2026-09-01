<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "absensi_db";

$conn = new mysqli(
    $host,
    $user,
    $pass,
    $db
);

if ($conn->connect_error) {
    die("Koneksi database gagal: " .
        $conn->connect_error);
}

if (!$conn->set_charset("utf8mb4")) {
    die("Gagal mengatur charset database: " .
        $conn->error);
}
