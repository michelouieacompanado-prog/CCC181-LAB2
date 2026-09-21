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

                    {{-- Delete form — uses POST + @method('DELETE') since HTML forms can't send DELETE --}}
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

