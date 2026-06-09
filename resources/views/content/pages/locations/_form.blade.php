@csrf

<div class="row g-4">

  <div class="col-md-8">
    <label class="form-label">
      ชื่อสถานที่ <span class="text-danger">*</span>
    </label>
    <input
      type="text"
      name="name"
      value="{{ old('name', $location->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น หอพัก A, ร้านซักผ้า สาขาบางนา"
      required
    >
    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">รหัสสถานที่</label>
    <input
      type="text"
      name="code"
      value="{{ old('code', $location->code ?? '') }}"
      class="form-control @error('code') is-invalid @enderror"
      placeholder="เช่น LOC-001"
    >
    @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ชื่อผู้ติดต่อ</label>
    <input
      type="text"
      name="contact_name"
      value="{{ old('contact_name', $location->contact_name ?? '') }}"
      class="form-control @error('contact_name') is-invalid @enderror"
      placeholder="ชื่อผู้ดูแลพื้นที่"
    >
    @error('contact_name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">เบอร์โทรผู้ติดต่อ</label>
    <input
      type="text"
      name="contact_phone"
      value="{{ old('contact_phone', $location->contact_phone ?? '') }}"
      class="form-control @error('contact_phone') is-invalid @enderror"
      placeholder="เช่น 0812345678"
    >
    @error('contact_phone')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">ที่อยู่</label>
    <textarea
      name="address"
      rows="3"
      class="form-control @error('address') is-invalid @enderror"
      placeholder="บ้านเลขที่, อาคาร, ชั้น, ห้อง, ซอย, ถนน"
    >{{ old('address', $location->address ?? '') }}</textarea>
    @error('address')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

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


  <div class="col-md-6">
    <label class="form-label">Latitude</label>
    <input
      type="number"
      step="0.0000001"
      name="latitude"
      value="{{ old('latitude', $location->latitude ?? '') }}"
      class="form-control @error('latitude') is-invalid @enderror"
      placeholder="เช่น 13.756331"
    >
    @error('latitude')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Longitude</label>
    <input
      type="number"
      step="0.0000001"
      name="longitude"
      value="{{ old('longitude', $location->longitude ?? '') }}"
      class="form-control @error('longitude') is-invalid @enderror"
      placeholder="เช่น 100.501762"
    >
    @error('longitude')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>
    <textarea
      name="remark"
      rows="3"
      class="form-control @error('remark') is-invalid @enderror"
      placeholder="รายละเอียดเพิ่มเติม เช่น จุดวางตู้, เวลาเข้าพื้นที่, ข้อควรระวัง"
    >{{ old('remark', $location->remark ?? '') }}</textarea>
    @error('remark')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <div class="form-check form-switch">
      <input
        type="hidden"
        name="is_active"
        value="0"
      >

      <input
        type="checkbox"
        name="is_active"
        value="1"
        class="form-check-input"
        id="is_active"
        {{ old('is_active', $location->is_active ?? true) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานสถานที่นี้
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('locations.index') }}" class="btn btn-label-secondary">
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      <i class="icon-base ti tabler-device-floppy me-1"></i>
      บันทึก
    </button>
  </div>

</div>
