<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoriasController extends Controller
{
    public function index(Request $request): Response
    {
        $categorias = Category::query()
            ->when($request->search, fn ($q, $search) => $q->where('nombre', 'like', "%{$search}%"))
            ->withCount('products')
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('categorias/Index', [
            'categorias' => $categorias,
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('categorias/Edit');
    }

    public function store(SaveCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Categoría creada.')]);

        return to_route('categorias.index');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('categorias/Edit', [
            'category' => $category,
        ]);
    }

    public function update(SaveCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Categoría actualizada.')]);

        return to_route('categorias.index');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Categoría eliminada.')]);

        return to_route('categorias.index');
    }
}
