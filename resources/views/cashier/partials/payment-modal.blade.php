<!-- Payment Confirmation Modal -->
<div id="payment-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">💰 Confirm Payment</h3>
                    <button id="close-payment-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <!-- Payment Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="modal-subtotal">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax (10%):</span>
                                <span id="modal-tax">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount:</span>
                                <span id="modal-discount">Rp 0</span>
                            </div>
                            <div class="border-t pt-2">
                                <div class="flex justify-between font-bold text-lg">
                                    <span>Total:</span>
                                    <span id="modal-total" class="text-blue-600">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method Display -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-600">Payment Method:</span>
                        <span id="modal-payment-method" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Cash</span>
                    </div>
                    
                    <!-- Cash Payment Details -->
                    <div id="modal-cash-details" class="bg-green-50 rounded-lg p-4">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cash Received:</span>
                                <span id="modal-cash-received" class="font-medium">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Change:</span>
                                <span id="modal-change" class="font-medium text-green-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div id="modal-customer-info" class="hidden bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600">Customer:</span>
                            <span id="modal-customer-name" class="font-medium"></span>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="transaction-notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add any notes for this transaction..."></textarea>
                    </div>

                    <!-- Sandbox Testing Info -->
                    @if(!config('midtrans.is_production'))
                    <div id="modal-digital-info" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm">
                                <p class="font-medium text-yellow-800">Sandbox Testing Mode</p>
                                <p class="text-yellow-700 mt-1">
                                    Test Card: <code class="bg-yellow-100 px-1 rounded">4811 1111 1111 1114</code><br>
                                    CVV: <code class="bg-yellow-100 px-1 rounded">123</code> | Exp: <code class="bg-yellow-100 px-1 rounded">01/25</code>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="flex space-x-3 mt-6">
                    <button id="cancel-payment" class="flex-1 py-2 px-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button id="confirm-payment" class="flex-1 py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>