# Money Cut API Documentation

## 🎯 Overview
This API allows users to purchase categories by deducting money from their wallet balance. It checks if the user has sufficient funds and deducts the category price if available.

## 📋 API Endpoint
```
POST /api/user/money-cut
```

## 🔐 Authentication
Requires Bearer token authentication (Laravel Sanctum)

## 📝 Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `category_id` | integer | Yes | ID of the category to purchase |
| `description` | string | No | Custom description for the transaction |

## 📤 Request Example

### Headers
```
Content-Type: application/json
Authorization: Bearer YOUR_API_TOKEN
Accept: application/json
```

### Body
```json
{
    "category_id": 10,
    "description": "Love consultation purchase"
}
```

## 📥 Response Examples

### ✅ Success Response (200)
```json
{
    "status": true,
    "message": "Payment successful! Money deducted from your account.",
    "data": {
        "transaction_id": 123,
        "category": {
            "id": 10,
            "name": "Love & Relationships",
            "price": 25.00
        },
        "payment": {
            "amount_deducted": 25.00,
            "previous_balance": 10000.00,
            "new_balance": 9975.00
        },
        "timestamp": "2025-08-03T19:30:00.000000Z"
    }
}
```

### ❌ Insufficient Balance (400)
```json
{
    "status": false,
    "message": "Money is not sufficient please add it",
    "error_code": "INSUFFICIENT_BALANCE",
    "data": {
        "current_balance": 100.00,
        "required_amount": 25.00,
        "shortage": -75.00
    }
}
```

### ❌ Category Not Available (400)
```json
{
    "status": false,
    "message": "This category is free or not available for purchase.",
    "error_code": "CATEGORY_FREE"
}
```

### ❌ Validation Error (422)
```json
{
    "message": "The category id field is required.",
    "errors": {
        "category_id": [
            "The category id field is required."
        ]
    }
}
```

## 🧪 cURL Examples

### Test with Sufficient Balance
```bash
curl -X POST "http://your-domain/api/user/money-cut" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json" \
  -d '{
    "category_id": 15,
    "description": "General Reading consultation"
  }'
```

### Test with Insufficient Balance
```bash
curl -X POST "http://your-domain/api/user/money-cut" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json" \
  -d '{
    "category_id": 11,
    "description": "Career consultation attempt"
  }'
```

### Check User Balance First
```bash
curl -X GET "http://your-domain/api/user/user_money_list" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"
```

### Get Available Categories
```bash
curl -X GET "http://your-domain/api/categories" \
  -H "Accept: application/json"
```

## 🧪 Test Data Available

### Test Users
1. **Rich User**
   - Phone: `9999999999`
   - Password: `password`
   - Balance: $10,000.00

2. **Poor User**
   - Phone: `8888888888`
   - Password: `password`
   - Balance: $100.00

### Available Categories with Prices
| ID | Name | Price |
|----|------|-------|
| 10 | Love & Relationships | $25.00 |
| 11 | Career & Finance | $30.00 |
| 12 | Health & Wellness | $20.00 |
| 13 | Family & Children | $22.00 |
| 14 | Spiritual Growth | $35.00 |
| 15 | General Reading | $15.00 |

## 🔄 Complete Test Flow

### 1. Login and Get Token
```bash
curl -X POST "http://your-domain/api/login" \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "9999999999",
    "password": "password",
    "device_name": "test-device"
  }'
```

### 2. Check Balance
```bash
curl -X GET "http://your-domain/api/user/user_money_list" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 3. Purchase Category (Success)
```bash
curl -X POST "http://your-domain/api/user/money-cut" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "category_id": 15,
    "description": "Test purchase"
  }'
```

### 4. Test Insufficient Balance (Login as Poor User)
```bash
# Login as poor user first
curl -X POST "http://your-domain/api/login" \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "8888888888",
    "password": "password",
    "device_name": "test-device"
  }'

# Try to purchase expensive category
curl -X POST "http://your-domain/api/user/money-cut" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer POOR_USER_TOKEN" \
  -d '{
    "category_id": 14,
    "description": "Expensive purchase attempt"
  }'
```

## 💾 Database Changes

### Money History Table
All transactions are saved in the `money_history` table with:
- **Negative amount** for deductions
- **Transaction type**: `category_purchase`
- **Description**: Category name or custom description
- **User ID**: Who made the purchase
- **Timestamp**: When the transaction occurred

### User Money Table
- User balance is updated in real-time
- Uses database locking to prevent race conditions
- Atomic transactions ensure data consistency

## 🔒 Security Features

- **Authentication Required**: Must be logged in
- **Database Locking**: Prevents concurrent balance modifications
- **Transaction Safety**: All operations are atomic
- **Input Validation**: Validates category existence and user permissions
- **Balance Verification**: Double-checks balance before deduction

## 🎉 Success!
Your money cut API is ready to handle category purchases with proper balance checking and transaction history!