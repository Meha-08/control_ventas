<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DescansoProducto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(Request $request): Response
    {
        $productos = DescansoProducto::query()
            ->where('estado', 'Disponible')
            ->orderBy('nombre_producto')
            ->get();

        $tipos = Category::activeNames();

        if ($tipos === []) {
            $tipos = $productos->pluck('tipo_producto')->filter()->unique()->values()->all();
        }

        $categoriaImagenes = [];
        foreach ($tipos as $tipo) {
            $producto = $productos->firstWhere('tipo_producto', $tipo);
            $categoriaImagenes[$tipo] = $producto?->imagen_url
                ?? match (true) {
                    str_contains(strtolower($tipo), 'colch') => 'https://images.unsplash.com/photo-1630493565608-e34c7f8b2a48?w=600&q=80',
                    str_contains(strtolower($tipo), 'somier') => 'https://images.unsplash.com/photo-1505692952047-1a78307da8e2?w=600&q=80',
                    str_contains(strtolower($tipo), 'espalda') => 'https://images.unsplash.com/photo-1616486029423-aaa4789e8c9a?w=600&q=80',
                    str_contains(strtolower($tipo), 'almohada') => 'https://images.unsplash.com/photo-1512918553160-c6e76e7b91b3?w=600&q=80',
                    default => 'https://images.unsplash.com/photo-1616627547584-bf28cee262db?w=600&q=80',
                };
        }

        $heroImagen = (string) config('app.hero_image');

        return Inertia::render('Welcome', [
            'canRegister' => true,
            'productos' => $productos,
            'tipos' => $tipos,
            'categoriaImagenes' => $categoriaImagenes,
            'heroImagen' => $heroImagen,
        ]);
    }
}
