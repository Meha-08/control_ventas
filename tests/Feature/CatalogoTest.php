<?php

use App\Models\Category;
use App\Models\DescansoProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    Category::create(['nombre' => 'Somieres', 'active' => true]);
    Category::create(['nombre' => 'Bases de cama', 'active' => true]);
    Category::create(['nombre' => 'Inactivos', 'active' => false]);

    DescansoProducto::create([
        'nombre_producto' => 'Somier Americano',
        'tipo_producto' => 'Somieres',
        'precio_venta' => 1000,
        'stock' => 5,
        'estado' => 'Disponible',
    ]);

    DescansoProducto::create([
        'nombre_producto' => 'Base de cama',
        'tipo_producto' => 'Bases de cama',
        'precio_venta' => 1500,
        'stock' => 5,
        'estado' => 'Disponible',
    ]);
});

test('the catalog types come from the active categories', function () {
    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('tipos', ['Bases de cama', 'Somieres'])
        ->where('heroImagen', config('app.hero_image'))
    );

    $this->get('/menu')->assertInertia(fn (Assert $page) => $page
        ->where('tipos', ['Bases de cama', 'Somieres'])
        ->where('heroImagen', config('app.hero_image'))
    );
});

test('the menu filters products by the selected catalog type', function () {
    $this->get('/menu?tipo=Somieres')->assertInertia(fn (Assert $page) => $page
        ->where('filters.tipo', 'Somieres')
        ->has('productos', 1)
        ->where('productos.0.nombre_producto', 'Somier Americano')
    );
});
