<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KioskDispense extends Model
{
    protected $fillable = [
        'dispense_token',
        'kiosk_payment_id',
        'kiosk_selection_id',
        'payment_token',
        'selection_token',
        'machine_id',
        'status',
        'started_at',
        'completed_at',
        'failure_message',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(KioskPayment::class, 'kiosk_payment_id');
    }

    public function selection(): BelongsTo
    {
        return $this->belongsTo(KioskSelection::class, 'kiosk_selection_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(KioskDispenseItem::class, 'kiosk_dispense_id');
    }
}
