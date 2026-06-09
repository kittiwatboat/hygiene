<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Zipcode;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    /**
     * Get districts by province id.
     */
    public function districts(int $province): JsonResponse
    {
        $districts = District::query()
            ->where('PROVINCE_ID', $province)
            ->orderBy('DISTRICT_NAME')
            ->get([
                'DISTRICT_ID',
                'DISTRICT_NAME',
            ]);

        return response()->json([
            'success' => true,
            'data' => $districts->map(function ($district) {
                return [
                    'id' => $district->DISTRICT_ID,
                    'name' => $district->DISTRICT_NAME,
                ];
            })->values(),
        ]);
    }

    /**
     * Get subdistricts by district id.
     */
    public function subdistricts(int $district): JsonResponse
    {
        $subdistricts = Subdistrict::query()
            ->leftJoin('zipcode', 'subdistrict.SUB_DISTRICT_ID', '=', 'zipcode.SUB_DISTRICT_ID')
            ->where('subdistrict.DISTRICT_ID', $district)
            ->orderBy('subdistrict.SUB_DISTRICT_NAME')
            ->get([
                'subdistrict.SUB_DISTRICT_ID',
                'subdistrict.SUB_DISTRICT_NAME',
                'zipcode.ZIPCODE',
            ]);

        return response()->json([
            'success' => true,
            'data' => $subdistricts->map(function ($subdistrict) {
                return [
                    'id' => $subdistrict->SUB_DISTRICT_ID,
                    'name' => $subdistrict->SUB_DISTRICT_NAME,
                    'zipcode' => $subdistrict->ZIPCODE,
                ];
            })->values(),
        ]);
    }

    /**
     * Get zipcode by subdistrict id.
     */
    public function zipcode(int $subdistrict): JsonResponse
    {
        $zipcode = Zipcode::query()
            ->where('SUB_DISTRICT_ID', $subdistrict)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'zipcode' => $zipcode?->ZIPCODE,
            ],
        ]);
    }
}
