<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        // Enforce viewAny policy check
        Gate::authorize('viewAny', Product::class);

        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Show single product detail view
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        // Enforce view policy check
        Gate::authorize('view', $product);

        return view('products.show', compact('product'));
    }

    // Show the form to add a new product
    public function create()
    {
        // Enforce create policy check (admin only)
        Gate::authorize('create', Product::class);

        return view('products.create');
    }

    // Save a new product to the database
    public function store(StoreProductRequest $request)
    {
        // Enforce create policy check (admin only)
        Gate::authorize('create', Product::class);

        $data = $request->validated();

        // Handle optional product image upload on public disk
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Generates a secure unique hash filename; never trusts the user's original filename
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'        => $data['name'],
            'qty'         => $data['qty'],
            'price'       => $data['price'],
            'description' => $data['description'] ?? null,
            'image'       => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // Show the form to edit an existing product
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        // Enforce update policy check (admin only)
        Gate::authorize('update', $product);

        return view('products.edit', compact('product'));
    }

    // Save the updated product to the database
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);

        // Enforce update policy check (admin only)
        Gate::authorize('update', $product);

        $data = $request->validated();

        $oldImage = $product->image;
        $imagePath = $oldImage;

        // If a new image is uploaded: store the new file first
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Update database record with new attributes and new image path
        $product->update([
            'name'        => $data['name'],
            'qty'         => $data['qty'],
            'price'       => $data['price'],
            'description' => $data['description'] ?? null,
            'image'       => $imagePath,
        ]);

        // Delete old image only after new image is successfully stored and DB updated
        if ($request->hasFile('image') && $oldImage && $oldImage !== $imagePath) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete a product from the database
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Enforce delete policy check (admin only)
        Gate::authorize('delete', $product);

        $imagePath = $product->image;

        $product->delete();

        // Remove associated image file from storage if present
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
