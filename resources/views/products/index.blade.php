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

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1e293b;
            border: 1px solid #334155;
            padding: 12px 24px;
            border-radius: 10px;
            max-width: 960px;
            margin: 0 auto 24px auto;
        }

        .navbar-brand {
            color: #f8fafc;
            font-weight: 600;
            font-size: 15px;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            font-size: 13px;
        }

        .btn-logout {
            background-color: #475569;
            color: #f8fafc;
            padding: 6px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-logout:hover { background-color: #ef4444; }

        .role-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-left: 4px;
        }
        .role-admin { background-color: #3b82f6; color: #ffffff; }
        .role-user  { background-color: #475569; color: #cbd5e1; }
    </style>
</head>
<body>

{{-- Top navbar: shows logged-in user, their role, and logout button --}}
<nav class="navbar">
    <span class="navbar-brand">🛒 CCC181 Product Manager</span>
    <div class="navbar-user">
        <span>Logged in as: <strong style="color:#f8fafc">{{ Auth::user()->name }}</strong>
            <span class="role-badge {{ Auth::user()->role === 'admin' ? 'role-admin' : 'role-user' }}">
                {{ Auth::user()->role }}
            </span>
        </span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</nav>

<div class="container">
    {{-- Show a flash message after create/update/delete --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="header-row">
        <h1>Product List</h1>
        @can('create', App\Models\Product::class)
            <a href="{{ route('products.create') }}" class="btn btn-green">+ Add New Product</a>
        @endcan
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
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
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px; border: 1px solid #334155;">
                    @else
                        <div style="width: 44px; height: 44px; background-color: #0f172a; border: 1px dashed #334155; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #64748b;" title="No image">📷</div>
                    @endif
                </td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}" style="color: #60a5fa; text-decoration: none; font-weight: 600;">{{ $product->name }}</a>
                </td>
                <td><span class="qty-badge">{{ $product->qty }}</span></td>
                <td>₱{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->description ?? '—' }}</td>
                <td>
                    {{-- View button (available to all users) --}}
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-gray" style="margin-right: 4px;">View</a>

                    {{-- Edit button (admin only) --}}
                    @can('update', $product)
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-blue">Edit</a>
                    @endcan

                    {{-- Delete form (admin only) --}}
                    @can('delete', $product)
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-red">Delete</button>
                        </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#94a3b8; padding: 24px;">No products yet. Add one!</td>
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

