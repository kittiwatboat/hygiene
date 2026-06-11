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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }
}
