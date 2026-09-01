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
    <body class="font-sans antialiased text-gray-700 bg-gray-100 overflow-hidden h-screen flex flex-col">
        
        <!-- POS Top Navbar -->
        <nav class="bg-white border-b border-gray-200 h-16 flex-shrink-0 flex items-center justify-between px-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div class="font-serif font-bold text-xl text-primary">Good Coffee.</div>
                <div class="ml-4 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-sm">
                    Shift Terbuka
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-sm font-medium text-gray-600">{{ auth()->user()->name ?? 'Kasir' }}</div>
                <div class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">
                    {{ substr(auth()->user()->name ?? 'K', 0, 1) }}
                </div>
            </div>
        </nav>

        <!-- Main POS Content -->
        <main class="flex-1 flex overflow-hidden">
            {{ $slot }}
        </main>
        
    </body>
</html>
