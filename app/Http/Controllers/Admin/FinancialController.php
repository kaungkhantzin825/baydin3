<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InMoney;
use App\Models\UserMoney;
use App\Models\MoneyHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function deposits(Request $request)
    {
        $query = InMoney::with('user');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $deposits = $query->latest()->paginate(15);
        
        return view('admin.financial.deposits', compact('deposits'));
    }
    
    public function approveDeposit(InMoney $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'This deposit has already been processed!');
        }
        
        return DB::transaction(function () use ($deposit) {
            // Update deposit status
            $deposit->update(['status' => 'approved']);
            
            // Add money to user wallet (same logic as API)
            $userMoney = UserMoney::firstOrNew(['user_id' => $deposit->user_id]);
            $userMoney->money = ($userMoney->money ?? 0) + $deposit->money;
            $userMoney->save();
            
            // Create money history record
            MoneyHistory::create([
                'user_id' => $deposit->user_id,
                'money' => $deposit->money,
                'description' => 'Deposit approved by admin via web panel',
                'type' => 'deposit'
            ]);
            
            return back()->with('success', 'Deposit approved successfully! New balance: $' . number_format($userMoney->money, 2));
        });
    }
    
    public function rejectDeposit(Request $request, InMoney $deposit)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);
        
        $deposit->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);
        
        return back()->with('success', 'Deposit rejected successfully!');
    }
    
    public function wallets(Request $request)
    {
        $query = UserMoney::with('user');
        
        // Search by user name or phone
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        
        $wallets = $query->orderBy('money', 'desc')->paginate(15);
        
        return view('admin.financial.wallets', compact('wallets'));
    }
    
    public function transactions(Request $request)
    {
        $query = MoneyHistory::with('user');
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $transactions = $query->latest()->paginate(15);
        
        return view('admin.financial.transactions', compact('transactions'));
    }
    
    public function addMoney(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255'
        ]);
        
        DB::transaction(function () use ($request) {
            // Add money to user wallet
            $userMoney = UserMoney::firstOrCreate(
                ['user_id' => $request->user_id],
                ['money' => 0]
            );
            
            $userMoney->increment('money', $request->amount);
            
            // Create money history record
            MoneyHistory::create([
                'user_id' => $request->user_id,
                'money' => $request->amount,
                'description' => $request->description,
                'type' => 'admin_credit'
            ]);
        });
        
        return back()->with('success', 'Money added successfully!');
    }
    
    public function deductMoney(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255'
        ]);
        
        $userMoney = UserMoney::where('user_id', $request->user_id)->first();
        
        if (!$userMoney || $userMoney->money < $request->amount) {
            return back()->with('error', 'Insufficient balance!');
        }
        
        DB::transaction(function () use ($request, $userMoney) {
            // Deduct money from user wallet
            $userMoney->decrement('money', $request->amount);
            
            // Create money history record
            MoneyHistory::create([
                'user_id' => $request->user_id,
                'money' => -$request->amount,
                'description' => $request->description,
                'type' => 'admin_debit'
            ]);
        });
        
        return back()->with('success', 'Money deducted successfully!');
    }
    
    /**
     * API-style money approval (same as /api/user/moneyapprove)
     */
    public function apiStyleApproval(Request $request)
    {
        $request->validate([
            'in_money_id' => 'required|exists:in_money,id',
            'status' => 'required|in:approved,rejected',
            'reason' => 'required_if:status,rejected|string|max:255'
        ]);

        return DB::transaction(function () use ($request) {
            // Find the money request
            $inMoney = InMoney::lockForUpdate()->findOrFail($request->in_money_id);

            // Check if already processed
            if ($inMoney->status !== 'pending') {
                return back()->with('error', 'This request has already been processed.');
            }

            // Update the status
            $inMoney->status = $request->status;
            if ($request->status === 'rejected' && $request->reason) {
                $inMoney->rejection_reason = $request->reason;
            }
            $inMoney->save();

            $newBalance = null;
            // If approved, update user's balance
            if ($request->status === 'approved') {
                $userMoney = UserMoney::firstOrNew(['user_id' => $inMoney->user_id]);
                $userMoney->money = ($userMoney->money ?? 0) + $inMoney->money;
                $userMoney->save();
                $newBalance = $userMoney->money;
                
                // Create money history record
                MoneyHistory::create([
                    'user_id' => $inMoney->user_id,
                    'money' => $inMoney->money,
                    'description' => 'Deposit approved by admin',
                    'type' => 'deposit'
                ]);
            }

            $message = 'Money request ' . $request->status . ' successfully';
            if ($newBalance) {
                $message .= '. New balance: $' . number_format($newBalance, 2);
            }

            return back()->with('success', $message);
        });
    }
    
    /**
     * Bulk approve multiple deposits
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'deposit_ids' => 'required|array',
            'deposit_ids.*' => 'exists:in_money,id'
        ]);
        
        $approved = 0;
        $errors = [];
        
        foreach ($request->deposit_ids as $depositId) {
            try {
                $deposit = InMoney::findOrFail($depositId);
                if ($deposit->status === 'pending') {
                    DB::transaction(function () use ($deposit) {
                        $deposit->update(['status' => 'approved']);
                        
                        $userMoney = UserMoney::firstOrNew(['user_id' => $deposit->user_id]);
                        $userMoney->money = ($userMoney->money ?? 0) + $deposit->money;
                        $userMoney->save();
                        
                        MoneyHistory::create([
                            'user_id' => $deposit->user_id,
                            'money' => $deposit->money,
                            'description' => 'Deposit approved by admin (bulk approval)',
                            'type' => 'deposit'
                        ]);
                    });
                    $approved++;
                } else {
                    $errors[] = "Deposit #{$depositId} already processed";
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing deposit #{$depositId}: " . $e->getMessage();
            }
        }
        
        $message = "Successfully approved {$approved} deposits.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }
        
        return back()->with('success', $message);
    }
}