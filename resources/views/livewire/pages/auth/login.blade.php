<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="font-serif text-2xl font-bold text-[#1A1A1A]">Welcome back</h1>
        <p class="text-[#6B7280] text-sm mt-2">Enter your credentials to access the POS</p>
    </div>

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block font-medium text-sm text-[#1A1A1A] mb-1">{{ __('Email Address') }}</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username" class="block w-full border-[#E5E7EB] focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm p-3 text-sm" placeholder="admin@coffeeshop.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block font-medium text-sm text-[#1A1A1A]">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-[#398263] hover:text-[#2C6B4F] font-medium" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password" class="block w-full border-[#E5E7EB] focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm p-3 text-sm" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="block">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded-sm border-[#E5E7EB] text-[#398263] shadow-sm focus:ring-[#398263]" name="remember">
                <span class="ms-2 text-sm text-[#6B7280]">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div>
            <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-3 bg-[#398263] border border-transparent rounded-sm font-semibold text-white hover:bg-[#2C6B4F] focus:outline-none focus:ring-2 focus:ring-[#398263] focus:ring-offset-2 transition-colors duration-200">
                <span wire:loading.remove wire:target="login">{{ __('Log in') }}</span>
                <span wire:loading wire:target="login">Authenticating...</span>
            </button>
        </div>
    </form>

    <!-- Demo Credentials -->
    <div class="mt-8 pt-6 border-t border-[#E5E7EB] text-xs text-center text-[#6B7280]">
        <p class="font-semibold mb-2">Demo Credentials (Password: password123):</p>
        <div class="flex justify-center gap-4 flex-wrap">
            <button wire:click="$set('form.email', 'admin@coffeeshop.com')" class="hover:text-[#398263] transition-colors">Admin</button>
            <button wire:click="$set('form.email', 'kasir@coffeeshop.com')" class="hover:text-[#398263] transition-colors">Kasir</button>
            <button wire:click="$set('form.email', 'barista@coffeeshop.com')" class="hover:text-[#398263] transition-colors">Barista</button>
        </div>
    </div>
</div>
