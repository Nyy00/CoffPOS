/**
 * POS Shopping Cart Management
 * Handles cart operations, calculations, and UI updates
 */

class POSShoppingCart {
    constructor() {
        this.cartContainer = document.getElementById('pos-cart-container');
        this.cartItems = document.getElementById('pos-cart-items');
        this.cartSummary = document.getElementById('pos-cart-summary');
        this.cartTotal = document.getElementById('pos-cart-total');
        this.cartCount = document.getElementById('pos-cart-count');
        this.clearCartBtn = document.getElementById('clear-cart-btn');
        
        this.cart = {};
        this.totals = {
            subtotal: 0,
            discount: 0,
            tax: 0,
            total: 0
        };
        
        this.init();
    }
    
    init() {
        if (!this.cartContainer) return;
        
        this.bindEvents();
        this.loadCartFromServer();
        this.setupKeyboardShortcuts();
    }
    
    bindEvents() {
        // Clear cart button
        if (this.clearCartBtn) {
            this.clearCartBtn.addEventListener('click', () => {
                this.clearCart();
            });
        }
        
        // Cart item events (delegated)
        if (this.cartItems) {
            this.cartItems.addEventListener('click', (e) => {
                this.handleCartItemClick(e);
            });
            
            this.cartItems.addEventListener('change', (e) => {
                this.handleCartItemChange(e);
            });
        }
        
        // Listen for cart update events
        document.addEventListener('cart:updated', (e) => {
            this.updateCartDisplay(e.detail.cart);
        });
        
        // Listen for discount/tax changes
        document.addEventListener('totals:changed', (e) => {
            this.updateTotals(e.detail);
        });
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl+D - Clear cart
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                this.clearCart();
            }
            
