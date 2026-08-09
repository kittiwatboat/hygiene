<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    public const MEMBER_TYPE_OPTIONS = [
        'member' => 'Member',
        'non_member' => 'Non-member',
        'new_member' => 'New member',
    ];

    protected $fillable = [
        'member_code',
        'name',
        'phone',
        'email',
        'line_id',
        'member_type',
        'registered_at',
        'branch_id',

        /*
        |--------------------------------------------------------------------------
        | points_balance
        |--------------------------------------------------------------------------
        | ยังคงไว้ใน fillable เพราะระบบต้องสามารถกำหนดแต้มเริ่มต้น
        | และปรับยอดจาก business flow ภายในระบบได้
        |
        | การห้ามแก้แต้มจากหน้าจัดการสมาชิก ถูกควบคุมที่ Controller/Form
        | โดยไม่รับ points_balance จาก request ของ create/edit/import
        */
        'points_balance',

        'total_topup',
        'status',
        'is_active',
        'is_new_member_discount_used',
        'last_used_at',
        'remark',
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
        return $this->hasMany(
            PointTransaction::class,
            'customer_id'
        );
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(
            Branch::class,
            'branch_id'
        );
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
