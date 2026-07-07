<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendTheme extends Model
{
    use SoftDeletes;

    protected $table = 'frontend_themes';

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

        'header_type',
        'header_background_color',
        'header_background_image',
        'header_background_video',
        'header_logo_main',
        'header_logo_right_1',
        'header_logo_right_2',
        'header_height',

        'is_default',
        'is_active',
        'remark',
    ];

    protected $casts = [
        'header_height' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getBackgroundImageUrlAttribute(): ?string
    {
        return $this->background_image
            ? asset('assets/img/frontend/themes/' . $this->background_image)
            : null;
    }

    public function getBackgroundVideoUrlAttribute(): ?string
    {
        return $this->background_video
            ? asset('assets/videos/frontend/themes/' . $this->background_video)
            : null;
    }

    public function getHeaderBackgroundImageUrlAttribute(): ?string
    {
        return $this->header_background_image
            ? asset('assets/img/frontend/themes/' . $this->header_background_image)
            : null;
    }

    public function getHeaderBackgroundVideoUrlAttribute(): ?string
    {
        return $this->header_background_video
            ? asset('assets/videos/frontend/themes/' . $this->header_background_video)
            : null;
    }

    public function getHeaderLogoMainUrlAttribute(): ?string
    {
        return $this->header_logo_main
            ? asset('assets/img/frontend/themes/' . $this->header_logo_main)
            : null;
    }

    public function getHeaderLogoRight1UrlAttribute(): ?string
    {
        return $this->header_logo_right_1
            ? asset('assets/img/frontend/themes/' . $this->header_logo_right_1)
            : null;
    }

    public function getHeaderLogoRight2UrlAttribute(): ?string
    {
        return $this->header_logo_right_2
            ? asset('assets/img/frontend/themes/' . $this->header_logo_right_2)
            : null;
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
