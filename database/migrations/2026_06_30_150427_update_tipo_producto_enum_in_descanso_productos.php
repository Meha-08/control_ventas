<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('descanso_productos', function ($table) {
            $table->string('tipo_producto_temp', 50)->nullable();
        });

        DB::statement('UPDATE descanso_productos SET tipo_producto_temp = tipo_producto');

        Schema::table('descanso_productos', function ($table) {
            $table->dropColumn('tipo_producto');
        });

        Schema::table('descanso_productos', function ($table) {
            $table->enum('tipo_producto', ['Colchones', 'Somier', 'Espaldar', 'Almohadas', 'Cama completa'])->default('Colchones');
        });

        DB::table('descanso_productos')
            ->where('tipo_producto_temp', 'Colchón')
            ->update(['tipo_producto' => 'Colchones']);

        DB::table('descanso_productos')
            ->whereNotNull('tipo_producto_temp')
            ->whereNull('tipo_producto')
            ->update(['tipo_producto' => DB::raw('tipo_producto_temp')]);

        Schema::table('descanso_productos', function ($table) {
            $table->dropColumn('tipo_producto_temp');
        });
    }

    public function down(): void
    {
        Schema::table('descanso_productos', function ($table) {
            $table->string('tipo_producto_temp', 50)->nullable();
        });

        DB::statement('UPDATE descanso_productos SET tipo_producto_temp = tipo_producto');

        Schema::table('descanso_productos', function ($table) {
            $table->dropColumn('tipo_producto');
        });

        Schema::table('descanso_productos', function ($table) {
            $table->enum('tipo_producto', ['Colchón', 'Somier', 'Espaldar'])->default('Colchón');
        });

        DB::table('descanso_productos')
            ->where('tipo_producto_temp', 'Colchones')
            ->update(['tipo_producto' => 'Colchón']);

        DB::table('descanso_productos')
            ->whereNotNull('tipo_producto_temp')
            ->whereNull('tipo_producto')
            ->update(['tipo_producto' => DB::raw('tipo_producto_temp')]);

        Schema::table('descanso_productos', function ($table) {
            $table->dropColumn('tipo_producto_temp');
        });
    }
};
