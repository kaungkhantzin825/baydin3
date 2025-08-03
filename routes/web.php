<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\FreeBaydinController as AdminFreeBaydinController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/test', function () {
    return 'Your plain text message here';
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (not logged in)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // User Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Consultation Management
        Route::resource('consultations', ConsultationController::class)->except(['create', 'store']);
        Route::patch('consultations/{consultation}/status', [ConsultationController::class, 'updateStatus'])->name('consultations.update-status');
        Route::patch('consultations/{consultation}/assign', [ConsultationController::class, 'assignAstrologer'])->name('consultations.assign');
        
        // Financial Management
        Route::prefix('financial')->name('financial.')->group(function () {
            Route::get('deposits', [FinancialController::class, 'deposits'])->name('deposits');
            Route::post('deposits/{deposit}/approve', [FinancialController::class, 'approveDeposit'])->name('deposits.approve');
            Route::post('deposits/{deposit}/reject', [FinancialController::class, 'rejectDeposit'])->name('deposits.reject');
            Route::post('deposits/api-approve', [FinancialController::class, 'apiStyleApproval'])->name('deposits.api-approve');
            Route::post('deposits/bulk-approve', [FinancialController::class, 'bulkApprove'])->name('deposits.bulk-approve');
            Route::get('api-management', function() { return view('admin.financial.api-management'); })->name('api-management');
            Route::get('wallets', [FinancialController::class, 'wallets'])->name('wallets');
            Route::get('transactions', [FinancialController::class, 'transactions'])->name('transactions');
            Route::post('add-money', [FinancialController::class, 'addMoney'])->name('add-money');
            Route::post('deduct-money', [FinancialController::class, 'deductMoney'])->name('deduct-money');
        });
        
        // Category Management
        Route::resource('categories', AdminCategoryController::class);
        Route::post('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        
        // Free Consultation Management
        Route::resource('free-consultations', AdminFreeBaydinController::class);
        Route::post('free-consultations/{consultation}/toggle-status', [AdminFreeBaydinController::class, 'toggleStatus'])->name('free-consultations.toggle-status');
    });
});

require __DIR__.'/auth.php';
