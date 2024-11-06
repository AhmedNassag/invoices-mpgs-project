<?php

namespace App\Services\Modules;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\InvoiceTax;

class QuotationService
{
    private int $type = 10;

    public function getQuotationList($quotations)
    {
        if (blank($quotations)) {
            return [];
        }

        return $quotations->map(function ($quotation, $index) {
            return [
                'auto_id' => ++$index,
                'id' => $quotation->id,
                'date' => $quotation->date->format('d M Y'),
                'payment_status_name' => $quotation->payment_status_name,
                'payment_amount' => green_number_format($quotation->payment_amount),
                'due_amount' => green_number_format($quotation->due_amount),
                'total_amount' => green_number_format($quotation->total_amount),
                'user' => [
                    'id' => $quotation->user_id,
                    'name' => optional($quotation->user)->name
                ],
            ];
        });
    }

    public function saveQuotation(Invoice $invoice, $request): Invoice
    {
        $invoice->user_id = $request->user_id;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->reference_no = $request->reference_no;
        $invoice->note = $request->note;
        $invoice->discount_amount = $request->get('discount_amount', 0);
        $invoice->delivery_charge = $request->get('delivery_charge', 0);
        $invoice->type = $this->type;
        $invoice->tax_amount = 0;
        $invoice->subtotal_amount = 0;
        $invoice->total_amount = 0;

        $invoice->save();

        return $invoice;
    }

    public function saveFile($invoice, $request): void
    {
        if ($request->file('file')) {
            $invoice->media()->delete();
            $invoice->addMedia($request->file('file'))->toMediaCollection('invoice');
        }
    }

    public function saveProduct(Invoice $invoice, $request)
    {
        $products = $request->get('items', []);
        if (blank($products)) {
            return [];
        }

        $i = 0;
        $productList = [];

        foreach ($products as $product) {
            $productList[$i]['invoice_id'] = $invoice->id;
            $productList[$i]['product_id'] = data_get($product, 'product_id');
            $productList[$i]['quantity'] = data_get($product, 'quantity');
            $productList[$i]['unit_price'] = data_get($product, 'unit_price');
            $productList[$i]['subtotal_amount'] = data_get($product, 'unit_price') * data_get($product, 'quantity');

            $i++;
        }

        if (!blank($productList)) {
            InvoiceProduct::insert($productList);
        }
    }


    public function saveTax(Invoice $invoice, $request)
    {
        $taxes = $request->get('taxes', []);
        if (blank($taxes)) {
            return [];
        }

        $i = 0;
        $taxItemList = [];

        foreach ($taxes as $tax) {
            $taxItemList[$i]['invoice_id'] = $invoice->id;
            $taxItemList[$i]['tax_rate_id'] = data_get($tax, 'tax_rate_id');
            $taxItemList[$i]['amount'] = data_get($tax, 'amount');

            $i++;
        }

        if (!blank($taxItemList)) {
            InvoiceTax::insert($taxItemList);
        }
    }

    public function calculate(Invoice $invoice): void
    {
        $invoice->tax_amount = $invoice->taxes()->sum('amount');
        $invoice->subtotal_amount = $invoice->products()->sum('subtotal_amount');

        $invoice->total_amount = ($invoice->subtotal_amount + $invoice->delivery_charge + $invoice->tax_amount) - $invoice->discount_amount;

        $invoice->save();
    }
}