<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'location_id',
        'serial_number',
        'model',
        'status',
        'is_active',
        'remark',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function tanks()
    {
        return $this->hasMany(MachineTank::class, 'machine_id')->orderBy('tank_no');
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active' => 'พร้อมใช้งาน',
            'maintenance' => 'ซ่อมบำรุง',
            'inactive' => 'ปิดใช้งาน',
            'offline' => 'ออฟไลน์',
            'error' => 'มีปัญหา',
            default => 'ไม่ทราบสถานะ',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-label-success',
            'maintenance' => 'bg-label-warning',
            'inactive' => 'bg-label-secondary',
            'offline' => 'bg-label-dark',
            'error' => 'bg-label-danger',
            default => 'bg-label-secondary',
        };
    }
}
