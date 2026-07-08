<?php
$host = "mysql";
$user = "absensi";
$pass = "absensi123";
$db   = "absensi_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
