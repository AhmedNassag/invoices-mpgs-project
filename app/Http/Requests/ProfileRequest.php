<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $id = auth()->id();

        return [
            'name' => ['required', 'string', 'max:60'],
            'designation' => ['nullable', 'string', 'max:60'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $id],
            'phone' => ['nullable', 'string', 'max:60'],
            'date_of_birth' => ['nullable', 'string', 'max:60'],
            'joining_date' => ['nullable', 'string', 'max:60'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:3096'],
            'address' => ['nullable', 'max:200'],
            'username' => ['required', 'string', 'max:60', 'unique:users,username,' . $id],
            'password' => ['nullable','min:6']
        ];
    }
}
