@extends('admin.layouts.app')

@section('title', 'Category Details')
@section('page-title', 'Category Details')
@section('page-description', 'View category information and usage')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Categories
        </a>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-edit mr-2"></i>Edit Category
            </a>
            
            @if($category->toasks->count() === 0)
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Category Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-list text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500">Category ID: #{{ $category->id }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($category->status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Price</label>
                        <div class="text-3xl font-bold text-green-600">
                            ${{ number_format($category->price ?? 0, 2) }}
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Per consultation session</p>
                    </div>
                    
                    <!-- Usage Stats -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Usage Statistics</label>
                        <div class="text-3xl font-bold text-blue-600">
                            {{ $category->toasks->count() }}
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Total consultations</p>
                    </div>
                </div>

                @if($category->description)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $category->description }}</p>
                    </div>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created Date</label>
                        <p class="text-gray-900">{{ $category->created_at ? $category->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                        <p class="text-gray-900">{{ $category->updated_at ? $category->updated_at->format('M d, Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Consultations -->
            @if($category->toasks->count() > 0)
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Consultations</h3>
                
                <div class="space-y-4">
                    @foreach($category->toasks->take(5) as $consultation)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">{{ substr($consultation->user->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">{{ $consultation->user->name ?? 'Unknown User' }}</h4>
                                <p class="text-sm text-gray-500">{{ Str::limit($consultation->description, 50) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($consultation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($consultation->status === 'in progress') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($consultation->status) }}
                            </span>
                            <p class="text-sm text-gray-500 mt-1">{{ $consultation->created_at ? $consultation->created_at->diffForHumans() : 'N/A' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($category->toasks->count() > 5)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.consultations.index', ['category_id' => $category->id]) }}" 
                       class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                        View all {{ $category->toasks->count() }} consultations →
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Revenue</span>
                        <span class="font-semibold text-green-600">
                            ${{ number_format(($category->price ?? 0) * $category->toasks->where('status', 'completed')->count(), 2) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Completed</span>
                        <span class="font-semibold text-green-600">
                            {{ $category->toasks->where('status', 'completed')->count() }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">In Progress</span>
                        <span class="font-semibold text-blue-600">
                            {{ $category->toasks->where('status', 'in progress')->count() }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Pending</span>
                        <span class="font-semibold text-yellow-600">
                            {{ $category->toasks->where('status', 'pending')->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 
                            {{ $category->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} 
                            text-white rounded-lg">
                            <i class="fas fa-toggle-{{ $category->status === 'active' ? 'off' : 'on' }} mr-2"></i>
                            {{ $category->status === 'active' ? 'Deactivate' : 'Activate' }} Category
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-edit mr-2"></i>Edit Category
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection