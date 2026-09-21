# Activity No. 2: Creating CRUD Modules in Laravel

> **Course:** CCC181 — Web Systems and Technologies  
> **Topic:** Product CRUD Web Application with Dark Mode UI  
> **Framework:** Laravel 12 (PHP)  
> **Database:** MySQL (`app-crud`)  
> **Architecture Pattern:** Model-View-Controller (MVC)

---

## 📑 Table of Contents
1. [Project Overview & Architecture](#-project-overview--architecture)
2. [Project File Structure](#-project-file-structure)
3. [Environment Configuration (`.env`)](#1-environment-configuration-env)
4. [Database Migration](#2-database-migration)
5. [Eloquent Model (`Product.php`)](#3-eloquent-model-productphp)
6. [Controller (`ProductController.php`)](#4-controller-productcontrollerphp)
7. [Routes Configuration (`routes/web.php`)](#5-routes-configuration-routeswebphp)
8. [Blade Views (Dark Mode UI)](#6-blade-views-dark-mode-ui)
   - [`index.blade.php`](#61-resourcesviewsproductsindexbladephp)
   - [`create.blade.php`](#62-resourcesviewsproductscreatebladephp)
   - [`edit.blade.php`](#63-resourcesviewsproductseditbladephp)
9. [CRUD Route Mapping](#-crud-route-mapping)
10. [Setup & Running Instructions](#-setup--running-instructions)
11. [Code Defense Study Guide & Key Concepts](#-code-defense-study-guide--key-concepts)

---

## 🏛 Project Overview & Architecture

This application implements full **CRUD (Create, Read, Update, Delete)** functionality for managing products using Laravel's standard MVC architecture and Eloquent ORM.

```
[ Browser / Client ]
        │
        ▼ (HTTP Request)
 [ routes/web.php ] ─── (Route::resource)
        │
        ▼
[ ProductController.php ] ◄──► [ Product Model ] ◄──► [ MySQL: products table ]
        │
        ▼ (Renders Data)
 [ Blade Views (*.blade.php) ] (Dark Mode UI)
```

---

## 📁 Project File Structure

Only the custom core files created for this CRUD activity:

```
CCC181-LAB2/
├── .env
├── routes/
│   └── web.php
├── app/
│   ├── Models/
│   │   └── Product.php
│   └── Http/
│       └── Controllers/
│           └── ProductController.php
├── database/
│   └── migrations/
│       └── 2026_09_19_112146_create_products_table.php
└── resources/
    └── views/
        └── products/
            ├── index.blade.php
            ├── create.blade.php
            └── edit.blade.php
```

---

## 1. Environment Configuration (`.env`)

Database connection configured for local MySQL server:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app-crud
DB_USERNAME=root
DB_PASSWORD=
```

---

## 2. Database Migration

**File:** `database/migrations/2026_09_19_112146_create_products_table.php`

Defines the database schema for the `products` table with columns: `name`, `qty`, `price`, `description`, and timestamps.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the products table in the database.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('qty');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Drops the products table if rolled back.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

---

## 3. Eloquent Model (`Product.php`)

**File:** `app/Models/Product.php`

Enables mass-assignment protection using `$fillable`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Allow these fields to be mass-assigned (used in create/update)
    protected $fillable = ['name', 'qty', 'price', 'description'];
}
```

---

## 4. Controller (`ProductController.php`)

**File:** `app/Http/Controllers/ProductController.php`

Handles incoming requests, input validation, Eloquent ORM queries, and view rendering.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // READ: Display a listing of all products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // CREATE: Show the form for creating a new product
    public function create()
    {
        return view('products.create');
    }

    // CREATE: Store a newly created product in the database
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

    // UPDATE: Show the form for editing the specified product
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // UPDATE: Update the specified product in the database
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

    // DELETE: Remove the specified product from the database
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
```

---

## 5. Routes Configuration (`routes/web.php`)

**File:** `routes/web.php`

Registers resource routes mapping all standard CRUD URIs to `ProductController`.

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Redirect homepage to products index
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Resource route registering all 7 RESTful actions
Route::resource('products', ProductController::class);
```

---

## 6. Blade Views (Dark Mode UI)

### 6.1 `resources/views/products/index.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            background-color: #1e293b;
            border: 1px solid #334155;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            max-width: 960px;
            margin: 0 auto;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h1 {
            color: #f8fafc;
            font-size: 1.6rem;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background-color: #0f172a;
            color: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #334155;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #334155;
            color: #f8fafc;
            font-size: 14px;
        }

        tbody tr:hover { background-color: rgba(255, 255, 255, 0.03); }
        tbody tr:last-child td { border-bottom: 1px solid #334155; }

        .qty-badge {
            background-color: #334155;
            color: #f8fafc;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 12px;
            display: inline-block;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn:active { transform: scale(0.97); }

        .btn-green { background-color: #10b981; color: #ffffff; }
        .btn-green:hover { background-color: #059669; }

        .btn-blue  { background-color: #3b82f6; color: #ffffff; }
        .btn-blue:hover  { background-color: #2563eb; }

        .btn-red   { background-color: #ef4444; color: #ffffff; }
        .btn-red:hover   { background-color: #dc2626; }

        /* Flash message */
        .alert-success {
            background-color: #064e3b;
            color: #a7f3d0;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #10b981;
        }

        /* Footer */
        .table-footer {
            margin-top: 18px;
            color: #94a3b8;
            font-size: 13px;
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container">
    {{-- Show a flash message after create/update/delete --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="header-row">
        <h1>Product List</h1>
        <a href="{{ route('products.create') }}" class="btn btn-green">+ Add New Product</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td><span class="qty-badge">{{ $product->qty }}</span></td>
                <td>₱{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->description ?? '—' }}</td>
                <td>
                    {{-- Edit button --}}
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-blue">Edit</a>

                    {{-- Delete form --}}
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-red">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#94a3b8; padding: 24px;">No products yet. Add one!</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-footer">
        Total Products: {{ $products->count() }}
    </div>
</div>
</body>
</html>
```

---

### 6.2 `resources/views/products/create.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            padding: 40px 20px;
        }

        h1 {
            color: #f8fafc;
            font-size: 1.6rem;
            margin-bottom: 24px;
        }

        .container {
            background-color: #1e293b;
            border: 1px solid #334155;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            margin: 0 auto;
        }

        /* Form groups */
        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
            color: #cbd5e1;
        }

        input[type=text],
        input[type=number],
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #334155;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #f8fafc;
            background-color: #0f172a;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
        }

        input[type=text]:focus,
        input[type=number]:focus,
        textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            background-color: #0f172a;
        }

        textarea { height: 90px; resize: vertical; }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn:active { transform: scale(0.97); }

        .btn-green { background-color: #10b981; color: #ffffff; }
        .btn-green:hover { background-color: #059669; }

        .btn-gray  { background-color: #64748b; color: #ffffff; margin-left: 10px; }
        .btn-gray:hover  { background-color: #475569; }

        /* Validation errors */
        .error {
            background-color: #450a0a;
            border-left: 4px solid #ef4444;
            color: #fca5a5;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Add New Product</h1>

    {{-- Show validation errors if any --}}
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="error">• {{ $error }}</div>
        @endforeach
        <br>
    @endif

    {{-- Form submits to products.store --}}
    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Apple">
        </div>

        <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="number" id="qty" name="qty" value="{{ old('qty') }}" placeholder="e.g. 50" min="0">
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" placeholder="e.g. 29.99" step="0.01" min="0">
        </div>

        <div class="form-group">
            <label for="description">Description <span style="font-weight:normal;color:#94a3b8;">(optional)</span></label>
            <textarea id="description" name="description" placeholder="Short description...">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-green">Save Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-gray">Cancel</a>
    </form>
</div>
</body>
</html>
```

---

### 6.3 `resources/views/products/edit.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            padding: 40px 20px;
        }

        h1 {
            color: #f8fafc;
            font-size: 1.6rem;
            margin-bottom: 24px;
        }

        .container {
            background-color: #1e293b;
            border: 1px solid #334155;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            margin: 0 auto;
        }

        /* Form groups */
        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
            color: #cbd5e1;
        }

        input[type=text],
        input[type=number],
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #334155;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #f8fafc;
            background-color: #0f172a;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
        }

        input[type=text]:focus,
        input[type=number]:focus,
        textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            background-color: #0f172a;
        }

        textarea { height: 90px; resize: vertical; }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn:active { transform: scale(0.97); }

        .btn-blue { background-color: #3b82f6; color: #ffffff; }
        .btn-blue:hover { background-color: #2563eb; }

        .btn-gray { background-color: #64748b; color: #ffffff; margin-left: 10px; }
        .btn-gray:hover { background-color: #475569; }

        /* Validation errors */
        .error {
            background-color: #450a0a;
            border-left: 4px solid #ef4444;
            color: #fca5a5;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Product</h1>

    {{-- Show validation errors if any --}}
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="error">• {{ $error }}</div>
        @endforeach
        <br>
    @endif

    {{-- Form sends PUT request via method spoofing --}}
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}">
        </div>

        <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="number" id="qty" name="qty" value="{{ old('qty', $product->qty) }}" min="0">
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0">
        </div>

        <div class="form-group">
            <label for="description">Description <span style="font-weight:normal;color:#94a3b8;">(optional)</span></label>
            <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-blue">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-gray">Cancel</a>
    </form>
</div>
</body>
</html>
```

---

## 🗺 CRUD Route Mapping

| HTTP Verb | URI Path | Controller Action | Route Name | Purpose |
|---|---|---|---|---|
| `GET` | `/products` | `ProductController@index` | `products.index` | View all products |
| `GET` | `/products/create` | `ProductController@create` | `products.create` | Show form to add product |
| `POST` | `/products` | `ProductController@store` | `products.store` | Save newly created product |
| `GET` | `/products/{product}/edit` | `ProductController@edit` | `products.edit` | Show form to edit product |
| `PUT`/`PATCH` | `/products/{product}` | `ProductController@update` | `products.update` | Update existing product |
| `DELETE` | `/products/{product}` | `ProductController@destroy` | `products.destroy` | Delete a product |

---

## 🚀 Setup & Running Instructions

```bash
# 1. Start MySQL Server (run terminal as Administrator if using MySQL service)
net start MySQL80

# 2. Create the Database
mysql -u root -h 127.0.0.1 -e "CREATE DATABASE IF NOT EXISTS \`app-crud\`;"

# 3. Navigate to workspace
cd "d:\Users\User\Desktop\CCC181-LAB2"

# 4. Run database migrations to build tables
php artisan migrate

# 5. Launch development server
php artisan serve
# Open browser at: http://127.0.0.1:8000
```

---

## 🎓 Code Defense Study Guide & Key Concepts

### 1. What is MVC?
- **Model (`app/Models/Product.php`):** Communicates with the database table via Eloquent ORM.
- **View (`resources/views/products/`):** HTML/Blade templates presented to the user.
- **Controller (`app/Http/Controllers/ProductController.php`):** Handles incoming HTTP requests, coordinates with Model, and passes data to Views.

### 2. Why do we need `$fillable` in `Product.php`?
- Protects against **Mass Assignment Vulnerabilities**.
- By declaring `protected $fillable = ['name', 'qty', 'price', 'description'];`, we explicitly tell Laravel which fields are safe to be populated using methods like `Product::create()` and `$product->update()`.

### 3. What is `@csrf`?
- **Cross-Site Request Forgery** token generator.
- It injects a hidden `<input type="hidden" name="_token" value="...">` field into HTML forms to protect against unauthorized malicious form submissions.

### 4. Why use `@method('PUT')` and `@method('DELETE')`?
- Standard HTML `<form>` tags only support `GET` and `POST` methods.
- Laravel uses **HTTP Method Spoofing** via `@method(...)` to simulate RESTful `PUT`, `PATCH`, and `DELETE` requests.

### 5. What does `Route::resource()` do?
- Automatically generates all 7 standard RESTful routes with consistent naming conventions in a single line of code.

### 6. What does `Product::findOrFail($id)` do?
- Queries the database for a product with the specified ID. If not found, it automatically throws a `404 Not Found` HTTP exception instead of causing a null pointer error.

