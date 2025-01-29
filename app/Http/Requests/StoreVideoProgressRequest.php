<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Handle authorization logic here. Typically,
        // you return true if user is authenticated via middleware.
        return true;
    }

    public function rules(): array
    {
        return [
            'animes_id'      => 'required|integer',
            'current_time'  => 'required|numeric',
        ];
    }
}
