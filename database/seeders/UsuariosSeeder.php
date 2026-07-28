<?php

namespace Database\Seeders;

use App\Actions\Teams\CreateTeam;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin Roze', 'email' => 'admin@roze.test', 'password' => bcrypt('admin1234'), 'role' => 'admin'],
            ['name' => 'Vendedor Roze', 'email' => 'vendedor@roze.test', 'password' => bcrypt('vendedor1234'), 'role' => 'vendedor'],
            ['name' => 'Cliente Roze', 'email' => 'cliente@roze.test', 'password' => bcrypt('cliente1234'), 'role' => 'cliente'],
        ];

        $createTeam = app(CreateTeam::class);

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            $user->assignRole($role);

            if ($user->teams()->count() === 0) {
                $createTeam->handle($user, $user->name."'s Team", isPersonal: true);
            }
        }
    }
}
