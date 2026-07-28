<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            // Safe rename: cajera -> vendedor (avoid unique constraint violation)
            $vendedorRole = DB::table('roles')->where('nombre', 'vendedor')->first();
            $cajeraRole = DB::table('roles')->where('nombre', 'cajera')->first();

            if ($cajeraRole && $vendedorRole) {
                DB::table('role_user')->where('role_id', $cajeraRole->id)->update(['role_id' => $vendedorRole->id]);
                DB::table('permission_role')->where('role_id', $cajeraRole->id)->update(['role_id' => $vendedorRole->id]);
                DB::table('roles')->where('id', $cajeraRole->id)->delete();
            } elseif ($cajeraRole) {
                DB::table('roles')->where('id', $cajeraRole->id)->update(['nombre' => 'vendedor']);
            }

            // Safe rename: operador -> cliente (avoid unique constraint violation)
            $clienteRole = DB::table('roles')->where('nombre', 'cliente')->first();
            $operadorRole = DB::table('roles')->where('nombre', 'operador')->first();

            if ($operadorRole && $clienteRole) {
                DB::table('role_user')->where('role_id', $operadorRole->id)->update(['role_id' => $clienteRole->id]);
                DB::table('permission_role')->where('role_id', $operadorRole->id)->update(['role_id' => $clienteRole->id]);
                DB::table('roles')->where('id', $operadorRole->id)->delete();
            } elseif ($operadorRole) {
                DB::table('roles')->where('id', $operadorRole->id)->update(['nombre' => 'cliente']);
            }

            // Update legacy users.role column too
            DB::table('users')->where('role', 'cajera')->update(['role' => 'vendedor']);
            DB::table('users')->where('role', 'operador')->update(['role' => 'cliente']);
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            $vendedorRole = DB::table('roles')->where('nombre', 'vendedor')->first();
            $cajeraRole = DB::table('roles')->where('nombre', 'cajera')->first();

            if ($vendedorRole && $cajeraRole) {
                DB::table('role_user')->where('role_id', $vendedorRole->id)->update(['role_id' => $cajeraRole->id]);
                DB::table('permission_role')->where('role_id', $vendedorRole->id)->update(['role_id' => $cajeraRole->id]);
                DB::table('roles')->where('id', $vendedorRole->id)->delete();
            } elseif ($vendedorRole) {
                DB::table('roles')->where('id', $vendedorRole->id)->update(['nombre' => 'cajera']);
            }

            $clienteRole = DB::table('roles')->where('nombre', 'cliente')->first();
            $operadorRole = DB::table('roles')->where('nombre', 'operador')->first();

            if ($clienteRole && $operadorRole) {
                DB::table('role_user')->where('role_id', $clienteRole->id)->update(['role_id' => $operadorRole->id]);
                DB::table('permission_role')->where('role_id', $clienteRole->id)->update(['role_id' => $operadorRole->id]);
                DB::table('roles')->where('id', $clienteRole->id)->delete();
            } elseif ($clienteRole) {
                DB::table('roles')->where('id', $clienteRole->id)->update(['nombre' => 'operador']);
            }

            DB::table('users')->where('role', 'vendedor')->update(['role' => 'cajera']);
            DB::table('users')->where('role', 'cliente')->update(['role' => 'operador']);
        });
    }
};
