<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IncomeRequest extends FormRequest
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
            'title' => 'required|string|max:200',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'receipt' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,docs|max:3096',
            'note' => 'nullable|string',
        ];
    }
}
