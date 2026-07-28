<?php

namespace App\Http\Controllers;

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

        return Inertia::render('menu/Index', [
            'productos' => $productos,
            'filters' => $request->only('search', 'tipo'),
        ]);
    }
}
