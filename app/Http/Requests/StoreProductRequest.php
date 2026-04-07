<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode'],
            'description' => ['nullable', 'string'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0', 'gte:buy_price'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'sell_price.gte' => 'Harga jual harus lebih besar atau sama dengan harga beli.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'barcode.unique' => 'Barcode sudah digunakan.',
            'sku.unique' => 'SKU sudah digunakan.',
        ];
    }
}