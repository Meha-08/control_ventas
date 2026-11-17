<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DescansoProducto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function index(Request $request): Response
    {
        $productos = DescansoProducto::query()
            ->where('estado', 'Disponible')
            ->when($request->search, fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('nombre_producto', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            }))
            ->when($request->tipo, fn ($q, $tipo) => $q->where('tipo_producto', $tipo))
            ->orderBy('nombre_producto')
            ->get();

        $tipos = Category::activeNames();

        if ($tipos === []) {
            $tipos = DescansoProducto::query()
                ->where('estado', 'Disponible')
                ->distinct()
                ->orderBy('tipo_producto')
                ->pluck('tipo_producto')
                ->all();
        }

        return Inertia::render('menu/Index', [
            'heroImagen' => (string) config('app.hero_image'),
            'productos' => $productos,
            'tipos' => $tipos,
            'filters' => $request->only('search', 'tipo'),
        ]);
    }
}
