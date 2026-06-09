<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model
{
    protected $table = 'zipcode';

    protected $primaryKey = 'ZIPCODE_ID';

    public $timestamps = false;

    protected $fillable = [
        'ZIPCODE_ID',
        'SUB_DISTRICT_CODE',
        'PROVINCE_ID',
        'DISTRICT_ID',
        'SUB_DISTRICT_ID',
        'ZIPCODE',
    ];

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'SUB_DISTRICT_ID', 'SUB_DISTRICT_ID');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'DISTRICT_ID', 'DISTRICT_ID');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'PROVINCE_ID', 'PROVINCE_ID');
    }
}
