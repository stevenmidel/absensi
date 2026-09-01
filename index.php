<?php

session_start();

require_once __DIR__ . '/config/koneksi.php';

/*
|--------------------------------------------------------------------------
| AMBIL DATA KARYAWAN
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT id, nama, departemen
    FROM karyawan
    ORDER BY nama ASC
";

$result = $conn->query($sql);

$karyawan = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $karyawan[] = $row;
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Absensi Karyawan</title>


    <!-- Google Font -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    <!-- Tailwind CSS -->

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

                            navy: '#0B3B60',

                            blue: '#0F5C97',

                            teal: '#00A99D',

                            gold: '#F5A623'

                        }

                    }

                }

            }

        };
    </script>

</head>


<body class="bg-slate-100 min-h-screen flex flex-col font-sans">


    <!-- =========================================================
         LOADING
    ========================================================== -->

    <div
        id="loading"
        class="hidden fixed inset-0 bg-primaya-navy/60 z-50 flex items-center justify-center">

        <div
            class="bg-white rounded-2xl p-8 flex flex-col items-center shadow-xl">

            <svg
                class="animate-spin h-12 w-12 text-primaya-teal mb-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24">

                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>

            </svg>

            <p class="font-semibold text-primaya-navy">
                Mengirim absensi...
            </p>

        </div>

    </div>


    <!-- =========================================================
         MAIN
    ========================================================== -->

    <main class="flex-1 flex items-center justify-center px-4 py-8">

        <?php include __DIR__ . '/partials/alert.php'; ?>


        <div
            class="w-full max-w-5xl bg-white rounded-2xl shadow-xl flex flex-col md:grid md:grid-cols-2 overflow-hidden">


            <!-- =================================================
                 FORM ABSENSI
            ================================================== -->

            <div
                class="order-1 md:order-2 p-6 md:p-8 space-y-4">

                <h1
                    class="text-xl font-semibold text-primaya-navy">
                    Absensi Karyawan
                </h1>


                <!-- =============================================
                     FOTO
                ============================================== -->

                <div class="space-y-2">

                    <label
                        for="inputFoto"
                        class="text-sm text-gray-600 font-medium">
                        Foto Bukti Absensi
                    </label>

                    <input
                        type="file"
                        id="inputFoto"
                        accept="image/*"
                        capture="user"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primaya-teal">

                    <img
                        id="previewFoto"
                        class="hidden w-full max-h-48 object-cover rounded-xl border border-gray-300"
                        alt="Preview foto">

                </div>


                <!-- =============================================
                     FORM
                ============================================== -->

                <form
                    id="formAbsensi"
                    method="POST"
                    action="absen.php"
                    class="space-y-3"
                    onsubmit="return validasi()">


                    <!-- Foto Base64 -->

                    <input
                        type="hidden"
                        name="foto"
                        id="foto">

                    <input type="hidden" name="device" id="device">


                    <!-- =========================================
                         NAMA KARYAWAN
                    ========================================== -->

                    <div>

                        <label
                            for="namaKaryawan"
                            class="block text-sm text-gray-600 font-medium mb-1">
                            Nama Karyawan
                        </label>


                        <div class="relative">

                            <!-- Input Nama -->

                            <input
                                type="text"
                                id="namaKaryawan"
                                name="nama"
                                autocomplete="off"
                                required
                                placeholder="Ketik nama karyawan..."
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primaya-teal">


                            <!-- ID Karyawan -->

                            <input
                                type="hidden"
                                name="karyawan_id"
                                id="karyawan_id">


                            <!-- Dropdown Autocomplete -->

                            <div
                                id="autocompleteKaryawan"
                                class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                            </div>

                        </div>

                    </div>


                    <!-- =========================================
                         DEPARTEMEN
                    ========================================== -->

                    <div>

                        <label
                            for="departemen"
                            class="block text-sm text-gray-600 font-medium mb-1">
                            Departemen
                        </label>

                        <input
                            type="text"
                            id="departemen"
                            name="departemen"
                            readonly
                            placeholder="Departemen"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed focus:outline-none">

                    </div>


                    <!-- =========================================
                         KETERANGAN
                    ========================================== -->

                    <div>

                        <label
                            for="keterangan"
                            class="block text-sm text-gray-600 font-medium mb-1">
                            Keterangan
                        </label>

                        <select
                            name="keterangan"
                            id="keterangan"
                            required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primaya-teal bg-white">

                            <option value="">
                                Keterangan
                            </option>

                            <option value="Masuk">
                                Masuk
                            </option>

                            <option value="Pulang">
                                Pulang
                            </option>

                            <option value="Lembur Masuk">
                                Lembur Masuk
                            </option>

                            <option value="Lembur Keluar">
                                Lembur Keluar
                            </option>

                        </select>

                    </div>


                    <!-- =========================================
                         SUBMIT
                    ========================================== -->

                    <button
                        type="submit"
                        id="btnSubmit"
                        class="w-full bg-primaya-teal hover:bg-[#00897E] transition-colors text-white py-2 rounded-xl font-semibold">

                        Kirim Absensi

                    </button>

                </form>

            </div>


            <!-- =================================================
                 PANEL KIRI
            ================================================== -->

            <div
                class="order-2 md:order-1 flex flex-col justify-center bg-gradient-to-br from-primaya-navy to-primaya-teal text-white p-8 md:p-10">

                <h2
                    class="text-2xl md:text-3xl font-bold mb-4">

                    Selamat Datang

                </h2>


                <p
                    class="text-base md:text-lg">

                    Sistem Absensi Karyawan<br>

                    <b>
                        Primaya Hospital Inco Sorowako
                    </b>

                </p>


                <!-- JAM REAL TIME -->

                <div class="mt-6">

                    <p
                        class="text-sm uppercase tracking-wider opacity-80">

                        Waktu Sekarang

                    </p>

                    <p
                        id="jamRealTime"
                        class="text-3xl md:text-4xl font-bold mt-1">

                        00:00:00

                    </p>

                </div>

            </div>

        </div>

    </main>


    <!-- =========================================================
         FOOTER
    ========================================================== -->

    <footer class="bg-transparent py-6">

        <div
            class="text-center text-sm text-gray-500">

            © <?= date('Y'); ?> Primaya Hospital Inco Sorowako •

            Sistem Absensi Karyawan •

            Directed by IT PHSW

        </div>

    </footer>


    <!-- =========================================================
         DATA KARYAWAN UNTUK JAVASCRIPT
    ========================================================== -->

    <script>
        const dataKaryawan = <?= json_encode(
                                    $karyawan,
                                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                                ); ?>;
    </script>


    <!-- =========================================================
         JAVASCRIPT
    ========================================================== -->

    <script>
        /*
        |--------------------------------------------------------------------------
        | JAM REAL TIME
        |--------------------------------------------------------------------------
        */

        function updateJam() {

            const now = new Date();

            const jam = String(
                now.getHours()
            ).padStart(2, '0');

            const menit = String(
                now.getMinutes()
            ).padStart(2, '0');

            const detik = String(
                now.getSeconds()
            ).padStart(2, '0');

            document.getElementById(
                    'jamRealTime'
                ).textContent =
                `${jam}:${menit}:${detik}`;
        }

        updateJam();

        setInterval(
            updateJam,
            1000
        );


        /*
        |--------------------------------------------------------------------------
        | ELEMENT FOTO
        |--------------------------------------------------------------------------
        */

        const inputFoto =
            document.getElementById('inputFoto');

        const previewFoto =
            document.getElementById('previewFoto');

        const foto =
            document.getElementById('foto');


        /*
        |--------------------------------------------------------------------------
        | PREVIEW FOTO
        |--------------------------------------------------------------------------
        */

        inputFoto.addEventListener(
            'change',
            function() {

                const file = this.files[0];

                if (!file) {
                    return;
                }


                /*
                | Maksimal 5 MB
                */

                if (
                    file.size >
                    5 * 1024 * 1024
                ) {

                    alert(
                        'Ukuran foto maksimal 5MB'
                    );

                    this.value = '';

                    previewFoto.src = '';

                    previewFoto.classList.add(
                        'hidden'
                    );

                    foto.value = '';

                    return;
                }


                /*
                | Pastikan file gambar
                */

                if (
                    !file.type.startsWith('image/')
                ) {

                    alert(
                        'File harus berupa gambar.'
                    );

                    this.value = '';

                    return;
                }


                const reader =
                    new FileReader();


                reader.onload =
                    function(e) {

                        foto.value =
                            e.target.result;

                        previewFoto.src =
                            e.target.result;

                        previewFoto.classList.remove(
                            'hidden'
                        );

                    };


                reader.readAsDataURL(file);

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ELEMENT KARYAWAN
        |--------------------------------------------------------------------------
        */

        const namaKaryawan =
            document.getElementById(
                'namaKaryawan'
            );

        const karyawanId =
            document.getElementById(
                'karyawan_id'
            );

        const departemenInput =
            document.getElementById(
                'departemen'
            );

        const autocompleteKaryawan =
            document.getElementById(
                'autocompleteKaryawan'
            );


        /*
        |--------------------------------------------------------------------------
        | AUTOCOMPLETE KARYAWAN
        |--------------------------------------------------------------------------
        */

        namaKaryawan.addEventListener(
            'input',
            function() {

                const keyword =
                    this.value
                    .trim()
                    .toLowerCase();


                /*
                | Jika input kosong
                */

                if (!keyword) {

                    autocompleteKaryawan.classList.add(
                        'hidden'
                    );

                    autocompleteKaryawan.innerHTML = '';

                    karyawanId.value = '';

                    departemenInput.value = '';

                    return;
                }


                /*
                | User mengetik ulang nama,
                | batalkan pilihan sebelumnya
                */

                karyawanId.value = '';

                departemenInput.value = '';


                /*
                | Cari karyawan
                */

                const hasil =
                    dataKaryawan.filter(
                        function(karyawan) {

                            return karyawan.nama
                                .toLowerCase()
                                .includes(keyword);

                        }
                    );


                /*
                | Tidak ditemukan
                */

                if (hasil.length === 0) {

                    autocompleteKaryawan.innerHTML = `

                        <div class="px-4 py-3 text-sm text-gray-500">
                            Karyawan tidak ditemukan
                        </div>

                    `;

                    autocompleteKaryawan.classList.remove(
                        'hidden'
                    );

                    return;
                }


                /*
                | Tampilkan hasil
                */

                autocompleteKaryawan.innerHTML = '';


                hasil.forEach(
                    function(karyawan) {

                        const item =
                            document.createElement('button');

                        item.type = 'button';

                        item.className = `
                            w-full
                            text-left
                            px-4
                            py-3
                            border-b
                            border-gray-100
                            hover:bg-teal-50
                            transition
                        `;


                        item.innerHTML = `

                            <div class="font-medium text-gray-800">
                                ${escapeHtml(karyawan.nama)}
                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                ${escapeHtml(karyawan.departemen)}
                            </div>

                        `;


                        /*
                        | Ketika nama dipilih
                        */

                        item.addEventListener(
                            'click',
                            function() {

                                namaKaryawan.value =
                                    karyawan.nama;

                                karyawanId.value =
                                    karyawan.id;

                                departemenInput.value =
                                    karyawan.departemen;


                                autocompleteKaryawan.classList.add(
                                    'hidden'
                                );

                                autocompleteKaryawan.innerHTML =
                                    '';

                            }
                        );


                        autocompleteKaryawan.appendChild(
                            item
                        );

                    }
                );


                autocompleteKaryawan.classList.remove(
                    'hidden'
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TUTUP AUTOCOMPLETE KETIKA KLIK DI LUAR
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            function(event) {

                if (
                    !namaKaryawan.contains(event.target) &&
                    !autocompleteKaryawan.contains(event.target)
                ) {

                    autocompleteKaryawan.classList.add(
                        'hidden'
                    );

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        |
        | Mencegah data nama/departemen dari database
        | langsung dimasukkan sebagai HTML.
        |
        */

        function escapeHtml(value) {

            const div =
                document.createElement('div');

            div.textContent =
                value ?? '';

            return div.innerHTML;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI FORM
        |--------------------------------------------------------------------------
        */

        function validasi() {

            /*
            | Pastikan karyawan benar-benar dipilih
            */

            if (!karyawanId.value) {

                alert(
                    'Silakan ketik dan pilih nama karyawan dari rekomendasi.'
                );

                namaKaryawan.focus();

                return false;
            }


            /*
            | Pastikan foto sudah diambil
            */

            if (!foto.value) {

                alert(
                    'Ambil foto selfie terlebih dahulu.'
                );

                inputFoto.focus();

                return false;
            }


            /*
            | Tampilkan loading
            */

            document
                .getElementById('loading')
                .classList
                .remove('hidden');


            /*
            | Disable tombol submit
            */

            const tombol =
                document.getElementById(
                    'btnSubmit'
                );

            tombol.disabled = true;

            tombol.innerHTML =
                'Mengirim...';


            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | CEGAH FORM DIKIRIM DENGAN NAMA YANG TIDAK DIPILIH
        |--------------------------------------------------------------------------
        */

        namaKaryawan.addEventListener(
            'blur',
            function() {

                setTimeout(
                    function() {

                        if (
                            namaKaryawan.value.trim() !== '' &&
                            !karyawanId.value
                        ) {

                            departemenInput.value = '';

                        }

                    },
                    200
                );

            }
        );

        // =====================================================
        // DETEKSI JENIS DEVICE
        // =====================================================

        function deteksiDevice() {

            const userAgent = navigator.userAgent;

            if (/iPhone|iPad|iPod/i.test(userAgent)) {
                return 'iPhone/iPad';
            }

            if (/Android/i.test(userAgent)) {
                return 'Android';
            }

            if (/Windows/i.test(userAgent)) {
                return 'Windows PC';
            }

            if (/Macintosh|Mac OS X/i.test(userAgent)) {
                return 'Mac';
            }

            if (/Linux/i.test(userAgent)) {
                return 'Linux';
            }

            return 'Unknown';
        }

        // Isi device saat halaman dibuka
        document.getElementById('device').value = deteksiDevice();
    </script>

</body>

</html>