@extends('admin.layouts.app')

@section('title', 'Consultations Management')
@section('page-title', 'Consultations Management')
@section('page-description', 'Manage all consultation requests and assignments')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <form method="GET" class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-4">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Search consultations..." 
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in progress" {{ request('status') === 'in progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            
            <select name="astrologer_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="">All Astrologers</option>
                @foreach($astrologers as $astrologer)
                    <option value="{{ $astrologer->id }}" {{ request('astrologer_id') == $astrologer->id ? 'selected' : '' }}>
                        {{ $astrologer->name }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            
            @if(request()->hasAny(['search', 'status', 'astrologer_id']))
                <a href="{{ route('admin.consultations.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Consultations Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Astrologer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($consultations as $consultation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $consultation->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-xs">{{ substr($consultation->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $consultation->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $consultation->user->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $consultation->description }}">
                                {{ Str::limit($consultation->description, 50) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($consultation->photos) <i class="fas fa-image mr-1"></i> @endif
                                @if($consultation->voice) <i class="fas fa-microphone mr-1"></i> @endif
                                @if($consultation->video) <i class="fas fa-video mr-1"></i> @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($consultation->astrologer)
                                <div class="text-sm font-medium text-gray-900">{{ $consultation->astrologer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $consultation->astrologer->phone }}</div>
                            @else
                                <span class="text-red-500 text-sm">Not Assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($consultation->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $consultation->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">No Category</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($consultation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($consultation->status === 'in progress') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($consultation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $consultation->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">{{ $consultation->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.consultations.show', $consultation) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.consultations.edit', $consultation) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit Consultation">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button onclick="showStatusModal({{ $consultation->id }}, '{{ $consultation->status }}')" 
                                        class="text-purple-600 hover:text-purple-900" title="Update Status">
                                    <i class="fas fa-flag"></i>
                                </button>
                                
                                @if(!$consultation->astrologer)
                                <button onclick="showAssignModal({{ $consultation->id }})" 
                                        class="text-green-600 hover:text-green-900" title="Assign Astrologer">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                                @endif
                                
                                <form method="POST" action="{{ route('admin.consultations.destroy', $consultation) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No consultations found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($consultations->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $consultations->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Update Status</h3>
        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="pending">Pending</option>
                    <option value="in progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Astrologer Modal -->
<div id="assignModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Assign Astrologer</h3>
        <form id="assignForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Astrologer</label>
                <select name="astrologers_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Choose Astrologer</option>
                    @foreach($astrologers as $astrologer)
                        <option value="{{ $astrologer->id }}">{{ $astrologer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeAssignModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Assign</button>
            </div>
        </form>
    </div>
</div>

<script>
function showStatusModal(consultationId, currentStatus) {
    document.getElementById('statusForm').action = `/admin/consultations/${consultationId}/status`;
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function showAssignModal(consultationId) {
    document.getElementById('assignForm').action = `/admin/consultations/${consultationId}/assign`;
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}
</script>
@endsection