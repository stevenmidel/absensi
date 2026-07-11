<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Selesai</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        primaya: {
                            navy: '#0B3B60', // biru navy utama (identitas Primaya Hospital)
                            blue: '#0F5C97', // biru sekunder untuk gradasi
                            teal: '#00A99D', // teal/aksen kesehatan
                            gold: '#F5A623' // aksen emas untuk highlight kecil
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-100 min-h-screen flex flex-col font-sans">

    <main class="flex-1 flex items-center justify-center px-4 py-8">

        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:grid md:grid-cols-2">

            <!-- SISI KANAN (Konfirmasi) -->
            <!-- Mobile: tampil paling atas. Desktop: kembali ke kolom kanan -->
            <div class="order-1 md:order-2 p-6 md:p-10 flex flex-col justify-center text-center md:text-left">

                <div class="mx-auto md:mx-0 mb-4 w-16 h-16 flex items-center justify-center rounded-full bg-primaya-teal/10">
                    <svg class="w-8 h-8 text-primaya-teal" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-primaya-navy mb-2">
                    Absensi Berhasil
                </h1>

                <p class="text-gray-600 mb-6">
                    Terima kasih telah melakukan absensi hari ini.
                    <br class="hidden md:block">
                    Semoga aktivitas Anda berjalan lancar.
                </p>



            </div>

            <!-- SISI KIRI (Sambutan) -->
            <!-- Mobile: tampil di bawah konfirmasi. Desktop: kembali ke kolom kiri -->
            <div class="order-2 md:order-1 flex flex-col justify-center bg-gradient-to-br from-primaya-navy to-primaya-teal text-white p-8 md:p-10">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">
                    Terima Kasih
                </h2>
                <p class="text-base md:text-lg leading-relaxed">
                    Absensi Anda telah<br>
                    <span class="font-semibold">berhasil direkam</span>
                </p>
            </div>

        </div>

    </main>
    <footer class="bg-transparent py-6">
        <div class="text-center text-sm text-gray-500">
            © <?php echo date('Y'); ?> Primaya Hospital Inco Sorowako •
            Sistem Absensi Karyawan •
            Directed by IT PHSW
        </div>
    </footer>

</body>

</html>