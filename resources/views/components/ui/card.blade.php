@props([
    'padding' => 'p-6',
    'bg' => 'bg-white',
])

<div {{ $attributes->merge(['class' => $bg . ' ' . $padding . ' rounded-sm border border-gray-100']) }}>
    {{ $slot }}
</div>
