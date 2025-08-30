{{-- Transaction Details Modal --}}
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Details</h3>
            <button onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div id="modalContent" class="mt-4">
            <!-- Content will be loaded dynamically -->
            <div class="animate-pulse">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-5/6"></div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700 mt-4">
            <button onclick="closeTransactionModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 mr-2">
                Close
            </button>
            <button onclick="exportSingleTransaction()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Export Details
            </button>
        </div>
    </div>
</div>

<script>
let currentTransactionId = null;

function openTransactionModal(transactionId) {
    currentTransactionId = transactionId;
    document.getElementById('transactionModal').classList.remove('hidden');
    loadTransactionDetails(transactionId);
}

function closeTransactionModal() {
    document.getElementById('transactionModal').classList.add('hidden');
    currentTransactionId = null;
}

async function loadTransactionDetails(transactionId) {
    try {
        // In a real implementation, you'd fetch transaction details via API
        // For now, we'll show a placeholder
        const modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction ID</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">#${transactionId}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Completed
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                                 <p class="mt-1 text-lg font-semibold text-green-600 dark:text-green-400">₮3000</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Date</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">${new Date().toLocaleDateString()}</p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer</label>
                    <div class="mt-1 flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-white">U</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Sample User</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">user@example.com</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference ID</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">REF${Math.random().toString(36).substr(2, 9).toUpperCase()}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">Credit Card</p>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction Timeline</h4>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-gray-600 dark:text-gray-400">Payment completed - ${new Date().toLocaleString()}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-gray-600 dark:text-gray-400">Payment initiated - ${new Date(Date.now() - 30000).toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } catch (error) {
        console.error('Error loading transaction details:', error);
        document.getElementById('modalContent').innerHTML = '<p class="text-red-600 dark:text-red-400">Error loading transaction details.</p>';
    }
}

function exportSingleTransaction() {
    if (currentTransactionId) {
        // Export single transaction
        const params = new URLSearchParams({
            transaction_id: currentTransactionId,
            format: 'csv'
        });
        window.open(`{{ route('admin.transactions.export') }}?${params.toString()}`, '_blank');
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('transactionModal');
    if (event.target === modal) {
        closeTransactionModal();
    }
});
</script>
