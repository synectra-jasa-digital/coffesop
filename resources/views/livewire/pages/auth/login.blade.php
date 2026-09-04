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

    <div class="mb-10">
        <h1 class="font-serif text-4xl md:text-5xl font-black text-ink tracking-tight mb-3">Welcome back</h1>
        <p class="text-ink-secondary text-lg">Enter your credentials to access the system.</p>
    </div>

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold text-sm text-ink mb-2 uppercase tracking-wider">{{ __('Email Address') }}</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username" class="block w-full border-line focus:border-primary focus:ring-primary rounded-lg shadow-sm p-4 text-base transition-all bg-surface-alt hover:bg-white" placeholder="admin@coffeeshop.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block font-bold text-sm text-ink uppercase tracking-wider">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-primary hover:text-primary-hover font-semibold transition-colors" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password" class="block w-full border-line focus:border-primary focus:ring-primary rounded-lg shadow-sm p-4 text-base transition-all bg-surface-alt hover:bg-white" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="block pt-2">
            <label for="remember" class="inline-flex items-center group cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="w-5 h-5 rounded border-line text-primary shadow-sm focus:ring-primary focus:ring-offset-2 transition-colors cursor-pointer" name="remember">
                <span class="ms-3 text-sm font-medium text-ink-secondary group-hover:text-ink transition-colors">{{ __('Remember my device') }}</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-4 bg-primary rounded-lg font-bold text-lg text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all shadow-[0_4px_14px_rgba(57,130,99,0.3)] hover:shadow-[0_6px_20px_rgba(57,130,99,0.4)] active:scale-[0.98]">
                <span wire:loading.remove wire:target="login">{{ __('Sign In') }}</span>
                <span wire:loading wire:target="login" class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Authenticating...
                </span>
            </button>
        </div>
    </form>

    <!-- Demo Credentials -->
    <div class="mt-12 pt-8 border-t border-line">
        <p class="text-xs font-bold uppercase tracking-wider text-ink-secondary mb-4">Demo Roles (Password: password123):</p>
        <div class="flex flex-wrap gap-3">
            <button wire:click="$set('form.email', 'admin@coffeeshop.com')" class="px-4 py-2 rounded-md bg-surface-alt border border-line text-sm font-semibold hover:border-primary hover:text-primary transition-all">Owner/Admin</button>
            <button wire:click="$set('form.email', 'kasir@coffeeshop.com')" class="px-4 py-2 rounded-md bg-surface-alt border border-line text-sm font-semibold hover:border-primary hover:text-primary transition-all">Kasir</button>
            <button wire:click="$set('form.email', 'barista@coffeeshop.com')" class="px-4 py-2 rounded-md bg-surface-alt border border-line text-sm font-semibold hover:border-primary hover:text-primary transition-all">Barista</button>
        </div>
    </div>
</div>