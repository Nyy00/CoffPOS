/**
 * CoffPOS - Point of Sale JavaScript
 * Handles all POS functionality including cart management, payments, and UI interactions
 * Updated: 2025-12-19 18:50:00
 */

class POSSystem {
    constructor() {
        this.cart = {};
        this.customers = [];
        this.currentPaymentMethod = 'cash';
        this.currentTransaction = null;
        
        console.log('POSSystem initialized with currentPaymentMethod:', this.currentPaymentMethod);
        
        this.init();
    }
    
    getFallbackImage(productName) {
        const name = productName.toLowerCase();
        if (name.includes('cheesecake')) return '/images/products/cheesecake.jpg';
        if (name.includes('sandwich')) return '/images/products/sandwich.jpg';
        if (name.includes('tiramisu')) return '/images/products/tiramisu.jpg';
        if (name.includes('chocolate')) return '/images/products/chocolate.jpg';
        if (name.includes('croissant')) return '/images/products/croissants.jpg';
        if (name.includes('americano')) return '/images/products/americano.jpg';
        if (name.includes('latte')) return '/images/products/latte.jpg';
        if (name.includes('cappuccino')) return '/images/products/cappuccino.jpg';
        if (name.includes('espresso')) return '/images/products/espresso.jpg';
        if (name.includes('mocha')) return '/images/products/mocha.jpg';
        if (name.includes('tea')) return '/images/products/green-tea.jpg';
        return '/images/placeholder-product.png';
    }
    
    init() {
        this.setupEventListeners();
        this.loadCart();
        this.loadCustomers();
        this.updateTime();
        
        // Update time every minute
        setInterval(() => this.updateTime(), 60000);
    }
    
