<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Jumlah stok wajib diisi',
            'reason.required' => 'Alasan penyesuaian wajib diisi',
        ];
    }
}