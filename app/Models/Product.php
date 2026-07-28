<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'precio_oferta',
        'stock',
        'imagen',
        'category_id',
        'active',
        'destacado',
    ];

    protected $appends = ['imagen_url'];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'precio_oferta' => 'decimal:2',
            'active' => 'boolean',
            'destacado' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getPrecioActualAttribute(): float
    {
        return $this->precio_oferta ?: $this->precio;
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        if (str_starts_with($this->imagen, 'http')) {
            return $this->imagen;
        }

        return Storage::disk('public')->url($this->imagen);
    }
}
