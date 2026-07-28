<?php

namespace App\Models;

use App\Enums\Permission as PermissionEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    public const ADMIN = 'admin';

    public const VENDEDOR = 'vendedor';

    public const CLIENTE = 'cliente';

    protected $fillable = ['nombre', 'descripcion'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function syncPermissionsByName(array $names): void
    {
        $permissions = Permission::whereIn('nombre', $names)->get();
        $this->permissions()->sync($permissions->pluck('id'));
    }

    public function ensureDefaultPermissions(): void
    {
        if ($this->permissions()->exists()) {
            return;
        }

        $names = match ($this->nombre) {
            self::ADMIN => PermissionEnum::valuesForAdministrador(),
            self::VENDEDOR => PermissionEnum::valuesForVendedor(),
            self::CLIENTE => PermissionEnum::valuesForCliente(),
            default => [],
        };

        if (! empty($names)) {
            $this->syncPermissionsByName($names);
        }
    }

    public static function defaultNames(): array
    {
        return [self::ADMIN, self::VENDEDOR, self::CLIENTE];
    }
}
