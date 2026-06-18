<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'code',
    'name',
    'type',
    'unit',
    'description',
    'image',
    'is_active',
];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tanks()
    {
        return $this->hasMany(MachineTank::class, 'product_id');
    }
    public function sales()
{
    return $this->hasMany(Sale::class, 'product_id');
}
public function getImageUrlAttribute(): string
{
    if ($this->image) {
        return asset('storage/' . $this->image);
    }

    return asset('assets/img/default-product.png');
}
}
