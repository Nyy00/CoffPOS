import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/reports-pdf.css',
                'resources/js/app.js',
                // Components
                'resources/js/components/toast.js',
                'resources/js/components/image-preview.js',
                // Admin
                'resources/js/admin/products-search.js',
                'resources/js/admin/customers-search.js',
                'resources/js/admin/dashboard-charts.js',
                // POS
                'resources/js/pos/products-search.js',
                'resources/js/pos/shopping-cart.js',
                'resources/js/pos/payment.js'
            ],
            refresh: true,
        }),
    ],
});
