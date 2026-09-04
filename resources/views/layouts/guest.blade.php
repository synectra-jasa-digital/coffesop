<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Good Coffee.') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|playfair-display:400,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-ink antialiased bg-white">

        <div class="min-h-screen flex">
            <!-- Form Side -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 md:px-24 xl:px-32 relative z-10 bg-white">
                <div class="w-full max-w-md mx-auto animate-fade-in-up">
                    <!-- Brand -->
                    <a href="/" wire:navigate class="font-serif font-bold text-3xl tracking-tight text-ink mb-12 block group">
                        Good Coffee<span class="text-primary group-hover:text-primary-hover transition-colors">.</span>
                    </a>

                    {{ $slot }}
                </div>
            </div>

            <!-- Image Side -->
            <div class="hidden lg:block lg:w-1/2 relative bg-surface-alt">
                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80" alt="Pouring coffee" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-ink/50 backdrop-blur-[1px]"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-16 animate-fade-in-up" style="animation-delay: 200ms">
                    <h2 class="font-serif font-bold text-4xl text-white mb-4 leading-tight">Focus on the coffee.<br>We handle the rest.</h2>
                    <p class="text-white/80 text-lg max-w-md">The unified operational engine for modern cafes, roasteries, and hospitality.</p>
                </div>
            </div>
        </div>

        @livewireScripts
    </body>
</html>