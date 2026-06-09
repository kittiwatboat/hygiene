@csrf

<div class="row g-4">

  <div class="col-md-6">
    <label class="form-label">
      ชื่อตู้ <span class="text-danger">*</span>
    </label>
    <input
      type="text"
      name="name"
      value="{{ old('name', $machine->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น ตู้หน้าอาคาร A"
      required
    >
    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">รหัสตู้</label>
    <input
      type="text"
      name="code"
      value="{{ old('code', $machine->code ?? '') }}"
      class="form-control @error('code') is-invalid @enderror"
      placeholder="เช่น VM-001"
    >
    @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">สถานที่ติดตั้ง</label>
    <select
      name="location_id"
      class="form-select @error('location_id') is-invalid @enderror"
    >
      <option value="">-- เลือกสถานที่ --</option>

      @foreach ($locations as $location)
        <option
          value="{{ $location->id }}"
          {{ (string) old('location_id', $machine->location_id ?? '') === (string) $location->id ? 'selected' : '' }}
        >
          {{ $location->name }}
          @if ($location->code)
            ({{ $location->code }})
          @endif
        </option>
      @endforeach
    </select>

    @error('location_id')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      สถานะตู้ <span class="text-danger">*</span>
    </label>
    <select
      name="status"
      class="form-select @error('status') is-invalid @enderror"
      required
    >
      <option value="active" {{ old('status', $machine->status ?? 'active') === 'active' ? 'selected' : '' }}>
        พร้อมใช้งาน
      </option>

      <option value="maintenance" {{ old('status', $machine->status ?? '') === 'maintenance' ? 'selected' : '' }}>
        ซ่อมบำรุง
      </option>

      <option value="inactive" {{ old('status', $machine->status ?? '') === 'inactive' ? 'selected' : '' }}>
        ปิดใช้งาน
      </option>
    </select>

    @error('status')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Serial Number</label>
    <input
      type="text"
      name="serial_number"
      value="{{ old('serial_number', $machine->serial_number ?? '') }}"
      class="form-control @error('serial_number') is-invalid @enderror"
      placeholder="Serial Number"
    >
    @error('serial_number')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">รุ่นตู้</label>
    <input
      type="text"
      name="model"
      value="{{ old('model', $machine->model ?? '') }}"
      class="form-control @error('model') is-invalid @enderror"
      placeholder="เช่น HY-2026"
    >
    @error('model')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ความจุถังน้ำยา / ลิตร</label>
    <input
      type="number"
      step="0.01"
      min="0"
      name="capacity_liters"
      value="{{ old('capacity_liters', $machine->capacity_liters ?? '') }}"
      class="form-control @error('capacity_liters') is-invalid @enderror"
      placeholder="เช่น 20"
    >
    @error('capacity_liters')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">น้ำยาคงเหลือ / ลิตร</label>
    <input
      type="number"
      step="0.01"
      min="0"
      name="remaining_liters"
      value="{{ old('remaining_liters', $machine->remaining_liters ?? '') }}"
      class="form-control @error('remaining_liters') is-invalid @enderror"
      placeholder="เช่น 15"
    >
    @error('remaining_liters')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ปริมาณต่อการกด / ml</label>
    <input
      type="number"
      min="0"
      name="volume_per_press_ml"
      value="{{ old('volume_per_press_ml', $machine->volume_per_press_ml ?? '') }}"
      class="form-control @error('volume_per_press_ml') is-invalid @enderror"
      placeholder="เช่น 30"
    >
    @error('volume_per_press_ml')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ราคาต่อการกด / บาท</label>
    <input
      type="number"
      step="0.01"
      min="0"
      name="price_per_press"
      value="{{ old('price_per_press', $machine->price_per_press ?? '') }}"
      class="form-control @error('price_per_press') is-invalid @enderror"
      placeholder="เช่น 10"
    >
    @error('price_per_press')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>
    <textarea
      name="remark"
      rows="3"
      class="form-control @error('remark') is-invalid @enderror"
      placeholder="รายละเอียดเพิ่มเติม"
    >{{ old('remark', $machine->remark ?? '') }}</textarea>

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
        {{ old('is_active', isset($machine) ? (int) $machine->is_active : 1) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานตู้นี้
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      <i class="icon-base ti tabler-device-floppy me-1"></i>
      บันทึก
    </button>
  </div>

</div>
