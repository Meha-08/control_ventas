<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'nombre_u' => ['required', 'string', 'max:255'],
            'a_paterno' => ['required', 'string', 'max:255'],
            'a_materno' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$userId],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', 'string', 'exists:roles,nombre'],
            'fecha_ingreso' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_ingreso'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'ci' => ['nullable', 'string', 'max:20'],
        ];
    }
}
