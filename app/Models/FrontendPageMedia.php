<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendPageMedia extends Model
{
    use SoftDeletes;

    protected $table = 'frontend_page_media';

    protected $fillable = [
        'frontend_page_id',
        'media_type',
        'file_path',
        'title',
        'subtitle',
        'duration_seconds',
        'object_fit',
        'sort_order',
        'is_active',
        'remark',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(FrontendPage::class, 'frontend_page_id');
    }

    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        if ($this->media_type === 'video') {
            return asset('assets/videos/frontend/pages/' . $this->file_path);
        }

        return asset('assets/img/frontend/pages/' . $this->file_path);
    }

    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    }

    public function getStatusClassAttribute(): string
    {
        return $this->is_active
            ? 'bg-label-success'
            : 'bg-label-secondary';
    }
}
