<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'image',
        'promotion_type',
        'discount_type',
        'discount_value',
        'max_discount',
        'points_required',
        'points_reward',
        'minimum_amount',
        'scope',
        'product_id',
        'usage_limit',
        'used_count',
        'start_at',
        'end_at',
        'sort_order',
        'is_active',
        'description',
        'remark',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'points_required' => 'integer',
        'points_reward' => 'integer',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPromotionTypeTextAttribute(): string
    {
        return match ($this->promotion_type) {
            'earn_points' => 'รับแต้มสะสม',
            'redeem_discount' => 'ใช้แต้มแลกส่วนลด',
            'direct_discount' => 'ส่วนลดทันที',
            default => '-',
        };
    }

    public function getDiscountTypeTextAttribute(): string
    {
        return match ($this->discount_type) {
            'fixed' => 'ลดเป็นจำนวนเงิน',
            'percent' => 'ลดเป็นเปอร์เซ็นต์',
            default => '-',
        };
    }

    public function getDisplayStatusTextAttribute(): string
    {
        if (!$this->is_active) {
            return 'ปิดใช้งาน';
        }

        if ($this->start_at && now()->lt($this->start_at)) {
            return 'รอเริ่ม';
        }

        if ($this->end_at && now()->gt($this->end_at)) {
            return 'หมดอายุ';
        }

        if (
            $this->usage_limit !== null &&
            $this->used_count >= $this->usage_limit
        ) {
            return 'ครบจำนวนแล้ว';
        }

        return 'กำลังใช้งาน';
    }

    public function getDisplayStatusClassAttribute(): string
    {
        return match ($this->display_status_text) {
            'กำลังใช้งาน' => 'bg-label-success',
            'รอเริ่ม' => 'bg-label-info',
            'หมดอายุ', 'ครบจำนวนแล้ว' => 'bg-label-warning',
            default => 'bg-label-secondary',
        };
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image
            ? asset('assets/img/promotions/' . $this->image)
            : null;
    }
}
