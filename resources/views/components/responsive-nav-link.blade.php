@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-3 py-2.5 rounded-sm text-start text-sm font-medium text-primary bg-primary-light focus:outline-none focus:ring-2 focus:ring-primary/30 transition duration-150 ease-in-out'
            : 'block w-full px-3 py-2.5 rounded-sm text-start text-sm font-medium text-ink-secondary hover:text-primary hover:bg-gray-50 focus:outline-none focus:bg-gray-50 focus:text-primary focus:ring-2 focus:ring-primary/20 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
