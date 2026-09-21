<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Redirect the homepage to the products list
Route::get('/', function () {
    return redirect()->route('products.index');
});

// This one line registers all CRUD routes for products:
// GET /products           -> index
// GET /products/create    -> create
// POST /products          -> store
// GET /products/{id}/edit -> edit
// PUT /products/{id}      -> update
// DELETE /products/{id}   -> destroy
Route::resource('products', ProductController::class);
