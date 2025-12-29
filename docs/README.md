# 📚 CoffPOS Documentation

Welcome to the comprehensive documentation for CoffPOS - Modern Point of Sale System.

## 📋 **Documentation Index**

### **Core Documentation**
- **[API Documentation](./API_DOCUMENTATION.md)** - Complete REST API reference
- **[Database ERD](../DATABASE_ERD.md)** - Database structure and relationships
- **[Project Specification](../SPESIFIKASI_PROJECT_COFFPOS.md)** - Complete project specifications

### **API Reference**
- **[OpenAPI Specification](./openapi/coffpos-api.yaml)** - OpenAPI 3.0 specification
- **[Postman Collection](./postman/CoffPOS_API.postman_collection.json)** - Ready-to-use Postman collection

### **SDK & Integration**
- **[JavaScript SDK](./examples/js-sdk/coffpos-sdk.js)** - Complete JavaScript SDK
- **[React Integration](./examples/react-integration/POSComponent.jsx)** - React component example

### **Deployment & Setup**
- **[Railway Deployment](./RAILWAY_DEPLOYMENT.md)** - Deploy to Railway platform
- **[Midtrans Integration](../MIDTRANS_QUICK_START.md)** - Payment gateway setup
- **[Midtrans Testing](./MIDTRANS_SANDBOX_TESTING.md)** - Sandbox testing guide

---

## 🚀 **Quick Start**

### **1. API Authentication**
```javascript
// Initialize the API client
const api = new CoffPOSAPI('http://localhost:8000', csrfToken);

// Login
const response = await api.login('admin@coffpos.com', 'password');
```

### **2. Basic POS Operations**
```javascript
// Add item to cart
await api.addToCart(1, 2); // product_id: 1, quantity: 2

// Process transaction
const transaction = await api.processTransaction({
    customer_id: 1,
    payment: {
        method: 'cash',
        amount: 50000
    },
    tax_percent: 10
});
```

### **3. Product Management**
```javascript
// Search products
const products = await api.searchProducts('coffee', { category_id: 1 });

// Create product
const product = await api.createProduct({
    category_id: 1,
    name: 'Cappuccino',
    price: 18000,
    cost: 12600,
    stock: 30
}, imageFile);
```

---

## 📊 **API Overview**

### **Base URL**
- **Development**: `http://localhost:8000`
- **Production**: `https://your-domain.com`

### **Authentication**
- **Session-based** for web interface
- **Sanctum tokens** for API access

### **Main Endpoints**

| Category | Endpoint | Description |
|----------|----------|-------------|
| **Authentication** | `POST /login` | User login |
| **Dashboard** | `GET /admin/dashboard/stats` | Dashboard statistics |
| **Products** | `GET /admin/products` | List products |
| **POS** | `POST /cashier/pos/cart/add` | Add to cart |
| **Transactions** | `POST /cashier/pos/process-transaction` | Process transaction |
| **Midtrans** | `POST /cashier/pos/midtrans/create-token` | Create payment token |

---

## 🔐 **User Roles & Permissions**

### **Admin**
- Full system access
- User management
- All reports and analytics
- System configuration

### **Manager**
- Business operations
- Product & inventory management
- Customer management
- Financial reports
- No user management access

### **Cashier**
- POS operations only
- Transaction processing
- Customer lookup
- Basic reporting (own transactions)

---

## 💳 **Payment Methods**

### **Supported Methods**
- **Cash** - Traditional cash payments
- **Debit** - Debit card payments
- **Credit** - Credit card payments
- **E-wallet** - Digital wallet payments
- **QRIS** - QR code payments
- **Digital** - Midtrans integrated payments

### **Midtrans Integration**
CoffPOS integrates with Midtrans for digital payments:
- QRIS payments
- Virtual Account
- Credit/Debit cards
- E-wallet (GoPay, OVO, DANA)
- Bank transfers

---

## 📱 **Frontend Integration**

### **React Example**
```jsx
import { CoffPOSAPI, CoffPOSCart } from './coffpos-sdk';

const POSComponent = () => {
    const [api] = useState(() => new CoffPOSAPI(baseURL, csrfToken));
    const [cart] = useState(() => new CoffPOSCart(api));
    
    // Component implementation...
};
```

