<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointTransaction extends Model
{
    protected $fillable = [
        'customer_id',
        'sale_id',
        'promotion_id',
        'type',
        'points',
        'balance_before',
        'balance_after',
        'reference_no',
        'description',
        'expired_at',
        'created_by',
    ];

    protected $casts = [
        'points' => 'integer',
        'balance_before' => 'integer',
        'balance_after' => 'integer',
        'expired_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeTextAttribute(): string
    {
        return match ($this->type) {
            'earn' => 'ได้รับแต้ม',
            'redeem' => 'ใช้แต้ม',
            'adjust' => 'ปรับแต้มโดยแอดมิน',
            'expire' => 'แต้มหมดอายุ',
            'refund' => 'คืนแต้ม',
            default => 'รายการแต้ม',
        };
    }

    public function getTypeClassAttribute(): string
    {
        return match ($this->type) {
            'earn', 'refund' => 'bg-label-success',
            'redeem', 'expire' => 'bg-label-danger',
            'adjust' => 'bg-label-warning',
            default => 'bg-label-secondary',
        };
    }
}
