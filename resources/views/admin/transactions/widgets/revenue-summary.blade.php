 {{-- Revenue Summary Widget for Dashboard --}}
 @php
     $todayRevenue = \App\Models\PaymentHistory::whereDate('created_at', today())->sum('amount');
     $weekRevenue = \App\Models\PaymentHistory::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');
     $monthRevenue = \App\Models\PaymentHistory::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
     $totalRevenue = \App\Models\PaymentHistory::sum('amount');
 @endphp

<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Revenue Summary</h3>
            <a href="{{ route('admin.transactions.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                View All →
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="text-center">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Today</dt>
                                 <dd class="text-lg font-semibold text-green-600 dark:text-green-400">₮{{ number_format($todayRevenue, 0) }}</dd>
             </div>
             <div class="text-center">
                 <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">This Week</dt>
                 <dd class="text-lg font-semibold text-blue-600 dark:text-blue-400">₮{{ number_format($weekRevenue, 0) }}</dd>
             </div>
             <div class="text-center">
                 <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">This Month</dt>
                 <dd class="text-lg font-semibold text-purple-600 dark:text-purple-400">₮{{ number_format($monthRevenue, 0) }}</dd>
             </div>
             <div class="text-center">
                 <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">All Time</dt>
                 <dd class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">₮{{ number_format($totalRevenue, 0) }}</dd>
            </div>
        </div>
    </div>
</div>
