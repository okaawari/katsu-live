<?php

use App\Models\UserSession;
use Livewire\Volt\Component;

new class extends Component
{
    public function mount(): void
    {
        // No additional setup needed
    }
    
    public function endSession($id): void
    {
        $session = UserSession::findOrFail($id);
        
        // Check authorization
        $this->authorize('update', $session);
        
        // Mark session as logged out
        $session->update([
            'logout_at' => now(),
            'is_current' => false,
        ]);
        
        $this->dispatch('session-ended');
    }
    
    public function endOtherSessions(): void
    {
        $user = auth()->user();
        
        UserSession::where('user_id', $user->id)
                  ->where('is_current', false)
                  ->whereNull('logout_at')
                  ->update(['logout_at' => now()]);
        
        $this->dispatch('sessions-ended');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Browser Sessions') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Manage and logout your active sessions on other browsers and devices.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        @php
            $activeSessionsCount = auth()->user()->sessions()->where('is_current', false)->whereNull('logout_at')->count();
        @endphp
        
        @if ($activeSessionsCount >= 1)
            <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                <span class="font-medium">Warning!</span> You have {{ $activeSessionsCount }} active session(s). The maximum allowed is 5. If you exceed this limit, you will be automatically logged out.
            </div>
        @endif
        
        @if (count(auth()->user()->sessions) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Device') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('IP Address') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Last Active') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 rounded-md">
                        @foreach (auth()->user()->sessions()->orderBy('login_at', 'desc')->get() as $session)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $session->device_type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $session->ip_address }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $session->login_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($session->is_current)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __('Current') }}
                                        </span>
                                    @elseif ($session->logout_at)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ __('Logged Out') }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ __('Active') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if (!$session->is_current && !$session->logout_at)
                                        <button wire:click="endSession({{ $session->id }})" class="text-red-600 hover:text-red-900">
                                            {{ __('Logout') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="flex items-center gap-4">
                <x-primary-button wire:click="endOtherSessions">
                    {{ __('Logout Other Browser Sessions') }}
                </x-primary-button>
                
                <x-action-message class="mr-3" on="session-ended">
                    {{ __('Session ended.') }}
                </x-action-message>
                
                <x-action-message class="mr-3" on="sessions-ended">
                    {{ __('Other sessions ended.') }}
                </x-action-message>
            </div>
        @else
            <p class="text-sm text-gray-600">
                {{ __('No active sessions found.') }}
            </p>
        @endif
    </div>
</section>
