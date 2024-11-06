<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
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
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'reference_no' => 'nullable|string',
            'file' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,docx|max:5120',
            'note' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'delivery_charge' => 'nullable|numeric',
            'items' => 'required|array',
            'taxes' => 'nullable|array'
        ];
    }

    public function attributes(): array
    {
        return ['user_id' => 'user'];
    }

    /**
     * @param $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $items = $this->get('items', []);
            $taxes = $this->get('taxes', []);

            foreach ($items as $item) {
                if (empty($item['product_id']) || empty($item['quantity'])) {
                    $validator->errors()->add('taxes', 'Each item must have a product and quantity.');
                }
            }


            foreach ($taxes as $tax) {
                if (empty($tax['tax_rate_id']) || empty($tax['percent'])) {
                    $validator->errors()->add('taxes', 'Each tax item must have a tax rate option.');
                }
            }
        });
    }
}
