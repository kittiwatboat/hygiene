<?php

namespace App\Http\Controllers\locations;

use App\Models\Location;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use App\Models\Zipcode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    /**
     * Display a listing of locations.
     */
    public function index(Request $request): View
    {
        $keyword = $request->input('keyword');
        $status = $request->input('status');

        $locations = Location::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . $keyword . '%')
                        ->orWhere('contact_name', 'like', '%' . $keyword . '%')
                        ->orWhere('contact_phone', 'like', '%' . $keyword . '%')
                        ->orWhere('address', 'like', '%' . $keyword . '%')
                        ->orWhere('province', 'like', '%' . $keyword . '%')
                        ->orWhere('district', 'like', '%' . $keyword . '%')
                        ->orWhere('sub_district', 'like', '%' . $keyword . '%')
                        ->orWhere('postcode', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', (bool) $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('content.pages.locations.index', compact('locations', 'keyword', 'status'));
    }

    /**
     * Show the form for creating a new location.
     */
    public function create(): View
{
    $location = new Location();

    $provinces = Province::query()
        ->orderBy('PROVINCE_NAME')
        ->get();

    $districts = collect();
    $subdistricts = collect();

    return view('locations.create', compact(
        'location',
        'provinces',
        'districts',
        'subdistricts'
    ));
}

    /**
     * Store a newly created location in storage.
     */
    public function store(Request $request): RedirectResponse
{
    $validated = $this->validateLocation($request);

    $validated = $this->fillAddressNames($validated);

    $validated['is_active'] = $request->boolean('is_active');

    Location::create($validated);

    return redirect()
        ->route('locations.index')
        ->with('success', 'เพิ่มสถานที่เรียบร้อยแล้ว');
}

    /**
     * Display the specified location.
     */
    public function show(Location $location): View
    {
        return view('content.pages.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified location.
     */
    public function edit(Location $location): View
{
    $provinces = Province::query()
        ->orderBy('PROVINCE_NAME')
        ->get();

    $districts = $location->province_id
        ? District::query()
            ->where('PROVINCE_ID', $location->province_id)
            ->orderBy('DISTRICT_NAME')
            ->get()
        : collect();

    $subdistricts = $location->district_id
        ? Subdistrict::query()
            ->leftJoin('zipcode', 'subdistrict.SUB_DISTRICT_ID', '=', 'zipcode.SUB_DISTRICT_ID')
            ->where('subdistrict.DISTRICT_ID', $location->district_id)
            ->orderBy('subdistrict.SUB_DISTRICT_NAME')
            ->get([
                'subdistrict.SUB_DISTRICT_ID',
                'subdistrict.SUB_DISTRICT_NAME',
                'zipcode.ZIPCODE',
            ])
        : collect();

    return view('locations.edit', compact(
        'location',
        'provinces',
        'districts',
        'subdistricts'
    ));
}

    /**
     * Update the specified location in storage.
     */
    public function update(Request $request, Location $location): RedirectResponse
{
    $validated = $this->validateLocation($request, $location->id);

    $validated = $this->fillAddressNames($validated);

    $validated['is_active'] = $request->boolean('is_active');

    $location->update($validated);

    return redirect()
        ->route('locations.index')
        ->with('success', 'แก้ไขสถานที่เรียบร้อยแล้ว');
}

    /**
     * Remove the specified location from storage.
     */
    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with('success', 'ลบสถานที่เรียบร้อยแล้ว');
    }

    /**
     * Validate location request.
     */
    private function validateLocation(Request $request, ?int $locationId = null): array
{
    return $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'code' => ['nullable', 'string', 'max:100', 'unique:locations,code,' . $locationId],

        'contact_name' => ['nullable', 'string', 'max:255'],
        'contact_phone' => ['nullable', 'string', 'max:50'],

        'address' => ['nullable', 'string'],

        'province_id' => ['nullable', 'integer', 'exists:province,PROVINCE_ID'],
        'district_id' => ['nullable', 'integer', 'exists:district,DISTRICT_ID'],
        'subdistrict_id' => ['nullable', 'integer', 'exists:subdistrict,SUB_DISTRICT_ID'],

        'latitude' => ['nullable', 'numeric', 'between:-90,90'],
        'longitude' => ['nullable', 'numeric', 'between:-180,180'],

        'remark' => ['nullable', 'string'],
        'is_active' => ['nullable', 'boolean'],
    ]);
}

private function fillAddressNames(array $validated): array
{
    $validated['province'] = null;
    $validated['district'] = null;
    $validated['sub_district'] = null;
    $validated['postcode'] = null;

    if (!empty($validated['province_id'])) {
        $province = Province::query()
            ->where('PROVINCE_ID', $validated['province_id'])
            ->first();

        $validated['province'] = $province?->PROVINCE_NAME;
    }

    if (!empty($validated['district_id'])) {
        $district = District::query()
            ->where('DISTRICT_ID', $validated['district_id'])
            ->first();

        $validated['district'] = $district?->DISTRICT_NAME;
    }

    if (!empty($validated['subdistrict_id'])) {
        $subdistrict = Subdistrict::query()
            ->where('SUB_DISTRICT_ID', $validated['subdistrict_id'])
            ->first();

        $zipcode = Zipcode::query()
            ->where('SUB_DISTRICT_ID', $validated['subdistrict_id'])
            ->first();

        $validated['sub_district'] = $subdistrict?->SUB_DISTRICT_NAME;
        $validated['postcode'] = $zipcode?->ZIPCODE;
    }

    return $validated;
}
}
