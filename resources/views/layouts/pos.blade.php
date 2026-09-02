<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>POS - {{ config('app.name', 'Good Coffee') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-ink-secondary bg-gray-100 flex flex-col h-screen h-dvh">

        <!-- POS Top Navbar -->
        <nav class="bg-white border-b border-line h-16 flex-shrink-0 flex items-center justify-between px-4 z-10">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-primary transition-colors p-1.5" aria-label="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div class="font-serif font-bold text-lg md:text-xl text-primary">Good Coffee.</div>

                <livewire:pos.shift-manager />

                <a href="{{ route('pos.history') }}" class="ml-1 flex items-center justify-center p-2 text-gray-400 hover:text-primary transition-colors bg-gray-100 rounded-sm" title="Riwayat Transaksi" aria-label="Riwayat">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-sm font-medium text-ink-secondary">{{ auth()->user()->name ?? 'Kasir' }}</div>
                <div class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm select-none">
                    {{ substr(auth()->user()->name ?? 'K', 0, 1) }}
                </div>
            </div>
        </nav>

        <!-- Main POS Content -->
        <main class="flex-1 flex overflow-hidden print:hidden" :class="$wire?.cartItemsCount ?? 0 > 0 ? 'safe' : ''">
            {{ $slot }}
        </main>

    </body>
</html>
