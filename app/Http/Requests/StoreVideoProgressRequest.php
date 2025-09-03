<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // User must be authenticated via middleware
        return true;
    }

    public function rules(): array
    {
        return [
            'episode_id'   => 'required|integer|exists:episodes,id',
            'current_time' => 'required|numeric|min:0',
            'duration'     => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'episode_id.required' => 'Episode ID is required.',
            'episode_id.integer' => 'Episode ID must be an integer.',
            'episode_id.exists' => 'The specified episode does not exist.',
            'current_time.required' => 'Current time is required.',
            'current_time.numeric' => 'Current time must be a number.',
            'current_time.min' => 'Current time cannot be negative.',
            'duration.numeric' => 'Duration must be a number.',
            'duration.min' => 'Duration cannot be negative.',
        ];
    }
}