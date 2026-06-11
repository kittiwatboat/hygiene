<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Printer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'machine_id',
        'code',
        'name',
        'brand',
        'model',
        'serial_number',
        'connection_type',
        'ip_address',
        'port',
        'paper_size',
        'status',
        'paper_available',
        'is_active',
        'last_seen_at',
        'remark',
    ];

    protected $casts = [
        'paper_available' => 'boolean',
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active' => 'พร้อมใช้งาน',
            'inactive' => 'ปิดใช้งาน',
            'offline' => 'ออฟไลน์',
            'error' => 'มีปัญหา',
            'paper_out' => 'กระดาษหมด',
            default => 'ไม่ทราบสถานะ',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-label-success',
            'inactive' => 'bg-label-secondary',
            'offline' => 'bg-label-dark',
            'error' => 'bg-label-danger',
            'paper_out' => 'bg-label-warning',
            default => 'bg-label-secondary',
        };
    }

    public function getConnectionTypeTextAttribute(): string
    {
        return match ($this->connection_type) {
            'usb' => 'USB',
            'lan' => 'LAN',
            'wifi' => 'Wi-Fi',
            'bluetooth' => 'Bluetooth',
            default => $this->connection_type ?: '-',
        };
    }
}
