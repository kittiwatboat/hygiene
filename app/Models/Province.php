<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'province';

    protected $primaryKey = 'PROVINCE_ID';

    public $timestamps = false;

    protected $fillable = [
        'PROVINCE_ID',
        'PROVINCE_CODE',
        'PROVINCE_NAME',
        'GEO_ID',
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'PROVINCE_ID', 'PROVINCE_ID');
    }
}
