<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Good Coffee - Elevating Coffee Operations</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-ink bg-white antialiased selection:bg-primary selection:text-white">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-lg border-b border-line/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="font-serif font-bold text-2xl tracking-tight text-ink group">
                Good Coffee<span class="text-primary group-hover:text-primary-hover transition-colors">.</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="#features" class="hidden md:block text-sm font-semibold text-ink-secondary hover:text-ink transition-colors">Features</a>
                <a href="/login" wire:navigate class="inline-flex items-center justify-center font-bold text-sm bg-ink text-white px-6 py-2.5 rounded-full hover:bg-ink-secondary transition-all hover:scale-105 active:scale-95 shadow-[0_4px_14px_rgba(26,26,26,0.15)]">
                    Sign In
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center pt-20 overflow-hidden bg-surface-alt">
        <div class="absolute inset-0 z-0">
            <!-- Asymmetric abstract shapes -->
            <div class="absolute -top-[20%] -right-[10%] w-[50%] h-[70%] bg-primary/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[10%] -left-[10%] w-[40%] h-[50%] bg-amber-900/5 rounded-full blur-[100px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 w-full relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            <!-- Typography Focus -->
            <div class="lg:col-span-7 pt-12 lg:pt-0">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-line mb-8 animate-fade-in-up" style="animation-delay: 100ms">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    <span class="text-xs font-bold text-ink-secondary tracking-wide uppercase">Unified Point of Sale</span>
                </div>
                
                <h1 class="font-serif font-black text-6xl md:text-7xl lg:text-[5.5rem] leading-[0.95] tracking-tight text-ink mb-8 animate-fade-in-up" style="animation-delay: 200ms">
                    Crafting <span class="text-primary italic font-medium">clarity</span><br>
                    out of chaos.
                </h1>
                
                <p class="text-lg md:text-xl text-ink-secondary mb-10 max-w-xl leading-relaxed font-medium animate-fade-in-up" style="animation-delay: 300ms">
                    The quiet engine behind exceptional coffee shops. Inventory, kitchen display, and elegant point of sale—united in one breathless experience.
                </p>
                
                <div class="flex flex-wrap items-center gap-5 animate-fade-in-up" style="animation-delay: 400ms">
                    <a href="/login" wire:navigate class="inline-flex items-center justify-center font-bold text-base bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-full transition-all shadow-[0_8px_30px_rgb(57,130,99,0.3)] hover:shadow-[0_8px_30px_rgb(57,130,99,0.45)] hover:-translate-y-1">
                        Open Register
                    </a>
                    <a href="#features" class="inline-flex items-center gap-2 font-bold text-base text-ink px-6 py-4 hover:text-primary transition-colors group">
                        Explore System
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5 group-hover:translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Visual Evidence -->
            <div class="lg:col-span-5 relative animate-fade-in-up" style="animation-delay: 300ms">
                <div class="relative w-full aspect-[4/5] rounded-2xl overflow-hidden shadow-2xl bg-line">
                    <img src="https://images.unsplash.com/photo-1559925313-8a14050a4cb3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Barista pulling an espresso shot" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-1000 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-ink/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <div class="bg-white/90 backdrop-blur-md p-4 rounded-xl flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-ink-secondary uppercase tracking-wider mb-0.5">Live KDS Sync</div>
                                <div class="font-serif font-black text-ink text-lg leading-none">0.05s Latency</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Essential Features -->
    <section id="features" class="py-24 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20" data-reveal>
                <h2 class="font-serif font-black text-4xl lg:text-5xl leading-tight text-ink mb-6">Designed to operate at<br>the speed of hospitality.</h2>
                <p class="text-xl text-ink-secondary leading-relaxed">No clunky reloads. No lost tickets. Just pure operational flow.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Feature 1 -->
                <div class="group" data-reveal>
                    <div class="h-64 rounded-2xl bg-surface-alt mb-8 overflow-hidden relative">
                        <div class="absolute inset-0 bg-primary/5 group-hover:bg-primary/10 transition-colors duration-500"></div>
                        <div class="absolute inset-x-8 bottom-0 h-48 bg-white rounded-t-xl shadow-[0_-8px_30px_rgba(0,0,0,0.05)] border border-b-0 border-line translate-y-4 group-hover:translate-y-0 transition-transform duration-500 ease-out p-6 flex flex-col">
                            <div class="w-full h-8 bg-line-light rounded mb-4"></div>
                            <div class="w-2/3 h-6 bg-line-light rounded mb-3"></div>
                            <div class="w-1/2 h-6 bg-line-light rounded"></div>
                        </div>
                    </div>
                    <h3 class="font-serif font-bold text-2xl text-ink mb-3">Fluid POS Terminal</h3>
                    <p class="text-ink-secondary leading-relaxed mb-6">Process orders, split payments, and apply discounts with a touch-first interface engineered for zero hesitation.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group" data-reveal data-reveal-delay="100">
                    <div class="h-64 rounded-2xl bg-surface-alt mb-8 overflow-hidden relative">
                        <div class="absolute inset-0 bg-amber-900/5 group-hover:bg-amber-900/10 transition-colors duration-500"></div>
                        <div class="absolute inset-y-8 left-8 right-0 bg-white rounded-l-xl shadow-[-8px_0_30px_rgba(0,0,0,0.05)] border border-r-0 border-line translate-x-4 group-hover:translate-x-0 transition-transform duration-500 ease-out p-6">
                            <div class="flex gap-4 mb-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="w-3/4 h-5 bg-line-light rounded mb-2"></div>
                                    <div class="w-1/2 h-4 bg-line-light rounded"></div>
                                </div>
                            </div>
                            <div class="w-full h-24 bg-line-light rounded-lg"></div>
                        </div>
                    </div>
                    <h3 class="font-serif font-bold text-2xl text-ink mb-3">Instant Kitchen Display</h3>
                    <p class="text-ink-secondary leading-relaxed mb-6">Orders flow from counter to bar instantly. Beautiful high-contrast KDS ensures your baristas never miss a modifier.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group" data-reveal data-reveal-delay="200">
                    <div class="h-64 rounded-2xl bg-surface-alt mb-8 overflow-hidden relative">
                        <div class="absolute inset-0 bg-blue-900/5 group-hover:bg-blue-900/10 transition-colors duration-500"></div>
                        <div class="absolute inset-8 bg-white rounded-xl shadow-[0_8px_30px_rgba(0,0,0,0.05)] border border-line scale-95 group-hover:scale-100 transition-transform duration-500 ease-out p-6 flex flex-col justify-end">
                            <div class="flex items-end gap-2 h-24 w-full">
                                <div class="flex-1 bg-line-light rounded-t-sm h-[30%]"></div>
                                <div class="flex-1 bg-line-light rounded-t-sm h-[50%]"></div>
                                <div class="flex-1 bg-primary rounded-t-sm h-[100%] shadow-[0_0_15px_rgba(57,130,99,0.3)]"></div>
                                <div class="flex-1 bg-line-light rounded-t-sm h-[70%]"></div>
                            </div>
                        </div>
                    </div>
                    <h3 class="font-serif font-bold text-2xl text-ink mb-3">Automated Inventory BOM</h3>
                    <p class="text-ink-secondary leading-relaxed mb-6">Sell a latte, and the system automatically deducts milk and beans based on the recipe logic. Real-time cost control.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-ink text-white/50 py-12 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="font-serif font-bold text-2xl text-white">
                Good Coffee.
            </div>
            <div class="text-sm font-medium">
                &copy; {{ date('Y') }} Impeccable POS System. All rights reserved.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
