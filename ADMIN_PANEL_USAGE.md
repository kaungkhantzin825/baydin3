# Admin Panel for Money Approval API

## 🎯 Overview
This admin panel provides a web interface for the `/api/user/moneyapprove` API endpoint with enhanced features for bulk operations and better user experience.

## 🚀 Getting Started

### 1. Access the Admin Panel
- **URL**: `http://your-domain/admin/login`
- **Credentials**: 
  - Phone: `1234567890`
  - Password: `admin123`

### 2. Navigate to Financial Management
After logging in, go to:
- **Deposit Requests**: `Admin Panel → Financial → Deposit Requests`
- **API Management**: `Admin Panel → Financial → API Management`

## 💰 Money Approval Features

### Individual Approval
1. Go to **Deposit Requests** page
2. Find the deposit you want to approve/reject
3. Click the **Approve** or **Reject** button
4. For rejections, provide a reason

### API-Style Approval
1. Go to **API Management** page
2. Use the **Quick API Test** form:
   - Enter Deposit ID
   - Select Action (Approve/Reject)
   - Add reason if rejecting
   - Click **Execute API Call**

### Bulk Approval
1. Go to **Deposit Requests** page
2. Select multiple pending deposits using checkboxes
3. Click **Bulk Approve** button
4. Confirm the action

## 🔧 API Integration

### Endpoint Compatibility
The admin panel uses the same logic as your API:
- **API Endpoint**: `POST /api/user/moneyapprove`
- **Parameters**: `in_money_id`, `status`, `reason` (optional)
- **Response**: Same transaction handling and balance updates

### Database Operations
- ✅ Updates `in_money` table status
- ✅ Updates user balance in `user_money` table
- ✅ Creates history record in `money_history` table
- ✅ Uses database transactions for consistency

## 📊 Available Data

### Test Data Created
- **Pending Deposits**: 2 deposits ready for approval
- **Test Users**: Customer accounts for testing
- **Sample Transactions**: Various status examples

### Current Pending Deposits
You can test with these deposit IDs:
- Deposit #3: $100,000.00 (Customer User)
- Deposit #4: $50.00 (Test Customer 1)
- Deposit #5: $100.00 (Test Customer 2)

## 🎮 How to Test

### Test Individual Approval
1. Go to API Management page
2. Enter Deposit ID: `4`
3. Select Action: `Approve`
4. Click **Execute API Call**
5. Check that user balance is updated

### Test Bulk Approval
1. Go to Deposit Requests page
2. Select deposits #4 and #5
3. Click **Bulk Approve**
4. Verify both deposits are approved

### Test Rejection
1. Use API Management page
2. Enter Deposit ID: `3`
3. Select Action: `Reject`
4. Enter reason: "Invalid payment proof"
5. Execute the request

## 📈 Monitoring

### Statistics Available
- **Pending Requests**: Real-time count
- **Approved Today**: Daily approval count
- **Rejected Today**: Daily rejection count
- **Total Approved**: Sum of all approved amounts

### Recent Activities
- View last 10 money approval activities
- Quick approve/reject buttons
- Real-time status updates

## 🔐 Security Features

- **Role-based Access**: Only admin users can access
- **Database Locking**: Prevents concurrent modification
- **Transaction Safety**: All operations are atomic
- **Audit Trail**: Complete history of all actions

## 🛠️ Troubleshooting

### Common Issues
1. **Table not found**: Make sure migrations are run
2. **Permission denied**: Check user role is 'admin'
3. **Balance not updated**: Verify UserMoney model exists

### Debug Commands
```bash
# Check pending deposits
php artisan tinker --execute="echo App\Models\InMoney::where('status', 'pending')->count();"

# Check user balances
php artisan tinker --execute="print_r(App\Models\UserMoney::with('user')->get()->toArray());"

# Check money history
php artisan tinker --execute="print_r(App\Models\MoneyHistory::latest()->take(5)->get()->toArray());"
```

## 🎉 Success!
Your admin panel is now ready to manage money approvals with the same reliability as your API endpoint, plus enhanced bulk operations and better user experience!