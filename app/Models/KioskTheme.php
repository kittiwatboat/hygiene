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

        'primary_color',
        'secondary_color',
        'accent_color',

        'background_color',
        'text_color',
        'muted_text_color',

        'button_background_color',
        'button_text_color',
        'button_border_color',
        'button_hover_background_color',
        'button_hover_text_color',

        'card_background_color',
        'card_text_color',
        'card_border_color',

        'success_color',
        'warning_color',
        'danger_color',
        'info_color',

        'font_family',

        'button_radius',
        'card_radius',
        'input_radius',

        'logo',

        'settings_json',

        'is_default',
        'is_active',
        'remark',
    ];

    protected $casts = [
        'button_radius' => 'integer',
        'card_radius' => 'integer',
        'input_radius' => 'integer',
        'settings_json' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        return asset('assets/img/kiosk/themes/' . $this->logo);
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
