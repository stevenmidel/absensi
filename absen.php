<?php
session_start();

/* ===== KONEKSI DB ===== */
$conn = new mysqli("localhost", "root", "", "absensi_db");
if ($conn->connect_error) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Database Error',
        'message' => 'Koneksi database gagal'
    ];
    header("Location: index.php");
    exit;
}

/* ===== AMBIL DATA ===== */
$nama        = trim($_POST['nama'] ?? '');
$departemen  = trim($_POST['departemen'] ?? '');
$keterangan  = trim($_POST['keterangan'] ?? '');
$foto        = $_POST['foto'] ?? '';

/* ===== VALIDASI ===== */
if ($nama === '' || $departemen === '' || $keterangan === '' || $foto === '') {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Data Tidak Lengkap',
        'message' => 'Mohon lengkapi seluruh data absensi'
    ];
    header("Location: index.php");
    exit;
}

$tanggal = date("Y-m-d");
$waktu   = date("H:i:s");

/* ===== CEK ABSEN GANDA ===== */
$stmt = $conn->prepare("
    SELECT id FROM absensi 
    WHERE nama = ?
    AND tanggal = ?
    AND keterangan = ?
");
$stmt->bind_param("sss", $nama, $tanggal, $keterangan);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Absensi Gagal',
        'message' => 'Anda sudah melakukan absensi ini hari ini'
    ];
    header("Location: index.php");
    exit;
}
$stmt->close();

/* ===== SIMPAN DATA ===== */
$stmt = $conn->prepare("
    INSERT INTO absensi
    (nama, departemen, keterangan, foto, tanggal, waktu)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "ssssss",
    $nama,
    $departemen,
    $keterangan,
    $foto,
    $tanggal,
    $waktu
);

if ($stmt->execute()) {
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Berhasil',
        'message' => 'Absensi berhasil disimpan'
    ];
    header("Location: selesaiAbsen.php");
} else {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Gagal',
        'message' => 'Terjadi kesalahan saat menyimpan data'
    ];
    header("Location: index.php");
}

$stmt->close();
$conn->close();
exit;
