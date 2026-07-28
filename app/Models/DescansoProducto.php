<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DescansoProducto extends Model
{
    protected $table = 'descanso_productos';

    protected $fillable = [
        'nombre_producto',
        'tipo_producto',
        'descripcion',
        'tamaño',
        'material',
        'color',
        'precio_venta',
        'stock',
        'garantia',
        'imagen',
        'estado',
        'fecha_registro',
    ];

    protected $appends = ['imagen_url'];

    protected function casts(): array
    {
        return [
            'precio_venta' => 'decimal:2',
            'fecha_registro' => 'date',
        ];
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        return Storage::disk('public')->url($this->imagen);
    }
}
