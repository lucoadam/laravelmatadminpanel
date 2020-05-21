<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Http\Requests\product\ProductCreateRequest;
use App\Http\Requests\product\ProductEditRequest;
use App\Http\Requests\product\ProductStoreRequest;
use App\Http\Requests\product\ProductUpdateRequest;
use App\Http\Requests\product\ProductDeleteRequest;
use App\Http\Requests\product\ProductViewRequest;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    /**
     * Display a listing of the product
     *
     * @param  \App\Product  $model
     * @return \Illuminate\View\View
     */
    public function index(ProductViewRequest $request,Product $model)
    {
        return view('product.index', ['product' => $model->all()]);
    }

    /**
     * Show the form for creating a new product
     *
     * @return \Illuminate\View\View
     */
    public function create(ProductCreateRequest $request)
    {

        return view('product.create');
    }

    /**
     * Store a newly created product in storage
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Product  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductStoreRequest $request, Product $model)
    {
        $input =$request->all();
        
        $model->create($input);
        return redirect()->route('product.index')->withStatus(__('Product successfully created.'));
    }

    /**
     * Show the form for editing the specified product
     *
     * @param  \App\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(ProductEditRequest $request,Product $product)
    {
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified product in storage
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductUpdateRequest $request,Product  $product)
    {
          $input =$request->all();
        

        $product->update($input);
        return redirect()->route('product.index')->withStatus(__('Product successfully updated.'));
    }

    /**
     * Remove the specified product from storage
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProductDeleteRequest $request,Product  $product)
    {
        $product->delete();

        return redirect()->route('product.index')->withStatus(__('Product successfully deleted.'));
    }
}