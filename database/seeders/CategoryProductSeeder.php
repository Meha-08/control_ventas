<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nombre' => 'Colchones', 'descripcion' => 'Colchones viscoelásticos, ortopédicos y de resortes'],
            ['nombre' => 'Somieres', 'descripcion' => 'Somieres americanos, abatibles y metálicos'],
            ['nombre' => 'Espaldares', 'descripcion' => 'Espaldares tapizados, modernos y clásicos'],
            ['nombre' => 'Almohadas', 'descripcion' => 'Almohadas viscoelásticas, pluma y ortopédicas'],
            ['nombre' => 'Bases de cama', 'descripcion' => 'Bases de cama tapizadas y metálicas'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['nombre' => $cat['nombre']],
                ['descripcion' => $cat['descripcion'], 'active' => true]
            );
        }

        if (Product::count() === 0) {
            $colchones = Category::where('nombre', 'Colchones')->first();
            $somieres = Category::where('nombre', 'Somieres')->first();

            Product::create([
                'nombre' => 'Colchón Viscoelástico Premium',
                'descripcion' => 'Colchón viscoelástico de alta densidad con memoria de forma.',
                'precio' => 2499.00,
                'precio_oferta' => 1899.00,
                'stock' => 15,
                'category_id' => $colchones?->id,
                'active' => true,
                'destacado' => true,
            ]);

            Product::create([
                'nombre' => 'Somier Americano Luxe',
                'descripcion' => 'Somier americano con cabecero tapizado y almacenamiento.',
                'precio' => 1399.00,
                'precio_oferta' => 999.00,
                'stock' => 10,
                'category_id' => $somieres?->id,
                'active' => true,
                'destacado' => true,
            ]);

            Product::create([
                'nombre' => 'Colchón Ortopédico Duro',
                'descripcion' => 'Colchón ortopédico de firmeza media-alta para descanso terapéutico.',
                'precio' => 2899.00,
                'precio_oferta' => 2199.00,
                'stock' => 8,
                'category_id' => $colchones?->id,
                'active' => true,
                'destacado' => false,
            ]);

            Product::create([
                'nombre' => 'Somier Abatible con Almacén',
                'descripcion' => 'Somier abatible con gran espacio de almacenamiento interior.',
                'precio' => 1999.00,
                'precio_oferta' => 1499.00,
                'stock' => 5,
                'category_id' => $somieres?->id,
                'active' => true,
                'destacado' => false,
            ]);
        }
    }
}
