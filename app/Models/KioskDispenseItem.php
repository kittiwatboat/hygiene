<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KioskDispenseItem extends Model
{
    protected $fillable = [
        'kiosk_dispense_id',
        'tank_id',
        'product_id',
        'price_option_id',
        'product_code',
        'product_name',
        'product_type',
        'quantity',
        'amount_ml_per_unit',
        'target_ml',
        'dispensed_ml',
        'status',
        'started_at',
        'completed_at',
        'failure_message',
    ];

    protected $casts = [
        'amount_ml_per_unit' => 'decimal:2',
        'target_ml' => 'decimal:2',
        'dispensed_ml' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(KioskDispense::class, 'kiosk_dispense_id');
    }

    public function tank(): BelongsTo
    {
        return $this->belongsTo(MachineTank::class, 'tank_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
