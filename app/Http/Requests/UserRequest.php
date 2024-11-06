<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $id = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:60'],
            'designation' => ['nullable', 'string', 'max:60'],
            'email' => ['required', 'string','email', 'max:100', 'unique:users,email,' . $id],
            'phone' => ['nullable', 'string', 'max:60'],
            'date_of_birth' => ['nullable', 'string', 'max:60'],
            'joining_date' => ['nullable', 'string', 'max:60'],
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:3096',
            'address' => ['nullable', 'max:200'],
            'role' => 'required|numeric|not_in:0',
            'username' => ['required', 'string', 'max:60', 'unique:users,username,' . $id],
            'password' => $id ? "nullable|min:6" : "required|min:6",
        ];
    }
}
