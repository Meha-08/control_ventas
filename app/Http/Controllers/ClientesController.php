<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientesController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('clientes/Edit');
    }

    public function index(Request $request): Response
    {
        $clientes = Cliente::query()
            ->when($request->search, fn ($q, $search) => $q->whereAny(['name', 'email', 'phone', 'address'], 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('clientes/Index', [
            'clientes' => $clientes,
            'filters' => $request->only('search'),
        ]);
    }

    public function store(SaveClienteRequest $request): RedirectResponse
    {
        Cliente::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Cliente creado.')]);

        return to_route('clientes.index');
    }

    public function edit(Request $request, Cliente $cliente): Response
    {
        return Inertia::render('clientes/Edit', [
            'cliente' => $cliente,
        ]);
    }

    public function update(SaveClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Cliente actualizado.')]);

        return to_route('clientes.index');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Cliente eliminado.')]);

        return to_route('clientes.index');
    }
}
