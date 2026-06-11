<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineTank extends Model
{
    protected $fillable = [
        'machine_id',
        'product_id',
        'tank_no',
        'tank_name',
        'capacity_liters',
        'remaining_liters',
        'low_stock_liters',
        'empty_stock_liters',
        'volume_per_press_ml',
        'price_per_press',
        'is_active',
    ];

    protected $casts = [
        'capacity_liters' => 'decimal:2',
        'remaining_liters' => 'decimal:2',
        'low_stock_liters' => 'decimal:2',
        'empty_stock_liters' => 'decimal:2',
        'volume_per_press_ml' => 'decimal:2',
        'price_per_press' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function machine()
{
    return $this->belongsTo(Machine::class, 'machine_id');
}

public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

public function getStockPercentAttribute(): float
{
    $capacity = (float) $this->capacity_liters;
    $remaining = (float) $this->remaining_liters;

    if ($capacity <= 0) {
        return 0;
    }

    return round(min(max(($remaining / $capacity) * 100, 0), 100), 2);
}

public function getStockStatusTextAttribute(): string
{
    $remaining = (float) $this->remaining_liters;
    $low = (float) $this->low_stock_liters;
    $empty = (float) $this->empty_stock_liters;

    if ($remaining <= $empty) {
        return 'หมด';
    }

    if ($remaining <= $low) {
        return 'ใกล้หมด';
    }

    return 'ปกติ';
}

public function getStockStatusBadgeClassAttribute(): string
{
    $remaining = (float) $this->remaining_liters;
    $low = (float) $this->low_stock_liters;
    $empty = (float) $this->empty_stock_liters;

    if ($remaining <= $empty) {
        return 'bg-label-danger';
    }

    if ($remaining <= $low) {
        return 'bg-label-warning';
    }

    return 'bg-label-success';
}
public function refills()
{
    return $this->hasMany(Refill::class, 'machine_tank_id');
}
}
