<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#398263] border border-transparent rounded-sm font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2C6B4F] focus:bg-[#2C6B4F] active:bg-[#2C6B4F] focus:outline-none focus:ring-2 focus:ring-[#398263] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
