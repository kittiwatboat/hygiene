<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KioskTheme extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',

        'text_color',

        'background_type',
        'background_color',
        'background_image',
        'background_video',

        'button_color',
        'button_text_color',
        'button_hover_border_color',

        'is_default',
        'is_active',
        'remark',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getBackgroundImageUrlAttribute(): ?string
    {
        if (!$this->background_image) {
            return null;
        }

        return asset('assets/img/kiosk/themes/' . $this->background_image);
    }

    public function getBackgroundVideoUrlAttribute(): ?string
    {
        if (!$this->background_video) {
            return null;
        }

        return asset('assets/videos/kiosk/themes/' . $this->background_video);
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
