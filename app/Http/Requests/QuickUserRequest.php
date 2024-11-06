<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuickUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:60'],
            'address' => ['nullable', 'max:200'],
        ];
    }
}
