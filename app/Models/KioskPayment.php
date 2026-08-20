<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KioskPayment extends Model
{
    protected $fillable = [
        'payment_token','kiosk_selection_id','selection_token','phone',
        'provider','payment_method','order_id','reference1','reference2',
        'amount','provider_transaction_id','provider_status','qr_data',
        'qr_image_url','status','paid_at','expires_at',
        'request_payload','provider_response','callback_payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'request_payload' => 'array',
        'provider_response' => 'array',
        'callback_payload' => 'array',
    ];

    public function selection(): BelongsTo
    {
        return $this->belongsTo(KioskSelection::class, 'kiosk_selection_id');
    }
}
