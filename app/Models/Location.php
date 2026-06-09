<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'contact_name',
        'contact_phone',
        'address',
        'province',
        'district',
        'sub_district',
        'postcode',
        'latitude',
        'longitude',
        'remark',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address,
            $this->sub_district ? 'ต.' . $this->sub_district : null,
            $this->district ? 'อ.' . $this->district : null,
            $this->province ? 'จ.' . $this->province : null,
            $this->postcode,
        ])->filter()->implode(' ');
    }
}
