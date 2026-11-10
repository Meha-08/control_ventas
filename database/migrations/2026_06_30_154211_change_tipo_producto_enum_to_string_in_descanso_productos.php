<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('descanso_productos', function (Blueprint $table) {
            $table->string('tipo_producto_temp', 255)->nullable();
        });

        DB::statement('UPDATE descanso_productos SET tipo_producto_temp = tipo_producto');

        Schema::table('descanso_productos', function (Blueprint $table) {
            $table->dropColumn('tipo_producto');
        });

        Schema::table('descanso_productos', function (Blueprint $table) {
            $table->string('tipo_producto', 255)->default('Colchones');
        });

        DB::table('descanso_productos')
            ->whereNotNull('tipo_producto_temp')
            ->update(['tipo_producto' => DB::raw('tipo_producto_temp')]);

        Schema::table('descanso_productos', function (Blueprint $table) {
            $table->dropColumn('tipo_producto_temp');
        });
    }

    public function down(): void
    {
        Schema::table('descanso_productos', function (Blueprint $table) {
            $table->string('tipo_producto_temp', 255)->nullable();
        });

        DB::statement('UPDATE descanso_productos SET tipo_producto_temp = tipo_producto');

        Schema::table('descanso_productos', function (Blueprint $table) {
            $table->dropColumn('tipo_producto');
        });

        Schema::table('descanso_productos', function (Blueprint $table) {
            $table->enum('tipo_producto', ['Colchones', 'Somier', 'Espaldar', 'Almohadas', 'Cama completa'])->default('Colchones');
        });

        DB::table('descanso_productos')
            ->whereNotNull('tipo_producto_temp')
            ->update(['tipo_producto' => DB::raw('tipo_producto_temp')]);

        Schema::table('descanso_productos', function (Blueprint $table) {
            $table->dropColumn('tipo_producto_temp');
        });
    }
};
