<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => Role::ADMIN, 'descripcion' => 'Administrador del sistema con acceso completo'],
            ['nombre' => Role::VENDEDOR, 'descripcion' => 'Vendedor para operaciones de venta diaria'],
            ['nombre' => Role::CLIENTE, 'descripcion' => 'Cliente con acceso a productos e inventario'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nombre' => $role['nombre']], $role);
        }
    }
}
