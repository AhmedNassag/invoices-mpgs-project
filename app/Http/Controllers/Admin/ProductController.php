<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Unit;
use App\Services\Modules\ProductService;
use App\Services\Notifications\ProductNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProductController extends BackendController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct();

        $this->middleware(['permission:product'])->only('index');
        $this->middleware(['permission:product_create'])->only('create', 'store');
        $this->middleware(['permission:product_edit'])->only('edit', 'update');
        $this->middleware(['permission:product_destroy'])->only('destroy');

        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->data['products'] = Product::latest()->get();

        return view('backend.product.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $this->data['units'] = Unit::latest()->get();

        return view('backend.product.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return mixed
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(ProductRequest $request)
    {
        $product = $this->productService->saveProduct(new Product(), $request);

        $this->productService->saveImage($product, $request);

        app(ProductNotificationService::class)->productAddedToPermissionUser($product, auth()->user());

        return redirect(route('admin.product.index'))->withSuccess('The product added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $this->data['product'] = Product::findOrfail($id);
        $this->data['units'] = Unit::latest()->get();

        return view('backend.product.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param int $id
     * @return Response
     */
    public function update(ProductRequest $request, int $id)
    {
        $product = Product::findOrfail($id);

        $this->productService->saveProduct($product, $request);

        $this->productService->saveImage($product, $request);

        app(ProductNotificationService::class)->productUpdatedToPermissionUser($product, auth()->user());

        return redirect(route('admin.product.index'))->withSuccess('The product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $product = Product::findOrfail($id);
        $product->delete();

        app(ProductNotificationService::class)->productDeletedToPermissionUser($product, auth()->user());

        return redirect(route('admin.product.index'))->withSuccess('The product deleted successfully.');
    }
}
