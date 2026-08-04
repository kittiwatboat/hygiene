<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FrontendTranslationKey extends Model
{
    protected $table = 'frontend_translation_keys';

    protected $fillable = [
        'key',
        'group',
        'description',
        'default_value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(
            FrontendTranslation::class,
            'translation_key_id'
        );
    }
}
