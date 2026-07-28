<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::updateOrCreate(
                ['nombre' => $permission->value],
                ['descripcion' => $permission->label(), 'grupo' => $permission->group()]
            );
        }

        $admin = Role::where('nombre', Role::ADMIN)->first();
        $vendedor = Role::where('nombre', Role::VENDEDOR)->first();
        $cliente = Role::where('nombre', Role::CLIENTE)->first();

        if ($admin) {
            $admin->syncPermissionsByName(PermissionEnum::valuesForAdministrador());
        }

        if ($vendedor) {
            $vendedor->syncPermissionsByName(PermissionEnum::valuesForVendedor());
        }

        if ($cliente) {
            $cliente->syncPermissionsByName(PermissionEnum::valuesForCliente());
        }
    }
}
