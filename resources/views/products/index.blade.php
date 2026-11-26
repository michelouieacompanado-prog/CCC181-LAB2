<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #07090e;
            color: #f1f5f9;
            min-height: 100vh;
            padding: 40px 20px;
            line-height: 1.5;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Ambient soft top glow */
        .ambient-glow {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 240px;
            background: radial-gradient(ellipse at top, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Top Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0e121a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(255, 255, 255, 0.16);
            padding: 13px 24px;
            border-radius: 14px;
            max-width: 1040px;
            margin: 0 auto 24px auto;
            position: relative;
            z-index: 1;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .navbar-brand {
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00d2ff, #a855f7);
            box-shadow: 0 0 10px rgba(0, 210, 255, 0.7);
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            font-size: 13px;
        }

        .role-badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-left: 4px;
        }
        .role-admin {
            background: rgba(139, 92, 246, 0.18);
            color: #c084fc;
            border: 1px solid rgba(168, 85, 247, 0.4);
            box-shadow: 0 0 12px -2px rgba(168, 85, 247, 0.3);
        }
        .role-user {
            background-color: #171d29;
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-logout {
            background-color: #151b27;
            color: #cbd5e1;
            padding: 5px 14px;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-logout:hover {
            background-color: #ef4444;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 15px -2px rgba(239, 68, 68, 0.4);
        }

        /* Container Card */
        .container {
            background-color: #0e121a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(255, 255, 255, 0.16);
            padding: 30px;
            border-radius: 18px;
            box-shadow: 
                0 25px 50px -15px rgba(0, 0, 0, 0.7),
                0 0 0 1px rgba(255, 255, 255, 0.02) inset;
            max-width: 1040px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* Flash Message */
        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 22px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(16, 185, 129, 0.3);
            box-shadow: 0 4px 15px -3px rgba(16, 185, 129, 0.15);
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        h1 {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Search Section */
        .search-container {
            margin-bottom: 22px;
        }

        .search-form {
            display: flex;
            gap: 8px;
            max-width: 460px;
        }

        .search-input {
            flex: 1;
            padding: 9px 16px;
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 9999px;
            font-size: 13px;
            color: #f1f5f9;
            background-color: #080a10;
            outline: none;
            transition: all 0.2s ease;
        }
        .search-input:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.25), 0 0 15px -3px rgba(168, 85, 247, 0.3);
            background-color: #0d1017;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 7px 15px;
            border-radius: 9999px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
        }

        .btn:active { transform: scale(0.97); }

        /* Electric Violet Neon Gradient Button */
        .btn-green,
        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #d946ef 100%);
            color: #ffffff;
            box-shadow: 0 6px 20px -3px rgba(139, 92, 246, 0.45);
        }
        .btn-green:hover,
        .btn-gradient:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 10px 25px -3px rgba(139, 92, 246, 0.65);
            filter: brightness(1.08);
        }

        /* Dark Matte Button */
        .btn-gray,
        .btn-blue {
            background-color: #151b27;
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .btn-gray:hover,
        .btn-blue:hover {
            background-color: #20293a;
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.15);
        }

        /* Delete Button */
        .btn-red {
            background-color: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }
        .btn-red:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
            box-shadow: 0 4px 15px -2px rgba(220, 38, 38, 0.4);
        }

        .action-buttons {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .action-buttons form {
            display: inline-flex;
            margin: 0;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.07);
        }

        th {
            background-color: #090c12;
            color: #8896ab;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        td {
            padding: 13px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            color: #cbd5e1;
            font-size: 13px;
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        tbody tr:last-child td {
            border-bottom: none;
        }

        .product-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s;
        }
        .product-link:hover {
            color: #c084fc;
        }

        /* Mint Neon Stock Badge */
        .qty-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            padding: 3px 9px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 11px;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        /* Table Footer & Pagination */
        .table-footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #64748b;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-container nav {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pagination-container svg {
            width: 14px;
            height: 14px;
            display: inline-block;
        }

        .pagination-container a,
        .pagination-container span[aria-current="page"] span,
        .pagination-container span[aria-disabled="true"] span {
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .pagination-container a {
            background-color: #121722;
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.2s;
        }
        .pagination-container a:hover {
            background-color: #1c2436;
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.15);
        }

        .pagination-container span[aria-current="page"] span {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #d946ef);
            color: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0 4px 15px -2px rgba(139, 92, 246, 0.5);
        }

        .pagination-container span[aria-disabled="true"] span {
            background-color: #0b0e15;
            color: #475569;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }
    </style>
</head>
<body>

<div class="ambient-glow"></div>

{{-- Top navbar: shows logged-in user, their role, and logout button --}}
<nav class="navbar">
    <div class="navbar-brand">
        <span class="navbar-brand-dot"></span>
        <span>CCC181 Product Manager</span>
    </div>
    <div class="navbar-user">
        <span>Logged in as: <strong style="color:#ffffff">{{ Auth::user()->name }}</strong>
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

    {{-- Search Form: preserves query across pagination --}}
    <div class="search-container">
        <form action="{{ route('products.index') }}" method="GET" class="search-form">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search products by name..."
                   class="search-input">
            <button type="submit" class="btn btn-blue">Search</button>
            @if(request('search'))
                <a href="{{ route('products.index') }}" class="btn btn-gray">Clear</a>
            @endif
        </form>
        @if(request('search'))
            <p style="font-size: 13px; color: #8896ab; margin-top: 8px;">
                Showing results for: "<strong style="color: #ffffff;">{{ request('search') }}</strong>"
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 70px;">Image</th>
                <th>Name</th>
                <th style="width: 100px;">Quantity</th>
                <th style="width: 130px;">Price</th>
                <th>Description</th>
                <th style="width: 180px; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td class="mono" style="color: #64748b;">{{ $product->id }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                    @else
                        <div style="width: 40px; height: 40px; background-color: #121722; border: 1px dashed rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; color: #64748b;" title="No image">📷</div>
                    @endif
                </td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}" class="product-link">{{ $product->name }}</a>
                </td>
                <td><span class="qty-badge">{{ $product->qty }}</span></td>
                <td class="mono" style="color: #ffffff; font-weight: 700;">₱{{ number_format($product->price, 2) }}</td>
                <td style="color: #8896ab;">{{ $product->description ?? '—' }}</td>
                <td style="text-align: right; white-space: nowrap;">
                    <div class="action-buttons">
                        {{-- View button (available to all users) --}}
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-gray">View</a>

                        {{-- Edit button (admin only) --}}
                        @can('update', $product)
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-blue">Edit</a>
                        @endcan

                        {{-- Delete form (admin only) --}}
                        @can('delete', $product)
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-red">Delete</button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#8896ab; padding: 36px 16px;">
                    @if(request('search'))
                        No products found matching "<strong style="color: #ffffff;">{{ request('search') }}</strong>".
                        <div style="margin-top: 10px;">
                            <a href="{{ route('products.index') }}" style="color: #c084fc; text-decoration: underline;">Clear search filter</a>
                        </div>
                    @else
                        No products available yet.
                        @can('create', App\Models\Product::class)
                            <div style="margin-top: 10px;">
                                <a href="{{ route('products.create') }}" style="color: #34d399; text-decoration: underline;">Add your first product</a>
                            </div>
                        @endcan
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-footer">
        <div>
            Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
        </div>
        <div class="pagination-container">
            {{ $products->links() }}
        </div>
    </div>
</div>
</body>
</html>