            // F4 - Focus on first cart item quantity
            if (e.key === 'F4') {
                e.preventDefault();
                const firstQuantityInput = this.cartItems?.querySelector('input[type="number"]');
                if (firstQuantityInput) {
                    firstQuantityInput.focus();
                    firstQuantityInput.select();
                }
            }
        });
    }
    
    async loadCartFromServer() {
        try {
            const response = await fetch('/cashier/pos/cart/items', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.updateCartDisplay(data.cart);
                this.updateCartSummary(data.subtotal, data.total_items);
            }
            
        } catch (error) {
            console.error('Failed to load cart:', error);
        }
    }
    
    updateCartDisplay(cart) {
        this.cart = cart || {};
        
        if (!this.cartItems) return;
        
        if (Object.keys(this.cart).length === 0) {
            this.cartItems.innerHTML = this.getEmptyCartHTML();
            this.updateCartCount(0);
            this.updateCartSummary(0, 0);
            return;
        }
        
        const html = Object.values(this.cart).map(item => this.getCartItemHTML(item)).join('');
        this.cartItems.innerHTML = html;
        
        const totalItems = Object.values(this.cart).reduce((sum, item) => sum + item.quantity, 0);
        this.updateCartCount(totalItems);
        
        this.calculateTotals();
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
    
    getCartItemHTML(item) {
        // Use public images as primary in production
        const imageUrl = item.image 
            ? `/images/products/${item.image.replace('products/', '')}` 
            : '/images/placeholder-product.png';
        const fallbackUrl = this.getFallbackImage(item.name);
        const subtotal = item.price * item.quantity;
        
        return `
            <div class="cart-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg" data-product-id="${item.product_id}">
                <div class="flex-shrink-0">
                    <img src="${imageUrl}" 
                         alt="${item.name}" 
                         class="w-12 h-12 rounded-md object-cover"
                         onerror="this.onerror=null; this.src='${fallbackUrl}';">
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 truncate">${item.name}</h4>
                    <p class="text-sm text-gray-500">Rp ${this.formatPrice(item.price)} each</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" 
                            class="quantity-btn decrease-btn w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600"
                            data-action="decrease">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <input type="number" 
                           class="quantity-input w-16 text-center border border-gray-300 rounded-md py-1 text-sm"
                           value="${item.quantity}" 
                           min="1" 
                           max="999"
                           data-product-id="${item.product_id}">
                    <button type="button" 
                            class="quantity-btn increase-btn w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600"
                            data-action="increase">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Rp ${this.formatPrice(subtotal)}</p>
                    <button type="button" 
                            class="remove-btn text-xs text-red-600 hover:text-red-800"
                            data-action="remove">
                        Remove
                    </button>
                </div>
            </div>
        `;
    }
    
    getEmptyCartHTML() {
        return `
            <div class="empty-cart text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v5a2 2 0 01-2 2H9a2 2 0 01-2-2v-5m6-5V6a2 2 0 00-2-2H9a2 2 0 00-2-2v1"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Add products to start a transaction</p>
            </div>
        `;
    }
    
    handleCartItemClick(e) {
        const productId = e.target.closest('.cart-item')?.dataset.productId;
        if (!productId) return;
        
        const action = e.target.closest('[data-action]')?.dataset.action;
        
        switch (action) {
            case 'increase':
                this.updateQuantity(productId, 1, 'increase');
                break;
            case 'decrease':
                this.updateQuantity(productId, 1, 'decrease');
                break;
            case 'remove':
                this.removeItem(productId);
                break;
        }
    }
    
    handleCartItemChange(e) {
        if (e.target.classList.contains('quantity-input')) {
            const productId = e.target.dataset.productId;
            const newQuantity = parseInt(e.target.value) || 1;
            this.updateQuantity(productId, newQuantity, 'set');
        }
    }
    
    async updateQuantity(productId, quantity, action = 'set') {
        try {
            let finalQuantity = quantity;
            
            if (action === 'increase') {
                const currentItem = this.cart[productId];
                finalQuantity = currentItem ? currentItem.quantity + quantity : quantity;
            } else if (action === 'decrease') {
                const currentItem = this.cart[productId];
                finalQuantity = currentItem ? Math.max(1, currentItem.quantity - quantity) : 1;
            }
            
            const response = await fetch('/cashier/pos/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: finalQuantity
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.updateCartDisplay(result.cart);
                
                // Update quantity input to reflect server value
                const quantityInput = document.querySelector(`input[data-product-id="${productId}"]`);
                if (quantityInput && result.cart[productId]) {
                    quantityInput.value = result.cart[productId].quantity;
                }
                
            } else {
                if (window.Toast) {
                    window.Toast.error(result.error || 'Failed to update quantity');
                }
                // Revert input value
                this.loadCartFromServer();
            }
            
        } catch (error) {
            console.error('Update quantity error:', error);
            if (window.Toast) {
                window.Toast.error('Failed to update quantity');
            }
        }
    }
    
    async removeItem(productId) {
        if (!confirm('Remove this item from cart?')) return;
        
        try {
            const response = await fetch('/cashier/pos/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.updateCartDisplay(result.cart);
                
                if (window.Toast) {
                    window.Toast.success('Item removed from cart');
                }
            } else {
                if (window.Toast) {
                    window.Toast.error(result.error || 'Failed to remove item');
                }
            }
            
        } catch (error) {
            console.error('Remove item error:', error);
            if (window.Toast) {
                window.Toast.error('Failed to remove item');
            }
        }
    }
    
    async clearCart() {
        if (Object.keys(this.cart).length === 0) return;
        
        if (!confirm('Clear all items from cart?')) return;
        
        try {
            const response = await fetch('/cashier/pos/cart/clear', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.updateCartDisplay({});
                
                if (window.Toast) {
                    window.Toast.success('Cart cleared');
                }
                
                // Reset payment form
                if (window.POSPayment) {
                    window.POSPayment.resetForm();
                }
            }
            
        } catch (error) {
            console.error('Clear cart error:', error);
            if (window.Toast) {
                window.Toast.error('Failed to clear cart');
            }
        }
    }
    
    calculateTotals() {
        const subtotal = Object.values(this.cart).reduce((sum, item) => {
            return sum + (item.price * item.quantity);
        }, 0);
        
        const totalItems = Object.values(this.cart).reduce((sum, item) => sum + item.quantity, 0);
        
        this.updateCartSummary(subtotal, totalItems);
        
        // Trigger totals calculation with discount and tax
        this.calculateFullTotals();
    }
    
    async calculateFullTotals() {
        try {
            const discountAmount = parseFloat(document.getElementById('discount-amount')?.value || 0);
            const discountPercent = parseFloat(document.getElementById('discount-percent')?.value || 0);
            const taxPercent = parseFloat(document.getElementById('tax-percent')?.value || 10);
            const loyaltyPointsUsed = parseInt(document.getElementById('loyalty-points-used')?.value || 0);
            const customerId = document.getElementById('customer-id')?.value || null;
            
            const response = await fetch('/cashier/pos/calculate-totals', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    discount_amount: discountAmount,
                    discount_percent: discountPercent,
                    tax_percent: taxPercent,
                    loyalty_points_used: loyaltyPointsUsed,
                    customer_id: customerId
                })
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    this.updateTotalsDisplay(result.totals);
                }
            }
            
        } catch (error) {
            console.error('Calculate totals error:', error);
        }
    }
    
    updateCartCount(count) {
        if (this.cartCount) {
            this.cartCount.textContent = count;
            
            // Add animation class
            this.cartCount.classList.add('animate-pulse');
            setTimeout(() => {
                this.cartCount.classList.remove('animate-pulse');
            }, 500);
        }
        
        // Update badge visibility
        const cartBadge = document.getElementById('cart-badge');
        if (cartBadge) {
            if (count > 0) {
                cartBadge.textContent = count;
                cartBadge.classList.remove('hidden');
            } else {
                cartBadge.classList.add('hidden');
            }
        }
    }
    
    updateCartSummary(subtotal, totalItems) {
        // Update subtotal display
        const subtotalElement = document.getElementById('cart-subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = `Rp ${this.formatPrice(subtotal)}`;
        }
        
        // Update items count
        const itemsCountElement = document.getElementById('cart-items-count');
        if (itemsCountElement) {
            itemsCountElement.textContent = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;
        }
    }
    
    updateTotalsDisplay(totals) {
        this.totals = totals;
        
        // Update individual total elements
        const elements = {
            'cart-subtotal': totals.subtotal,
            'cart-discount': totals.discount || 0,
            'cart-tax': totals.tax || 0,
            'cart-total': totals.total,
            'loyalty-discount': totals.loyalty_discount || 0
        };
        
        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = `Rp ${this.formatPrice(value)}`;
            }
        });
        
        // Update main total display
        if (this.cartTotal) {
            this.cartTotal.textContent = `Rp ${this.formatPrice(totals.total)}`;
        }
        
        // Trigger payment update
        if (window.POSPayment) {
            window.POSPayment.updateTotal(totals.total);
        }
    }
    
    updateTotals(totals) {
        this.updateTotalsDisplay(totals);
    }
    
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
    
    // Public methods
    getCart() {
        return this.cart;
    }
    
    getTotals() {
        return this.totals;
    }
    
    getItemCount() {
        return Object.values(this.cart).reduce((sum, item) => sum + item.quantity, 0);
    }
    
    isEmpty() {
        return Object.keys(this.cart).length === 0;
    }
    
    refreshCart() {
        this.loadCartFromServer();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('pos-cart-container')) {
        window.POSCart = new POSShoppingCart();
    }
});

export default POSShoppingCart;