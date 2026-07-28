<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'nombre' => ['required', 'string', 'max:50', 'unique:roles,nombre,'.$roleId],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,nombre'],
        ];
    }
}
