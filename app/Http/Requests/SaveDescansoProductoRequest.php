<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveDescansoProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categorias = Category::where('active', true)->pluck('nombre')->toArray();

        return [
            'nombre_producto' => ['required', 'string', 'max:255'],
            'tipo_producto' => ['required', Rule::in($categorias)],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'tamaño' => ['nullable', 'string', 'max:100'],
            'material' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:100'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'garantia' => ['nullable', 'string', 'max:100'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'estado' => ['required', Rule::in(['Disponible', 'No disponible'])],
            'fecha_registro' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_producto.required' => 'El nombre del producto es obligatorio.',
            'tipo_producto.required' => 'El tipo de producto es obligatorio.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.min' => 'El precio de venta debe ser mayor o igual a 0.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.min' => 'El stock debe ser mayor o igual a 0.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser Disponible o No disponible.',
        ];
    }
}
