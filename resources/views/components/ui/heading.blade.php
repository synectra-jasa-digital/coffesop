@props([
    'level' => '1',
    'color' => 'text-[#1A1A1A]',
])

@php
    $baseClasses = 'font-serif font-bold ' . $color;
    
    $levels = [
        '1' => 'text-5xl md:text-6xl leading-[1.1] tracking-tight',
        '2' => 'text-4xl md:text-5xl leading-[1.2]',
        '3' => 'text-2xl md:text-3xl leading-[1.3] font-semibold',
        '4' => 'text-xl md:text-2xl leading-[1.4] font-semibold',
    ];

    $classes = $baseClasses . ' ' . ($levels[$level] ?? $levels['1']);
    $tag = 'h' . $level;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
