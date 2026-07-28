<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class UsuariosController extends Controller
{
    private function getUserStats(): array
    {
        return Cache::remember('user_stats', 300, function () {
            $counts = Role::whereIn('nombre', [Role::ADMIN, Role::VENDEDOR, Role::CLIENTE])
                ->withCount('users')
                ->get()
                ->keyBy('nombre');

            return [
                'admins' => $counts->get(Role::ADMIN)?->users_count ?? 0,
                'vendedores' => $counts->get(Role::VENDEDOR)?->users_count ?? 0,
                'clientes' => $counts->get(Role::CLIENTE)?->users_count ?? 0,
            ];
        });
    }

    public function index(Request $request): Response
    {
        $users = User::query()
            ->with('roles')
            ->when($request->search, fn ($q, $search) => $q->whereAny(['nombre_u', 'a_paterno', 'a_materno', 'email'], 'like', "%{$search}%"))
            ->when(
                $request->filled('active'),
                fn ($q, $active) => $q->where('active', filter_var($request->active, FILTER_VALIDATE_BOOLEAN))
            )
            ->orderByDesc('active')
            ->orderBy('nombre_u')
            ->paginate(12)
            ->withQueryString()
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'nombre_u' => $user->nombre_u,
                'a_paterno' => $user->a_paterno,
                'a_materno' => $user->a_materno,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'active' => $user->active,
                'role' => $user->roles->first()?->nombre,
                'telefono' => $user->telefono,
                'direccion' => $user->direccion,
                'ci' => $user->ci,
                'fecha_ingreso' => $user->fecha_ingreso?->format('Y-m-d'),
                'fecha_fin' => $user->fecha_fin?->format('Y-m-d'),
                'created_at' => $user->created_at,
            ]);

        return Inertia::render('usuarios/Index', [
            'users' => $users,
            'filters' => $request->only('search', 'active'),
            'userStats' => $this->getUserStats(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('usuarios/Edit', [
            'roles' => Role::defaultNames(),
            'userStats' => $this->getUserStats(),
        ]);
    }

    public function store(SaveUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'nombre_u' => $request->nombre_u,
            'a_paterno' => $request->a_paterno,
            'a_materno' => $request->a_materno,
            'direccion' => $request->direccion,
            'ci' => $request->ci,
            'email' => $request->email,
            'password' => $request->password,
            'active' => true,
            'fecha_ingreso' => $request->fecha_ingreso,
            'fecha_fin' => $request->fecha_fin,
            'telefono' => $request->telefono,
        ]);

        $user->assignRole($request->role);

        Cache::forget('user_stats');

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Usuario creado.')]);

        return to_route('usuarios.index');
    }

    public function edit(Request $request, User $user): Response
    {
        $user->load('roles');

        return Inertia::render('usuarios/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nombre_u' => $user->nombre_u,
                'a_paterno' => $user->a_paterno,
                'a_materno' => $user->a_materno,
                'email' => $user->email,
                'role' => $user->roles->first()?->nombre,
                'active' => $user->active,
                'fecha_ingreso' => $user->fecha_ingreso?->format('Y-m-d'),
                'fecha_fin' => $user->fecha_fin?->format('Y-m-d'),
                'telefono' => $user->telefono,
                'direccion' => $user->direccion,
                'ci' => $user->ci,
            ],
            'roles' => Role::defaultNames(),
            'userStats' => $this->getUserStats(),
        ]);
    }

    public function update(SaveUserRequest $request, User $user): RedirectResponse
    {
        $data = [
            'nombre_u' => $request->nombre_u,
            'a_paterno' => $request->a_paterno,
            'a_materno' => $request->a_materno,
            'direccion' => $request->direccion,
            'ci' => $request->ci,
            'email' => $request->email,
            'fecha_ingreso' => $request->fecha_ingreso,
            'fecha_fin' => $request->fecha_fin,
            'telefono' => $request->telefono,
        ];

        if ($request->has('active')) {
            $data['active'] = $request->boolean('active');
        }

        $user->update($data);

        if ($request->filled('password')) {
            $user->update(['password' => $request->password]);
        }

        if ($request->role) {
            $user->roles()->sync([]);
            $user->assignRole($request->role);
        }

        Cache::forget('user_stats');

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Usuario actualizado.')]);

        return to_route('usuarios.index');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        if ($user->active && $user->is($request->user())) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('No puedes desactivar tu propia cuenta.')]);

            return back();
        }

        $user->update(['active' => ! $user->active]);

        $status = $user->active ? 'activado' : 'desactivado';

        Inertia::flash('toast', ['type' => 'success', 'message' => __("Usuario {$status}.")]);

        return back();
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('No puedes eliminarte a ti mismo.')]);

            return back();
        }

        $user->roles()->detach();
        $user->delete();

        Cache::forget('user_stats');

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Usuario eliminado.')]);

        return to_route('usuarios.index');
    }
}
