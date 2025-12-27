/**
 * Dashboard Chart Debugging Helper
 * File ini digunakan untuk membantu proses debugging chart pada dashboard
 * melalui browser console (DevTools).
 */

window.DashboardDebug = {
    /**
     * Mengecek apakah library Chart.js sudah berhasil dimuat
     * dan menampilkan versinya jika tersedia
     */
    checkChartJs: function() {
        if (typeof Chart !== 'undefined') {
            console.log('✅ Chart.js is loaded');
            console.log('Chart version:', Chart.version || 'Unknown');
        } else {
            console.error('❌ Chart.js is NOT loaded');
        }
    },

    /**
     * Menampilkan seluruh data chart yang tersedia
     * dari variabel global window.chartDataGlobal
     */
    checkData: function() {
        console.log('📊 Chart Data:');
        console.table(window.chartDataGlobal || {});
    },

    /**
     * Mengecek data chart berdasarkan tipe tertentu
     * Contoh: revenue, topProducts, dll
     * @param {string} type - tipe data chart
     */
    checkChartData: function(type) {
        const data = window.chartDataGlobal?.[type];
        if (data) {
            console.log(`📊 ${type} data:`, data);
            console.table(data);
        } else {
            console.warn(`⚠️ No data found for ${type}`);
        }
    },

    /**
     * Mengecek semua elemen <canvas> yang ada di halaman
     * Digunakan untuk memastikan canvas chart tersedia
     */
    checkCanvases: function() {
        const canvases = document.querySelectorAll('canvas');
        console.log(`Found ${canvases.length} canvas elements:`, canvases);
        
        // Menampilkan detail setiap canvas
        canvases.forEach((canvas, index) => {
            console.log(
                `Canvas ${index + 1}: id="${canvas.id}", width=${canvas.width}, height=${canvas.height}`
            );
        });
    },

    /**
     * Menampilkan semua instance chart yang sedang aktif
     * Berguna untuk debugging chart ganda atau memory leak
     */
    getActiveCharts: function() {
        if (window.Chart && window.Chart.instances) {
            console.log('Active Chart instances:', window.Chart.instances);
            return window.Chart.instances;
        } else {
            console.log('No active Chart instances found');
            return null;
        }
    },

    /**
     * Menguji format data chart (khusus contoh data revenue)
     * Digunakan untuk memastikan struktur data sesuai
     */
    testDataFormat: function() {
        const sampleRevenue = window.chartDataGlobal?.revenue?.[0];
        if (sampleRevenue) {
            console.log('Sample revenue data format:', sampleRevenue);
            console.log('  - label:', sampleRevenue.label);
            console.log('  - value:', sampleRevenue.value);
            console.log('  - date:', sampleRevenue.date);
            console.log('  - revenue:', sampleRevenue.revenue);
        }
    },

    /**
     * Menjalankan pengecekan kesehatan dashboard secara keseluruhan
     * Meliputi Chart.js, canvas, data, dan format data
     */
    healthCheck: function() {
        console.log('🏥 Dashboard Health Check:');
        console.log('----------------------------');
        
        this.checkChartJs();
        console.log('');
        
        this.checkCanvases();
        console.log('');
        
        this.checkData();
        console.log('');
        
        this.testDataFormat();
        console.log('');
        
        console.log('✅ Health check complete');
    },

    /**
     * Memuat ulang seluruh chart pada dashboard
     * Digunakan ketika data berubah atau perlu refresh chart
     */
    reloadCharts: async function() {
        console.log('🔄 Reloading charts...');
        
        // Mengecek apakah dashboard chart manager tersedia
        if (window.DashboardCharts && window.DashboardCharts.destroyAllCharts) {
            window.DashboardCharts.destroyAllCharts();
            await window.DashboardCharts.initializeCharts();
            console.log('✅ Charts reloaded');
        } else {
            console.warn('⚠️ Dashboard charts manager not found');
        }
    },

    /**
     * Mengekspor data chart dalam format JSON
     * Berguna untuk debugging atau dokumentasi
     */
    exportData: function() {
        const data = window.chartDataGlobal;
        const json = JSON.stringify(data, null, 2);
        console.log('📄 Chart data as JSON:');
        console.log(json);
        return json;
    },

    /**
     * Menampilkan daftar perintah debugging yang tersedia
     * di browser console
     */
    help: function() {
        console.clear();
        console.log(
            '%c📊 Dashboard Debug Commands',
            'font-size: 16px; font-weight: bold; color: #3B82F6;'
        );
        console.log('');
        console.log('Available commands:');
        console.log('  • DashboardDebug.checkChartJs()       - Check if Chart.js is loaded');
        console.log('  • DashboardDebug.checkData()          - Display all chart data');
        console.log('  • DashboardDebug.checkChartData(type) - Check specific chart data');
        console.log('  • DashboardDebug.checkCanvases()      - List all canvas elements');
        console.log('  • DashboardDebug.getActiveCharts()    - Get active Chart instances');
        console.log('  • DashboardDebug.testDataFormat()     - Check data format');
        console.log('  • DashboardDebug.healthCheck()        - Full health check');
        console.log('  • DashboardDebug.reloadCharts()       - Reload charts');
        console.log('  • DashboardDebug.exportData()         - Export data as JSON');
        console.log('  • DashboardDebug.help()               - Show this help');
        console.log('');
        console.log('Example: DashboardDebug.checkChartData("revenue")');
    }
};

// Event listener yang dijalankan setelah halaman selesai dimuat
// Menampilkan tips penggunaan debugging di console
window.addEventListener('load', function() {
    setTimeout(() => {
        console.log('');
        console.log(
            '%c💡 Tip: Type DashboardDebug.help() to see debug commands',
            'color: #10B981; font-style: italic;'
        );
    }, 1000);
});

export default window.DashboardDebug;
