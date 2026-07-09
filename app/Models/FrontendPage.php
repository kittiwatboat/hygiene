<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendPage extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'page_key',
    'name',
    'title',
    'subtitle',
    'settings_json',
    'is_active',
    'remark',
];

    protected $casts = [
        'settings_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(FrontendPageMedia::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function activeMedia(): HasMany
    {
        return $this->media()
            ->where('is_active', true);
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
