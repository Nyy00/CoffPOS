/**
 * POS Payment Processing
 * Handles payment methods, calculations, and transaction processing
 */

class POSPayment {
    constructor() {
        this.paymentForm = document.getElementById('pos-payment-form');
        this.paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
        this.paymentAmountInput = document.getElementById('payment-amount');
        this.changeAmountDisplay = document.getElementById('change-amount');
        this.processBtn = document.getElementById('process-payment-btn');
        this.holdBtn = document.getElementById('hold-transaction-btn');
        
        this.customerSelect = document.getElementById('customer-select');
        this.loyaltyPointsInput = document.getElementById('loyalty-points-used');
        this.discountAmountInput = document.getElementById('discount-amount');
        this.discountPercentInput = document.getElementById('discount-percent');
        
        this.currentTotal = 0;
        this.selectedPaymentMethod = 'cash';
        this.isProcessing = false;
        
        this.init();
    }
    
    init() {
        if (!this.paymentForm) return;
        
        this.bindEvents();
        this.setupKeyboardShortcuts();
        this.initializePaymentMethod();
    }
    
    bindEvents() {
        // Payment method selection
        this.paymentMethodInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                this.handlePaymentMethodChange(e.target.value);
            });
        });
        
        // Payment amount input
        if (this.paymentAmountInput) {
            this.paymentAmountInput.addEventListener('input', () => {
                this.calculateChange();
            });
            
            this.paymentAmountInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.processPayment();
                }
            });
        }
        
        // Process payment button
        if (this.processBtn) {
            this.processBtn.addEventListener('click', () => {
                this.processPayment();
            });
        }
        
        // Hold transaction button
        if (this.holdBtn) {
            this.holdBtn.addEventListener('click', () => {
                this.holdTransaction();
            });
        }
        
        // Customer selection
        if (this.customerSelect) {
            this.customerSelect.addEventListener('change', () => {
                this.handleCustomerChange();
            });
        }
        
        // Loyalty points input
        if (this.loyaltyPointsInput) {
            this.loyaltyPointsInput.addEventListener('input', () => {
                this.calculateTotals();
            });
        }
        
        // Discount inputs
        if (this.discountAmountInput) {
            this.discountAmountInput.addEventListener('input', () => {
                this.calculateTotals();
            });
        }
        
        if (this.discountPercentInput) {
            this.discountPercentInput.addEventListener('input', () => {
                this.calculateTotals();
            });
        }
        
        // Quick payment buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-quick-payment]')) {
                const amount = parseFloat(e.target.dataset.quickPayment);
                this.setPaymentAmount(amount);
            }
        });
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // F5 - Process payment
            if (e.key === 'F5') {
                e.preventDefault();
                this.processPayment();
            }
            
            // F6 - Hold transaction
            if (e.key === 'F6') {
                e.preventDefault();
                this.holdTransaction();
            }
            
            // F7 - Focus payment amount
            if (e.key === 'F7') {
                e.preventDefault();
                if (this.paymentAmountInput) {
                    this.paymentAmountInput.focus();
                    this.paymentAmountInput.select();
                }
            }
            
            // F8 - Set exact amount
            if (e.key === 'F8') {
                e.preventDefault();
                this.setPaymentAmount(this.currentTotal);
            }
            
            // Number keys for payment method selection
            if (e.altKey && e.key >= '1' && e.key <= '5') {
                e.preventDefault();
                const methods = ['cash', 'debit', 'credit', 'e-wallet', 'qris'];
                const methodIndex = parseInt(e.key) - 1;
                if (methods[methodIndex]) {
                    this.selectPaymentMethod(methods[methodIndex]);
                }
            }
        });
    }
    
    initializePaymentMethod() {
        const defaultMethod = document.querySelector('input[name="payment_method"]:checked');
        if (defaultMethod) {
            this.handlePaymentMethodChange(defaultMethod.value);
        }
    }
    
    handlePaymentMethodChange(method) {
        this.selectedPaymentMethod = method;
        
        // Update UI based on payment method
        this.updatePaymentMethodUI(method);
        
        // Auto-set payment amount for non-cash methods
        if (method !== 'cash' && this.currentTotal > 0) {
            this.setPaymentAmount(this.currentTotal);
        }
    }
    
    updatePaymentMethodUI(method) {
        // Hide/show payment reference field
        const referenceField = document.getElementById('payment-reference-field');
        if (referenceField) {
            if (method === 'cash') {
                referenceField.classList.add('hidden');
            } else {
                referenceField.classList.remove('hidden');
            }
        }
        
        // Update payment amount label
        const amountLabel = document.getElementById('payment-amount-label');
        if (amountLabel) {
            const labels = {
                'cash': 'Cash Received',
                'debit': 'Debit Amount',
                'credit': 'Credit Amount',
                'e-wallet': 'E-Wallet Amount',
                'qris': 'QRIS Amount'
            };
            amountLabel.textContent = labels[method] || 'Payment Amount';
        }
        
        // Show/hide change calculation
        const changeSection = document.getElementById('change-section');
        if (changeSection) {
            if (method === 'cash') {
                changeSection.classList.remove('hidden');
            } else {
                changeSection.classList.add('hidden');
            }
        }
    }
    
    selectPaymentMethod(method) {
        const methodInput = document.querySelector(`input[name="payment_method"][value="${method}"]`);
        if (methodInput) {
            methodInput.checked = true;
            this.handlePaymentMethodChange(method);
        }
    }
    
    setPaymentAmount(amount) {
        if (this.paymentAmountInput) {
            this.paymentAmountInput.value = amount;
            this.calculateChange();
        }
    }
    
    calculateChange() {
        const paymentAmount = parseFloat(this.paymentAmountInput?.value || 0);
        const change = paymentAmount - this.currentTotal;
        
        if (this.changeAmountDisplay) {
            if (change >= 0) {
                this.changeAmountDisplay.textContent = `Rp ${this.formatPrice(change)}`;
                this.changeAmountDisplay.className = 'text-lg font-semibold text-green-600';
            } else {
                this.changeAmountDisplay.textContent = `Rp ${this.formatPrice(Math.abs(change))} short`;
                this.changeAmountDisplay.className = 'text-lg font-semibold text-red-600';
            }
        }
        
        // Enable/disable process button
        if (this.processBtn) {
            this.processBtn.disabled = change < 0 || this.isProcessing;
        }
    }
    
    async handleCustomerChange() {
        const customerId = this.customerSelect?.value;
        
        if (customerId) {
            try {
                const response = await fetch(`/cashier/pos/customer/${customerId}/loyalty`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        this.updateCustomerInfo(result.customer);
                    }
                }
                
            } catch (error) {
                console.error('Failed to load customer info:', error);
            }
        } else {
            this.clearCustomerInfo();
        }
    }
    
    updateCustomerInfo(customer) {
        // Update customer info display
        const customerInfo = document.getElementById('customer-info');
        if (customerInfo) {
            customerInfo.innerHTML = `
                <div class="text-sm text-gray-600">
                    <p><strong>${customer.name}</strong></p>
                    <p>Points: ${customer.points}</p>
                    <p>Total Spent: Rp ${this.formatPrice(customer.total_spent)}</p>
                </div>
            `;
            customerInfo.classList.remove('hidden');
        }
        
        // Update loyalty points input max value
        if (this.loyaltyPointsInput) {
            this.loyaltyPointsInput.max = customer.points;
            this.loyaltyPointsInput.placeholder = `Max: ${customer.points}`;
        }
    }
    
    clearCustomerInfo() {
        const customerInfo = document.getElementById('customer-info');
        if (customerInfo) {
            customerInfo.classList.add('hidden');
        }
        
        if (this.loyaltyPointsInput) {
            this.loyaltyPointsInput.value = '';
            this.loyaltyPointsInput.max = '';
            this.loyaltyPointsInput.placeholder = 'Enter points to use';
        }
        
        this.calculateTotals();
    }
    
    async calculateTotals() {
        if (window.POSCart) {
            await window.POSCart.calculateFullTotals();
        }
    }
    
    async processPayment() {
        if (this.isProcessing) return;
        
        // Validate cart
        if (window.POSCart && window.POSCart.isEmpty()) {
            if (window.Toast) {
                window.Toast.error('Cart is empty');
            }
            return;
        }
        
        // Validate payment amount
        const paymentAmount = parseFloat(this.paymentAmountInput?.value || 0);
        if (paymentAmount < this.currentTotal) {
            if (window.Toast) {
                window.Toast.error('Insufficient payment amount');
            }
            return;
        }
        
        this.isProcessing = true;
        this.updateProcessingState(true);
        
        try {
            const paymentData = this.collectPaymentData();
            
            const response = await fetch('/cashier/pos/process-transaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(paymentData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show success message
                if (window.Toast) {
                    window.Toast.success('Transaction processed successfully!');
                }
                
                // Show receipt modal
                this.showReceiptModal(result.receipt_data);
                
                // Reset form and cart
                this.resetForm();
                if (window.POSCart) {
                    window.POSCart.updateCartDisplay({});
                }
                
            } else {
                if (window.Toast) {
                    window.Toast.error(result.error || 'Transaction failed');
                }
            }
            
        } catch (error) {
            console.error('Payment processing error:', error);
            if (window.Toast) {
                window.Toast.error('Transaction failed. Please try again.');
            }
        } finally {
            this.isProcessing = false;
            this.updateProcessingState(false);
        }
    }
    
    collectPaymentData() {
        return {
            customer_id: this.customerSelect?.value || null,
            payment: {
                method: this.selectedPaymentMethod,
                amount: parseFloat(this.paymentAmountInput?.value || 0),
                reference: document.getElementById('payment-reference')?.value || null
            },
            discount_amount: parseFloat(this.discountAmountInput?.value || 0),
            discount_percent: parseFloat(this.discountPercentInput?.value || 0),
            use_loyalty_points: !!this.loyaltyPointsInput?.value,
            loyalty_points_used: parseInt(this.loyaltyPointsInput?.value || 0),
            notes: document.getElementById('transaction-notes')?.value || null
        };
    }
    
    async holdTransaction() {
        if (window.POSCart && window.POSCart.isEmpty()) {
            if (window.Toast) {
                window.Toast.error('Cart is empty');
            }
            return;
        }
        
        const reason = prompt('Reason for holding transaction:');
        if (!reason) return;
        
        try {
            const holdData = {
                reason: reason,
                customer_id: this.customerSelect?.value || null,
                discount_amount: parseFloat(this.discountAmountInput?.value || 0),
                notes: document.getElementById('transaction-notes')?.value || null
            };
            
            const response = await fetch('/cashier/pos/hold-transaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(holdData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (window.Toast) {
                    window.Toast.success('Transaction held successfully');
                }
                
                // Reset form and cart
                this.resetForm();
                if (window.POSCart) {
                    window.POSCart.updateCartDisplay({});
                }
                
                // Update held transactions list
                this.updateHeldTransactionsList();
                
            } else {
                if (window.Toast) {
                    window.Toast.error(result.error || 'Failed to hold transaction');
                }
            }
            
        } catch (error) {
            console.error('Hold transaction error:', error);
            if (window.Toast) {
                window.Toast.error('Failed to hold transaction');
            }
        }
    }
    
    showReceiptModal(receiptData) {
        // Create or update receipt modal
        let modal = document.getElementById('receipt-modal');
        if (!modal) {
            modal = this.createReceiptModal();
            document.body.appendChild(modal);
        }
        
        // Update modal content
        const receiptContent = modal.querySelector('#receipt-content');
        if (receiptContent) {
            receiptContent.innerHTML = this.generateReceiptHTML(receiptData);
        }
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Auto-focus print button
        const printBtn = modal.querySelector('#print-receipt-btn');
        if (printBtn) {
            printBtn.focus();
        }
    }
    
    createReceiptModal() {
        const modal = document.createElement('div');
        modal.id = 'receipt-modal';
        modal.className = 'fixed inset-0 z-50 overflow-y-auto hidden';
        modal.innerHTML = `
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
                <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Transaction Complete</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="this.closest('.fixed').classList.add('hidden')">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="receipt-content" class="mb-6"></div>
                    <div class="flex space-x-3">
                        <button type="button" id="print-receipt-btn" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                            Print Receipt
                        </button>
                        <button type="button" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400" onclick="this.closest('.fixed').classList.add('hidden')">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Bind print button
        const printBtn = modal.querySelector('#print-receipt-btn');
        printBtn.addEventListener('click', () => {
            this.printReceipt();
        });
        
        return modal;
    }
    
    generateReceiptHTML(receiptData) {
        const transaction = receiptData.transaction;
        const company = receiptData.company;
        
        return `
            <div class="receipt-preview text-sm">
                <div class="text-center mb-4">
                    <h4 class="font-bold">${company.name}</h4>
                    <p class="text-xs text-gray-600">${company.address}</p>
                    <p class="text-xs text-gray-600">${company.phone}</p>
                </div>
                
                <div class="border-t border-b border-gray-300 py-2 mb-2">
                    <div class="flex justify-between">
                        <span>Receipt #:</span>
                        <span>${receiptData.receipt_number}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Date:</span>
                        <span>${receiptData.date}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Cashier:</span>
                        <span>${receiptData.cashier}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Customer:</span>
                        <span>${receiptData.customer}</span>
                    </div>
                </div>
                
                <div class="mb-2">
                    ${receiptData.items.map(item => `
                        <div class="flex justify-between">
                            <div class="flex-1">
                                <div>${item.name}</div>
                                <div class="text-xs text-gray-600">${item.quantity} x Rp ${this.formatPrice(item.price)}</div>
                            </div>
                            <div>Rp ${this.formatPrice(item.subtotal)}</div>
                        </div>
                    `).join('')}
                </div>
                
                <div class="border-t border-gray-300 pt-2">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>Rp ${this.formatPrice(receiptData.totals.subtotal)}</span>
                    </div>
                    ${receiptData.totals.discount > 0 ? `
                        <div class="flex justify-between">
                            <span>Discount:</span>
                            <span>-Rp ${this.formatPrice(receiptData.totals.discount)}</span>
                        </div>
                    ` : ''}
                    <div class="flex justify-between">
                        <span>Tax:</span>
                        <span>Rp ${this.formatPrice(receiptData.totals.tax)}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span>Rp ${this.formatPrice(receiptData.totals.total)}</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-300 pt-2 mt-2">
                    <div class="flex justify-between">
                        <span>Payment (${receiptData.payment.method}):</span>
                        <span>Rp ${this.formatPrice(receiptData.payment.amount)}</span>
                    </div>
                    ${receiptData.payment.change > 0 ? `
                        <div class="flex justify-between">
                            <span>Change:</span>
                            <span>Rp ${this.formatPrice(receiptData.payment.change)}</span>
                        </div>
                    ` : ''}
                </div>
                
                <div class="text-center mt-4 text-xs text-gray-600">
                    <p>Thank you for your purchase!</p>
                    <p>Please come again</p>
                </div>
            </div>
        `;
    }
    
    printReceipt() {
        const receiptContent = document.getElementById('receipt-content');
        if (!receiptContent) return;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Receipt</title>
                    <style>
                        body { font-family: monospace; font-size: 12px; margin: 0; padding: 20px; }
                        .receipt-preview { max-width: 300px; margin: 0 auto; }
                        @media print {
                            body { margin: 0; padding: 0; }
                            .receipt-preview { max-width: none; }
                        }
                    </style>
                </head>
                <body>
                    ${receiptContent.innerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
        
        // Close modal after printing
        document.getElementById('receipt-modal')?.classList.add('hidden');
    }
    
    updateHeldTransactionsList() {
        // This would update a held transactions sidebar or modal
        // Implementation depends on your UI design
        if (window.POSHeldTransactions) {
            window.POSHeldTransactions.refresh();
        }
    }
    
    updateProcessingState(processing) {
        if (this.processBtn) {
            this.processBtn.disabled = processing;
            this.processBtn.textContent = processing ? 'Processing...' : 'Process Payment';
        }
        
        if (this.holdBtn) {
            this.holdBtn.disabled = processing;
        }
    }
    
    updateTotal(total) {
        this.currentTotal = total;
        this.calculateChange();
    }
    
    resetForm() {
        // Reset payment form
        if (this.paymentForm) {
            this.paymentForm.reset();
        }
        
        // Reset customer selection
        if (this.customerSelect) {
            this.customerSelect.value = '';
        }
        
        // Clear customer info
        this.clearCustomerInfo();
        
        // Reset payment method to cash
        this.selectPaymentMethod('cash');
        
        // Clear payment amount
        if (this.paymentAmountInput) {
            this.paymentAmountInput.value = '';
        }
        
        // Reset change display
        if (this.changeAmountDisplay) {
            this.changeAmountDisplay.textContent = 'Rp 0';
            this.changeAmountDisplay.className = 'text-lg font-semibold text-gray-600';
        }
        
        this.currentTotal = 0;
    }
    
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('pos-payment-form')) {
        window.POSPayment = new POSPayment();
    }
});

export default POSPayment;