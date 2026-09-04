<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Good Coffee') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|playfair-display:400,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-[#6B7280] bg-[#FAFAFA]" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
        <div class="min-h-screen flex">

            <!-- Mobile overlay -->
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/50 z-40 md:hidden"
                @click="sidebarOpen = false"
                style="display: none;"
            ></div>

            <!-- Sidebar -->
            <livewire:layout.navigation />

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Header -->
                <header data-navbar-scroll class="bg-white border-b border-line h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 sticky top-0 z-30 transition-colors duration-300">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="text-ink-secondary hover:text-primary p-2 -ml-2 md:hidden transition-colors" aria-label="Buka menu">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="md:hidden font-bold text-xl text-primary">
                            Good Coffee.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-primary-light text-primary rounded-sm text-sm font-medium">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-pulse-dot absolute inline-flex h-full w-full rounded-full bg-primary opacity-60"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                            </span>
                            {{ now()->format('d M Y') }}
                        </div>
                        <div class="text-sm font-medium text-ink-secondary hidden sm:block">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm select-none">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-8">
                    @if (isset($header))
                        <div class="mb-6 animate-fade-in-down">
                            {{ $header }}
                        </div>
                    @endif

                    <div class="animate-fade-in">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
