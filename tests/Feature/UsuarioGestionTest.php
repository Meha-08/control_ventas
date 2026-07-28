<?php

use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    $this->seed(PermissionsSeeder::class);
});

it('admin has usuarios.gestionar permission', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $role = Role::where('nombre', 'admin')->firstOrFail();
    $user->roles()->sync([$role->id]);
    $user = $user->fresh();

    expect($user->hasPermission('usuarios.gestionar'))->toBeTrue();
});

it('vendedor does not have usuarios.gestionar permission', function () {
    $user = User::factory()->create(['role' => 'vendedor']);
    $role = Role::where('nombre', 'vendedor')->firstOrFail();
    $user->roles()->sync([$role->id]);
    $user = $user->fresh();

    expect($user->hasPermission('usuarios.gestionar'))->toBeFalse();
});
