<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Note: Route & policy authorization handles user permission.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * Synchronizes 'stock' and 'qty' so both naming conventions work seamlessly.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('stock') && !$this->filled('qty')) {
            $this->merge(['qty' => $this->input('stock')]);
        }
        if ($this->has('qty') && !$this->filled('stock')) {
            $this->merge(['stock' => $this->input('qty')]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'qty'         => ['required', 'integer', 'min:0'],
            'image'       => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Custom attribute names for clear error messages.
     */
    public function attributes(): array
    {
        return [
            'name'  => 'product name',
            'qty'   => 'stock / quantity',
            'price' => 'price',
            'image' => 'product image',
        ];
    }
}
