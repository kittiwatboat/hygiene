<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'district';

    protected $primaryKey = 'DISTRICT_ID';

    public $timestamps = false;

    protected $fillable = [
        'DISTRICT_ID',
        'DISTRICT_CODE',
        'DISTRICT_NAME',
        'GEO_ID',
        'PROVINCE_ID',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'PROVINCE_ID', 'PROVINCE_ID');
    }

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class, 'DISTRICT_ID', 'DISTRICT_ID');
    }
}
