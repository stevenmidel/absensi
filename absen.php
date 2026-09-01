<?php

session_start();

/*
|--------------------------------------------------------------------------
| TIMEZONE
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Asia/Makassar');


/*
|--------------------------------------------------------------------------
| KONEKSI DATABASE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config/koneksi.php';


/*
|--------------------------------------------------------------------------
| PASTIKAN REQUEST MENGGUNAKAN POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Akses Tidak Valid',
        'message' => 'Permintaan tidak valid'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| AMBIL DATA DARI FORM
|--------------------------------------------------------------------------
|
| karyawan_id :
| ID karyawan yang dipilih dari autocomplete.
|
| keterangan :
| Jenis absensi.
|
| foto :
| Foto selfie dalam bentuk Base64 Data URL.
|
| device :
| Jenis device yang dideteksi browser.
|
*/

$karyawan_id = intval($_POST['karyawan_id'] ?? 0);

$keterangan = trim(
    $_POST['keterangan'] ?? ''
);

$foto = $_POST['foto'] ?? '';

$device = trim(
    $_POST['device'] ?? 'Unknown'
);


/*
|--------------------------------------------------------------------------
| VALIDASI DEVICE
|--------------------------------------------------------------------------
|
| Device hanya boleh berisi jenis device yang kita kenal.
| Jika browser mengirim nilai lain, gunakan Unknown.
|
*/

$deviceValid = [
    'Android',
    'iPhone/iPad',
    'Windows PC',
    'Mac',
    'Linux',
    'Unknown'
];

if (!in_array($device, $deviceValid, true)) {
    $device = 'Unknown';
}


/*
|--------------------------------------------------------------------------
| VALIDASI DASAR
|--------------------------------------------------------------------------
*/

if (
    $karyawan_id <= 0 ||
    $keterangan === '' ||
    $foto === ''
) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Data Tidak Lengkap',
        'message' => 'Mohon lengkapi seluruh data absensi'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| VALIDASI KETERANGAN
|--------------------------------------------------------------------------
*/

$keteranganValid = [
    'Masuk',
    'Pulang',
    'Lembur Masuk',
    'Lembur Keluar'
];

if (!in_array($keterangan, $keteranganValid, true)) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Keterangan Tidak Valid',
        'message' => 'Keterangan absensi tidak valid'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| AMBIL DATA KARYAWAN DARI DATABASE
|--------------------------------------------------------------------------
|
| Jangan mengambil nama/departemen dari browser.
|
| Kita mengambil:
|
| id
| nama
| departemen
|
| langsung dari tabel karyawan berdasarkan karyawan_id.
|
*/

$stmt = $conn->prepare("
    SELECT
        id,
        nama,
        departemen
    FROM karyawan
    WHERE id = ?
    LIMIT 1
");


if (!$stmt) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Database Error',
        'message' => 'Gagal memproses data karyawan'
    ];

    header("Location: index.php");
    exit;
}


$stmt->bind_param(
    "i",
    $karyawan_id
);


$stmt->execute();


$result = $stmt->get_result();


$karyawan = $result->fetch_assoc();


$stmt->close();


/*
|--------------------------------------------------------------------------
| VALIDASI KARYAWAN
|--------------------------------------------------------------------------
*/

if (!$karyawan) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Karyawan Tidak Ditemukan',
        'message' => 'Data karyawan yang dipilih tidak ditemukan'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| AMBIL NAMA & DEPARTEMEN DARI DATABASE
|--------------------------------------------------------------------------
*/

$nama = trim(
    $karyawan['nama']
);

$departemen = trim(
    $karyawan['departemen']
);


/*
|--------------------------------------------------------------------------
| VALIDASI DATA KARYAWAN
|--------------------------------------------------------------------------
*/

if (
    $nama === '' ||
    $departemen === ''
) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Data Karyawan Tidak Lengkap',
        'message' => 'Nama atau departemen karyawan belum tersedia'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| VALIDASI FOTO
|--------------------------------------------------------------------------
|
| Foto dikirim dari index.php dalam bentuk:
|
| data:image/jpeg;base64,.....
|
| atau:
|
| data:image/png;base64,.....
|
| atau:
|
| data:image/webp;base64,.....
|
*/

if (
    !preg_match(
        '/^data:image\/(jpeg|jpg|png|webp);base64,/i',
        $foto
    )
) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Foto Tidak Valid',
        'message' => 'Format foto tidak valid'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| PISAHKAN HEADER BASE64 DAN DATA FOTO
