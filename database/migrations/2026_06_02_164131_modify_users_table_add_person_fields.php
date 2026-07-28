<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('nombre_u')->after('id');
            $table->string('a_paterno')->after('nombre_u');
            $table->string('a_materno')->nullable()->after('a_paterno');
            $table->date('fecha_ingreso')->nullable()->after('a_materno');
            $table->date('fecha_fin')->nullable()->after('fecha_ingreso');
            $table->string('telefono', 20)->nullable()->after('fecha_fin');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nombre_u', 'a_paterno', 'a_materno', 'fecha_ingreso', 'fecha_fin', 'telefono']);
            $table->string('name')->after('id');
        });
    }
};
