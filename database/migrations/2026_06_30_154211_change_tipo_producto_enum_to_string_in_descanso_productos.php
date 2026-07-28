<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE descanso_productos MODIFY COLUMN tipo_producto VARCHAR(255) NOT NULL DEFAULT \'Colchones\'');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE descanso_productos MODIFY COLUMN tipo_producto ENUM(\'Colchones\',\'Somier\',\'Espaldar\',\'Almohadas\',\'Cama completa\') NOT NULL DEFAULT \'Colchones\'');
    }
};
