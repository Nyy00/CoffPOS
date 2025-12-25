/**
 * Dashboard Chart Debugging Helper
 * Gunakan di browser console untuk debugging
 */

window.DashboardDebug = {
    /**
     * Check if Chart.js is loaded
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
     * Check chart data
     */
    checkData: function() {
        console.log('📊 Chart Data:');
        console.table(window.chartDataGlobal || {});
    },

    /**
     * Check specific chart data
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
     * Check canvas elements
     */
    checkCanvases: function() {
        const canvases = document.querySelectorAll('canvas');
        console.log(`Found ${canvases.length} canvas elements:`, canvases);
        
        canvases.forEach((canvas, index) => {
            console.log(`  Canvas ${index + 1}: id="${canvas.id}", width=${canvas.width}, height=${canvas.height}`);
        });
    },

    /**
     * Get all active charts
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
     * Test data format
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
     * Full dashboard health check
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
     * Reload charts with new data
     */
    reloadCharts: async function() {
        console.log('🔄 Reloading charts...');
        
        // Check if reload function exists
        if (window.DashboardCharts && window.DashboardCharts.destroyAllCharts) {
            window.DashboardCharts.destroyAllCharts();
            await window.DashboardCharts.initializeCharts();
            console.log('✅ Charts reloaded');
        } else {
            console.warn('⚠️ Dashboard charts manager not found');
        }
    },

    /**
     * Export chart data as JSON
     */
    exportData: function() {
        const data = window.chartDataGlobal;
        const json = JSON.stringify(data, null, 2);
        console.log('📄 Chart data as JSON:');
        console.log(json);
        return json;
    },

    /**
     * Help menu
     */
    help: function() {
        console.clear();
        console.log('%c📊 Dashboard Debug Commands', 'font-size: 16px; font-weight: bold; color: #3B82F6;');
        console.log('');
        console.log('Available commands:');
        console.log('  • DashboardDebug.checkChartJs()      - Check if Chart.js is loaded');
        console.log('  • DashboardDebug.checkData()         - Display all chart data');
        console.log('  • DashboardDebug.checkChartData(type) - Check specific chart data (revenue, topProducts, etc)');
        console.log('  • DashboardDebug.checkCanvases()     - List all canvas elements');
        console.log('  • DashboardDebug.getActiveCharts()   - Get active Chart instances');
        console.log('  • DashboardDebug.testDataFormat()    - Check data format');
        console.log('  • DashboardDebug.healthCheck()       - Full health check');
        console.log('  • DashboardDebug.reloadCharts()      - Reload charts');
        console.log('  • DashboardDebug.exportData()        - Export data as JSON');
        console.log('  • DashboardDebug.help()              - Show this help');
        console.log('');
        console.log('Example: DashboardDebug.checkChartData("revenue")');
    }
};

// Run health check on page load
window.addEventListener('load', function() {
    setTimeout(() => {
        console.log('');
        console.log('%c💡 Tip: Type DashboardDebug.help() to see debug commands', 'color: #10B981; font-style: italic;');
    }, 1000);
});

export default window.DashboardDebug;