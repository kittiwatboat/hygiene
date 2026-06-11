<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'machine_id',
        'machine_tank_id',
        'product_id',
        'press_count',
        'volume_per_press_ml',
        'volume_liters',
        'price_per_press',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_ref',
        'sold_at',
        'created_by',
        'payload',
        'remark',
    ];

    protected $casts = [
        'press_count' => 'integer',
        'volume_per_press_ml' => 'decimal:2',
        'volume_liters' => 'decimal:3',
        'price_per_press' => 'decimal:2',
        'amount' => 'decimal:2',
        'sold_at' => 'datetime',
        'payload' => 'array',
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPaymentMethodTextAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'เงินสด',
            'qr' => 'QR Payment',
            'true_money' => 'TrueMoney',
            'shopee_pay' => 'ShopeePay',
            'card' => 'บัตรเครดิต',
            'free' => 'ฟรี / ทดสอบ',
            default => $this->payment_method ?: '-',
        };
    }

    public function getPaymentStatusTextAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'ชำระแล้ว',
            'pending' => 'รอชำระ',
            'failed' => 'ชำระไม่สำเร็จ',
            'refunded' => 'คืนเงินแล้ว',
            default => $this->payment_status ?: '-',
        };
    }

    public function getPaymentStatusBadgeClassAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-label-success',
            'pending' => 'bg-label-warning',
            'failed' => 'bg-label-danger',
            'refunded' => 'bg-label-info',
            default => 'bg-label-secondary',
        };
    }
}
