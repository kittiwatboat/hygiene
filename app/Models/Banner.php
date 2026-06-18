<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'link_url',
        'sort_order',
        'start_at',
        'end_at',
        'is_active',
        'remark',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('assets/img/banners/' . $this->image);
    }

    public function getDisplayStatusTextAttribute(): string
    {
        if (!$this->is_active) {
            return 'ปิดใช้งาน';
        }

        if ($this->start_at && now()->lt($this->start_at)) {
            return 'รอเริ่มแสดง';
        }

        if ($this->end_at && now()->gt($this->end_at)) {
            return 'หมดเวลาแสดง';
        }

        return 'กำลังแสดง';
    }

    public function getDisplayStatusClassAttribute(): string
    {
        if (!$this->is_active) {
            return 'bg-label-secondary';
        }

        if ($this->start_at && now()->lt($this->start_at)) {
            return 'bg-label-info';
        }

        if ($this->end_at && now()->gt($this->end_at)) {
            return 'bg-label-warning';
        }

        return 'bg-label-success';
    }
}
