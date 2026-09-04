@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm']) }}>
