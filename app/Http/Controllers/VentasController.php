<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSaleRequest;
use App\Models\Cliente;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class VentasController extends Controller
{
    public function index(Request $request): Response
    {
        $ventas = Sale::query()
            ->with('user:id,name', 'cliente:id,name', 'items')
            ->when($request->search, fn ($q, $search) => $q->where('nro_comprobante', 'like', "%{$search}%"))
            ->when($request->estado, fn ($q, $estado) => $q->where('estado', $estado))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('ventas/Index', [
            'ventas' => $ventas,
            'filters' => $request->only('search', 'estado'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('ventas/Edit', [
            'clientes' => Cliente::select('id', 'name')->orderBy('name')->get(),
            'productos' => Product::where('active', true)
                ->where('stock', '>', 0)
                ->with('category:id,nombre')
                ->select('id', 'nombre', 'precio', 'precio_oferta', 'stock', 'category_id')
                ->get(),
        ]);
    }

    public function store(SaveSaleRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'user_id' => $request->user()->id,
                'cliente_id' => $request->cliente_id,
                'total' => $request->total,
                'estado' => 'completado',
                'tipo_comprobante' => $request->tipo_comprobante,
                'nro_comprobante' => $request->nro_comprobante,
            ]);

            $sale->items()->createMany($request->items);

            foreach ($request->items as $item) {
                if ($item['product_id']) {
                    Product::where('id', $item['product_id'])->decrement('stock', $item['cantidad']);
                }
            }
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Venta registrada.')]);

        return to_route('ventas.index');
    }

    public function show(Sale $sale): Response
    {
        $sale->load('user:id,name', 'cliente:id,name', 'items');

        return Inertia::render('ventas/Show', [
            'venta' => $sale,
        ]);
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->load('items');

        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock', $item->cantidad);
                }
            }

            $sale->update(['estado' => 'anulado']);
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Venta anulada.')]);

        return to_route('ventas.index');
    }
}
