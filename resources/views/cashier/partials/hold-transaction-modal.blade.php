<!-- Hold Transaction Modal -->
<div id="hold-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Hold Transaction</h3>
                    <button id="close-hold-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Hold Transaction Form -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hold Reason</label>
                        <input type="text" id="hold-reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Customer will return later">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name (Optional)</label>
                        <input type="text" id="hold-customer-name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Customer name for easy identification">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="hold-notes" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="2" placeholder="Additional notes about this transaction"></textarea>
                    </div>
                    
                    <!-- Current Cart Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Current Cart</h4>
                        <div id="hold-cart-summary" class="space-y-1 text-sm text-gray-600">
                            <!-- Cart items will be populated here -->
                        </div>
                        <div class="border-t mt-2 pt-2">
                            <div class="flex justify-between font-medium">
                                <span>Total:</span>
                                <span id="hold-total">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-3 mt-6">
                    <button id="cancel-hold" class="flex-1 py-2 px-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button id="confirm-hold" class="flex-1 py-2 px-4 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                        Hold Transaction
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Held Transactions Modal -->
<div id="held-transactions-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Held Transactions</h3>
                    <button id="close-held-transactions-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                <div id="held-transactions-list" class="space-y-4">
                    <!-- Held transactions will be populated here -->
                    <div id="no-held-transactions" class="text-center py-8">
                        <div class="text-gray-400 text-6xl mb-4">📋</div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No held transactions</h4>
                        <p class="text-gray-500">Held transactions will appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>