    setupEventListeners() {
        // Product search
        const searchInput = document.getElementById('product-search');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => this.searchProducts(e.target.value), 300));
        }
        
        // Category filter
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => this.filterByCategory(e.target.value));
        }
        
        // Category tabs
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', (e) => this.switchCategory(e.target.dataset.category));
        });
        
        // Event delegation hanya untuk add to cart buttons
        const productsGrid = document.getElementById('products-grid');
        if (productsGrid) {
            productsGrid.addEventListener('click', (e) => {
                // Hanya handle add to cart button click
                if (e.target.closest('.add-to-cart-btn')) {
                    e.stopPropagation();
                    const productCard = e.target.closest('.product-card');
                    if (productCard) {
                        const productId = productCard.dataset.productId;
                        this.addToCart(productId);
                    }
                }
            });
        }
        
        // Fallback: Direct event listeners hanya pada add to cart buttons
        const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
        
        addToCartBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                
                const productCard = e.target.closest('.product-card');
                if (productCard) {
                    const productId = productCard.dataset.productId;
                    this.addToCart(productId);
                }
            });
        });
        
        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(btn => {
            btn.addEventListener('click', (e) => this.selectPaymentMethod(e.target.dataset.method));
        });
        
        // Payment amount input
        const paymentAmount = document.getElementById('payment-amount');
        if (paymentAmount) {
            paymentAmount.addEventListener('input', () => this.calculateChange());
        }
        
        // Clear cart
        const clearCartBtn = document.getElementById('clear-cart');
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', () => this.clearCart());
        }
        
        // Checkout
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', () => this.showPaymentModal());
        }
        
        // Hold transaction
        const holdBtn = document.getElementById('hold-transaction');
        if (holdBtn) {
            holdBtn.addEventListener('click', () => this.showHoldModal());
        }
        
        // View held transactions
        const viewHeldBtn = document.getElementById('view-held-transactions');
        if (viewHeldBtn) {
            viewHeldBtn.addEventListener('click', () => this.showHeldTransactionsModal());
        }
        
        // Modal event listeners
        this.setupModalListeners();
    }
    
    setupModalListeners() {
        // Payment modal
        const paymentModal = document.getElementById('payment-modal');
        const closePaymentModal = document.getElementById('close-payment-modal');
        const cancelPayment = document.getElementById('cancel-payment');
        const confirmPayment = document.getElementById('confirm-payment');
        
        if (closePaymentModal) closePaymentModal.addEventListener('click', () => this.hidePaymentModal());
        if (cancelPayment) cancelPayment.addEventListener('click', () => this.hidePaymentModal());
        if (confirmPayment) confirmPayment.addEventListener('click', () => this.processPayment());
        
        // Receipt modal
        const closeReceiptModal = document.getElementById('close-receipt-modal');
        const printReceipt = document.getElementById('print-receipt');
        const newTransaction = document.getElementById('new-transaction');
        
        if (closeReceiptModal) closeReceiptModal.addEventListener('click', () => this.hideReceiptModal());
        if (printReceipt) printReceipt.addEventListener('click', () => this.printReceipt());
        if (newTransaction) newTransaction.addEventListener('click', () => this.startNewTransaction());
        
        // Hold modal
        const closeHoldModal = document.getElementById('close-hold-modal');
        const cancelHold = document.getElementById('cancel-hold');
        const confirmHold = document.getElementById('confirm-hold');
        
        if (closeHoldModal) closeHoldModal.addEventListener('click', () => this.hideHoldModal());
        if (cancelHold) cancelHold.addEventListener('click', () => this.hideHoldModal());
        if (confirmHold) confirmHold.addEventListener('click', () => this.holdTransaction());
        
        // Held transactions modal
        const closeHeldTransactionsModal = document.getElementById('close-held-transactions-modal');
        if (closeHeldTransactionsModal) closeHeldTransactionsModal.addEventListener('click', () => this.hideHeldTransactionsModal());
    }
    
    // Time management
    updateTime() {
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            const now = new Date();
            const timeString = now.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            timeElement.textContent = timeString;
        }
    }
    
    // Product search and filtering
    searchProducts(query) {
        const products = document.querySelectorAll('.product-card');
        const noProducts = document.getElementById('no-products');
        let visibleCount = 0;
        
        products.forEach(product => {
            const name = product.querySelector('h3').textContent.toLowerCase();
            const category = product.querySelector('p').textContent.toLowerCase();
            const isVisible = name.includes(query.toLowerCase()) || category.includes(query.toLowerCase());
            
            product.style.display = isVisible ? 'block' : 'none';
            if (isVisible) visibleCount++;
        });
        
        if (noProducts) {
            noProducts.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }
    
    filterByCategory(categoryId) {
        const products = document.querySelectorAll('.product-card');
        const noProducts = document.getElementById('no-products');
        let visibleCount = 0;
        
        products.forEach(product => {
            const productCategory = product.dataset.category;
            const isVisible = !categoryId || productCategory === categoryId;
            
            product.style.display = isVisible ? 'block' : 'none';
            if (isVisible) visibleCount++;
        });
        
        if (noProducts) {
            noProducts.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }
    
    switchCategory(categoryId) {
        // Update active tab
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.classList.remove('active', 'bg-blue-500', 'text-white');
            tab.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        const activeTab = document.querySelector(`[data-category="${categoryId}"]`);
        if (activeTab) {
            activeTab.classList.add('active', 'bg-blue-500', 'text-white');
            activeTab.classList.remove('bg-gray-100', 'text-gray-700');
        }
        
        // Filter products
        this.filterByCategory(categoryId);
        
        // Update category filter dropdown
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.value = categoryId;
        }
    }
    
    // Cart management
    async addToCart(productId, quantity = 1) {
        if (!productId) {
            this.showNotification('ID produk tidak ditemukan', 'error');
            return;
        }
        
        console.log('Adding to cart:', { productId, quantity });
        
        try {
            const response = await fetch('/cashier/pos/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            console.log('Add to cart response:', data);
            
            if (data.success) {
                this.cart = data.cart;
                this.updateCartDisplay();
                this.showNotification('Produk ditambahkan ke keranjang', 'success');
            } else {
                this.showNotification(data.error || 'Gagal menambahkan produk', 'error');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification('Gagal menambahkan produk ke keranjang', 'error');
        }
    }
    
    async updateCartQuantity(productId, quantity) {
        try {
            const response = await fetch('/cashier/pos/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.cart = data.cart;
                this.updateCartDisplay();
            } else {
                this.showNotification(data.error || 'Gagal memperbarui keranjang', 'error');
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            this.showNotification('Gagal memperbarui keranjang', 'error');
        }
    }
    
    async removeFromCart(productId) {
        try {
            const response = await fetch('/cashier/pos/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.cart = data.cart;
                this.updateCartDisplay();
                this.showNotification('Produk dihapus dari keranjang', 'success');
            } else {
                throw new Error(data.message || 'Gagal menghapus produk');
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
            this.showNotification('Gagal menghapus produk dari keranjang', 'error');
        }
    }
    
    async clearCart() {
        if (Object.keys(this.cart).length === 0) {
            this.showNotification('Keranjang sudah kosong', 'info');
            return;
        }
        
        if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
            try {
                const response = await fetch('/cashier/pos/cart/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                console.log('Clear cart response:', data);
                
                if (data.success) {
                    this.cart = {};
                    this.updateCartDisplay();
                    this.showNotification('Keranjang dikosongkan', 'success');
                } else {
                    throw new Error(data.message || 'Gagal mengosongkan keranjang');
                }
            } catch (error) {
                console.error('Error clearing cart:', error);
                this.showNotification('Gagal mengosongkan keranjang', 'error');
            }
        }
    }
    
    async loadCart() {
        try {
            const response = await fetch('/cashier/pos/cart/items');
            const data = await response.json();
            
            console.log('Loading cart from server:', data);
            
            this.cart = data.cart || {};
            this.updateCartDisplay();
        } catch (error) {
            console.error('Error loading cart:', error);
        }
    }
    
    updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cart-items');
        const emptyCart = document.getElementById('empty-cart');
        const cartCount = document.getElementById('cart-count');
        
        if (!cartItemsContainer) return;
        
        if (Object.keys(this.cart).length === 0) {
            if (emptyCart) emptyCart.style.display = 'block';
            cartItemsContainer.innerHTML = '';
            if (cartCount) cartCount.textContent = '0 items';
            this.updateTotals();
            return;
        }
        
        if (emptyCart) emptyCart.style.display = 'none';
        
        let totalItems = 0;
        let html = '';
        
        Object.values(this.cart).forEach(item => {
            totalItems += item.quantity;
            
            // Fix image path - ensure proper URL construction
            let imageUrl = '/images/placeholder-product.png'; // Default fallback
            let fallbackUrl = this.getFallbackImage(item.name);
            
            if (item.image) {
                // Clean the image path and construct proper URL
                const cleanImagePath = item.image.replace(/^products\//, '');
                imageUrl = `/images/products/${cleanImagePath}`;
            }
            
            html += `
                <div class="cart-item flex items-center space-x-3 p-3 border rounded-lg">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                        <img src="${imageUrl}" 
                             alt="${item.name}" 
                             class="w-full h-full object-cover rounded-lg"
                             onerror="this.onerror=null; this.src='${fallbackUrl}';">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">${item.name}</h4>
                        <p class="text-sm text-gray-500">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="pos.updateCartQuantity(${item.product_id}, ${item.quantity - 1})" class="w-6 h-6 bg-gray-200 text-gray-600 rounded-full hover:bg-gray-300 flex items-center justify-center text-sm">-</button>
                        <span class="w-8 text-center text-sm font-medium">${item.quantity}</span>
                        <button onclick="pos.updateCartQuantity(${item.product_id}, ${item.quantity + 1})" class="w-6 h-6 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex items-center justify-center text-sm">+</button>
                    </div>
                    <button onclick="pos.removeFromCart(${item.product_id})" class="text-red-500 hover:text-red-700 text-sm">
                        🗑️
                    </button>
                </div>
            `;
        });
        
        cartItemsContainer.innerHTML = html;
        if (cartCount) cartCount.textContent = `${totalItems} item${totalItems !== 1 ? '' : ''}`;
        this.updateTotals();
    }
    
    updateTotals() {
        let subtotal = 0;
        Object.values(this.cart).forEach(item => {
            subtotal += item.price * item.quantity;
        });
        
        const discount = 0; // Can be implemented later
        const tax = subtotal * 0.1; // 10% tax
        const total = subtotal - discount + tax;
        
        // Update main totals
        const subtotalEl = document.getElementById('subtotal');
        const discountEl = document.getElementById('discount');
        const taxEl = document.getElementById('tax');
        const totalEl = document.getElementById('total');
        
        if (subtotalEl) subtotalEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}`;
        if (discountEl) discountEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(discount)}`;
        if (taxEl) taxEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(tax)}`;
        if (totalEl) totalEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
        
        // Enable/disable checkout button
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.disabled = Object.keys(this.cart).length === 0;
        }
        
        this.calculateChange();
    }
    
    calculateChange() {
        const totalEl = document.getElementById('total');
        const paymentAmountEl = document.getElementById('payment-amount');
        const changeEl = document.getElementById('change');
        
        if (!totalEl || !paymentAmountEl || !changeEl) return;
        
        const total = parseFloat(totalEl.textContent.replace(/[^\d]/g, ''));
        const paymentAmount = parseFloat(paymentAmountEl.value) || 0;
        const change = paymentAmount - total;
        
        changeEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(Math.max(0, change))}`;
        changeEl.className = change >= 0 ? 'font-medium text-green-600' : 'font-medium text-red-600';
    }
    
    // Payment methods
    selectPaymentMethod(method) {
        this.currentPaymentMethod = method;
        
        // Update UI - use coffee theme colors
        document.querySelectorAll('.payment-method').forEach(btn => {
            btn.classList.remove('border-gold', 'bg-light-coffee', 'text-coffee-dark', 'active');
            btn.classList.add('border-light-coffee', 'bg-white', 'text-coffee-dark');
        });
        
        const selectedBtn = document.querySelector(`[data-method="${method}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('border-gold', 'bg-light-coffee', 'text-coffee-dark', 'active');
            selectedBtn.classList.remove('border-light-coffee', 'bg-white');
        }
        
        // Show/hide cash payment section
        const cashPayment = document.getElementById('cash-payment');
        if (cashPayment) {
            cashPayment.style.display = method === 'cash' ? 'block' : 'none';
        }
        
        // Update checkout button text based on payment method
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            if (method === 'digital') {
                checkoutBtn.innerHTML = '💳 Bayar Digital';
            } else {
                checkoutBtn.innerHTML = '💰 Proses Pembayaran';
            }
        }
    }
    
    // Modal management
    showPaymentModal() {
        if (Object.keys(this.cart).length === 0) {
            this.showNotification('Keranjang kosong', 'error');
            return;
        }
        
        // Update modal content
        this.updatePaymentModalContent();
        
        const modal = document.getElementById('payment-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }
    
    hidePaymentModal() {
        const modal = document.getElementById('payment-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    updatePaymentModalContent() {
        // Calculate totals
        let subtotal = 0;
        Object.values(this.cart).forEach(item => {
            subtotal += item.price * item.quantity;
        });
        
        const discount = 0;
        const tax = subtotal * 0.1;
        const total = subtotal - discount + tax;
        
        // Update modal totals
        const modalSubtotal = document.getElementById('modal-subtotal');
        const modalTax = document.getElementById('modal-tax');
        const modalDiscount = document.getElementById('modal-discount');
        const modalTotal = document.getElementById('modal-total');
        const modalPaymentMethod = document.getElementById('modal-payment-method');
        
        if (modalSubtotal) modalSubtotal.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}`;
        if (modalTax) modalTax.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(tax)}`;
        if (modalDiscount) modalDiscount.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(discount)}`;
        if (modalTotal) modalTotal.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
        
        // Ensure currentPaymentMethod has a default value
        const paymentMethod = this.currentPaymentMethod || 'cash';
        console.log('updatePaymentModalContent - currentPaymentMethod:', this.currentPaymentMethod, 'using:', paymentMethod);
        if (modalPaymentMethod) modalPaymentMethod.textContent = paymentMethod.charAt(0).toUpperCase() + paymentMethod.slice(1);
        
        // Update cash details if cash payment
        const modalCashDetails = document.getElementById('modal-cash-details');
        if (modalCashDetails) {
            if (paymentMethod === 'cash') {
                modalCashDetails.style.display = 'block';
                
                const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
                const change = paymentAmount - total;
                
                const modalCashReceived = document.getElementById('modal-cash-received');
                const modalChange = document.getElementById('modal-change');
                
                if (modalCashReceived) modalCashReceived.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(paymentAmount)}`;
                if (modalChange) modalChange.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(Math.max(0, change))}`;
            } else {
                modalCashDetails.style.display = 'none';
            }
        }
        
        // Update customer info
        const customerSelect = document.getElementById('customer-select');
        const modalCustomerInfo = document.getElementById('modal-customer-info');
        const modalCustomerName = document.getElementById('modal-customer-name');
        
        if (customerSelect && modalCustomerInfo && modalCustomerName) {
            if (customerSelect.value) {
                const selectedCustomer = this.customers.find(c => c.id == customerSelect.value);
                if (selectedCustomer) {
                    modalCustomerInfo.style.display = 'block';
                    modalCustomerName.textContent = selectedCustomer.name;
                } else {
                    modalCustomerInfo.style.display = 'none';
                }
            } else {
                modalCustomerInfo.style.display = 'none';
            }
        }

        // Show/hide digital payment info for sandbox
        const modalDigitalInfo = document.getElementById('modal-digital-info');
        if (modalDigitalInfo) {
            if (paymentMethod === 'digital') {
                modalDigitalInfo.style.display = 'block';
            } else {
                modalDigitalInfo.style.display = 'none';
            }
        }
    }
    
    async processPayment() {
        try {
            // Check if digital payment method is selected
            if (this.currentPaymentMethod === 'digital') {
                await this.processDigitalPayment();
                return;
            }

            // Validate cash payment
            if (this.currentPaymentMethod === 'cash') {
                const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
                const total = parseFloat(document.getElementById('total').textContent.replace(/[^\d]/g, ''));
                
                if (paymentAmount < total) {
                    this.showNotification('Jumlah pembayaran tidak mencukupi', 'error');
                    return;
                }
            }
            
            // Process regular payment (cash, debit, credit)
            await this.processRegularPayment();
            
        } catch (error) {
            console.error('Error processing payment:', error);
            this.showNotification('Gagal memproses pembayaran', 'error');
        }
    }

    async processRegularPayment() {
        // Prepare transaction data
        const customerSelect = document.getElementById('customer-select');
        const transactionNotes = document.getElementById('transaction-notes');
        const paymentAmountEl = document.getElementById('payment-amount');
        
        const paymentAmount = this.currentPaymentMethod === 'cash' ? 
            (paymentAmountEl ? parseFloat(paymentAmountEl.value) || 0 : 0) : 
            parseFloat(document.getElementById('total').textContent.replace(/[^\d]/g, ''));
        
        const transactionData = {
            customer_id: customerSelect ? customerSelect.value || null : null,
            payment: {
                method: this.currentPaymentMethod,
                amount: paymentAmount,
                reference: null
            },
            discount_amount: 0,
            discount_percent: 0,
            tax_percent: 10,
            use_loyalty_points: false,
            loyalty_points_used: 0,
            notes: transactionNotes ? transactionNotes.value : ''
        };
        
        // Process transaction
        const response = await fetch('/cashier/pos/process-transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(transactionData)
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server error response:', errorText);
            throw new Error(`Server error: ${response.status} - ${errorText.substring(0, 100)}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const responseText = await response.text();
            console.error('Non-JSON response:', responseText.substring(0, 200));
            throw new Error('Server returned non-JSON response. Please check if you are logged in.');
        }
        
        const data = await response.json();
            
        if (data.success) {
            this.currentTransaction = data.transaction;
            this.cart = {};
            this.updateCartDisplay();
            this.hidePaymentModal();
            this.showReceiptModal();
            this.showNotification('Transaksi berhasil diproses', 'success');
        } else {
            this.showNotification(data.error, 'error');
        }
    }

    async processDigitalPayment() {
        try {
            const customerSelect = document.getElementById('customer-select');
            
            // Create Midtrans token
            const response = await fetch('/cashier/pos/midtrans/create-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    customer_id: customerSelect ? customerSelect.value || null : null
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error response:', errorText);
                throw new Error(`Failed to create payment token: ${response.status} - ${errorText.substring(0, 100)}`);
            }

            const data = await response.json();

            if (data.success) {
                // Check if Midtrans Snap is loaded
                if (typeof window.snap === 'undefined') {
                    throw new Error('Midtrans Snap is not loaded. Please refresh the page and try again.');
                }

                // Hide payment modal
                this.hidePaymentModal();
                
                // Show Midtrans Snap
                window.snap.pay(data.snap_token, {
                    onSuccess: (result) => {
                        console.log('Payment success:', result);
                        this.handleMidtransSuccess(result, data.order_id);
                    },
                    onPending: (result) => {
                        console.log('Payment pending:', result);
                        this.showNotification('Pembayaran sedang diproses', 'info');
                    },
                    onError: (result) => {
                        console.log('Payment error:', result);
                        this.showNotification('Pembayaran gagal', 'error');
                    },
                    onClose: () => {
                        console.log('Payment popup closed');
                        this.showNotification('Pembayaran dibatalkan', 'warning');
                    }
                });
            } else {
                throw new Error(data.error || 'Failed to create payment token');
            }

        } catch (error) {
            console.error('Error processing digital payment:', error);
            this.showNotification(`Gagal memproses pembayaran digital: ${error.message}`, 'error');
        }
    }

    async handleMidtransSuccess(result, orderId) {
        try {
            const customerSelect = document.getElementById('customer-select');
            const transactionNotes = document.getElementById('transaction-notes');

            console.log('Processing Midtrans success:', result);

            const response = await fetch('/cashier/pos/midtrans/process-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    order_id: orderId,
                    transaction_status: result.transaction_status,
                    transaction_id: result.transaction_id,
                    payment_type: result.payment_type,
                    customer_id: customerSelect ? customerSelect.value || null : null,
                    notes: transactionNotes ? transactionNotes.value : ''
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error response:', errorText);
                throw new Error(`Server error: ${response.status} - ${errorText.substring(0, 100)}`);
            }

            const data = await response.json();

            if (data.success) {
                this.currentTransaction = data.transaction;
                this.cart = {};
                this.updateCartDisplay();
                this.showReceiptModal();
                this.showNotification('Pembayaran berhasil diproses', 'success');
            } else {
                this.showNotification(data.error || 'Gagal memproses pembayaran', 'error');
            }

        } catch (error) {
            console.error('Error handling Midtrans success:', error);
            this.showNotification(`Gagal memproses hasil pembayaran: ${error.message}`, 'error');
        }
    }
    
    showReceiptModal() {
        if (!this.currentTransaction) return;
        
        // Update receipt modal content
        const receiptTransactionId = document.getElementById('receipt-transaction-id');
        const receiptTotal = document.getElementById('receipt-total');
        const receiptPaymentMethod = document.getElementById('receipt-payment-method');
        
        if (receiptTransactionId) receiptTransactionId.textContent = `#${this.currentTransaction.transaction_code}`;
        if (receiptTotal) receiptTotal.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(this.currentTransaction.total_amount)}`;
        if (receiptPaymentMethod) receiptPaymentMethod.textContent = this.currentTransaction.payment_method.charAt(0).toUpperCase() + this.currentTransaction.payment_method.slice(1);
        
        const modal = document.getElementById('receipt-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }
    
    hideReceiptModal() {
        const modal = document.getElementById('receipt-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    printReceipt() {
        if (!this.currentTransaction) return;
        
        const printWindow = window.open(`/cashier/pos/receipt/${this.currentTransaction.id}?auto_print=1`, '_blank', 'width=400,height=600');
        if (printWindow) {
            printWindow.focus();
        }
    }
    
    startNewTransaction() {
        this.currentTransaction = null;
        this.hideReceiptModal();
        
        // Reset payment method to cash
        this.selectPaymentMethod('cash');
        
        // Clear payment amount
        const paymentAmount = document.getElementById('payment-amount');
        if (paymentAmount) {
            paymentAmount.value = '';
        }
        
        // Clear customer selection
        const customerSelect = document.getElementById('customer-select');
        if (customerSelect) {
            customerSelect.value = '';
        }
        
        this.showNotification('Siap untuk transaksi baru', 'success');
    }
    
    // Hold transaction functionality
    showHoldModal() {
        if (Object.keys(this.cart).length === 0) {
            this.showNotification('Keranjang kosong', 'error');
            return;
        }
        
        // Update hold modal content
        this.updateHoldModalContent();
        
        const modal = document.getElementById('hold-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }
    
    hideHoldModal() {
        const modal = document.getElementById('hold-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    updateHoldModalContent() {
        const holdCartSummary = document.getElementById('hold-cart-summary');
        const holdTotal = document.getElementById('hold-total');
        
        if (holdCartSummary) {
            let html = '';
            let total = 0;
            
            Object.values(this.cart).forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                html += `
                    <div class="flex justify-between">
                        <span>${item.name} x${item.quantity}</span>
                        <span>Rp ${new Intl.NumberFormat('id-ID').format(itemTotal)}</span>
                    </div>
                `;
            });
            
            holdCartSummary.innerHTML = html;
        }
        
        if (holdTotal) {
            let subtotal = 0;
            Object.values(this.cart).forEach(item => {
                subtotal += item.price * item.quantity;
            });
            const tax = subtotal * 0.1;
            const total = subtotal + tax;
            
            holdTotal.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
        }
    }
    
    async holdTransaction() {
        const holdReason = document.getElementById('hold-reason');
        const holdCustomerName = document.getElementById('hold-customer-name');
        const holdNotes = document.getElementById('hold-notes');
        
        if (!holdReason || !holdReason.value.trim()) {
            this.showNotification('Silakan masukkan alasan penahanan', 'error');
            return;
        }
        
        try {
            const requestData = {
                reason: holdReason.value,
                customer_name: holdCustomerName ? holdCustomerName.value : '',
                notes: holdNotes ? holdNotes.value : ''
            };

            const response = await fetch('/cashier/pos/hold-transaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.cart = {};
                this.updateCartDisplay();
                this.hideHoldModal();
                this.showNotification('Transaksi berhasil ditahan', 'success');
                
                // Clear hold form
                if (holdReason) holdReason.value = '';
                if (holdCustomerName) holdCustomerName.value = '';
                if (holdNotes) holdNotes.value = '';
            } else {
                this.showNotification(data.error || 'Gagal menahan transaksi', 'error');
            }
        } catch (error) {
            console.error('Error holding transaction:', error);
            this.showNotification(error.message || 'Gagal menahan transaksi', 'error');
        }
    }
    
    async showHeldTransactionsModal() {
        try {
            const response = await fetch('/cashier/pos/held-transactions');
            const data = await response.json();
            
            this.updateHeldTransactionsList(data.held_transactions || []);
            
            const modal = document.getElementById('held-transactions-modal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error loading held transactions:', error);
            this.showNotification('Gagal memuat transaksi yang ditahan', 'error');
        }
    }
    
    hideHeldTransactionsModal() {
        const modal = document.getElementById('held-transactions-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    updateHeldTransactionsList(heldTransactions) {
        const listContainer = document.getElementById('held-transactions-list');
        const noHeldTransactions = document.getElementById('no-held-transactions');
        
        if (!listContainer) return;
        
        if (heldTransactions.length === 0) {
            if (noHeldTransactions) noHeldTransactions.style.display = 'block';
            listContainer.innerHTML = '';
            return;
        }
        
        if (noHeldTransactions) noHeldTransactions.style.display = 'none';
        
        let html = '';
        heldTransactions.forEach(transaction => {
            const heldDate = new Date(transaction.held_at);
            const itemCount = Object.keys(transaction.cart).length;
            let total = 0;
            Object.values(transaction.cart).forEach(item => {
                total += item.price * item.quantity;
            });
            total = total * 1.1; // Add tax
            
            html += `
                <div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-medium text-gray-900">${transaction.customer_name || 'Pelanggan Umum'}</h4>
                            <p class="text-sm text-gray-500">${transaction.reason}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium">Rp ${new Intl.NumberFormat('id-ID').format(total)}</div>
                            <div class="text-xs text-gray-500">${itemCount} item</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-sm text-gray-500 mb-3">
                        <span>Ditahan oleh: ${transaction.held_by}</span>
                        <span>${heldDate.toLocaleDateString('id-ID')} ${heldDate.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="pos.resumeHeldTransaction('${transaction.id}')" class="flex-1 py-2 px-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm">
                            Lanjutkan
                        </button>
                        <button onclick="pos.deleteHeldTransaction('${transaction.id}')" class="py-2 px-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                            Hapus
                        </button>
                    </div>
                </div>
            `;
        });
        
        listContainer.innerHTML = html;
    }
    
    async resumeHeldTransaction(holdId) {
        try {
            const response = await fetch(`/cashier/pos/resume-transaction/${holdId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.cart = data.cart;
                this.updateCartDisplay();
                this.hideHeldTransactionsModal();
                this.showNotification('Transaksi berhasil dilanjutkan', 'success');
            } else {
                this.showNotification(data.error, 'error');
            }
        } catch (error) {
            console.error('Error resuming transaction:', error);
            this.showNotification('Gagal melanjutkan transaksi', 'error');
        }
    }
    
    async deleteHeldTransaction(holdId) {
        if (!confirm('Apakah Anda yakin ingin menghapus transaksi yang ditahan ini?')) {
            return;
        }
        
        try {
            const response = await fetch(`/cashier/pos/held-transaction/${holdId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.showHeldTransactionsModal(); // Refresh the list
                this.showNotification('Transaksi yang ditahan berhasil dihapus', 'success');
            } else {
                this.showNotification(data.error || 'Gagal menghapus transaksi', 'error');
            }
        } catch (error) {
            console.error('Error deleting held transaction:', error);
            this.showNotification('Gagal menghapus transaksi yang ditahan', 'error');
        }
    }
    
    // Customer management
    async loadCustomers() {
        try {
            const response = await fetch('/cashier/customers/quick-search');
            const data = await response.json();
            
            this.customers = data.customers || [];
            this.updateCustomerDropdown();
        } catch (error) {
            console.error('Error loading customers:', error);
        }
    }
    
    updateCustomerDropdown() {
        const customerSelect = document.getElementById('customer-select');
        if (!customerSelect) return;
        
        // Keep the first option (Walk-in Customer)
        const firstOption = customerSelect.querySelector('option[value=""]');
        customerSelect.innerHTML = '';
        if (firstOption) {
            customerSelect.appendChild(firstOption);
        }
        
        this.customers.forEach(customer => {
            const option = document.createElement('option');
            option.value = customer.id;
            option.textContent = `${customer.name} - ${customer.phone || 'No phone'}`;
            customerSelect.appendChild(option);
        });
    }
    
    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            type === 'warning' ? 'bg-yellow-500' :
            'bg-blue-500'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <span>${
                    type === 'success' ? '✅' :
                    type === 'error' ? '❌' :
                    type === 'warning' ? '⚠️' :
                    'ℹ️'
                }</span>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Initialize POS system when DOM is loaded
let pos;
document.addEventListener('DOMContentLoaded', function() {
    pos = new POSSystem();
    // Make functions available globally for onclick handlers
    window.pos = pos;
});

// Fallback untuk memastikan pos tersedia
if (typeof window !== 'undefined') {
    window.pos = pos;
}