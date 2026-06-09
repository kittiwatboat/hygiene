<div class="col-md-3">
  <label class="form-label">จังหวัด</label>
  <select
    name="province_id"
    id="province_id"
    class="form-select select2-address @error('province_id') is-invalid @enderror"
    data-placeholder="ค้นหาจังหวัด"
  >
    <option value="">-- เลือกจังหวัด --</option>

    @foreach ($provinces as $province)
      <option
        value="{{ $province->PROVINCE_ID }}"
        {{ (string) old('province_id', $location->province_id ?? '') === (string) $province->PROVINCE_ID ? 'selected' : '' }}
      >
        {{ $province->PROVINCE_NAME }}
      </option>
    @endforeach
  </select>

  @error('province_id')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="col-md-3">
  <label class="form-label">อำเภอ/เขต</label>
  <select
    name="district_id"
    id="district_id"
    class="form-select select2-address @error('district_id') is-invalid @enderror"
    data-placeholder="ค้นหาอำเภอ/เขต"
  >
    <option value="">-- เลือกอำเภอ/เขต --</option>

    @foreach ($districts as $district)
      <option
        value="{{ $district->DISTRICT_ID }}"
        {{ (string) old('district_id', $location->district_id ?? '') === (string) $district->DISTRICT_ID ? 'selected' : '' }}
      >
        {{ $district->DISTRICT_NAME }}
      </option>
    @endforeach
  </select>

  @error('district_id')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="col-md-3">
  <label class="form-label">ตำบล/แขวง</label>
  <select
    name="subdistrict_id"
    id="subdistrict_id"
    class="form-select select2-address @error('subdistrict_id') is-invalid @enderror"
    data-placeholder="ค้นหาตำบล/แขวง"
  >
    <option value="">-- เลือกตำบล/แขวง --</option>

    @foreach ($subdistricts as $subdistrict)
      <option
        value="{{ $subdistrict->SUB_DISTRICT_ID }}"
        data-zipcode="{{ $subdistrict->ZIPCODE ?? '' }}"
        {{ (string) old('subdistrict_id', $location->subdistrict_id ?? '') === (string) $subdistrict->SUB_DISTRICT_ID ? 'selected' : '' }}
      >
        {{ $subdistrict->SUB_DISTRICT_NAME }}
      </option>
    @endforeach
  </select>

  @error('subdistrict_id')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="col-md-3">
  <label class="form-label">รหัสไปรษณีย์</label>
  <input
    type="text"
    name="postcode"
    id="postcode"
    value="{{ old('postcode', $location->postcode ?? '') }}"
    class="form-control"
    placeholder="รหัสไปรษณีย์"
    readonly
  >
</div>
