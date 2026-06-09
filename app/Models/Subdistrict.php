<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $table = 'subdistrict';

    protected $primaryKey = 'SUB_DISTRICT_ID';

    public $timestamps = false;

    protected $fillable = [
        'SUB_DISTRICT_ID',
        'SUB_DISTRICT_CODE',
        'SUB_DISTRICT_NAME',
        'DISTRICT_ID',
        'PROVINCE_ID',
        'GEO_ID',
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'DISTRICT_ID', 'DISTRICT_ID');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'PROVINCE_ID', 'PROVINCE_ID');
    }

    public function zipcode()
    {
        return $this->hasOne(Zipcode::class, 'SUB_DISTRICT_ID', 'SUB_DISTRICT_ID');
    }
}
