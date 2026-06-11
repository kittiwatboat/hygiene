<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refill extends Model
{
    protected $fillable = [
        'machine_id',
        'machine_tank_id',
        'product_id',
        'before_liters',
        'refill_liters',
        'after_liters',
        'refill_by',
        'refill_at',
        'remark',
    ];

    protected $casts = [
        'before_liters' => 'decimal:2',
        'refill_liters' => 'decimal:2',
        'after_liters' => 'decimal:2',
        'refill_at' => 'datetime',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function tank()
    {
        return $this->belongsTo(MachineTank::class, 'machine_tank_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function refillBy()
    {
        return $this->belongsTo(User::class, 'refill_by');
    }
}
