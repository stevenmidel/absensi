<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>

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

    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden grid md:grid-cols-2">

        <!-- KIRI -->
        <div class="hidden md:flex flex-col justify-center bg-gradient-to-br from-blue-600 to-teal-500 text-white p-10">
            <h2 class="text-3xl font-bold mb-4">Dashboard Admin</h2>
            <p class="text-lg leading-relaxed">
                Sistem Absensi Karyawan<br>
                <span class="font-semibold">Primaya Hospital Inco Sorowako</span>
            </p>
        </div>

        <!-- KANAN -->
        <div class="p-6 md:p-10 flex flex-col justify-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center md:text-left">
                Login Admin
            </h1>

            <form action="login_proses.php" method="POST" class="space-y-4">
                <input type="text" name="username" required
                    placeholder="Username"
                    class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                <input type="password" name="password" required
                    placeholder="Password"
                    class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                <button
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                    Masuk
                </button>
            </form>
        </div>
    </div>

</body>

</html>