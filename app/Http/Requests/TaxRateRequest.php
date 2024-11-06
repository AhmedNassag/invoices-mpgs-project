<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxRateRequest extends FormRequest
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
        $id = $this->route('tax_rate');

        return [
            'name'        => ['required', 'string', 'max:200', 'unique:tax_rates,name,' . $id],
            'percent'     => 'required|numeric|min:0',
            'status'      => 'required|numeric',
            'description' => 'nullable|string',
        ];
    }
}
