@extends('admin.layouts.app')

@section('title', 'Deposit Requests')
@section('page-title', 'Deposit Requests')
@section('page-description', 'Manage user deposit requests and approvals')

@section('content')
<div class="space-y-6">
    <!-- Filters and Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <!-- Filters -->
            <form method="GET" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Types</option>
                    <option value="bank_transfer" {{ request('type') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="mobile_payment" {{ request('type') === 'mobile_payment' ? 'selected' : '' }}>Mobile Payment</option>
                    <option value="cash" {{ request('type') === 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                
                @if(request()->hasAny(['status', 'type']))
                    <a href="{{ route('admin.financial.deposits') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
            </form>
            
            <!-- Bulk Actions -->
            <div class="flex space-x-3">
                <button onclick="showBulkApproveModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700" id="bulkApproveBtn" style="display: none;">
                    <i class="fas fa-check-double mr-2"></i>Bulk Approve
                </button>
                
                <button onclick="showApiApprovalModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-cogs mr-2"></i>API Approval
                </button>
            </div>
        </div>
    </div>

    <!-- Deposits Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proof</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($deposits as $deposit)
                    <tr class="hover:bg-gray-50 {{ $deposit->status === 'pending' ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($deposit->status === 'pending')
                                <input type="checkbox" class="deposit-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500" value="{{ $deposit->id }}">
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900">#{{ $deposit->id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr($deposit->user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $deposit->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $deposit->user->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-semibold text-green-600">${{ number_format($deposit->money, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $deposit->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($deposit->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($deposit->status === 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $deposit->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($deposit->image)
                                <button onclick="showImage('{{ asset('storage/' . $deposit->image) }}')" 
                                        class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-image mr-1"></i>View
                                </button>
                            @else
                                <span class="text-gray-400">No proof</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($deposit->status === 'pending')
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('admin.financial.deposits.approve', $deposit) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900"
                                                onclick="return confirm('Approve this deposit?')">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                    </form>
                                    
                                    <button onclick="showRejectModal({{ $deposit->id }})" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-times mr-1"></i>Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-gray-400">{{ ucfirst($deposit->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            No deposit requests found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($deposits->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $deposits->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-4 max-w-2xl max-h-full overflow-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Payment Proof</h3>
            <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <img id="modalImage" src="" alt="Payment Proof" class="max-w-full h-auto">
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Reject Deposit</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                <textarea name="reason" 
                          rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                          placeholder="Enter reason for rejection..."
                          required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeRejectModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Reject Deposit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- API Approval Modal -->
<div id="apiApprovalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-cogs text-blue-500 mr-2"></i>API-Style Approval
        </h3>
        <p class="text-sm text-gray-600 mb-4">This uses the same logic as the <code>/api/user/moneyapprove</code> endpoint</p>
        
        <form method="POST" action="{{ route('admin.financial.deposits.api-approve') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deposit ID</label>
                <input type="number" 
                       name="in_money_id" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                       placeholder="Enter deposit ID..."
                       required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                <select name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                        onchange="toggleReasonField(this.value)"
                        required>
                    <option value="">Select Action</option>
                    <option value="approved">Approve</option>
                    <option value="rejected">Reject</option>
                </select>
            </div>
            
            <div class="mb-4" id="reasonField" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                <textarea name="reason" 
                          rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                          placeholder="Enter reason for rejection..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeApiApprovalModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-paper-plane mr-2"></i>Process Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div id="bulkApproveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-check-double text-green-500 mr-2"></i>Bulk Approve Deposits
        </h3>
        <p class="text-sm text-gray-600 mb-4">Are you sure you want to approve <span id="selectedCount">0</span> selected deposits?</p>
        
        <form method="POST" action="{{ route('admin.financial.deposits.bulk-approve') }}" id="bulkApproveForm">
            @csrf
            <input type="hidden" name="deposit_ids" id="selectedDepositIds">
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeBulkApproveModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-check-double mr-2"></i>Approve All Selected
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showImage(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function showRejectModal(depositId) {
    document.getElementById('rejectForm').action = `/admin/financial/deposits/${depositId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function showApiApprovalModal() {
    document.getElementById('apiApprovalModal').classList.remove('hidden');
}

function closeApiApprovalModal() {
    document.getElementById('apiApprovalModal').classList.add('hidden');
}

function toggleReasonField(status) {
    const reasonField = document.getElementById('reasonField');
    if (status === 'rejected') {
        reasonField.style.display = 'block';
        reasonField.querySelector('textarea').required = true;
    } else {
        reasonField.style.display = 'none';
        reasonField.querySelector('textarea').required = false;
    }
}

function showBulkApproveModal() {
    const selectedCheckboxes = document.querySelectorAll('.deposit-checkbox:checked');
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        alert('Please select at least one deposit to approve.');
        return;
    }
    
    document.getElementById('selectedCount').textContent = selectedIds.length;
    document.getElementById('selectedDepositIds').value = JSON.stringify(selectedIds);
    document.getElementById('bulkApproveModal').classList.remove('hidden');
}

function closeBulkApproveModal() {
    document.getElementById('bulkApproveModal').classList.add('hidden');
}

// Handle select all functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const depositCheckboxes = document.querySelectorAll('.deposit-checkbox');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    
    selectAllCheckbox.addEventListener('change', function() {
        depositCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkApproveButton();
    });
    
    depositCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkApproveButton();
            
            // Update select all checkbox
            const checkedCount = document.querySelectorAll('.deposit-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === depositCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < depositCheckboxes.length;
        });
    });
    
    function updateBulkApproveButton() {
        const checkedCount = document.querySelectorAll('.deposit-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkApproveBtn.style.display = 'block';
        } else {
            bulkApproveBtn.style.display = 'none';
        }
    }
});
</script>
@endsection