/**
 * POS Products Search & Management
 * Handles product search and cart management for POS system
 */

class POSProductsSearch {
    constructor() {
        this.searchInput = document.getElementById('pos-product-search');
        this.categoryFilter = document.getElementById('pos-category-filter');
        this.productsGrid = document.getElementById('pos-products-grid');
        this.loadingIndicator = document.getElementById('pos-loading');
        
        this.searchTimeout = null;
        this.isLoading = false;
        this.currentCategory = null;
        
        this.init();
    }
    
    init() {
        if (!this.searchInput) return;
        
        this.bindSearchEvents();
        this.bindCategoryFilter();
        this.bindProductGrid();
        this.loadInitialProducts();
    }
    
    bindSearchEvents() {
        // Search input with debounce
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearch(e.target.value);
        });
        
        // Clear search button
        const clearBtn = document.getElementById('clear-product-search');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                this.clearSearch();
            });
        }
        
        // Enter key to focus on first product
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.focusFirstProduct();
            }
        });
    }
    
    bindCategoryFilter() {
        if (!this.categoryFilter) return;
        
        this.categoryFilter.addEventListener('change', (e) => {
            this.currentCategory = e.target.value || null;
            this.performSearch(this.searchInput.value);
        });
    }
    
    bindProductGrid() {
        if (!this.productsGrid) return;
        
        // Delegate click events for product cards
        this.productsGrid.addEventListener('click', (e) => {
            const productCard = e.target.closest('[data-product-id]');
            if (productCard) {
                const productId = productCard.dataset.productId;
                const quantity = e.target.closest('[data-quantity]')?.dataset.quantity || 1;
                this.addToCart(productId, parseInt(quantity));
            }
        });
        
        // Keyboard navigation
        this.productsGrid.addEventListener('keydown', (e) => {
            this.handleKeyboardNavigation(e);
        });
    }
    
    handleSearch(query) {
        // Clear previous timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        // Debounce search - wait 300ms after user stops typing
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }
    
    async performSearch(query) {
        if (this.isLoading) return;
        
        this.showLoading();
        this.isLoading = true;
        
        try {
            const params = new URLSearchParams({
                q: query || '',
                category_id: this.currentCategory || '',
                limit: 20
            });
            
            const response = await fetch(`/pos/products/search?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error('Search request failed');
            }
            
            const products = await response.json();
            this.displayProducts(products);
            
        } catch (error) {
            console.error('Product search error:', error);
            this.showError('Failed to search products. Please try again.');
        } finally {
            this.hideLoading();
            this.isLoading = false;
        }
    }
    
    displayProducts(products) {
        if (!this.productsGrid) return;
        
        if (products.length === 0) {
            this.productsGrid.innerHTML = this.getEmptyStateHTML();
            return;
        }
        
        const html = products.map(product => this.getProductCardHTML(product)).join('');
        this.productsGrid.innerHTML = html;
    }
    
    getProductCardHTML(product) {
        const imageUrl = product.image 
            ? `/storage/${product.image}` 
            : '/images/placeholder-product.png';
        
        const stockClass = product.stock < 10 ? 'text-red-600' : 'text-green-600';
        const isOutOfStock = product.stock === 0;
        
        return `
            <div class="product-card bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 ${isOutOfStock ? 'opacity-50' : 'cursor-pointer'}" 
                 data-product-id="${product.id}" 
                 tabindex="0">
                <div class="p-3">
                    <div class="aspect-w-1 aspect-h-1 mb-3">
                        <img src="${imageUrl}" 
                             alt="${product.name}" 
                             class="w-full h-24 object-cover rounded-md">
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm font-medium text-gray-900 truncate" title="${product.name}">
                            ${product.name}
                        </h3>
                        <p class="text-xs text-gray-500">${product.category?.name || 'No Category'}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-blue-600">
                                Rp ${this.formatPrice(product.price)}
                            </span>
                            <span class="text-xs ${stockClass}">
                                Stock: ${product.stock}
                            </span>
                        </div>
                        ${!isOutOfStock ? `
                            <div class="flex items-center space-x-1 mt-2">
                                <button type="button" 
                                        class="flex-1 bg-blue-600 text-white text-xs py-1.5 px-2 rounded hover:bg-blue-700 transition-colors"
                                        data-quantity="1">
                                    Add to Cart
                                </button>
                                <button type="button" 
                                        class="bg-gray-200 text-gray-700 text-xs py-1.5 px-2 rounded hover:bg-gray-300 transition-colors"
                                        data-quantity="2">
                                    +2
                                </button>
                                <button type="button" 
                                        class="bg-gray-200 text-gray-700 text-xs py-1.5 px-2 rounded hover:bg-gray-300 transition-colors"
                                        data-quantity="5">
                                    +5
                                </button>
                            </div>
                        ` : `
                            <div class="mt-2">
                                <span class="text-xs text-red-600 font-medium">Out of Stock</span>
                            </div>
                        `}
                    </div>
                </div>
            </div>
        `;
    }
    
    getEmptyStateHTML() {
        return `
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4a1 1 0 00-1-1H9a1 1 0 00-1 1v1"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or category filter.</p>
            </div>
        `;
    }
    
    async addToCart(productId, quantity = 1) {
        try {
            const response = await fetch('/pos/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Update cart UI
                if (window.POSCart) {
                    window.POSCart.updateCartDisplay(result.cart);
                }
                
                // Show success message
                if (window.Toast) {
                    window.Toast.success(`Added ${quantity} item(s) to cart`);
                }
                
                // Trigger cart update event
                document.dispatchEvent(new CustomEvent('cart:updated', {
                    detail: { cart: result.cart, totalItems: result.total_items }
                }));
                
            } else {
                if (window.Toast) {
                    window.Toast.error(result.error || 'Failed to add item to cart');
                }
            }
            
        } catch (error) {
            console.error('Add to cart error:', error);
            if (window.Toast) {
                window.Toast.error('Failed to add item to cart');
            }
        }
    }
    
    handleKeyboardNavigation(e) {
        const focusedCard = document.activeElement;
        if (!focusedCard.classList.contains('product-card')) return;
        
        let nextCard = null;
        
        switch (e.key) {
            case 'ArrowRight':
                nextCard = focusedCard.nextElementSibling;
                break;
            case 'ArrowLeft':
                nextCard = focusedCard.previousElementSibling;
                break;
            case 'ArrowDown':
                // Move to card in next row (assuming 4 cards per row)
                const cards = Array.from(this.productsGrid.querySelectorAll('.product-card'));
                const currentIndex = cards.indexOf(focusedCard);
                nextCard = cards[currentIndex + 4];
                break;
            case 'ArrowUp':
                // Move to card in previous row
                const allCards = Array.from(this.productsGrid.querySelectorAll('.product-card'));
                const index = allCards.indexOf(focusedCard);
                nextCard = allCards[index - 4];
                break;
            case 'Enter':
            case ' ':
                e.preventDefault();
                const productId = focusedCard.dataset.productId;
                this.addToCart(productId, 1);
                return;
        }
        
        if (nextCard) {
            e.preventDefault();
            nextCard.focus();
        }
    }
    
    focusFirstProduct() {
        const firstProduct = this.productsGrid.querySelector('.product-card');
        if (firstProduct) {
            firstProduct.focus();
        }
    }
    
    clearSearch() {
        this.searchInput.value = '';
        this.currentCategory = null;
        if (this.categoryFilter) {
            this.categoryFilter.value = '';
        }
        this.loadInitialProducts();
    }
    
    loadInitialProducts() {
        this.performSearch('');
    }
    
    showLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.classList.remove('hidden');
        }
    }
    
    hideLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.classList.add('hidden');
        }
    }
    
    showError(message) {
        if (window.Toast) {
            window.Toast.error(message);
        } else {
            alert(message);
        }
    }
    
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
    
    // Public methods
    refreshProducts() {
        this.performSearch(this.searchInput.value);
    }
    
    setCategory(categoryId) {
        this.currentCategory = categoryId;
        if (this.categoryFilter) {
            this.categoryFilter.value = categoryId || '';
        }
        this.performSearch(this.searchInput.value);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('pos-product-search')) {
        window.POSProductsSearch = new POSProductsSearch();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    // F1 - Focus search
    if (e.key === 'F1') {
        e.preventDefault();
        const searchInput = document.getElementById('pos-product-search');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
    
    // F3 - Clear search
    if (e.key === 'F3') {
        e.preventDefault();
        if (window.POSProductsSearch) {
            window.POSProductsSearch.clearSearch();
        }
    }
});

export default POSProductsSearch;