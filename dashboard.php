<?php
session_start();

/* ===== CEK LOGIN ===== */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* ===== KONEKSI DB ===== */
$conn = new mysqli("localhost", "root", "", "absensi_db");
if ($conn->connect_error) {
    die("Koneksi database gagal");
}

/* ===== HITUNG JUMLAH KARYAWAN ===== */
$q = $conn->query("SELECT COUNT(id) AS total FROM karyawan");
$row = $q->fetch_assoc();
$totalKaryawan = $row['total'] ?? 0;

/* ===== DEMO DATA ===== */
$masuk         = 120;
$keluar        = 110;
$lemburMasuk   = 15;
$lemburKeluar  = 10;
$totalDept     = 8;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-100 font-sans min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-lg hidden md:flex flex-col">
        <div class="p-6 font-bold text-lg text-blue-600">
            Admin Panel
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php"
                class="block px-4 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                Dashboard
            </a>
            <a href="#"
                class="block px-4 py-2 rounded-lg hover:bg-slate-100">
                Karyawan
            </a>
            <a href="#"
                class="block px-4 py-2 rounded-lg hover:bg-slate-100">
                Absensi
            </a>
            <a href="login.php"
                class="block px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                Logout
            </a>
        </nav>
    </aside>

    <!-- KONTEN -->
    <div class="flex-1 flex flex-col">

        <!-- NAVBAR -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-gray-800">
                Dashboard
            </h1>

            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-600">
                    <?= htmlspecialchars($_SESSION['admin']) ?>
                </div>
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                    <?= strtoupper(substr($_SESSION['admin'], 0, 1)) ?>
                </div>
            </div>
        </header>

        <!-- ISI -->
        <main class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <!-- CARD -->
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Jumlah Karyawan</p>
                <h2 class="text-3xl font-bold text-blue-600">
                    <?= $totalKaryawan ?>
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Jumlah Masuk</p>
                <h2 class="text-3xl font-bold text-green-600">
                    <?= $masuk ?>
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Jumlah Keluar</p>
                <h2 class="text-3xl font-bold text-indigo-600">
                    <?= $keluar ?>
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Lembur Masuk</p>
                <h2 class="text-3xl font-bold text-orange-600">
                    <?= $lemburMasuk ?>
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Lembur Keluar</p>
                <h2 class="text-3xl font-bold text-pink-600">
                    <?= $lemburKeluar ?>
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Jumlah Departemen</p>
                <h2 class="text-3xl font-bold text-teal-600">
                    <?= $totalDept ?>
                </h2>
            </div>

        </main>
    </div>

</body>

</html>