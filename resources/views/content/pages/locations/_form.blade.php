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
    <input
      type="text"
      name="province"
      value="{{ old('province', $location->province ?? '') }}"
      class="form-control @error('province') is-invalid @enderror"
      placeholder="จังหวัด"
    >
    @error('province')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">อำเภอ/เขต</label>
    <input
      type="text"
      name="district"
      value="{{ old('district', $location->district ?? '') }}"
      class="form-control @error('district') is-invalid @enderror"
      placeholder="อำเภอ/เขต"
    >
    @error('district')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">ตำบล/แขวง</label>
    <input
      type="text"
      name="sub_district"
      value="{{ old('sub_district', $location->sub_district ?? '') }}"
      class="form-control @error('sub_district') is-invalid @enderror"
      placeholder="ตำบล/แขวง"
    >
    @error('sub_district')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">รหัสไปรษณีย์</label>
    <input
      type="text"
      name="postcode"
      value="{{ old('postcode', $location->postcode ?? '') }}"
      class="form-control @error('postcode') is-invalid @enderror"
      placeholder="รหัสไปรษณีย์"
    >
    @error('postcode')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
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
