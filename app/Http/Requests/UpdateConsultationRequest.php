<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'astrologers_id' => 'nullable|exists:users,id',
            'categories_id' => 'nullable|exists:categories,id',
            'description' => 'required|string',
            'status' => 'required|in:pending,in progress,completed',
            'photos' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'voice' => 'nullable|mimes:mp3,wav,m4a|max:512000',
            'video' => 'nullable|mimes:mp4,mov,avi|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'photos.max' => 'The photo file must not be larger than 2MB.',
            'voice.max' => 'The voice file must not be larger than 500MB.',
            'video.max' => 'The video file must not be larger than 10MB.',
            'photos.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif.',
            'voice.mimes' => 'The voice file must be a file of type: mp3, wav, m4a.',
            'video.mimes' => 'The video file must be a file of type: mp4, mov, avi.',
            'user_id.required' => 'Please select a customer.',
            'user_id.exists' => 'The selected customer does not exist.',
            'astrologers_id.exists' => 'The selected astrologer does not exist.',
            'categories_id.exists' => 'The selected category does not exist.',
            'description.required' => 'Please provide a consultation description.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be pending, in progress, or completed.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'customer',
            'astrologers_id' => 'astrologer',
            'categories_id' => 'category',
        ];
    }
}