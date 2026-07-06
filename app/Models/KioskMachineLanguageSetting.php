<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KioskMachineLanguageSetting extends Model
{
    protected $fillable = [
        'machine_id',
        'language_id',
        'sort_order',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(KioskLanguage::class);
    }
}
