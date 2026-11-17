<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DescansoProductosController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('menu', [MenuController::class, 'index'])->name('menu.index');

Route::middleware('guest')->group(function () {
    Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
    Route::get('login/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback.legacy');
});

Route::middleware(['auth', 'verified', 'role:admin,vendedor,cliente'])->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->middleware('permission:panel.ver')
        ->name('dashboard');
});

Route::middleware(['auth', 'role:admin,vendedor,cliente'])->group(function () {
    Route::middleware('permission:clientes.ver')->group(function () {
        Route::get('clientes', [ClientesController::class, 'index'])->name('clientes.index');

        Route::middleware('permission:clientes.gestionar')->group(function () {
            Route::get('clientes/create', [ClientesController::class, 'create'])->name('clientes.create');
            Route::post('clientes', [ClientesController::class, 'store'])->name('clientes.store');
            Route::get('clientes/{cliente}/edit', [ClientesController::class, 'edit'])->name('clientes.edit');
            Route::patch('clientes/{cliente}', [ClientesController::class, 'update'])->name('clientes.update');
            Route::delete('clientes/{cliente}', [ClientesController::class, 'destroy'])->name('clientes.destroy');
        });
    });

    Route::middleware('permission:usuarios.gestionar')->group(function () {
        Route::get('roles', [RolesController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [RolesController::class, 'create'])->name('roles.create');
        Route::post('roles', [RolesController::class, 'store'])->name('roles.store');
        Route::get('roles/{role}/edit', [RolesController::class, 'edit'])->name('roles.edit');
        Route::patch('roles/{role}', [RolesController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RolesController::class, 'destroy'])->name('roles.destroy');

        Route::get('usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
        Route::get('usuarios/create', [UsuariosController::class, 'create'])->name('usuarios.create');
        Route::post('usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
        Route::get('usuarios/{user}/edit', [UsuariosController::class, 'edit'])->name('usuarios.edit');
        Route::patch('usuarios/{user}', [UsuariosController::class, 'update'])->name('usuarios.update');
        Route::patch('usuarios/{user}/toggle-active', [UsuariosController::class, 'toggleActive'])->name('usuarios.toggle-active');
        Route::delete('usuarios/{user}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
    });

    // Categorías
    Route::middleware('permission:categorias.ver')->group(function () {
        Route::get('categorias', [CategoriasController::class, 'index'])->name('categorias.index');

        Route::middleware('permission:categorias.gestionar')->group(function () {
            Route::get('categorias/create', [CategoriasController::class, 'create'])->name('categorias.create');
            Route::post('categorias', [CategoriasController::class, 'store'])->name('categorias.store');
            Route::get('categorias/{category}/edit', [CategoriasController::class, 'edit'])->name('categorias.edit');
            Route::patch('categorias/{category}', [CategoriasController::class, 'update'])->name('categorias.update');
            Route::delete('categorias/{category}', [CategoriasController::class, 'destroy'])->name('categorias.destroy');
        });
    });

    // Productos
    Route::middleware('permission:productos.ver')->group(function () {
        Route::get('descanso-productos', [DescansoProductosController::class, 'index'])->name('descanso-productos.index');

        Route::middleware('permission:productos.gestionar')->group(function () {
            Route::get('descanso-productos/create', [DescansoProductosController::class, 'create'])->name('descanso-productos.create');
            Route::post('descanso-productos', [DescansoProductosController::class, 'store'])->name('descanso-productos.store');
            Route::get('descanso-productos/{descansoProducto}/edit', [DescansoProductosController::class, 'edit'])->name('descanso-productos.edit');
            Route::patch('descanso-productos/{descansoProducto}', [DescansoProductosController::class, 'update'])->name('descanso-productos.update');
            Route::delete('descanso-productos/{descansoProducto}', [DescansoProductosController::class, 'destroy'])->name('descanso-productos.destroy');
        });
    });

    // Ventas
    Route::middleware('permission:ventas.ver')->group(function () {
        Route::get('ventas', [VentasController::class, 'index'])->name('ventas.index');

        Route::middleware('permission:ventas.gestionar')->group(function () {
            Route::get('ventas/create', [VentasController::class, 'create'])->name('ventas.create');
            Route::post('ventas', [VentasController::class, 'store'])->name('ventas.store');
            Route::get('ventas/{sale}', [VentasController::class, 'show'])->name('ventas.show');
            Route::delete('ventas/{sale}', [VentasController::class, 'destroy'])->name('ventas.destroy');
        });
    });

    // Reportes
    Route::middleware('permission:reportes.ver')->group(function () {
        Route::get('reportes', [ReportesController::class, 'index'])->name('reportes.index');
    });
});

require __DIR__.'/settings.php';
