<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'qty'   => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($request->only('name', 'qty', 'price', 'description'));

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // Show the form to edit an existing product
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Save the updated product to the database
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'qty'   => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only('name', 'qty', 'price', 'description'));

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
