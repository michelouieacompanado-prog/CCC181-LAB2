<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — Product Details</title>
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
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            max-width: 650px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #334155;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        h1 {
            color: #f8fafc;
            font-size: 1.6rem;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .image-container {
            text-align: center;
            background-color: #0f172a;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 20px;
        }

        .product-image {
            max-width: 100%;
            max-height: 320px;
            object-fit: contain;
            border-radius: 8px;
        }

        .no-image {
            color: #64748b;
            font-size: 14px;
            padding: 40px 0;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            row-gap: 12px;
            font-size: 14px;
        }

        .label {
            color: #94a3b8;
            font-weight: 600;
        }

        .value {
            color: #f8fafc;
        }

        .price-badge {
            color: #34d399;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .stock-badge {
            background-color: #334155;
            color: #f8fafc;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .btn {
            display: inline-block;
            padding: 9px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn:active { transform: scale(0.97); }
        .btn-blue  { background-color: #3b82f6; color: #ffffff; }
        .btn-blue:hover  { background-color: #2563eb; }
        .btn-red   { background-color: #ef4444; color: #ffffff; }
        .btn-red:hover   { background-color: #dc2626; }
        .btn-gray  { background-color: #475569; color: #ffffff; }
        .btn-gray:hover  { background-color: #334155; }

        .actions {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #334155;
            display: flex;
            gap: 12px;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Product Details</h1>
        <a href="{{ route('products.index') }}" class="btn btn-gray">← Back to List</a>
    </div>

    <div class="product-card">
        <div class="image-container">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
            @else
                <div class="no-image">📷 No image uploaded for this product</div>
            @endif
        </div>

        <div class="details-grid">
            <div class="label">Product Name:</div>
            <div class="value" style="font-size: 1.1rem; font-weight: 600;">{{ $product->name }}</div>

            <div class="label">Price:</div>
            <div class="value price-badge">₱{{ number_format($product->price, 2) }}</div>

            <div class="label">Stock / Quantity:</div>
            <div class="value"><span class="stock-badge">{{ $product->qty }} in stock</span></div>

            <div class="label">Description:</div>
            <div class="value">{{ $product->description ?: 'No description provided.' }}</div>

            <div class="label">Created At:</div>
            <div class="value" style="color: #94a3b8;">{{ $product->created_at ? $product->created_at->format('M d, Y h:i A') : '—' }}</div>

            <div class="label">Last Updated:</div>
            <div class="value" style="color: #94a3b8;">{{ $product->updated_at ? $product->updated_at->format('M d, Y h:i A') : '—' }}</div>
        </div>

        <div class="actions">
            @can('update', $product)
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-blue">Edit Product</a>
            @endcan

            @can('delete', $product)
                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                      style="display:inline;"
                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-red">Delete Product</button>
                </form>
            @endcan

            <a href="{{ route('products.index') }}" class="btn btn-gray">Back</a>
        </div>
    </div>
</div>
</body>
</html>
