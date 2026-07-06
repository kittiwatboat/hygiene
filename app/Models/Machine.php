<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machine extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'code',
    'name',
    'location_id',
    'serial_number',
    'model',
    'status',
    'is_active',
    'remark',
    'last_seen_at',
];

    protected $casts = [
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
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
    public function printers()
{
    return $this->hasMany(Printer::class, 'machine_id');
}
public function refills()
{
    return $this->hasMany(Refill::class, 'machine_id');
}
public function maintenances()
{
    return $this->hasMany(Maintenance::class, 'machine_id');
}
public function sales()
{
    return $this->hasMany(Sale::class, 'machine_id');
}

public function kioskLanguageSettings()
{
    return $this->hasMany(\App\Models\KioskMachineLanguageSetting::class, 'machine_id');
}
}
