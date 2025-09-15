<x-guest-layout>
    <div class="prose dark:prose-invert max-w-2xl mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Terms & Conditions</h1>
        <p class="mb-2">Welcome to {{ config('app.name') }}. By using our service, you agree to the following terms and conditions.</p>
        <h2 class="text-xl font-semibold mt-6 mb-2">1. Eligibility</h2>
        <p>You must be at least 18 years old to use this service.</p>
        <h2 class="text-xl font-semibold mt-6 mb-2">2. Account</h2>
        <p>You are responsible for maintaining the confidentiality of your account and password.</p>
        <h2 class="text-xl font-semibold mt-6 mb-2">3. Usage</h2>
        <p>Do not misuse the service. Respect applicable laws and intellectual property.</p>
        <h2 class="text-xl font-semibold mt-6 mb-2">4. Changes</h2>
        <p>We may update these terms from time to time. Continued use constitutes acceptance of changes.</p>
        <p class="mt-6"><a href="{{ route('register') }}" class="text-indigo-600 hover:underline" wire:navigate>Back to Register</a></p>
    </div>
</x-guest-layout>

