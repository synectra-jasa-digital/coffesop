<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Good Coffee - Sistem POS Terpadu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink-secondary antialiased bg-[#FAFAFA]">

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-16 md:py-24 lg:py-32">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-light/60 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-primary/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Text Column -->
                <div class="animate-fade-in-up">
                    <p class="text-sm font-semibold text-primary uppercase tracking-widest mb-4 animate-fade-in-up" style="animation-delay: 0ms">Sistem Point of Sale Terpadu</p>
                    <h1 class="font-serif font-bold text-4xl md:text-5xl lg:text-6xl leading-[1.1] tracking-tight text-ink mb-6 animate-fade-in-up" style="animation-delay: 100ms">
                        Kopi Asli,<br>
                        <span class="text-primary">Sistem Mumpuni.</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-10 max-w-lg leading-relaxed animate-fade-in-up" style="animation-delay: 200ms">
                        Kelola point of sale, stok bahan baku, dan laporan harian dalam satu dashboard elegan yang dirancang khusus untuk coffee shop.
                    </p>
                    <div class="flex flex-wrap gap-4 animate-fade-in-up" style="animation-delay: 300ms">
                        <a href="/login" class="inline-flex items-center justify-center font-semibold rounded-sm bg-primary hover:bg-primary-hover text-white px-8 py-4 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2">
                            Buka Kasir
                        </a>
                        <a href="#fitur" class="inline-flex items-center justify-center font-semibold rounded-sm bg-transparent border border-primary text-primary hover:bg-primary hover:text-white px-8 py-4 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                            Pelajari Sistem
                        </a>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative animate-fade-in-up" style="animation-delay: 200ms">
                    <div class="relative">
                        <div class="rounded-sm overflow-hidden border border-line shadow-none">
                            <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Barista menuangkan kopi" class="w-full aspect-[4/5] object-cover">
                        </div>
                        <!-- Floating stat badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white border border-line rounded-sm px-5 py-4 shadow-none animate-fade-in-up" style="animation-delay: 500ms">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-primary">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400">Kasir Aktif</div>
                                    <div class="font-serif font-bold text-lg text-primary">1,200+</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section id="fitur" class="py-20 md:py-28 bg-surface">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16 text-center max-w-3xl mx-auto">
                <p class="text-sm font-semibold text-primary uppercase tracking-widest mb-4">Fitur Utama</p>
                <h2 class="font-serif font-bold text-3xl md:text-4xl lg:text-5xl leading-[1.2] text-ink mb-6">Dirancang untuk Barista & Kasir</h2>
                <p class="text-lg text-gray-600">Fokus pada menyeduh kopi terbaik, biarkan sistem yang mengurus sisanya. Dari order masuk sampai laporan penjualan akhir hari.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div data-reveal class="bg-white border border-line rounded-sm p-8 hover:border-primary transition-colors duration-300 group">
                    <div class="w-12 h-12 rounded-sm bg-primary-light flex items-center justify-center mb-6 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-4.24 0-7.75 3.25-8 7.5 0 3.36 2.29 6.16 5.36 6.84a.75.75 0 0 0 .94-.29 1.5 1.5 0 0 1 1.98-1.98 3 3 0 0 1 3.54.29.75.75 0 0 0 .97-.15 7.43 7.43 0 0 0 1.48-3.68c.25-1.22.37-2.47.35-3.72A7.96 7.96 0 0 0 12 2.25Z" />
                        </svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl md:text-2xl leading-[1.3] text-ink mb-3">POS Super Cepat</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Proses order di bawah 30 detik. Mendukung dine-in, take away, QR order, dan payment gateway.</p>
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-primary-hover transition-colors">
                        Lihat modul kasir
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>

                <div data-reveal data-reveal-delay="100" class="bg-white border border-line rounded-sm p-8 hover:border-primary transition-colors duration-300 group">
                    <div class="w-12 h-12 rounded-sm bg-primary-light flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl md:text-2xl leading-[1.3] text-ink mb-3">Stok Otomatis</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Stok biji kopi dan susu otomatis berkurang sesuai resep (BOM) tiap kali menu terjual.</p>
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-primary-hover transition-colors">
                        Pelajari manajemen stok
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>

                <div data-reveal data-reveal-delay="200" class="bg-white border border-line rounded-sm p-8 hover:border-primary transition-colors duration-300 group">
                    <div class="w-12 h-12 rounded-sm bg-primary-light flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                        </svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl md:text-2xl leading-[1.3] text-ink mb-3">Kitchen Display</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Tinggalkan kertas order. Barista menerima pesanan secara real-time langsung di layar dapur.</p>
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-primary-hover transition-colors">
                        Cek sistem KDS
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-primary py-16 md:py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="font-serif font-bold text-3xl md:text-4xl lg:text-5xl leading-[1.2] text-white mb-6">Siap menyeduh lebih baik?</h2>
                <p class="text-white/80 text-lg md:text-xl mb-10 max-w-2xl mx-auto">Mulai gunakan sistem point of sale yang didesain khusus untuk alur kerja coffee shop Anda.</p>
                <a href="/login" class="inline-flex items-center justify-center font-semibold rounded-sm bg-transparent border border-white text-white hover:bg-white hover:text-primary px-8 py-4 text-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-primary">
                    Masuk ke Dashboard
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-surface-dark text-white/50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="font-serif font-bold text-xl text-white">
                Good Coffee.
            </div>
            <div class="text-sm">
                &copy; {{ date('Y') }} POS System. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
