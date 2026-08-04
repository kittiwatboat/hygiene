<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrontendTranslation extends Model
{
    protected $table = 'frontend_translations';

    protected $fillable = [
        'language_id',
        'translation_key_id',
        'value',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(
            FrontendLanguage::class,
            'language_id'
        );
    }

    public function translationKey(): BelongsTo
    {
        return $this->belongsTo(
            FrontendTranslationKey::class,
            'translation_key_id'
        );
    }
}
