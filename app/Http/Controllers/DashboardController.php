<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $now = Carbon::now();

        $stats = [
            'totalClientes' => Cliente::count(),
            'totalUsuarios' => User::count(),
            'clientesHoy' => Cliente::whereDate('created_at', $now->toDateString())->count(),
            'usuariosActivos' => User::where('active', true)->count(),
        ];

        $recentClientes = Cliente::query()
            ->latest()
            ->limit(5)
            ->get();

        $recentUsuarios = User::query()
            ->latest()
            ->limit(5)
            ->get();

        $clientesPorSemana = Cliente::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $chartLabels[] = $now->copy()->subDays($i)->isoFormat('ddd');
            $chartData[] = $clientesPorSemana[$date] ?? 0;
        }

        $recentActivity = collect();

        Cliente::query()->latest()->limit(3)->get()->each(function ($cliente) use ($recentActivity) {
            $recentActivity->push([
                'type' => 'cliente',
                'message' => "Nuevo cliente: {$cliente->name}",
                'time' => $cliente->created_at->diffForHumans(),
                'created_at' => $cliente->created_at->toISOString(),
            ]);
        });

        User::query()->latest()->limit(3)->get()->each(function ($user) use ($recentActivity) {
            $recentActivity->push([
                'type' => 'usuario',
                'message' => "Usuario registrado: {$user->name}",
                'time' => $user->created_at->diffForHumans(),
                'created_at' => $user->created_at->toISOString(),
            ]);
        });

        $recentActivity = $recentActivity->sortByDesc('created_at')->values()->take(6);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentClientes' => $recentClientes,
            'recentUsuarios' => $recentUsuarios,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'recentActivity' => $recentActivity,
        ]);
    }
}
