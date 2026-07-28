<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'nombre_u' => $input['nombre_u'],
            'a_paterno' => $input['a_paterno'],
            'a_materno' => $input['a_materno'] ?? null,
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        $user->assignRole(Role::VENDEDOR);

        return $user;
    }
}
