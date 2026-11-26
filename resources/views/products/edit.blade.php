<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            max-width: 520px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        h1 {
            color: #ffffff;
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 24px;
            letter-spacing: -0.02em;
        }

        /* Form groups */
        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            font-size: 13px;
            color: #cbd5e1;
        }

        input[type=text],
        input[type=number],
        textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 10px;
            font-size: 13px;
            font-family: inherit;
            color: #f1f5f9;
            background-color: #080a10;
            transition: all 0.2s ease;
            outline: none;
        }

        input[type=text]:focus,
        input[type=number]:focus,
        textarea:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.25), 0 0 15px -3px rgba(168, 85, 247, 0.3);
            background-color: #0d1017;
        }

        input[type=file] {
            width: 100%;
            padding: 9px 12px;
            border: 1px dashed rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            font-size: 12px;
            color: #94a3b8;
            background-color: #080a10;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        input[type=file]:hover {
            border-color: #a855f7;
        }

        textarea { height: 95px; resize: vertical; }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 9px 20px;
            border-radius: 9999px;
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
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

        .btn-gray {
            background-color: #151b27;
            color: #cbd5e1;
            border-color: rgba(255, 255, 255, 0.08);
            margin-left: 10px;
        }
        .btn-gray:hover {
            background-color: #20293a;
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.15);
        }

        /* Validation errors */
        .error {
            background-color: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-left: 4px solid #ef4444;
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .field-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }

        .input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
        }

        /* Image preview card */
        .image-preview-box {
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            background-color: #080a10;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            flex-wrap: wrap;
        }

        .remove-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #f87171;
            cursor: pointer;
            margin: 0;
            font-weight: 600;
            background: rgba(239, 68, 68, 0.12);
            padding: 6px 12px;
            border-radius: 9999px;
            border: 1px solid rgba(239, 68, 68, 0.28);
            user-select: none;
            transition: all 0.15s ease;
        }
        .remove-pill:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.45);
        }
    </style>
</head>
<body>

<div class="ambient-glow"></div>

<div class="container">
    <h1>Edit Product</h1>

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

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Product Name <span style="color:#f87171;">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="@error('name') input-error @enderror">
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="qty">Quantity <span style="color:#f87171;">*</span></label>
            <input type="number" id="qty" name="qty" value="{{ old('qty', old('stock', $product->qty)) }}" min="0" class="@error('qty') input-error @enderror">
            @error('qty')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">Price (₱) <span style="color:#f87171;">*</span></label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" class="@error('price') input-error @enderror">
            @error('price')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description <span style="font-weight:normal;color:#8896ab;">(optional)</span></label>
            <textarea id="description" name="description" class="@error('description') input-error @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Product Image <span style="font-weight:normal;color:#8896ab;">(optional, max 2MB: JPG, PNG, WebP)</span></label>
            @if($product->image)
                <div class="image-preview-box">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.12);">
                        <div>
                            <div style="font-size: 13px; color: #ffffff; font-weight: 600;">Current Image</div>
                            <div style="font-size: 12px; color: #8896ab;">Upload a new image below to replace it.</div>
                        </div>
                    </div>
                    <label class="remove-pill">
                        <input type="checkbox" name="remove_image" value="1" style="cursor: pointer; width: 15px; height: 15px; accent-color: #ef4444;">
                        Remove current image
                    </label>
                </div>
            @endif
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" class="@error('image') input-error @enderror">
            @error('image')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-top: 26px;">
            <button type="submit" class="btn btn-blue">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-gray">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
