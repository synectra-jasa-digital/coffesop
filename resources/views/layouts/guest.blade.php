<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Good Coffee.') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|playfair-display:400,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink-secondary antialiased bg-[#FAFAFA]">

        <!-- Hero stripe -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-light/50 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-primary/10 rounded-full blur-3xl opacity-40"></div>
        </div>

        <div class="min-h-screen flex items-center justify-center py-12 px-4">
            <div class="w-full max-w-md">
                <!-- Card -->
                <div class="bg-white border border-line rounded-sm p-10 shadow-none animate-scale-in">
                    <div class="flex justify-center mb-8">
                        <a href="/" wire:navigate class="font-serif font-bold text-3xl text-primary tracking-tight hover:text-primary-hover transition-colors">
                            Good Coffee.
                        </a>
                    </div>

                    {{ $slot }}
                </div>

                <div class="mt-8 text-center text-sm text-gray-400">
                    <p class="flex items-center justify-center gap-2">
                        <span>&copy; {{ date('Y') }} Good Coffee POS System.</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Animated coffee steam / background particles -->
        <div class="fixed bottom-8 left-4 text-primary/5 pointer-events-none hidden sm:block">
            <svg class="w-8 h-8 animate-pulse" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2.25c-4.24 0-7.75 3.25-8 7.5 0 3.36 2.29 6.16 5.36 6.84a.75.75 0 0 0 .94-.29 1.5 1.5 0 0 1 1.98-1.98 3 3 0 0 1 3.54.29.75.75 0 0 0 .97-.15 7.43 7.43 0 0 0 1.48-3.68c.25-1.22.37-2.47.35-3.72A7.96 7.96 0 0 0 12 2.25Z" />
            </svg>
        </div>
        <div class="fixed bottom-24 right-6 text-primary/5 pointer-events-none hidden sm:block">
            <svg class="w-6 h-6 animate-pulse" viewBox="0 0 24 24" fill="currentColor" style="animation-delay: 300ms">
                <path d="M12 2.25c-4.24 0-7.75 3.25-8 7.5 0 3.36 2.29 6.16 5.36 6.84a.75.75 0 0 0 .94-.29 1.5 1.5 0 0 1 1.98-1.98 3 3 0 0 1 3.54.29.75.75 0 0 0 .97-.15 7.43 7.43 0 0 0 1.48-3.68c.25-1.22.37-2.47.35-3.72A7.96 7.96 0 0 0 12 2.25Z" />
            </svg>
        </div>

    </body>
</html>
