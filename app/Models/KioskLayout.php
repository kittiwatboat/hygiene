<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KioskLayout extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'theme_id',
        'name',

        'show_top_section',
        'top_section_type',
        'top_section_image',
        'top_section_video',
        'top_section_height',

        'show_overlay_logo',
        'overlay_logo',
        'show_overlay_icon',
        'overlay_icon',
        'overlay_position',
        'overlay_top',
        'overlay_left',
        'overlay_right',
        'overlay_bottom',
        'overlay_width',
        'overlay_height',

        'show_media_overlay',
        'media_overlay_color',

        'settings_json',

        'is_default',
        'is_active',
        'remark',
    ];

    protected $casts = [
        'show_top_section' => 'boolean',
        'top_section_height' => 'integer',

        'show_overlay_logo' => 'boolean',
        'show_overlay_icon' => 'boolean',

        'overlay_top' => 'integer',
        'overlay_left' => 'integer',
        'overlay_right' => 'integer',
        'overlay_bottom' => 'integer',
        'overlay_width' => 'integer',
        'overlay_height' => 'integer',

        'show_media_overlay' => 'boolean',

        'settings_json' => 'array',

        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(KioskTheme::class, 'theme_id');
    }

    public function getTopSectionImageUrlAttribute(): ?string
    {
        if (!$this->top_section_image) {
            return null;
        }

        return asset('assets/img/kiosk/layouts/' . $this->top_section_image);
    }

    public function getTopSectionVideoUrlAttribute(): ?string
    {
        if (!$this->top_section_video) {
            return null;
        }

        return asset('assets/videos/kiosk/layouts/' . $this->top_section_video);
    }

    public function getOverlayLogoUrlAttribute(): ?string
    {
        if (!$this->overlay_logo) {
            return null;
        }

        return asset('assets/img/kiosk/layouts/' . $this->overlay_logo);
    }

    public function getOverlayIconUrlAttribute(): ?string
    {
        if (!$this->overlay_icon) {
            return null;
        }

        return asset('assets/img/kiosk/layouts/' . $this->overlay_icon);
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
    public function screens(): HasMany
{
    return $this->hasMany(KioskScreen::class, 'layout_id');
}
}
