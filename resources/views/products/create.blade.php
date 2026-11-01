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
            margin-bottom: 14px;
        }

        .field-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444 !important;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Add New Product</h1>

    {{-- Show validation errors banner if any --}}
    @if($errors->any())
        <div class="error">
            <strong>Please correct the following errors:</strong>
            <ul style="margin-left: 18px; margin-top: 6px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Product Name <span style="color:#ef4444;">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Apple" class="@error('name') input-error @enderror">
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="qty">Quantity / Stock <span style="color:#ef4444;">*</span></label>
            <input type="number" id="qty" name="qty" value="{{ old('qty', old('stock')) }}" placeholder="e.g. 50" min="0" class="@error('qty') input-error @enderror">
            @error('qty')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">Price (₱) <span style="color:#ef4444;">*</span></label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" placeholder="e.g. 29.99" step="0.01" min="0" class="@error('price') input-error @enderror">
            @error('price')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description <span style="font-weight:normal;color:#94a3b8;">(optional)</span></label>
            <textarea id="description" name="description" placeholder="Short description..." class="@error('description') input-error @enderror">{{ old('description') }}</textarea>
            @error('description')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Product Image <span style="font-weight:normal;color:#94a3b8;">(optional, max 2MB: JPG, PNG, WebP)</span></label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" class="@error('image') input-error @enderror" style="padding: 7px;">
            @error('image')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-green">Save Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-gray">Cancel</a>
    </form>
</div>
</body>
</html>

