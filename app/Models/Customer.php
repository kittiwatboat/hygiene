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
        'phone',
        'email',
        'points_balance',
        'status',
        'is_active',
        'last_used_at',
        'remark',
    ];

    protected $casts = [
        'points_balance' => 'integer',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
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
}
