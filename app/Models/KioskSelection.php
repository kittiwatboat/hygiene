<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KioskSelection extends Model
{
    protected $fillable = [
        'selection_token',
        'machine_id',
        'machine_group_id',
        'phone',
        'otp_verified',
        'member_found',
        'member_id',
        'items',
        'summary',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'otp_verified' => 'boolean',
        'member_found' => 'boolean',
        'items' => 'array',
        'summary' => 'array',
        'expires_at' => 'datetime',
    ];
}
