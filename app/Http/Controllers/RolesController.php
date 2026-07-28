<?php

namespace App\Http\Controllers;

use App\Enums\Permission as PermissionEnum;
use App\Http\Requests\SaveRoleRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RolesController extends Controller
{
    public function index(Request $request): Response
    {
        $roles = Role::query()
            ->withCount('users')
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('roles/Edit', [
            'permissionGroups' => $this->getPermissionGroups(),
        ]);
    }

    public function store(SaveRoleRequest $request): RedirectResponse
    {
        $role = Role::create($request->validated());

        if ($request->has('permissions')) {
            $role->syncPermissionsByName($request->permissions ?? []);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Rol creado.')]);

        return to_route('roles.index');
    }

    public function edit(Request $request, Role $role): Response
    {
        $role->load('permissions');

        return Inertia::render('roles/Edit', [
            'role' => $role,
            'permissionGroups' => $this->getPermissionGroups(),
        ]);
    }

    public function update(SaveRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->validated());

        if ($request->has('permissions')) {
            $role->syncPermissionsByName($request->permissions ?? []);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Rol actualizado.')]);

        return to_route('roles.index');
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        $usersCount = $role->users()->count();

        if ($usersCount > 0) {
            $role->users()->detach();
        }

        $role->permissions()->detach();
        $role->delete();

        $message = $usersCount > 0
            ? __('Rol eliminado y desasignado a :count usuarios.', ['count' => $usersCount])
            : __('Rol eliminado.');

        Inertia::flash('toast', ['type' => 'success', 'message' => $message]);

        return to_route('roles.index');
    }

    private function getPermissionGroups(): array
    {
        $groups = [];
        foreach (PermissionEnum::cases() as $permission) {
            $group = $permission->group();
            $groups[$group][] = [
                'nombre' => $permission->value,
                'label' => $permission->label(),
            ];
        }

        return $groups;
    }
}
