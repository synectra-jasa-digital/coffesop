<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Good Coffee - Sistem POS Terpadu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-700 bg-[#FAFAFA] antialiased">
    
    <!-- Hero Section -->
    <section class="bg-[#FAFAFA] py-24 md:py-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <h1 class="font-serif font-bold text-5xl md:text-6xl leading-[1.1] tracking-tight text-[#1A1A1A] mb-6">
                        Kopi Asli,<br>
                        Sistem Mumpuni.
                    </h1>
                    <p class="text-xl mb-10 text-gray-600 max-w-lg leading-relaxed">
                        Kelola point of sale, stok bahan baku, dan laporan harian dalam satu dashboard elegan yang dirancang khusus untuk coffee shop.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="/login" class="inline-flex items-center justify-center font-semibold rounded-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-[#398263] hover:bg-[#2C6B4F] text-white px-8 py-3">
                            Buka Kasir
                        </a>
                        <a href="#fitur" class="inline-flex items-center justify-center font-semibold rounded-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-transparent border border-[#398263] text-[#398263] hover:bg-[#398263] hover:text-white px-8 py-3">
                            Pelajari Sistem
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-[4/5] bg-gray-200 rounded-sm overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Barista menuangkan kopi" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section id="fitur" class="bg-white py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16 text-center max-w-3xl mx-auto">
                <h2 class="font-serif font-bold text-4xl md:text-5xl leading-[1.2] text-[#1A1A1A] mb-6">Dirancang untuk Barista & Kasir</h2>
                <p class="text-lg text-gray-600">Fokus pada menyeduh kopi terbaik, biarkan sistem yang mengurus sisanya. Dari order masuk sampai laporan penjualan akhir hari.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <h3 class="font-serif font-bold text-2xl md:text-3xl leading-[1.3] text-[#1A1A1A] mb-4">POS Super Cepat</h3>
                    <p class="text-gray-600 mb-6">Proses order di bawah 30 detik. Mendukung dine-in, take away, QR order, dan payment gateway.</p>
                    <a href="#" class="text-[#398263] font-semibold hover:underline">Lihat modul kasir &rarr;</a>
                </div>

                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <h3 class="font-serif font-bold text-2xl md:text-3xl leading-[1.3] text-[#1A1A1A] mb-4">Stok Otomatis</h3>
                    <p class="text-gray-600 mb-6">Stok biji kopi dan susu otomatis berkurang sesuai resep (BOM) tiap kali menu terjual.</p>
                    <a href="#" class="text-[#398263] font-semibold hover:underline">Pelajari manajemen stok &rarr;</a>
                </div>

                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <h3 class="font-serif font-bold text-2xl md:text-3xl leading-[1.3] text-[#1A1A1A] mb-4">Kitchen Display</h3>
                    <p class="text-gray-600 mb-6">Tinggalkan kertas order. Barista menerima pesanan secara real-time langsung di layar dapur.</p>
                    <a href="#" class="text-[#398263] font-semibold hover:underline">Cek sistem KDS &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-[#398263] py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center py-12 md:py-20">
                <h2 class="font-serif font-bold text-4xl md:text-5xl leading-[1.2] text-white mb-6">Siap menyeduh lebih baik?</h2>
                <p class="text-white/80 text-xl mb-10 max-w-2xl mx-auto">Mulai gunakan sistem point of sale yang didesain khusus untuk alur kerja coffee shop Anda.</p>
                <a href="/login" class="inline-flex items-center justify-center font-semibold rounded-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-transparent border border-white text-white hover:bg-white hover:text-[#398263] px-8 py-3">
                    Masuk ke Dashboard
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#0A0A0A] text-white/60 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="font-serif font-bold text-2xl text-white mb-4 md:mb-0">
                Good Coffee.
            </div>
            <div class="text-sm">
                &copy; {{ date('Y') }} POS System. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
