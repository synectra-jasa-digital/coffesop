@props([
    'bg' => 'bg-[#FAFAFA]',
    'spacing' => 'py-20 md:py-32',
])

<section {{ $attributes->merge(['class' => $bg . ' ' . $spacing]) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
</section>
