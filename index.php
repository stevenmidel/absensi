<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Absensi Karyawan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center px-4 font-sans">

    <?php include 'partials/alert.php'; ?>

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl grid md:grid-cols-2 overflow-hidden">

        <!-- KIRI -->
        <div class="hidden md:flex flex-col justify-center bg-gradient-to-br from-blue-600 to-teal-500 text-white p-10">
            <h2 class="text-3xl font-bold mb-4">Selamat Datang</h2>

            <p class="text-lg">
                Sistem Absensi Karyawan<br>
                <b>Primaya Hospital Inco Sorowako</b>
            </p>

            <!-- JAM REAL TIME -->
            <div class="mt-6">
                <p class="text-sm uppercase tracking-wider opacity-80">Waktu Sekarang</p>
                <p id="jamRealTime" class="text-4xl font-bold mt-1">00:00:00</p>
            </div>
        </div>

        <!-- KANAN -->
        <div class="p-6 md:p-8 space-y-4">
            <h1 class="text-xl font-semibold text-gray-800">Absensi Karyawan</h1>

            <!-- FOTO -->
            <div class="space-y-2">
                <label class="text-sm text-gray-600 font-medium">Foto Bukti Absensi</label>

                <input type="file" id="inputFoto"
                    accept="image/*"
                    capture="user"
                    class="w-full border rounded-xl px-3 py-2 text-sm text-gray-700">

                <img id="previewFoto"
                    class="hidden w-full max-h-48 object-cover rounded-xl border"
                    alt="Preview foto">
            </div>

            <!-- FORM -->
            <form method="POST" action="absen.php" class="space-y-3" onsubmit="return validasi()">

                <input type="hidden" name="foto" id="foto">

                <input name="nama" required placeholder="Nama Lengkap"
                    class="w-full border rounded-xl px-3 py-2">

                <select name="departemen" required class="w-full border rounded-xl px-3 py-2">
                    <option value="">Pilih Departemen</option>
                    <option>IT</option>
                    <option>HRGA</option>
                    <option>Keperawatan</option>
                    <option>Laboratorium</option>
                    <option>Radiologi</option>
                    <option>Medical Record</option>
                    <option>Admission</option>
                    <option>Finance</option>
                    <option>Farmasi</option>
                    <option>Dokter GP</option>
                    <option>Dokter Spesialis</option>
                </select>

                <select name="keterangan" required class="w-full border rounded-xl px-3 py-2">
                    <option value="">Keterangan</option>
                    <option>Masuk</option>
                    <option>Pulang</option>
                    <option>Lembur Masuk</option>
                    <option>Lembur Keluar</option>
                </select>

                <button class="w-full bg-teal-600 text-white py-2 rounded-xl font-semibold">
                    Kirim Absensi
                </button>
            </form>
        </div>
    </div>

    <script>
        // Waktu Real Time
        function updateJam() {
            const now = new Date();
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('jamRealTime').textContent =
                `${jam}:${menit}:${detik}`;
        }

        updateJam(); // tampil langsung
        setInterval(updateJam, 1000); // update tiap 1 detik

        const inputFoto = document.getElementById("inputFoto");
        const previewFoto = document.getElementById("previewFoto");
        const foto = document.getElementById("foto");

        inputFoto.onchange = () => {
            const file = inputFoto.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert("Ukuran foto maksimal 5MB");
                inputFoto.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                foto.value = e.target.result;
                previewFoto.src = e.target.result;
                previewFoto.classList.remove("hidden");
            };
            reader.readAsDataURL(file);
        };

        function validasi() {
            if (!foto.value) {
                alert("Ambil foto selfie terlebih dahulu");
                return false;
            }
            return true;
        }
    </script>

</body>

</html>