@extends('admin.layouts.app')

@section('title', 'Consultation Details')
@section('page-title', 'Consultation Details')
@section('page-description', 'View consultation request details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.consultations.index') }}" class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Consultations
        </a>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.consultations.edit', $consultation) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-edit mr-2"></i>Edit Consultation
            </a>
            
            <form method="POST" action="{{ route('admin.consultations.destroy', $consultation) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this consultation?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Consultation Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Consultation #{{ $consultation->id }}</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($consultation->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($consultation->status === 'in progress') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ ucfirst($consultation->status) }}
                    </span>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $consultation->description }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created Date</label>
                            <p class="text-gray-900">{{ $consultation->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $consultation->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Files -->
            @if($consultation->photos || $consultation->voice || $consultation->video)
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Attached Media</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if($consultation->photos)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-image text-blue-500 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">Photo</span>
                        </div>
                        <img src="{{ asset('storage/' . $consultation->photos) }}" 
                             alt="Consultation Photo" 
                             class="w-full h-32 object-cover rounded cursor-pointer"
                             onclick="showImageModal('{{ asset('storage/' . $consultation->photos) }}')">
                    </div>
                    @endif
                    
                    @if($consultation->voice)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-microphone text-green-500 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">Voice</span>
                        </div>
                        <audio controls class="w-full">
                            <source src="{{ asset('storage/' . $consultation->voice) }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                    @endif
                    
                    @if($consultation->video)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-video text-red-500 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">Video</span>
                        </div>
                        <video controls class="w-full h-32">
                            <source src="{{ asset('storage/' . $consultation->video) }}" type="video/mp4">
                            Your browser does not support the video element.
                        </video>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                
                @if($consultation->user)
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr($consultation->user->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">{{ $consultation->user->name }}</h4>
                        <p class="text-sm text-gray-500">Customer</p>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone:</span>
                        <span class="text-gray-900">{{ $consultation->user->phone }}</span>
                    </div>
                    @if($consultation->user->email)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email:</span>
                        <span class="text-gray-900">{{ $consultation->user->email }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Joined:</span>
                        <span class="text-gray-900">{{ $consultation->user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                @else
                <p class="text-gray-500">Customer information not available</p>
                @endif
            </div>

            <!-- Astrologer Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Astrologer</h3>
                
                @if($consultation->astrologer)
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr($consultation->astrologer->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">{{ $consultation->astrologer->name }}</h4>
                        <p class="text-sm text-gray-500">Astrologer</p>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone:</span>
                        <span class="text-gray-900">{{ $consultation->astrologer->phone }}</span>
                    </div>
                    @if($consultation->astrologer->email)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email:</span>
                        <span class="text-gray-900">{{ $consultation->astrologer->email }}</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-user-slash text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-500 mb-3">No astrologer assigned</p>
                    <button onclick="showAssignModal({{ $consultation->id }})" 
                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                        <i class="fas fa-user-plus mr-2"></i>Assign Astrologer
                    </button>
                </div>
                @endif
            </div>

            <!-- Category Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Category</h3>
                
                @if($consultation->category)
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-medium text-gray-900">{{ $consultation->category->name }}</h4>
                        @if($consultation->category->description)
                        <p class="text-sm text-gray-500">{{ Str::limit($consultation->category->description, 50) }}</p>
                        @endif
                    </div>
                </div>
                @else
                <p class="text-gray-500">No category assigned</p>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    <button onclick="showStatusModal({{ $consultation->id }}, '{{ $consultation->status }}')" 
                            class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-edit mr-2"></i>Update Status
                    </button>
                    
                    @if(!$consultation->astrologer)
                    <button onclick="showAssignModal({{ $consultation->id }})" 
                            class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-user-plus mr-2"></i>Assign Astrologer
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="max-w-4xl max-h-full p-4">
        <div class="relative">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="modalImage" src="" alt="Consultation Image" class="max-w-full max-h-full object-contain">
        </div>
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
                    @foreach(\App\Models\User::where('role', 'astrology')->get() as $astrologer)
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
function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

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