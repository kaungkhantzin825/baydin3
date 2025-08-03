@extends('admin.layouts.app')

@section('title', 'API Management')
@section('page-title', 'Money Approval API Management')
@section('page-description', 'Manage money approvals using the same API logic as /api/user/moneyapprove')

@section('content')
<div class="space-y-6">
    <!-- API Information Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-code text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-gray-900">API Endpoint Integration</h3>
                <p class="text-gray-600">Direct integration with <code class="bg-gray-100 px-2 py-1 rounded text-sm">/api/user/moneyapprove</code></p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">API Endpoint</h4>
                <code class="text-sm text-blue-800">POST /api/user/moneyapprove</code>
                
                <h5 class="font-semibold text-blue-900 mt-3 mb-2">Required Parameters:</h5>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• <code>in_money_id</code> - Deposit request ID</li>
                    <li>• <code>status</code> - approved or rejected</li>
                </ul>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4">
                <h4 class="font-semibold text-green-900 mb-2">Admin Panel Features</h4>
                <ul class="text-sm text-green-800 space-y-1">
                    <li>• ✅ Same transaction logic as API</li>
                    <li>• ✅ Automatic balance updates</li>
                    <li>• ✅ Money history tracking</li>
                    <li>• ✅ Bulk approval support</li>
                    <li>• ✅ Real-time status updates</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Quick API Test -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-flask text-purple-500 mr-2"></i>Quick API Test
        </h3>
        
        <form method="POST" action="{{ route('admin.financial.deposits.api-approve') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deposit ID</label>
                <input type="number" 
                       name="in_money_id" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                       placeholder="e.g., 123"
                       required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                <select name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                        required>
                    <option value="">Select Action</option>
                    <option value="approved">Approve</option>
                    <option value="rejected">Reject</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason (if rejecting)</label>
                <input type="text" 
                       name="reason" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                       placeholder="Optional rejection reason">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-paper-plane mr-2"></i>Execute API Call
                </button>
            </div>
        </form>
    </div>

    <!-- Recent API Activities -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Money Approvals</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Processed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse(\App\Models\InMoney::with('user')->latest()->take(10)->get() as $deposit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900">#{{ $deposit->id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-xs">{{ substr($deposit->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $deposit->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $deposit->user->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-green-600">${{ number_format($deposit->money, 2) }}</div>
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
                            {{ $deposit->updated_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($deposit->status === 'pending')
                                <button onclick="quickApprove({{ $deposit->id }})" 
                                        class="text-green-600 hover:text-green-900 mr-3">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="quickReject({{ $deposit->id }})" 
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <span class="text-gray-400">{{ ucfirst($deposit->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No deposit requests found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- API Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\InMoney::where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\InMoney::where('status', 'approved')->whereDate('updated_at', today())->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\InMoney::where('status', 'rejected')->whereDate('updated_at', today())->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Approved</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format(\App\Models\InMoney::where('status', 'approved')->sum('money'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function quickApprove(depositId) {
    if (confirm('Are you sure you want to approve this deposit?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.financial.deposits.api-approve") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const depositIdInput = document.createElement('input');
        depositIdInput.type = 'hidden';
        depositIdInput.name = 'in_money_id';
        depositIdInput.value = depositId;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'approved';
        
        form.appendChild(csrfToken);
        form.appendChild(depositIdInput);
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function quickReject(depositId) {
    const reason = prompt('Enter rejection reason:');
    if (reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.financial.deposits.api-approve") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const depositIdInput = document.createElement('input');
        depositIdInput.type = 'hidden';
        depositIdInput.name = 'in_money_id';
        depositIdInput.value = depositId;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'rejected';
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        
        form.appendChild(csrfToken);
        form.appendChild(depositIdInput);
        form.appendChild(statusInput);
        form.appendChild(reasonInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection