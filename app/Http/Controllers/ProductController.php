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
    // Show all products with database search and pagination
    public function index(Request $request)
    {
        // Enforce viewAny policy check
        Gate::authorize('viewAny', Product::class);

        $search = $request->query('search');

        // Database-level query filtering (WHERE name LIKE %...%)
        $products = Product::when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    // Show single product detail view
    public function show(Product $product)
    {
        // Enforce view policy check (all authenticated users can view)
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
    public function edit(Product $product)
    {
        // Enforce update policy check (admin only)
        Gate::authorize('update', $product);

        return view('products.edit', compact('product'));
    }

    // Save the updated product to the database
    public function update(UpdateProductRequest $request, Product $product)
    {
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
    public function destroy(Product $product)
    {
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

