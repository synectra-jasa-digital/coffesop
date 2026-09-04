@props([
    'variant' => 'primary', // primary, ghost, outline
    'type' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $variants = [
        'primary' => 'bg-[#398263] hover:bg-[#2C6B4F] text-white px-8 py-3',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white px-8 py-3',
        'ghost' => 'bg-transparent border border-gray-300 hover:border-[#398263] hover:text-[#398263] text-gray-700 px-8 py-3',
        'outline' => 'bg-transparent border border-[#398263] text-[#398263] hover:bg-[#398263] hover:text-white px-8 py-3',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
