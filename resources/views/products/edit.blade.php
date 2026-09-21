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

    {{-- @method('PUT') tells Laravel this form should be treated as a PUT request --}}
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

