{{-- Advanced Filters Component --}}
<div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <button onclick="toggleAdvancedFilters()" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
            <svg id="filterIcon" class="w-4 h-4 mr-2 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
            Advanced Filters & Analytics
        </button>
    </div>
    
    <div id="advancedFilters" class="hidden p-6 border-t border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Amount Range Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount Range</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" placeholder="Min Amount" id="minAmount" 
                           class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <input type="number" placeholder="Max Amount" id="maxAmount"
                           class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <!-- Quick Date Ranges -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quick Ranges</label>
                <div class="space-y-2">
                    <button onclick="setDateRange('today')" class="w-full text-left px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md">Today</button>
                    <button onclick="setDateRange('week')" class="w-full text-left px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md">This Week</button>
                    <button onclick="setDateRange('month')" class="w-full text-left px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md">This Month</button>
                    <button onclick="setDateRange('quarter')" class="w-full text-left px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md">This Quarter</button>
                </div>
            </div>

            <!-- Analytics Options -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Analytics View</label>
                <div class="space-y-2">
                    <button onclick="showHeatmap()" class="w-full text-left px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md">Transaction Heatmap</button>
                    <button onclick="showTrends()" class="w-full text-left px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md">Trend Analysis</button>
                    <button onclick="showComparison()" class="w-full text-left px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md">Period Comparison</button>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="applyAdvancedFilters()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Apply Advanced Filters
            </button>
        </div>
    </div>
</div>

<script>
function toggleAdvancedFilters() {
    const filters = document.getElementById('advancedFilters');
    const icon = document.getElementById('filterIcon');
    
    if (filters.classList.contains('hidden')) {
        filters.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        filters.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function setDateRange(range) {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const today = new Date();
    
    switch(range) {
        case 'today':
            startDate.value = today.toISOString().split('T')[0];
            endDate.value = today.toISOString().split('T')[0];
            break;
        case 'week':
            const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
            const weekEnd = new Date(today.setDate(today.getDate() - today.getDay() + 6));
            startDate.value = weekStart.toISOString().split('T')[0];
            endDate.value = weekEnd.toISOString().split('T')[0];
            break;
        case 'month':
            const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            const monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            startDate.value = monthStart.toISOString().split('T')[0];
            endDate.value = monthEnd.toISOString().split('T')[0];
            break;
        case 'quarter':
            const quarter = Math.floor(today.getMonth() / 3);
            const quarterStart = new Date(today.getFullYear(), quarter * 3, 1);
            const quarterEnd = new Date(today.getFullYear(), quarter * 3 + 3, 0);
            startDate.value = quarterStart.toISOString().split('T')[0];
            endDate.value = quarterEnd.toISOString().split('T')[0];
            break;
    }
}

function applyAdvancedFilters() {
    // Get filter values
    const minAmount = document.getElementById('minAmount').value;
    const maxAmount = document.getElementById('maxAmount').value;
    
    // Add to current URL parameters
    const url = new URL(window.location.href);
    if (minAmount) url.searchParams.set('min_amount', minAmount);
    if (maxAmount) url.searchParams.set('max_amount', maxAmount);
    
    window.location.href = url.toString();
}

function showHeatmap() {
    // Implementation for transaction heatmap
    alert('Heatmap view would show transaction intensity by hour/day of week');
}

function showTrends() {
    // Implementation for trend analysis
    alert('Trend analysis would show growth patterns and predictions');
}

function showComparison() {
    // Implementation for period comparison
    alert('Period comparison would show side-by-side analytics for different date ranges');
}
</script>
