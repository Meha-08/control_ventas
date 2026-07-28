<?php

use App\Enums\Permission as PermissionEnum;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    $this->seed(PermissionsSeeder::class);
});

function userWithRole(string $roleName): User
{
    $user = User::factory()->create(['role' => $roleName]);
    $role = Role::where('nombre', $roleName)->firstOrFail();
    $user->roles()->sync([$role->id]);

    return $user->fresh();
}

it('admin can access clientes.create page', function () {
    $user = userWithRole('admin');

    actingAs($user)
        ->get('/clientes/create')
        ->assertOk();
});

it('vendedor cannot access clientes.create page', function () {
    $user = userWithRole('vendedor');

    actingAs($user)
        ->get('/clientes/create')
        ->assertForbidden();
});

it('vendedor can access clientes.index page', function () {
    $user = userWithRole('vendedor');

    actingAs($user)
        ->get('/clientes')
        ->assertOk();
});

it('hasPermission returns true for admin on any permission', function () {
    $user = userWithRole('admin');

    expect($user->hasPermission(PermissionEnum::UsuariosGestionar->value))->toBeTrue();
});

it('hasPermission returns false for vendedor on gestionar permissions', function () {
    $user = userWithRole('vendedor');

    expect($user->hasPermission(PermissionEnum::ClientesGestionar->value))->toBeFalse();
});

it('hasPermission returns true for vendedor on ventas.gestionar', function () {
    $user = userWithRole('vendedor');

    expect($user->hasPermission(PermissionEnum::VentasGestionar->value))->toBeTrue();
});

it('hasPermission returns true for vendedor on panel.ver', function () {
    $user = userWithRole('vendedor');

    expect($user->hasPermission(PermissionEnum::PanelVer->value))->toBeTrue();
});

it('vendedor cannot access reportes.gestionar', function () {
    $user = userWithRole('vendedor');

    expect($user->hasPermission(PermissionEnum::ReportesGestionar->value))->toBeFalse();
});

it('admin can access reportes.gestionar', function () {
    $user = userWithRole('admin');

    expect($user->hasPermission(PermissionEnum::ReportesGestionar->value))->toBeTrue();
});
