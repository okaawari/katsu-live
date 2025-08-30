{{-- Real-time Statistics Component --}}
<div class="bg-gradient-to-r from-blue-500 to-purple-600 shadow rounded-lg text-white">
    <div class="px-6 py-4 border-b border-blue-400">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium">Real-time Analytics</h3>
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-2"></div>
                <span class="text-sm">Live</span>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Today's Revenue -->
            <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-full mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <p class="text-sm opacity-90">Today's Revenue</p>
                <p id="todayRevenue" class="text-2xl font-bold">$0.00</p>
                <p id="todayGrowth" class="text-xs opacity-75">+0% from yesterday</p>
            </div>

            <!-- This Hour -->
            <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-full mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm opacity-90">This Hour</p>
                <p id="hourlyRevenue" class="text-2xl font-bold">$0.00</p>
                <p id="hourlyTransactions" class="text-xs opacity-75">0 transactions</p>
            </div>

            <!-- Active Users -->
            <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-full mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-sm opacity-90">Paying Customers</p>
                <p id="payingCustomers" class="text-2xl font-bold">0</p>
                <p id="conversionRate" class="text-xs opacity-75">0% conversion</p>
            </div>

            <!-- Average Order Value -->
            <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-full mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <p class="text-sm opacity-90">Avg Order Value</p>
                <p id="avgOrderValue" class="text-2xl font-bold">$0.00</p>
                <p id="aovTrend" class="text-xs opacity-75">Last 30 days</p>
            </div>
        </div>
        
        <!-- Real-time Transaction Feed -->
        <div class="mt-6 pt-6 border-t border-blue-400">
            <h4 class="text-sm font-medium mb-3">Live Transaction Feed</h4>
            <div id="liveTransactionFeed" class="space-y-2 max-h-40 overflow-y-auto">
                <p class="text-sm opacity-75">Waiting for new transactions...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Real-time statistics update
async function updateRealTimeStats() {
    try {
        const response = await fetch('{{ route("admin.transactions.api-data") }}?' + new URLSearchParams({
            period: 'day',
            start_date: new Date().toISOString().split('T')[0],
            end_date: new Date().toISOString().split('T')[0]
        }));
        
        const data = await response.json();
        
                 // Update today's revenue
         document.getElementById('todayRevenue').textContent = '₮' + data.stats.total_revenue.toLocaleString('en-US', {minimumFractionDigits: 0});
         
         // Update other real-time stats
         document.getElementById('payingCustomers').textContent = data.stats.unique_customers.toLocaleString();
         document.getElementById('avgOrderValue').textContent = '₮' + data.stats.average_transaction.toLocaleString('en-US', {minimumFractionDigits: 0});
        
        // Update growth indicators
        const growthText = data.stats.revenue_growth > 0 ? `+${data.stats.revenue_growth.toFixed(1)}%` : `${data.stats.revenue_growth.toFixed(1)}%`;
        document.getElementById('todayGrowth').textContent = growthText + ' from yesterday';
        
    } catch (error) {
        console.error('Error updating real-time stats:', error);
    }
}

// Update stats every 30 seconds
setInterval(updateRealTimeStats, 30000);

// Initial load
updateRealTimeStats();

// Simulate live transaction feed (in a real app, this would use WebSockets)
function addLiveTransaction(transaction) {
    const feed = document.getElementById('liveTransactionFeed');
    const transactionElement = document.createElement('div');
    transactionElement.className = 'flex items-center justify-between text-sm bg-white bg-opacity-10 rounded px-3 py-2';
         transactionElement.innerHTML = `
         <span>${transaction.user} - ₮${transaction.amount}</span>
         <span class="text-xs opacity-75">${new Date().toLocaleTimeString()}</span>
     `;
    
    feed.insertBefore(transactionElement, feed.firstChild);
    
    // Keep only last 5 transactions
    if (feed.children.length > 5) {
        feed.removeChild(feed.lastChild);
    }
}
</script>
