<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('descanso_productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_producto');
            $table->enum('tipo_producto', ['Colchón', 'Somier', 'Espaldar']);
            $table->text('descripcion')->nullable();
            $table->string('tamaño')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();
            $table->decimal('precio_venta', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('garantia')->nullable();
            $table->string('imagen')->nullable();
            $table->enum('estado', ['Disponible', 'No disponible'])->default('Disponible');
            $table->date('fecha_registro')->default(now()->toDateString());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descanso_productos');
    }
};
