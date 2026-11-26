<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — Product Details</title>
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

        .container {
            background-color: #0e121a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(255, 255, 255, 0.16);
            padding: 34px;
            border-radius: 18px;
            box-shadow: 
                0 25px 50px -15px rgba(0, 0, 0, 0.7),
                0 0 0 1px rgba(255, 255, 255, 0.02) inset;
            max-width: 660px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-bottom: 18px;
            margin-bottom: 24px;
        }

        h1 {
            color: #ffffff;
            font-size: 1.45rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .image-container {
            text-align: center;
            background-color: #080a10;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 24px;
        }

        .product-image {
            max-width: 100%;
            max-height: 320px;
            object-fit: contain;
            border-radius: 8px;
        }

        .no-image {
            color: #64748b;
            font-size: 13px;
            padding: 36px 0;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            row-gap: 14px;
            font-size: 13px;
        }

        .label {
            color: #8896ab;
            font-weight: 600;
        }

        .value {
            color: #f1f5f9;
        }

        .price-badge {
            color: #ffffff;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            padding: 3px 10px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 11px;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }
        .stock-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background-color: #34d399;
            box-shadow: 0 0 6px #34d399;
        }

        .btn {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 9999px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn:active { transform: scale(0.97); }

        .btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #d946ef 100%);
            color: #ffffff;
            box-shadow: 0 6px 20px -3px rgba(139, 92, 246, 0.45);
        }
        .btn-blue:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 10px 25px -3px rgba(139, 92, 246, 0.65);
            filter: brightness(1.08);
        }

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

        .btn-gray {
            background-color: #151b27;
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .btn-gray:hover {
            background-color: #20293a;
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.15);
        }

        .actions {
            margin-top: 14px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

<div class="ambient-glow"></div>

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
            <div class="value" style="font-size: 1.15rem; font-weight: 700; color: #ffffff;">{{ $product->name }}</div>

            <div class="label">Price:</div>
            <div class="value price-badge mono">₱{{ number_format($product->price, 2) }}</div>

            <div class="label">Quantity:</div>
            <div class="value">
                <span class="stock-badge">
                    <span class="stock-dot"></span>
                    {{ $product->qty }}
                </span>
            </div>

            <div class="label">Description:</div>
            <div class="value" style="color: #cbd5e1;">{{ $product->description ?: 'No description provided.' }}</div>

            <div class="label">Created At:</div>
            <div class="value" style="color: #8896ab;">{{ $product->created_at ? $product->created_at->format('M d, Y h:i A') : '—' }}</div>

            <div class="label">Last Updated:</div>
            <div class="value" style="color: #8896ab;">{{ $product->updated_at ? $product->updated_at->format('M d, Y h:i A') : '—' }}</div>
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
