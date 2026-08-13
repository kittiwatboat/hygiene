<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MachineGroup extends Model
{
  protected $fillable = ['name', 'code', 'frontend_theme_id', 'is_active', 'remark'];
  protected $casts = ['is_active' => 'boolean'];
  public function theme(): BelongsTo
  {
    return $this->belongsTo(FrontendTheme::class, 'frontend_theme_id');
  }
  public function machines(): HasMany
  {
    return $this->hasMany(Machine::class, 'machine_group_id');
  }
}
