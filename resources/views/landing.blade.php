<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Piket App - Landing Page</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-['Poppins'] bg-[#b7dcd1]">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="ml-2 text-xl font-semibold text-gray-800">Piket App</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-600 hover:text-teal-600 transition">Home</a>
                    <a href="#features" class="text-gray-600 hover:text-teal-600 transition">Fitur</a>
                    <a href="#about" class="text-gray-600 hover:text-teal-600 transition">Tentang</a>
                </div>

                <!-- Login Button -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Hero Section -->
            <div id="home" class="bg-white rounded-xl shadow-lg p-8 mb-6 mt-6">
                <div class="text-center">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        Selamat Datang di Piket App
                    </h1>
                    <p class="text-xl text-teal-600 font-medium mb-6">
                        "Jangan lupa piket ya!"
                    </p>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Kelola jadwal piket dengan mudah dan efisien. Pantau kehadiran, atur jadwal, 
                        dan lihat laporan piket dalam satu platform terintegrasi.
                    </p>
                    
                    <div class="mt-8">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Mulai Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features" class="grid md:grid-cols-3 gap-6 mb-6">
                <!-- Fitur 1: Manajemen User -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-center w-12 h-12 bg-teal-100 rounded-lg mb-4">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Manajemen User</h3>
                    <p class="text-gray-600 text-sm">
                        Kelola data pengguna dengan mudah. Tambah, edit, hapus, dan import user dari file Excel.
                    </p>
                    <div class="mt-4 flex items-center text-sm text-teal-600">
                        <span class="bg-teal-100 px-2 py-1 rounded-full text-xs">Fitur Unggulan</span>
                    </div>
                </div>

                <!-- Fitur 2: Jadwal Piket -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-center w-12 h-12 bg-teal-100 rounded-lg mb-4">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Jadwal Piket</h3>
                    <p class="text-gray-600 text-sm">
                        Buat dan atur jadwal piket dengan sistem yang fleksibel dan mudah digunakan.
                    </p>
                    <div class="mt-4 flex items-center text-sm text-teal-600">
                        <span class="bg-teal-100 px-2 py-1 rounded-full text-xs">Otomatis</span>
                    </div>
                </div>

                <!-- Fitur 3: Laporan -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-center w-12 h-12 bg-teal-100 rounded-lg mb-4">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Laporan & Statistik</h3>
                    <p class="text-gray-600 text-sm">
                        Lihat laporan kehadiran dan statistik piket dalam format yang informatif.
                    </p>
                    <div class="mt-4 flex items-center text-sm text-teal-600">
                        <span class="bg-teal-100 px-2 py-1 rounded-full text-xs">Real-time</span>
                    </div>
                </div>
            </div>

            <!-- Additional Features Grid -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <!-- Fitur 4: Import/Export Excel -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Import/Export Excel</h3>
                            <p class="text-sm text-gray-600">
                                Import data user dari file Excel atau export data untuk backup.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Fitur 5: Filter & Pencarian -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Filter & Pencarian</h3>
                            <p class="text-sm text-gray-600">
                                Cari user berdasarkan nama atau email, filter berdasarkan divisi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div id="about" class="bg-white rounded-xl shadow-lg p-8 mb-6">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="md:w-1/2">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Tentang Piket App</h2>
                        <p class="text-gray-600 mb-4">
                            Piket App adalah aplikasi manajemen piket yang dirancang untuk memudahkan 
                            pengelolaan jadwal piket di berbagai institusi seperti sekolah, kantor, 
                            dan organisasi.
                        </p>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Mudah digunakan</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Fitur lengkap</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Support Excel import/export</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Your First Visit Section -->
            <div class="bg-white rounded-xl shadow-lg p-12 mb-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Book Your First Visit
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto mb-8">
                    Siap untuk mulai mengelola piket dengan lebih mudah? 
                    Jadwalkan kunjungan pertama Anda dan lihat bagaimana Piket App dapat membantu.
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-8 py-4 rounded-lg hover:bg-teal-700 transition font-semibold text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Book Now
                </a>
            </div>

            <!-- Footer -->
            <footer class="text-center text-gray-600 py-4">
                <p>&copy; 2024 Piket App. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <!-- Smooth Scroll JavaScript -->
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>