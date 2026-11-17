<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /** @return list<string> */
    public static function activeNames(): array
    {
        return static::query()
            ->where('active', true)
            ->orderBy('nombre')
            ->pluck('nombre')
            ->all();
    }
}
