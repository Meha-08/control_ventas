<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_oferta' => ['nullable', 'numeric', 'min:0', 'lt:precio'],
            'stock' => ['required', 'integer', 'min:0'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'active' => ['boolean'],
            'destacado' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->category_id === '' || $this->category_id === 'null') {
            $this->merge(['category_id' => null]);
        }
    }
}
