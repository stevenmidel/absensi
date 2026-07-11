<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Karyawan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

    <!-- Loading -->
    <div id="loading"
        class="hidden fixed inset-0 bg-primaya-navy/60 z-50 flex items-center justify-center">

        <div class="bg-white rounded-2xl p-8 flex flex-col items-center shadow-xl">
            <svg class="animate-spin h-12 w-12 text-primaya-teal mb-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24">

                <circle class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>
            </svg>

            <p class="font-semibold text-primaya-navy">
                Mengirim absensi...
            </p>
        </div>

    </div>
</head>

<body class="bg-slate-100 min-h-screen flex flex-col font-sans">

    <main class="flex-1 flex items-center justify-center px-4 py-8">

        <?php include 'partials/alert.php'; ?>

        <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl flex flex-col md:grid md:grid-cols-2 overflow-hidden">

            <!-- KANAN (Form Absensi) -->
            <!-- Mobile: tampil paling atas. Desktop: kembali ke kolom kanan -->
            <div class="order-1 md:order-2 p-6 md:p-8 space-y-4">
                <h1 class="text-xl font-semibold text-primaya-navy">Absensi Karyawan</h1>

                <!-- FOTO -->
                <div class="space-y-2">
                    <label class="text-sm text-gray-600 font-medium">Foto Bukti Absensi</label>

                    <input type="file" id="inputFoto"
                        accept="image/*"
                        capture="user"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primaya-teal">

                    <img id="previewFoto"
                        class="hidden w-full max-h-48 object-cover rounded-xl border border-gray-300"
                        alt="Preview foto">
                </div>

                <!-- FORM -->
                <form
                    id="formAbsensi"
                    method="POST"
                    action="absen.php"
                    class="space-y-3"
                    onsubmit="return validasi()">

                    <input type="hidden" name="foto" id="foto">

                    <input name="nama" required placeholder="Nama Lengkap"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primaya-teal">

                    <select name="departemen" required
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primaya-teal">
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
                        <option>Mutu</option>
                        <option>Marketing</option>
                        <option>PPI</option>
                    </select>

                    <select name="keterangan" required
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primaya-teal">
                        <option value="">Keterangan</option>
                        <option>Masuk</option>
                        <option>Pulang</option>
                        <option>Lembur Masuk</option>
                        <option>Lembur Keluar</option>
                    </select>

                    <button
                        id="btnSubmit"
                        class="w-full bg-primaya-teal hover:bg-[#00897E] transition-colors text-white py-2 rounded-xl font-semibold">
                        Kirim Absensi
                    </button>
                </form>
            </div>

            <!-- KIRI (Sambutan) -->
            <!-- Mobile: tampil di bawah form. Desktop: kembali ke kolom kiri -->
            <div class="order-2 md:order-1 flex flex-col justify-center bg-gradient-to-br from-primaya-navy to-primaya-teal text-white p-8 md:p-10">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Selamat Datang</h2>

                <p class="text-base md:text-lg">
                    Sistem Absensi Karyawan<br>
                    <b>Primaya Hospital Inco Sorowako</b>
                </p>

                <!-- JAM REAL TIME -->
                <div class="mt-6">
                    <p class="text-sm uppercase tracking-wider opacity-80">Waktu Sekarang</p>
                    <p id="jamRealTime" class="text-3xl md:text-4xl font-bold mt-1">00:00:00</p>
                </div>
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

                document.getElementById("loading").classList.remove("hidden");

                const tombol = document.getElementById("btnSubmit");
                tombol.disabled = true;
                tombol.innerHTML = "Mengirim...";

                return true;
            }
        </script>

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