/**
 * Receipt Print Functionality
 * Handles receipt printing and formatting
 */

export const ReceiptPrinter = {
  /**
   * Print receipt
   * @param {string} elementId - Receipt element ID
   * @param {string} title - Receipt title
   */
  print: function(elementId, title = 'Receipt') {
    const printContent = document.getElementById(elementId);
    
    if (!printContent) {
      console.error('Receipt element not found');
      return;
    }

    const originalTitle = document.title;
    document.title = title;

    // Create a new window for printing
    const printWindow = window.open('', '', 'height=400,width=800');
    
    // Write the receipt content to the new window
    printWindow.document.write(`
      <!DOCTYPE html>
      <html>
        <head>
          <title>${title}</title>
          <link rel="stylesheet" href="/css/receipt-print.css">
          <style>
            body {
              font-family: 'Courier New', monospace;
              margin: 0;
              padding: 10px;
            }
          </style>
        </head>
        <body>
          ${printContent.innerHTML}
        </body>
      </html>
    `);

    printWindow.document.close();
    
    // Wait for content to load then print
    printWindow.onload = function() {
      printWindow.print();
      printWindow.close();
      document.title = originalTitle;
    };

    // Fallback for browsers that don't support onload
    setTimeout(() => {
      printWindow.print();
    }, 250);
  },

  /**
   * Print receipt using window.print()
   */
  printCurrent: function() {
    window.print();
  },

  /**
   * Export receipt as PDF
   * @param {string} filename - Output filename
   */
  exportPDF: function(filename = 'receipt.pdf') {
    // This would require a library like jsPDF or html2pdf
    console.log('PDF export requires additional library');
  },

  /**
   * Send receipt to printer
   */
  sendToPrinter: function(elementId) {
    const element = document.getElementById(elementId);
    
    if (!element) {
      console.error('Receipt element not found');
      return;
    }

    // Get print content
    const printContent = element.innerHTML;
    const originalContent = document.body.innerHTML;

    // Replace body with print content
    document.body.innerHTML = printContent;

    // Print
    window.print();

    // Restore original content
    document.body.innerHTML = originalContent;
  },

  /**
   * Format currency for receipt
   * @param {number} amount - Amount to format
   * @param {string} currency - Currency code (default: IDR)
   * @returns {string} Formatted currency
   */
  formatCurrency: function(amount, currency = 'IDR') {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: currency,
    }).format(amount);
  },

  /**
   * Format date for receipt
   * @param {Date} date - Date to format
   * @returns {string} Formatted date
   */
  formatDate: function(date) {
    return new Intl.DateTimeFormat('id-ID', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
    }).format(date);
  },
};

// Export for use in modules
export default ReceiptPrinter;