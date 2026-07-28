<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'total' => ['required', 'numeric', 'min:0'],
            'tipo_comprobante' => ['nullable', 'string', 'max:50'],
            'nro_comprobante' => ['nullable', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'items.*.precio_unitario' => ['required', 'numeric', 'min:0'],
            'items.*.subtotal' => ['required', 'numeric', 'min:0'],
        ];
    }
}
