<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-xl text-[#1A1A1A]">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <livewire:dashboard-stats />
</x-app-layout>
