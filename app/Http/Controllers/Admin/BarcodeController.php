<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\Invoice;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorJPG;

class BarcodeController extends BackendController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(['permission:barcode']);
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $this->data['showView'] = false;
        $this->data['set_product_id'] = 0;
        $this->data['set_quantity'] = '';
        $this->data['selectedProduct'] = [];

        $products = Product::query()->select('id', 'name', 'code')->get();

        if ($_POST) {
            $productId = $request->integer('product_id');
            $quantity = $request->get('quantity');
            $selectedProduct = $products->where('id', $productId)->first();

            $request->validate([
                'product_id' => 'required|numeric|gt:0',
                'quantity' => 'required|numeric|gt:0',
            ]);

            $this->data['showView'] = true;
            $this->data['set_product_id'] = $productId;
            $this->data['set_quantity'] = $quantity;
            $this->data['selectedProduct'] = $selectedProduct;

            $this->generateBarcode($selectedProduct);
        }

        $this->data['products'] = $products;

        return view('backend.barcode.index', $this->data);
    }

    /**
     * Generate PDF
     * @param $productID
     * @param $quantity
     * @return Response
     */
    public function pdf($productID, $quantity)
    {
        $this->data['set_quantity'] = $quantity;
        $this->data['selectedProduct'] = Product::query()->findOrFail($productID);

        return Pdf::loadView('dompdf.barcode', $this->data)->stream();
    }

    /**
     * Generate Barcode
     * @param $selectedProduct
     * @return void
     */
    private function generateBarcode($selectedProduct): void
    {
        // Generate the filename for the barcode image
        $filename = $selectedProduct->code . '.jpg';

        // Combine the base path and filename to create the full path
        $imagePath = 'barcode/' . $filename;

        // Check if the barcode image already exists
        if (!file_exists(public_path($imagePath))) {
            // If it doesn't exist, generate a new barcode image
            $barcode = new BarcodeGeneratorJPG();
            $barcodeImg = $barcode->getBarcode($selectedProduct->code, BarcodeGenerator::TYPE_CODE_128);

            // Store the barcode image in the specified path
            file_put_contents($imagePath, $barcodeImg);
        }
    }
}
