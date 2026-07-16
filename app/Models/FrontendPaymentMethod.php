<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendPaymentMethod extends Model
{
    use SoftDeletes;

    protected $table = 'frontend_payment_methods';

    protected $fillable = [
        'code',
        'name',
        'subtitle',
        'logo_path',
        'action_key',
        'is_active',
        'sort_order',
        'remark',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? asset('assets/img/frontend/payment-methods/' . $this->logo_path)
            : null;
    }

    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    }

    public function getStatusClassAttribute(): string
    {
        return $this->is_active ? 'bg-label-success' : 'bg-label-secondary';
    }
}
