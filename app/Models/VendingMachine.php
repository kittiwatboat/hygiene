<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendingMachine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'location_id',
        'name',
        'code',
        'serial_number',
        'model',
        'status',
        'capacity_liters',
        'remaining_liters',
        'volume_per_press_ml',
        'price_per_press',
        'remark',
        'is_active',
    ];

    protected $casts = [
        'location_id' => 'integer',
        'capacity_liters' => 'decimal:2',
        'remaining_liters' => 'decimal:2',
        'volume_per_press_ml' => 'integer',
        'price_per_press' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
