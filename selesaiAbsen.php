<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Absensi Selesai</title>

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

<body class="bg-slate-100 min-h-screen flex items-center justify-center px-4 font-sans">

    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden grid md:grid-cols-2">

        <!-- SISI KIRI -->
        <div class="hidden md:flex flex-col justify-center bg-gradient-to-br from-blue-600 to-teal-500 text-white p-10">
            <h2 class="text-3xl font-bold mb-4">
                Terima Kasih
            </h2>
            <p class="text-lg leading-relaxed">
                Absensi Anda telah<br>
                <span class="font-semibold">berhasil direkam</span>
            </p>
        </div>

        <!-- SISI KANAN -->
        <div class="p-6 md:p-10 flex flex-col justify-center text-center md:text-left">

            <div class="mx-auto md:mx-0 mb-4 w-16 h-16 flex items-center justify-center rounded-full bg-teal-100">
                <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                Absensi Berhasil
            </h1>

            <p class="text-gray-600 mb-6">
                Terima kasih telah melakukan absensi hari ini.
                <br class="hidden md:block">
                Semoga aktivitas Anda berjalan lancar.
            </p>

            <a href="index.php"
                class="inline-block w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition text-center">
                Kembali ke Halaman Absensi
            </a>
        </div>

    </div>

</body>

</html>