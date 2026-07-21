<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'member_code',
    'name',
    'email',
    'phone',
    'line_id',

    'member_type',
    'registered_at',
    'branch_id',

    'points_balance',
    'total_topup',
    'last_used_at',

    'status',
    'is_active',

    'is_new_member_discount_used',
];

    protected $casts = [
    'registered_at' => 'datetime',
    'last_used_at' => 'datetime',

    'points_balance' => 'integer',
    'total_topup' => 'decimal:2',

    'is_active' => 'boolean',
    'is_new_member_discount_used' => 'boolean',
];
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function getStatusTextAttribute(): string
    {
        if (!$this->is_active) {
            return 'ปิดใช้งาน';
        }

        return match ($this->status) {
            'active' => 'ใช้งานปกติ',
            'suspended' => 'ระงับการใช้งาน',
            'blocked' => 'บล็อก',
            default => 'ไม่ทราบสถานะ',
        };
    }

    public function getStatusClassAttribute(): string
    {
        if (!$this->is_active) {
            return 'bg-label-secondary';
        }

        return match ($this->status) {
            'active' => 'bg-label-success',
            'suspended' => 'bg-label-warning',
            'blocked' => 'bg-label-danger',
            default => 'bg-label-secondary',
        };
    }
    public const MEMBER_TYPE_OPTIONS = [
    'member' => 'Member',
    'non_member' => 'Non-member',
    'new_member' => 'New member',
];
}