|--------------------------------------------------------------------------
*/

$fotoParts = explode(
    ',',
    $foto,
    2
);


if (count($fotoParts) !== 2) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Foto Tidak Valid',
        'message' => 'Data foto tidak valid'
    ];

    header("Location: index.php");
    exit;
}


$fotoBase64 = $fotoParts[1];


/*
|--------------------------------------------------------------------------
| DECODE FOTO
|--------------------------------------------------------------------------
*/

$fotoBinary = base64_decode(
    $fotoBase64,
    true
);


if ($fotoBinary === false) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Foto Tidak Valid',
        'message' => 'Foto gagal diproses'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| CEK UKURAN FOTO
|--------------------------------------------------------------------------
|
| Maksimal 5 MB setelah decoding.
|
*/

if (
    strlen($fotoBinary) > 5 * 1024 * 1024
) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Ukuran Foto Terlalu Besar',
        'message' => 'Ukuran foto maksimal 5MB'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| VALIDASI ISI FOTO
|--------------------------------------------------------------------------
|
| Memastikan data benar-benar merupakan gambar.
|
*/

$imageInfo = @getimagesizefromstring(
    $fotoBinary
);


if ($imageInfo === false) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Foto Tidak Valid',
        'message' => 'File yang dikirim bukan gambar yang valid'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| TANGGAL & WAKTU
|--------------------------------------------------------------------------
*/

$tanggal = date("Y-m-d");

$waktu = date("H:i:s");


/*
|--------------------------------------------------------------------------
| CEK ABSENSI GANDA
|--------------------------------------------------------------------------
|
| Satu karyawan tidak boleh melakukan jenis absensi
| yang sama lebih dari satu kali pada tanggal yang sama.
|
| Contoh:
|
| Steven - 26/08/2026 - Masuk
|
| Jika mencoba Masuk lagi:
| DITOLAK
|
| Tetapi:
|
| Steven - 26/08/2026 - Masuk
| Steven - 26/08/2026 - Pulang
|
| Tetap diperbolehkan.
|
*/

$stmt = $conn->prepare("
    SELECT id
    FROM absensi
    WHERE karyawan_id = ?
      AND tanggal = ?
      AND keterangan = ?
    LIMIT 1
");


if (!$stmt) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Database Error',
        'message' => 'Gagal memeriksa absensi sebelumnya'
    ];

    header("Location: index.php");
    exit;
}


$stmt->bind_param(
    "iss",
    $karyawan_id,
    $tanggal,
    $keterangan
);


$stmt->execute();


$stmt->store_result();


/*
|--------------------------------------------------------------------------
| JIKA SUDAH ABSEN
|--------------------------------------------------------------------------
*/

if ($stmt->num_rows > 0) {

    $stmt->close();

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Absensi Gagal',
        'message' => 'Karyawan sudah melakukan absensi ini hari ini'
    ];

    header("Location: index.php");
    exit;
}


$stmt->close();


/*
|--------------------------------------------------------------------------
| SIMPAN ABSENSI
|--------------------------------------------------------------------------
|
| Nama dan departemen berasal dari DATABASE.
|
| Device berasal dari browser dan digunakan
| sebagai informasi audit.
|
*/

$stmt = $conn->prepare("
    INSERT INTO absensi
    (
        karyawan_id,
        nama,
        departemen,
        keterangan,
        foto,
        device,
        tanggal,
        waktu
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");


if (!$stmt) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Database Error',
        'message' => 'Gagal menyiapkan penyimpanan absensi'
    ];

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| BIND PARAMETER
|--------------------------------------------------------------------------
|
| i = integer
| s = string
|
*/

$stmt->bind_param(
    "isssssss",
    $karyawan_id,
    $nama,
    $departemen,
    $keterangan,
    $foto,
    $device,
    $tanggal,
    $waktu
);


/*
|--------------------------------------------------------------------------
| EKSEKUSI INSERT
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {

    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Berhasil',
        'message' => 'Absensi berhasil disimpan'
    ];

    $stmt->close();

    $conn->close();

    header("Location: selesaiAbsen.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| JIKA GAGAL SIMPAN
|--------------------------------------------------------------------------
*/

$stmt->close();

$conn->close();


$_SESSION['alert'] = [
    'type' => 'error',
    'title' => 'Gagal',
    'message' => 'Terjadi kesalahan saat menyimpan data absensi'
];


header("Location: index.php");
exit;
