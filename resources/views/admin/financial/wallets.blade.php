@extends('admin.layouts.app')

@section('title', 'User Wallets')
@section('page-title', 'User Wallets')
@section('page-description', 'Manage user wallet balances and transactions')

@section('content')
<div class="space-y-6">
    <!-- Search and Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <!-- Search -->
            <form method="GET" class="flex space-x-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by name or phone..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                
                @if(request('search'))
                    <a href="{{ route('admin.financial.wallets') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
            </form>
            
            <!-- Quick Actions -->
            <div class="flex space-x-3">
                <button onclick="showAddMoneyModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>Add Money
                </button>
                
                <button onclick="showDeductMoneyModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-minus mr-2"></i>Deduct Money
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Wallets</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $wallets->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Balance</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($wallets->sum('money'), 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Average Balance</p>
                    <p class="text-2xl font-bold text-gray-900">${{ $wallets->count() > 0 ? number_format($wallets->sum('money') / $wallets->count(), 2) : '0.00' }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Wallets</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $wallets->where('money', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallets Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($wallets as $wallet)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr($wallet->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $wallet->user->name ?? 'Unknown User' }}</div>
                                    <div class="text-sm text-gray-500">{{ $wallet->user->phone ?? 'No phone' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-semibold {{ $wallet->money > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                ${{ number_format($wallet->money, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($wallet->user->role === 'admin') bg-red-100 text-red-800
                                @elseif($wallet->user->role === 'astrology') bg-purple-100 text-purple-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($wallet->user->role ?? 'customer') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $wallet->updated_at ? $wallet->updated_at->format('M d, Y H:i') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showAddMoneyModal({{ $wallet->user_id }}, '{{ $wallet->user->name ?? 'User' }}')" 
                                        class="text-green-600 hover:text-green-900" title="Add Money">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                
                                <button onclick="showDeductMoneyModal({{ $wallet->user_id }}, '{{ $wallet->user->name ?? 'User' }}', {{ $wallet->money }})" 
                                        class="text-red-600 hover:text-red-900" title="Deduct Money">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                                
                                <a href="{{ route('admin.financial.transactions', ['user_id' => $wallet->user_id]) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View Transactions">
                                    <i class="fas fa-history"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No wallets found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($wallets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $wallets->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add Money Modal -->
<div id="addMoneyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-plus-circle text-green-500 mr-2"></i>Add Money
        </h3>
        <p class="text-sm text-gray-600 mb-4">Add money to <span id="addMoneyUserName">user</span>'s wallet</p>
        
        <form method="POST" action="{{ route('admin.financial.add-money') }}">
            @csrf
            <input type="hidden" name="user_id" id="addMoneyUserId">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" 
                           name="amount" 
                           step="0.01"
                           min="0.01"
                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                           placeholder="0.00"
                           required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <input type="text" 
                       name="description" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                       placeholder="Reason for adding money..."
                       required>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeAddMoneyModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>Add Money
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Deduct Money Modal -->
<div id="deductMoneyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-minus-circle text-red-500 mr-2"></i>Deduct Money
        </h3>
        <p class="text-sm text-gray-600 mb-4">Deduct money from <span id="deductMoneyUserName">user</span>'s wallet</p>
        <p class="text-sm text-blue-600 mb-4">Current balance: $<span id="currentBalance">0.00</span></p>
        
        <form method="POST" action="{{ route('admin.financial.deduct-money') }}">
            @csrf
            <input type="hidden" name="user_id" id="deductMoneyUserId">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" 
                           name="amount" 
                           step="0.01"
                           min="0.01"
                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                           placeholder="0.00"
                           required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <input type="text" 
                       name="description" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                       placeholder="Reason for deducting money..."
                       required>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeDeductMoneyModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-minus mr-2"></i>Deduct Money
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddMoneyModal(userId = null, userName = 'user') {
    if (userId) {
        document.getElementById('addMoneyUserId').value = userId;
        document.getElementById('addMoneyUserName').textContent = userName;
    }
    document.getElementById('addMoneyModal').classList.remove('hidden');
}

function closeAddMoneyModal() {
    document.getElementById('addMoneyModal').classList.add('hidden');
    document.getElementById('addMoneyUserId').value = '';
}

function showDeductMoneyModal(userId = null, userName = 'user', currentBalance = 0) {
    if (userId) {
        document.getElementById('deductMoneyUserId').value = userId;
        document.getElementById('deductMoneyUserName').textContent = userName;
        document.getElementById('currentBalance').textContent = parseFloat(currentBalance).toFixed(2);
    }
    document.getElementById('deductMoneyModal').classList.remove('hidden');
}

function closeDeductMoneyModal() {
    document.getElementById('deductMoneyModal').classList.add('hidden');
    document.getElementById('deductMoneyUserId').value = '';
}
</script>
@endsection