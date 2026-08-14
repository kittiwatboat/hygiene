<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
public function groupPrices(): HasMany
{
    return $this->hasMany(
        \App\Models\ProductGroupPrice::class,
        'product_id'
    );
}

}
