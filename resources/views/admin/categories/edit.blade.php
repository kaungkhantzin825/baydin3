@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')
@section('page-description', 'Modify category information')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <!-- Header -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Edit Category: {{ $category->name }}</h3>
            <p class="text-sm text-gray-500">Update category information and pricing</p>
        </div>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-2 text-indigo-500"></i>Category Name
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $category->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       placeholder="Enter category name..."
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Price -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Price
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" 
                           name="price" 
                           value="{{ old('price', $category->price ?? '0.00') }}"
                           step="0.01"
                           min="0"
                           max="999999.99"
                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('price') border-red-500 @enderror"
                           placeholder="0.00"
                           required>
                </div>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Set the consultation price for this category</p>
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-2 text-gray-500"></i>Description (Optional)
                </label>
                <textarea name="description" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Enter category description...">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on mr-2 text-blue-500"></i>Status
                </label>
                <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('status') border-red-500 @enderror"
                        required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Usage Info -->
            @if($category->toasks_count > 0)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Category Usage</p>
                        <p class="text-sm text-blue-600">This category is currently used in {{ $category->toasks_count }} consultation(s)</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Categories
                </a>
                
                <div class="space-x-3">
                    <a href="{{ route('admin.categories.show', $category) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-eye mr-2"></i>View Details
                    </a>
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-save mr-2"></i>Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection