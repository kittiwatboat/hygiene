<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'machine_id',
        'code',
        'type',
        'status',
        'priority',
        'problem',
        'solution',
        'reported_by',
        'assigned_to',
        'reported_at',
        'started_at',
        'finished_at',
        'remark',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getTypeTextAttribute(): string
    {
        return match ($this->type) {
            'machine_error' => 'เครื่องขัดข้อง',
            'printer_error' => 'เครื่องปริ้นมีปัญหา',
            'network_error' => 'Network / Internet',
            'cleaning' => 'ทำความสะอาด',
            'refill_issue' => 'ปัญหาการเติมน้ำยา',
            'other' => 'อื่น ๆ',
            default => 'ไม่ทราบประเภท',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'reported' => 'แจ้งปัญหา',
            'assigned' => 'มอบหมายงานแล้ว',
            'repairing' => 'กำลังซ่อม',
            'completed' => 'ซ่อมเสร็จแล้ว',
            'cancelled' => 'ยกเลิก',
            default => 'ไม่ทราบสถานะ',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'reported' => 'bg-label-danger',
            'assigned' => 'bg-label-info',
            'repairing' => 'bg-label-warning',
            'completed' => 'bg-label-success',
            'cancelled' => 'bg-label-secondary',
            default => 'bg-label-secondary',
        };
    }

    public function getPriorityTextAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'ต่ำ',
            'normal' => 'ปกติ',
            'high' => 'ด่วน',
            'urgent' => 'ด่วนมาก',
            default => 'ปกติ',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'bg-label-secondary',
            'normal' => 'bg-label-primary',
            'high' => 'bg-label-warning',
            'urgent' => 'bg-label-danger',
            default => 'bg-label-primary',
        };
    }
}
