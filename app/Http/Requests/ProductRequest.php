<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        $id = $this->route('product');

        return [
            'name' => 'required|string|max:200|unique:products,name,' . $id,
            'price' => 'required|numeric',
            'unit_id' => 'required|numeric|not_in:0',
            'status' => 'required|numeric',
            'code' => 'required|string|max:100|regex:/^[a-z0-9\-_]+$/|unique:products,code,' . $id,
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:3096',
            'description' => 'nullable|string',
        ];
    }
}
