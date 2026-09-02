@props([
    'variant' => 'default',
])

@php
    $baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-sm text-xs font-semibold';
    
    $variants = [
        'default' => 'bg-gray-100 text-gray-800',
        'primary' => 'bg-[#EAF3EF] text-[#398263]',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>