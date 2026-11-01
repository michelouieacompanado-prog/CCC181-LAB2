<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Show the form to add a new product
    public function create()
    {
        return view('products.create');
    }

    // Save a new product to the database
    public function store(StoreProductRequest $request)
    {
        // Server-side validated data from StoreProductRequest
        $data = $request->validated();

        Product::create([
            'name'        => $data['name'],
            'qty'         => $data['qty'],
            'price'       => $data['price'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // Show the form to edit an existing product
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Save the updated product to the database
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);

        // Server-side validated data from UpdateProductRequest
        $data = $request->validated();

        $product->update([
            'name'        => $data['name'],
            'qty'         => $data['qty'],
            'price'       => $data['price'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete a product from the database
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
