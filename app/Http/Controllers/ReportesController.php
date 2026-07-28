<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class ReportesController extends Controller
{
    public function index(Request $request): Response
    {
        $startDate = $request->fecha_desde ? CarbonImmutable::parse($request->fecha_desde) : CarbonImmutable::now()->subMonth();
        $endDate = $request->fecha_hasta ? CarbonImmutable::parse($request->fecha_hasta) : CarbonImmutable::now();

        $cacheKey = "reportes_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}";

        $data = Cache::remember($cacheKey, 600, function () use ($startDate, $endDate) {
            $ventas = Sale::whereBetween('created_at', [$startDate, $endDate])
                ->where('estado', '!=', 'anulado');

            $ventasResumen = (clone $ventas)
                ->selectRaw('COALESCE(SUM(total), 0) as total_ventas, COUNT(*) as cantidad_ventas')
                ->first();

            $productosMasVendidos = Sale::whereBetween('sales.created_at', [$startDate, $endDate])
                ->where('sales.estado', '!=', 'anulado')
                ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                ->selectRaw('sale_items.product_name, sum(sale_items.cantidad) as total_vendido, sum(sale_items.subtotal) as total_ingresos')
                ->groupBy('sale_items.product_name')
                ->orderByDesc('total_vendido')
                ->limit(10)
                ->get();

            $ventasPorDia = Sale::whereBetween('sales.created_at', [$startDate, $endDate])
                ->where('estado', '!=', 'anulado')
                ->selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad, SUM(total) as total')
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

            $productosStock = Product::where('active', true)
                ->with('category:id,nombre')
                ->select('id', 'nombre', 'stock', 'category_id', 'precio')
                ->orderBy('stock')
                ->limit(100)
                ->get();

            return [
                'totalVentas' => (float) ($ventasResumen->total_ventas ?? 0),
                'cantidadVentas' => (int) ($ventasResumen->cantidad_ventas ?? 0),
                'productosMasVendidos' => $productosMasVendidos,
                'ventasPorDia' => $ventasPorDia,
                'productosStock' => $productosStock,
            ];
        });

        return Inertia::render('reportes/Index', array_merge($data, [
            'filters' => $request->only('fecha_desde', 'fecha_hasta'),
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]));
    }
}
