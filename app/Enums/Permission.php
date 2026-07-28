<?php

namespace App\Enums;

enum Permission: string
{
    case PanelVer = 'panel.ver';
    case ClientesVer = 'clientes.ver';
    case ClientesGestionar = 'clientes.gestionar';
    case ProductosVer = 'productos.ver';
    case ProductosGestionar = 'productos.gestionar';
    case CategoriasVer = 'categorias.ver';
    case CategoriasGestionar = 'categorias.gestionar';
    case VentasVer = 'ventas.ver';
    case VentasGestionar = 'ventas.gestionar';
    case InventarioVer = 'inventario.ver';
    case InventarioGestionar = 'inventario.gestionar';
    case ReportesVer = 'reportes.ver';
    case ReportesGestionar = 'reportes.gestionar';
    case UsuariosGestionar = 'usuarios.gestionar';

    public function label(): string
    {
        return match ($this) {
            self::PanelVer => 'Ver panel principal',
            self::ClientesVer => 'Ver lista de clientes',
            self::ClientesGestionar => 'Crear, editar y eliminar clientes',
            self::ProductosVer => 'Ver lista de productos',
            self::ProductosGestionar => 'Crear, editar y eliminar productos',
            self::CategoriasVer => 'Ver categorías',
            self::CategoriasGestionar => 'Crear, editar y eliminar categorías',
            self::VentasVer => 'Ver historial de ventas',
            self::VentasGestionar => 'Registrar y procesar ventas',
            self::InventarioVer => 'Ver inventario y stock',
            self::InventarioGestionar => 'Gestionar movimientos de inventario',
            self::ReportesVer => 'Ver reportes',
            self::ReportesGestionar => 'Generar y exportar reportes',
            self::UsuariosGestionar => 'Gestionar usuarios del sistema',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::PanelVer => 'panel',
            self::ClientesVer, self::ClientesGestionar => 'clientes',
            self::ProductosVer, self::ProductosGestionar => 'productos',
            self::CategoriasVer, self::CategoriasGestionar => 'categorias',
            self::VentasVer, self::VentasGestionar => 'ventas',
            self::InventarioVer, self::InventarioGestionar => 'inventario',
            self::ReportesVer, self::ReportesGestionar => 'reportes',
            self::UsuariosGestionar => 'usuarios',
        };
    }

    /** @return array<int, self> */
    public static function forAdministrador(): array
    {
        return self::cases();
    }

    /** @return array<int, self> */
    public static function forVendedor(): array
    {
        return [
            self::PanelVer,
            self::ClientesVer,
            self::ProductosVer,
            self::CategoriasVer,
            self::VentasVer,
            self::VentasGestionar,
            self::InventarioVer,
            self::ReportesVer,
        ];
    }

    /** @return array<int, self> */
    public static function forCliente(): array
    {
        return [
            self::PanelVer,
            self::ProductosVer,
            self::ProductosGestionar,
            self::CategoriasVer,
            self::CategoriasGestionar,
            self::VentasVer,
            self::InventarioVer,
            self::InventarioGestionar,
            self::ReportesVer,
        ];
    }

    /** @return array<int, string> */
    public static function valuesForAdministrador(): array
    {
        return array_map(fn (self $p) => $p->value, self::forAdministrador());
    }

    /** @return array<int, string> */
    public static function valuesForVendedor(): array
    {
        return array_map(fn (self $p) => $p->value, self::forVendedor());
    }

    /** @return array<int, string> */
    public static function valuesForCliente(): array
    {
        return array_map(fn (self $p) => $p->value, self::forCliente());
    }
}
