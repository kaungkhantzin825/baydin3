@extends('admin.layouts.app')

@section('title', 'Edit Consultation')
@section('page-title', 'Edit Consultation')
@section('page-description', 'Modify consultation request details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Edit Consultation #{{ $consultation->id }}</h3>
                    <p class="text-sm text-gray-500">Modify consultation request information</p>
                </div>
                <a href="{{ route('admin.consultations.show', $consultation) }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.consultations.update', $consultation) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Customer Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Customer
                        </label>
                        <select name="user_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('user_id') border-red-500 @enderror"
                            required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $consultation->user_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Astrologer Assignment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-star mr-2 text-purple-500"></i>Assigned Astrologer
                        </label>
                        <select name="astrologers_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('astrologers_id') border-red-500 @enderror">
                            <option value="">No Astrologer Assigned</option>
                            @foreach($astrologers as $astrologer)
                            <option value="{{ $astrologer->id }}" {{ $consultation->astrologers_id == $astrologer->id ? 'selected' : '' }}>
                                {{ $astrologer->name }} ({{ $astrologer->phone }})
                            </option>
                            @endforeach
                        </select>
                        @error('astrologers_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-list mr-2 text-indigo-500"></i>Category
                        </label>
                        <select name="categories_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('categories_id') border-red-500 @enderror">
                            <option value="">No Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $consultation->categories_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('categories_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag mr-2 text-green-500"></i>Status
                        </label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('status') border-red-500 @enderror"
                            required>
                            <option value="pending" {{ $consultation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in progress" {{ $consultation->status === 'in progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $consultation->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment mr-2 text-gray-500"></i>Description
                        </label>
                        <textarea name="description"
                            rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                            placeholder="Enter consultation description..."
                            required>{{ old('description', $consultation->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Media Files -->
                    @if($consultation->photos || $consultation->voice || $consultation->video)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-paperclip mr-2 text-gray-500"></i>Current Media Files
                        </label>
                        <div class="grid grid-cols-1 gap-3">
                            @if($consultation->photos)
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center">
                                    <i class="fas fa-image text-blue-500 mr-3"></i>
                                    <span class="text-sm text-gray-700">Current Photo</span>
                                </div>
                                <button type="button" onclick="showImagePreview('{{ asset('storage/' . $consultation->photos) }}')"
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                            </div>
                            @endif

                            @if($consultation->voice)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center">
                                    <i class="fas fa-microphone text-green-500 mr-3"></i>
                                    <span class="text-sm text-gray-700">Current Voice</span>
                                </div>
                                <button type="button" onclick="playAudio('{{ asset('storage/' . $consultation->voice) }}')"
                                    class="text-green-600 hover:text-green-800 text-sm">
                                    <i class="fas fa-play mr-1"></i>Play
                                </button>
                            </div>
                            @endif

                            @if($consultation->video)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                <div class="flex items-center">
                                    <i class="fas fa-video text-red-500 mr-3"></i>
                                    <span class="text-sm text-gray-700">Current Video</span>
                                </div>
                                <button type="button" onclick="showVideoPreview('{{ asset('storage/' . $consultation->video) }}')"
                                    class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-play mr-1"></i>Play
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- File Uploads -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-upload mr-2 text-gray-500"></i>Update Media Files (Optional)
                        </label>

                        <!-- Photo Upload -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Photo (JPG, PNG - Max 2MB)</label>
                            <input type="file"
                                name="photos"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm @error('photos') border-red-500 @enderror">
                            @error('photos')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Voice Upload -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Voice (MP3, WAV, M4A - Max 500MB)</label>
                            <input type="file"
                                name="voice"
                                accept="audio/mp3,audio/wav,audio/m4a,audio/mpeg"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm @error('voice') border-red-500 @enderror">
                            @error('voice')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Video Upload -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Video (MP4, MOV - Max 10MB)</label>
                            <input type="file"
                                name="video"
                                accept="video/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm @error('video') border-red-500 @enderror">
                            @error('video')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mr-2 mt-0.5"></i>
                                <div class="text-xs text-blue-700">
                                    <p class="font-medium mb-1">File Upload Guidelines:</p>
                                    <ul class="space-y-1">
                                        <li>• Photo: Maximum 2MB (JPEG, PNG, JPG, GIF)</li>
                                        <li>• Voice: Maximum 500MB (MP3, WAV, M4A)</li>
                                        <li>• Video: Maximum 10MB (MP4, MOV, AVI)</li>
                                        <li>• Uploading new files will replace existing ones</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                <a href="{{ route('admin.consultations.show', $consultation) }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Details
                </a>

                <div class="flex space-x-3">
                    <button type="reset" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-save mr-2"></i>Update Consultation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Media Preview Modals -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="max-w-4xl max-h-full p-4">
        <div class="relative">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="previewImage" src="" alt="Preview" class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>

<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="max-w-4xl max-h-full p-4">
        <div class="relative">
            <button onclick="closeVideoModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <video id="previewVideo" controls class="max-w-full max-h-full">
                <source src="" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<script>
    function showImagePreview(src) {
        document.getElementById('previewImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    function showVideoPreview(src) {
        document.getElementById('previewVideo').src = src;
        document.getElementById('videoModal').classList.remove('hidden');
    }

    function closeVideoModal() {
        document.getElementById('videoModal').classList.add('hidden');
        document.getElementById('previewVideo').pause();
    }

    function playAudio(src) {
        const audio = new Audio(src);
        audio.play();
    }

    // File upload validation and preview
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.querySelector('input[name="photos"]');
        const voiceInput = document.querySelector('input[name="voice"]');
        const videoInput = document.querySelector('input[name="video"]');

        function validateFileSize(file, maxSizeMB, inputName) {
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            if (file.size > maxSizeBytes) {
                alert(`The ${inputName} file is too large. Maximum size allowed is ${maxSizeMB}MB. Your file is ${(file.size / 1024 / 1024).toFixed(2)}MB.`);
                return false;
            }
            return true;
        }

        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (!validateFileSize(file, 2, 'photo')) {
                        e.target.value = '';
                        return;
                    }
                    console.log('Photo selected:', file.name, 'Size:', (file.size / 1024 / 1024).toFixed(2) + 'MB');
                }
            });
        }

        if (voiceInput) {
            voiceInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (!validateFileSize(file, 500, 'voice')) {
                        e.target.value = '';
                        return;
                    }
                    console.log('Voice selected:', file.name, 'Size:', (file.size / 1024 / 1024).toFixed(2) + 'MB');
                }
            });
        }

        if (videoInput) {
            videoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (!validateFileSize(file, 10, 'video')) {
                        e.target.value = '';
                        return;
                    }
                    console.log('Video selected:', file.name, 'Size:', (file.size / 1024 / 1024).toFixed(2) + 'MB');
                }
            });
        }
    });
</script>
@endsection