### **Vanilla JavaScript**
```javascript
// Initialize
const api = new CoffPOSAPI('http://localhost:8000', csrfToken);

// Add to cart
document.getElementById('add-to-cart').addEventListener('click', async () => {
    await api.addToCart(productId, quantity);
});
```

---

## 🧪 **Testing**

### **Postman Collection**
1. Import the [Postman collection](./postman/CoffPOS_API.postman_collection.json)
2. Set environment variables:
   - `base_url`: Your CoffPOS URL
   - `csrf_token`: CSRF token from login
3. Run the collection tests

### **API Testing**
```bash
# Test authentication
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@coffpos.com","password":"password"}'

# Test product search
curl -X GET "http://localhost:8000/admin/products/search/api?q=coffee" \
  -H "Accept: application/json"
```

---

## 🔧 **Configuration**

### **Environment Variables**
```env
# Application
APP_NAME="CoffPOS"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=coffpos

# Midtrans
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false
```

### **API Configuration**
```javascript
const config = {
    baseURL: process.env.REACT_APP_COFFPOS_URL,
    timeout: 30000,
    retries: 3
};

const api = new CoffPOSAPI(config.baseURL, csrfToken, config);
```

---

## 📈 **Performance**

### **API Performance**
- **Response Time**: < 200ms for most endpoints
- **Rate Limiting**: 60 requests/minute per IP
- **Caching**: Redis caching for frequently accessed data
- **Database**: Optimized queries with proper indexing

### **Best Practices**
1. **Pagination**: Use pagination for large datasets
2. **Caching**: Cache frequently accessed data
3. **Batch Operations**: Use bulk endpoints when available
4. **Error Handling**: Implement proper error handling
5. **Rate Limiting**: Respect API rate limits

---

## 🐛 **Troubleshooting**

### **Common Issues**

#### **Authentication Errors**
```javascript
// Check CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Ensure proper headers
const headers = {
    'X-CSRF-TOKEN': csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};
```

#### **Cart Issues**
```javascript
// Force clear cart if stuck
await api.post('/cashier/pos/cart/force-clear');

// Sync cart with server
const cartData = await api.getCartItems();
```

#### **Midtrans Configuration**
```javascript
// Test Midtrans config
const config = await api.testMidtransConfig();
console.log('Midtrans config:', config);
```

### **Debug Mode**
Enable debug mode for detailed error information:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

---

## 📞 **Support**

### **Getting Help**
- **Documentation**: Check this documentation first
- **GitHub Issues**: Create an issue for bugs or feature requests
- **Email Support**: contact@coffpos.com
- **Community**: Join our Discord server

### **Contributing**
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

### **Reporting Bugs**
When reporting bugs, please include:
- CoffPOS version
- Environment (development/production)
- Steps to reproduce
- Expected vs actual behavior
- Error messages or logs

---

## 📄 **License**

CoffPOS is open-source software licensed under the [MIT License](../LICENSE).

---

## 🔄 **Changelog**

### **Version 1.0.0** (December 26, 2025)
- Initial release
- Complete POS functionality
- Midtrans integration
- Comprehensive API
- React integration examples
- Full documentation

---

## 🚀 **Roadmap**

### **Upcoming Features**
- **Multi-store Support** - Manage multiple store locations
- **Advanced Analytics** - AI-powered business insights
- **Mobile App** - Native iOS/Android applications
- **Inventory Forecasting** - Predictive stock management
- **Advanced Reporting** - Custom report builder

### **API Enhancements**
- **GraphQL API** - Alternative to REST API
- **Webhooks** - Real-time event notifications
- **Bulk Operations** - Enhanced batch processing
- **Advanced Filtering** - More sophisticated search and filter options

---

**Last Updated**: December 26, 2025  
**Documentation Version**: 1.0.0  
**CoffPOS Version**: 1.0.0

---

*For the most up-to-date information, please check the [GitHub repository](https://github.com/your-username/coffpos) or contact our support team.*