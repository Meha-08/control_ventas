<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveDescansoProductoRequest;
use App\Models\Category;
use App\Models\DescansoProducto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DescansoProductosController extends Controller
{
    public function index(Request $request): Response
    {
        $productos = DescansoProducto::query()
            ->when($request->search, fn ($q, $search) => $q->where('nombre_producto', 'like', "%{$search}%")
                ->orWhere('descripcion', 'like', "%{$search}%"))
            ->when($request->tipo, fn ($q, $tipo) => $q->where('tipo_producto', $tipo))
            ->when($request->estado, fn ($q, $estado) => $q->where('estado', $estado))
            ->orderBy('nombre_producto')
            ->paginate(10)
            ->withQueryString();

        $categorias = Category::where('active', true)->orderBy('nombre')->get(['id', 'nombre']);

        return Inertia::render('descanso-productos/Index', [
            'productos' => $productos,
            'filters' => $request->only('search', 'tipo', 'estado'),
            'categorias' => $categorias,
        ]);
    }

    public function create(): Response
    {
        $categorias = Category::where('active', true)->orderBy('nombre')->get(['id', 'nombre']);

        return Inertia::render('descanso-productos/Edit', [
            'categorias' => $categorias,
        ]);
    }

    public function store(SaveDescansoProductoRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['fecha_registro'])) {
            $data['fecha_registro'] = now()->toDateString();
        }

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('descanso-productos', 'public');
        }

        DescansoProducto::create($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Producto de descanso creado.')]);

        return to_route('descanso-productos.index');
    }

    public function edit(DescansoProducto $descansoProducto): Response
    {
        $categorias = Category::where('active', true)->orderBy('nombre')->get(['id', 'nombre']);

        return Inertia::render('descanso-productos/Edit', [
            'product' => $descansoProducto,
            'categorias' => $categorias,
        ]);
    }

    public function update(SaveDescansoProductoRequest $request, DescansoProducto $descansoProducto): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['fecha_registro'])) {
            $data['fecha_registro'] = now()->toDateString();
        }

        if ($request->hasFile('imagen')) {
            if ($descansoProducto->imagen) {
                Storage::disk('public')->delete($descansoProducto->imagen);
            }

            $data['imagen'] = $request->file('imagen')->store('descanso-productos', 'public');
        }

        $descansoProducto->update($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Producto de descanso actualizado.')]);

        return to_route('descanso-productos.index');
    }

    public function destroy(DescansoProducto $descansoProducto): RedirectResponse
    {
        $descansoProducto->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Producto de descanso eliminado.')]);

        return to_route('descanso-productos.index');
    }
}
