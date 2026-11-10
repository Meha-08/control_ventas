<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('app.admin.email');
        $password = config('app.admin.password');

        if (empty($email) || empty($password)) {
            $this->command?->warn('UsuariosSeeder omitido: define ADMIN_EMAIL y ADMIN_PASSWORD en el .env.');

            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'nombre_u' => config('app.admin.name', 'Administrador'),
                'a_paterno' => 'Sistema',
                'a_materno' => null,
                'password' => $password,
                'role' => Role::ADMIN,
                'active' => true,
            ]
        );

        $user->assignRole(Role::ADMIN);
    }
}
