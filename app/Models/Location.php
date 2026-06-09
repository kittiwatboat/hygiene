<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'contact_name',
        'contact_phone',
        'address',

        'province_id',
        'district_id',
        'subdistrict_id',

        'province',
        'district',
        'sub_district',
        'postcode',

        'latitude',
        'longitude',
        'remark',
        'is_active',
    ];

    protected $casts = [
        'province_id' => 'integer',
        'district_id' => 'integer',
        'subdistrict_id' => 'integer',
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function vendingMachines()
    {
        return $this->hasMany(VendingMachine::class);
    }

    public function provinceData()
    {
        return $this->belongsTo(Province::class, 'province_id', 'PROVINCE_ID');
    }

    public function districtData()
    {
        return $this->belongsTo(District::class, 'district_id', 'DISTRICT_ID');
    }

    public function subdistrictData()
    {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id', 'SUB_DISTRICT_ID');
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address,
            $this->sub_district ? 'ต.' . $this->sub_district : null,
            $this->district ? 'อ.' . $this->district : null,
            $this->province ? 'จ.' . $this->province : null,
            $this->postcode,
        ])->filter()->implode(' ');
    }
}
