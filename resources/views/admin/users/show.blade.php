@extends('layouts.admin')

@section('page-title', 'User Details - ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                            @if($user->profile_picture)
                                <img class="h-16 w-16 rounded-full object-cover" src="{{ $user->avatar() }}" alt="{{ $user->name }}">
                            @else
                                <span class="text-xl font-medium text-gray-700 dark:text-gray-300">{{ substr($user->name, 0, 1) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                        <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Member since {{ $user->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Profile Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</dd>
                        </div>
                        @if($user->bio)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bio</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->bio }}</dd>
                        </div>
                        @endif
                        @if($user->location)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->location }}</dd>
                        </div>
                        @endif
                        @if($user->website)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="{{ $user->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ $user->website }}</a>
                            </dd>
                        </div>
                        @endif
                        @if($user->birth_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->birth_date->format('M j, Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->status == 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($user->status == 'suspended') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($user->status == 'banned') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </dd>
                        </div>
                        @if($user->status_reason)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Reason</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->status_reason }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Subscription Management -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Subscription Management</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.users.subscription', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="subscription_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subscription Type</label>
                                <select name="subscription_type" id="subscription_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="free" {{ $user->subscription_type == 'free' ? 'selected' : '' }}>Free</option>
                                    <option value="premium" {{ $user->subscription_type == 'premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="vip" {{ $user->subscription_type == 'vip' ? 'selected' : '' }}>VIP</option>
                                </select>
                            </div>
                            <div>
                                <label for="subscription_duration_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Days)</label>
                                <input type="number" name="subscription_duration_days" id="subscription_duration_days" 
                                       min="1" max="365" placeholder="Enter days"
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="subscription_duration_months" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Months)</label>
                                <select name="subscription_duration_months" id="subscription_duration_months" 
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Select months</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'month' : 'months' }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="subscription_expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expires At</label>
                                <input type="datetime-local" name="subscription_expires_at" id="subscription_expires_at" 
                                       value="{{ $user->subscription_expires_at ? $user->subscription_expires_at->format('Y-m-d\TH:i') : '' }}"
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                        
                        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Note:</strong> Enter either days or months to extend the subscription. If both are provided, days will take priority. 
                                The system will automatically calculate the expiration date and set the subscription start date if not already set.
                            </p>
                        </div>
                        
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const daysInput = document.getElementById('subscription_duration_days');
                            const monthsSelect = document.getElementById('subscription_duration_months');
                            const expiresAtInput = document.getElementById('subscription_expires_at');
                            
                            // Function to calculate and update expiration date
                            function updateExpirationDate() {
                                const days = parseInt(daysInput.value) || 0;
                                const months = parseInt(monthsSelect.value) || 0;
                                
                                if (days > 0) {
                                    // Days take priority
                                    const newDate = new Date();
                                    newDate.setDate(newDate.getDate() + days);
                                    expiresAtInput.value = newDate.toISOString().slice(0, 16);
                                } else if (months > 0) {
                                    // Use months if no days specified
                                    const newDate = new Date();
                                    newDate.setMonth(newDate.getMonth() + months);
                                    expiresAtInput.value = newDate.toISOString().slice(0, 16);
                                }
                            }
                            
                            // Add event listeners
                            daysInput.addEventListener('input', function() {
                                if (this.value > 0) {
                                    monthsSelect.value = '';
                                }
                                updateExpirationDate();
                            });
                            
                            monthsSelect.addEventListener('change', function() {
                                if (this.value > 0) {
                                    daysInput.value = '';
                                }
                                updateExpirationDate();
                            });
                            
                            // Clear the other field when one is selected
                            daysInput.addEventListener('focus', function() {
                                if (monthsSelect.value) {
                                    monthsSelect.value = '';
                                }
                            });
                            
                            monthsSelect.addEventListener('focus', function() {
                                if (daysInput.value) {
                                    daysInput.value = '';
                                }
                            });
                        });
                        </script>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Subscription
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Roles Management -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Roles & Permissions</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.users.roles', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign Roles</label>
                            <div class="space-y-2">
                                @foreach($roles as $role)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                               {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">{{ $role->display_name ?? ucfirst($role->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Update Roles
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Activity -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Activity Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $user->anime_count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Anime Created</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $user->episodes_count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Episodes Uploaded</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $user->comments_count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Comments</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $user->ratings_count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Ratings</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payment History</h3>
                </div>
                <div class="p-6">
                    @if($user->paymentHistories->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reference</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($user->paymentHistories->sortByDesc('created_at') as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $payment->created_at ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                @if($payment->type == 1)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                        Extension
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Payment
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                @if($payment->amount > 0)
                                                    ${{ number_format($payment->amount, 2) }}
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400">Free</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $payment->duration ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-mono text-xs">{{ $payment->refId }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                @if($payment->code == 'ADMIN_EXTENSION')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Admin Extension
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        {{ $payment->code }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No payment history</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This user has no payment transactions yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Subscription Status -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Current Status</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Subscription Type</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->subscription_type == 'vip') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @elseif($user->subscription_type == 'premium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                    {{ ucfirst($user->subscription_type ?? 'free') }}
                                </span>
                            </div>
                        </div>
                        
                        @if($user->subscription_expires_at)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Expires At</span>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $user->subscription_expires_at->format('M j, Y g:i A') }}
                                @if($user->subscription_expires_at->isPast())
                                    <span class="text-red-600 dark:text-red-400 ml-2">(Expired)</span>
                                @else
                                    <span class="text-green-600 dark:text-green-400 ml-2">(Active)</span>
                                @endif
                            </div>
                        </div>
                        @endif
                        

                    </div>
                </div>
            </div>

            <!-- Current Roles -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Current Roles</h3>
                </div>
                <div class="p-6">
                    @if($user->roles->count() > 0)
                        <div class="space-y-2">
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $role->display_name ?? ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No roles assigned</p>
                    @endif
                </div>
            </div>

            <!-- Status Management -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Status Management</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.users.status', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>Banned</option>
                            </select>
                        </div>
                        <div>
                            <label for="status_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason (Optional)</label>
                            <textarea name="status_reason" id="status_reason" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Reason for status change...">{{ $user->status_reason }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
