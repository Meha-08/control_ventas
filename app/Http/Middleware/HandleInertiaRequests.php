<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        if ($user) {
            $user->loadMissing('roles.permissions');
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user,
                'roles' => fn () => $user ? $user->rolesNombres() : [],
                'permissions' => fn () => $user ? $user->permissionsNombres() : [],
                'isAdmin' => fn () => $user?->isAdmin() ?? false,
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'userStats' => fn () => $user ? [
                'admins' => Role::where('nombre', 'admin')->first()?->users()->count() ?? 0,
                'vendedores' => Role::where('nombre', 'vendedor')->first()?->users()->count() ?? 0,
                'clientes' => Role::where('nombre', 'cliente')->first()?->users()->count() ?? 0,
                'total' => User::count(),
            ] : ['admins' => 0, 'vendedores' => 0, 'clientes' => 0, 'total' => 0],
        ];
    }
}
