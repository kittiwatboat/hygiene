<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FrontendLanguage extends Model
{
    use SoftDeletes;
protected $table = 'frontend_languages';
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'flag_image',
        'locale',
        'sort_order',
        'is_active',
        'remark',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getFlagUrlAttribute(): ?string
    {
        if (!$this->flag_image) {
            return null;
        }

        return asset('assets/img/languages/' . $this->flag_image);
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
    public function setting(): HasOne
{
    return $this->hasOne(FrontendLanguageSetting::class, 'language_id');
}
public function machineSettings()
{
    return $this->hasMany(\App\Models\FrontendMachineLanguageSetting::class, 'language_id');
}
}
