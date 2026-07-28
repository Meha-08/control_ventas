<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Permission as PermissionEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['nombre_u', 'a_paterno', 'a_materno', 'direccion', 'ci', 'email', 'password', 'role', 'active', 'fecha_ingreso', 'fecha_fin', 'telefono'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $appends = ['name'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'active' => 'boolean',
            'fecha_ingreso' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => trim(implode(' ', array_filter([$this->nombre_u, $this->a_paterno, $this->a_materno]))),
        );
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function assignRole(string $roleName): void
    {
        $role = Role::firstOrCreate(['nombre' => $roleName]);
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('nombre', $role)->exists();
    }

    public function hasAnyRole(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : func_get_args();

        return $this->roles()->whereIn('nombre', $roles)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    /**
     * @return array<int, string>
     */
    public function rolesNombres(): array
    {
        return $this->roles->pluck('nombre')->toArray();
    }

    /**
     * @return array<int, string>
     */
    public function permissionsNombres(): array
    {
        if ($this->isAdmin()) {
            return PermissionEnum::valuesForAdministrador();
        }

        $this->loadMissing('roles.permissions');

        return $this->roles
            ->flatMap(fn (Role $role) => $role->permissions->pluck('nombre'))
            ->unique()
            ->values()
            ->toArray();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permissionNames = $this->permissionsNombres();

        if (! empty($permissionNames)) {
            return in_array($permission, $permissionNames, true);
        }

        return $this->defaultPermissionsForAssignedRoles()->contains($permission);
    }

    public function canAccessPanel(): bool
    {
        return $this->hasPermission(PermissionEnum::PanelVer->value);
    }

    public function syncRolesFromLegacyColumn(): void
    {
        if ($this->role !== null) {
            $this->assignRole($this->role);
        }

        if ($this->roles()->count() === 0) {
            $this->assignRole(Role::VENDEDOR);
        }
    }

    public function ensureRolesHavePermissions(): void
    {
        $this->loadMissing('roles');
        $this->roles->each(fn (Role $role) => $role->ensureDefaultPermissions());
    }

    /**
     * @return Collection<int, string>
     */
    private function defaultPermissionsForAssignedRoles(): Collection
    {
        $roleNames = $this->rolesNombres();
        $permissions = collect();

        foreach ($roleNames as $roleName) {
            $permissions = $permissions->merge(
                match ($roleName) {
                    Role::ADMIN => PermissionEnum::valuesForAdministrador(),
                    Role::VENDEDOR => PermissionEnum::valuesForVendedor(),
                    Role::CLIENTE => PermissionEnum::valuesForCliente(),
                    default => [],
                }
            );
        }

        return $permissions->unique();
    }
}
