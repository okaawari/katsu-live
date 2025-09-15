<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $termsAccepted = false;

    public bool $ageConfirmed = false;

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'ageConfirmed' => ['accepted'],
            'termsAccepted' => ['accepted'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        auth()->login($user);

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<div>
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-white mb-2">Join the Adventure!</h1>
        <p class="text-purple-200">Create your account and dive into the world of anime</p>
    </div>
    <form wire:submit="register" class="space-y-6">
        <!-- Name -->
        <div class="space-y-1">
            <x-input-label for="name" :value="__('Display Name')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input wire:model.blur="name" id="name" class="pl-10" type="text" name="name" required autofocus autocomplete="name" placeholder="Choose your username" maxlength="30" />
            </div>
            <x-input-error :messages="$errors->get('name')" />
            <p class="text-xs text-purple-300">Maximum 30 characters</p>
        </div>

        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input wire:model.lazy="email" id="email" class="pl-10" type="email" name="email" required autocomplete="username" placeholder="Enter your email" />
            </div>
            <x-input-error :messages="$errors->get('email')" />
            @php
                $emailSuggestion = null;
                if (str_contains($email, '@')) {
                    [$local, $domain] = explode('@', strtolower($email)) + [null, null];
                    $typos = [
                        'gamil.com' => 'gmail.com',
                        'gmailc.om' => 'gmail.com',
                        'gnail.com' => 'gmail.com',
                        'hotnail.com' => 'hotmail.com',
                        'yaho.com' => 'yahoo.com',
                        'outlok.com' => 'outlook.com',
                    ];
                    if ($domain && array_key_exists($domain, $typos)) {
                        $emailSuggestion = $local.'@'.$typos[$domain];
                    }
                }
            @endphp
            @if ($emailSuggestion)
                <div class="mt-2 p-2 bg-yellow-500/20 border border-yellow-400/30 rounded-lg text-yellow-200 text-xs">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Did you mean 
                        <button type="button" class="underline font-medium ml-1" wire:click="$set('email', '{{ $emailSuggestion }}')">{{ $emailSuggestion }}</button>?
                    </div>
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input wire:model="password" id="password" class="pl-10" type="password" name="password" required autocomplete="new-password" placeholder="Create a strong password" />
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="pl-10" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <!-- Age Confirmation -->
        <div class="space-y-3 pt-2">
            <div class="flex items-start">
                <input wire:model="ageConfirmed" id="ageConfirmed" type="checkbox" class="mt-1 rounded bg-white/10 border-white/20 text-purple-600 shadow-sm focus:ring-purple-500 focus:ring-offset-transparent" name="ageConfirmed">
                <label for="ageConfirmed" class="ml-3 text-sm text-purple-200">
                    <span class="block font-medium">Age Verification</span>
                    <span class="text-purple-300">I confirm that I am 18 years old or older</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('ageConfirmed')" />
        </div>

        <!-- Terms -->
        <div class="space-y-3">
            <div class="flex items-start">
                <input wire:model="termsAccepted" id="terms" type="checkbox" class="mt-1 rounded bg-white/10 border-white/20 text-purple-600 shadow-sm focus:ring-purple-500 focus:ring-offset-transparent" name="terms">
                <label for="terms" class="ml-3 text-sm text-purple-200">
                    <span class="block font-medium">Terms & Conditions</span>
                    <span class="text-purple-300">
                        I agree to the 
                        <a class="text-white hover:text-purple-200 underline decoration-purple-400 underline-offset-2 transition-colors duration-200" href="{{ route('terms') }}" wire:navigate target="_blank">Terms & Conditions</a>
                    </span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('termsAccepted')" />
        </div>

        <!-- Register Button -->
        <div class="pt-4">
            <x-primary-button>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ __('Create Account') }}
            </x-primary-button>
        </div>
        
        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-white/10">
            <p class="text-purple-200 text-sm">
                Already have an account?
                <a class="text-white font-medium hover:text-purple-200 transition-colors duration-200 underline decoration-purple-400 underline-offset-4 ml-1" href="{{ route('login') }}" wire:navigate>
                    {{ __('Sign in here') }}
                </a>
            </p>
        </div>
    </form>
</div>
