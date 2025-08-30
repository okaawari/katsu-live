{{-- Analytics Insights Component --}}
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Business Insights</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Breakdown -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Revenue Breakdown</h4>
                
                @php
                    $subscriptionRevenue = \App\Models\PaymentHistory::whereHas('user', function($q) {
                        $q->where('subscription_type', '!=', 'free');
                    })->sum('amount');
                    
                    $premiumRevenue = \App\Models\PaymentHistory::whereHas('user', function($q) {
                        $q->where('subscription_type', 'premium');
                    })->sum('amount');
                    
                    $vipRevenue = \App\Models\PaymentHistory::whereHas('user', function($q) {
                        $q->where('subscription_type', 'vip');
                    })->sum('amount');
                @endphp
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Premium</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">₮{{ number_format($premiumRevenue, 0) }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">VIP</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">₮{{ number_format($vipRevenue, 0) }}</span>
                    </div>
                    
                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">₮{{ number_format($subscriptionRevenue, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Performance Metrics</h4>
                
                @php
                    $thisMonth = \App\Models\PaymentHistory::whereMonth('created_at', now()->month)->sum('amount');
                    $lastMonth = \App\Models\PaymentHistory::whereMonth('created_at', now()->subMonth()->month)->sum('amount');
                    $monthlyGrowthPercent = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
                    
                    $avgTransactionValue = \App\Models\PaymentHistory::avg('amount') ?? 0;
                    $totalCustomers = \App\Models\PaymentHistory::distinct('user_id')->count();
                @endphp
                
                <div class="space-y-3">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Monthly Growth</span>
                            <span class="text-sm font-medium {{ $monthlyGrowthPercent >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $monthlyGrowthPercent >= 0 ? '+' : '' }}{{ number_format($monthlyGrowthPercent, 1) }}%
                            </span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Avg Transaction</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">₮{{ number_format($avgTransactionValue, 0) }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Customers</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($totalCustomers) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Quick Actions</h4>
                
                <div class="space-y-2">
                    <button onclick="generateReport()" class="w-full text-left px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-md hover:bg-blue-200 dark:hover:bg-blue-800">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Generate Monthly Report
                        </div>
                    </button>
                    
                    <button onclick="viewTrends()" class="w-full text-left px-3 py-2 text-sm bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-md hover:bg-purple-200 dark:hover:bg-purple-800">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Analyze Trends
                        </div>
                    </button>
                    
                    <button onclick="exportAll()" class="w-full text-left px-3 py-2 text-sm bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-md hover:bg-green-200 dark:hover:bg-green-800">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export All Data
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateReport() {
    const startDate = '{{ $startDate }}';
    const endDate = '{{ $endDate }}';
    
    // Generate comprehensive monthly report
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
        format: 'csv',
        report_type: 'comprehensive'
    });
    
    window.open(`{{ route('admin.transactions.export') }}?${params.toString()}`, '_blank');
}

function viewTrends() {
    // Switch to trend analysis view
    window.location.href = '{{ route("admin.transactions.index") }}?period=month&start_date=' + 
                           new Date(new Date().setFullYear(new Date().getFullYear() - 1)).toISOString().split('T')[0] +
                           '&end_date=' + new Date().toISOString().split('T')[0];
}

function exportAll() {
    exportData('csv');
}
</script>
