<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to products (requires login)
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Dashboard (requires login + email verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth middleware group — only logged-in users can access products
Route::middleware('auth')->group(function () {
    // Product CRUD routes
    Route::resource('products', ProductController::class);

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze authentication routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
