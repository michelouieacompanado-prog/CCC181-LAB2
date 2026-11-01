<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// Redirect root to products (requires login)
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Dashboard (requires login + email verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth middleware group — only logged-in users can access the application
Route::middleware('auth')->group(function () {

    // --- Product Catalog & Details (viewAny & view policy) ---
    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('can:viewAny,App\Models\Product')
        ->name('products.index');

    // --- Product Creation (admin only via can:create) ---
    Route::get('/products/create', [ProductController::class, 'create'])
        ->middleware('can:create,App\Models\Product')
        ->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('can:create,App\Models\Product')
        ->name('products.store');

    // --- Product Detail View (accessible to all authenticated users) ---
    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->middleware('can:view,product')
        ->name('products.show');

    // --- Product Editing (admin only via can:update) ---
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->middleware('can:update,product')
        ->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->middleware('can:update,product')
        ->name('products.update');

    // --- Product Deletion (admin only via can:delete) ---
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->middleware('can:delete,product')
        ->name('products.destroy');

    // --- Profile routes (from Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze authentication routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
