<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAlert extends Model
{
    protected $fillable = [
        'alert_key',
        'type',
        'level',
        'source_type',
        'source_id',
        'title',
        'message',
        'url',
        'status',
        'read_at',
        'resolved_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function getLevelTextAttribute(): string
    {
        return match ($this->level) {
            'urgent' => 'ด่วนมาก',
            'danger' => 'ผิดปกติ',
            'warning' => 'ควรตรวจสอบ',
            'info' => 'ข้อมูล',
            default => 'แจ้งเตือน',
        };
    }

    public function getLevelBadgeClassAttribute(): string
    {
        return match ($this->level) {
            'urgent', 'danger' => 'bg-label-danger',
            'warning' => 'bg-label-warning',
            'info' => 'bg-label-info',
            default => 'bg-label-secondary',
        };
    }

    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'stock' => 'tabler-droplet-exclamation',
            'machine' => 'tabler-wash-machine',
            'printer' => 'tabler-printer-off',
            'maintenance' => 'tabler-tools',
            default => 'tabler-bell',
        };
    }
}
