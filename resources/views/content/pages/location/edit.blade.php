@extends('layouts/layoutMaster')

@section('title', 'แก้ไขตู้')

@section('content')
  <div class="row g-6">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">แก้ไขตู้</h5>
            <p class="mb-0 text-muted">
              แก้ไขข้อมูลตู้ {{ $machine->machine_code }} - {{ $machine->machine_name }}
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('machines.show', $machine) }}" class="btn btn-label-info">
              <i class="icon-base ti tabler-eye me-1"></i>
              ดูรายละเอียด
            </a>

            <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>
          </div>
        </div>

        <div class="card-body">

          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
              <h6 class="alert-heading mb-2">กรุณาตรวจสอบข้อมูล</h6>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form action="{{ route('machines.update', $machine) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-6">

              <div class="col-md-6">
                <label for="machine_code" class="form-label">
                  รหัสตู้ <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control @error('machine_code') is-invalid @enderror"
                  id="machine_code"
                  name="machine_code"
                  value="{{ old('machine_code', $machine->machine_code) }}"
                  placeholder="เช่น HYG-001"
                  required />

                @error('machine_code')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="form-text">
                  รหัสตู้ต้องไม่ซ้ำกัน ใช้สำหรับอ้างอิงตู้ในระบบ
                </div>
              </div>

              <div class="col-md-6">
                <label for="machine_name" class="form-label">
                  ชื่อตู้ <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control @error('machine_name') is-invalid @enderror"
                  id="machine_name"
                  name="machine_name"
                  value="{{ old('machine_name', $machine->machine_name) }}"
                  placeholder="เช่น ตู้ซักผ้า อาคาร A"
                  required />

                @error('machine_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="location_name" class="form-label">ชื่อสถานที่ติดตั้ง</label>
                <input
                  type="text"
                  class="form-control @error('location_name') is-invalid @enderror"
                  id="location_name"
                  name="location_name"
                  value="{{ old('location_name', $machine->location_name) }}"
                  placeholder="เช่น หอพักสุขใจ / อาคาร A / ชั้น 1" />

                @error('location_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="status" class="form-label">
                  สถานะ <span class="text-danger">*</span>
                </label>
                <select
                  class="form-select @error('status') is-invalid @enderror"
                  id="status"
                  name="status"
                  required>
                  <option value="active" @selected(old('status', $machine->status) === 'active')>
                    ใช้งานปกติ
                  </option>
                  <option value="inactive" @selected(old('status', $machine->status) === 'inactive')>
                    ปิดใช้งาน
                  </option>
                  <option value="maintenance" @selected(old('status', $machine->status) === 'maintenance')>
                    ซ่อมบำรุง
                  </option>
                  <option value="out_of_stock" @selected(old('status', $machine->status) === 'out_of_stock')>
                    น้ำยาหมด
                  </option>
                </select>

                @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <label for="address" class="form-label">ที่อยู่</label>
                <textarea
                  class="form-control @error('address') is-invalid @enderror"
                  id="address"
                  name="address"
                  rows="3"
                  placeholder="กรอกที่อยู่หรือรายละเอียดจุดติดตั้ง">{{ old('address', $machine->address) }}</textarea>

                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="latitude" class="form-label">Latitude</label>
                <input
                  type="number"
                  step="0.0000001"
                  class="form-control @error('latitude') is-invalid @enderror"
                  id="latitude"
                  name="latitude"
                  value="{{ old('latitude', $machine->latitude) }}"
                  placeholder="เช่น 13.756331" />

                @error('latitude')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="longitude" class="form-label">Longitude</label>
                <input
                  type="number"
                  step="0.0000001"
                  class="form-control @error('longitude') is-invalid @enderror"
                  id="longitude"
                  name="longitude"
                  value="{{ old('longitude', $machine->longitude) }}"
                  placeholder="เช่น 100.501762" />

                @error('longitude')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label for="tank_capacity_liter" class="form-label">
                  ความจุถัง ลิตร <span class="text-danger">*</span>
                </label>
                <input
                  type="number"
                  step="0.01"
                  min="0"
                  class="form-control @error('tank_capacity_liter') is-invalid @enderror"
                  id="tank_capacity_liter"
                  name="tank_capacity_liter"
                  value="{{ old('tank_capacity_liter', $machine->tank_capacity_liter) }}"
                  required />

                @error('tank_capacity_liter')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label for="current_stock_liter" class="form-label">
                  คงเหลือ ลิตร <span class="text-danger">*</span>
                </label>
                <input
                  type="number"
                  step="0.01"
                  min="0"
                  class="form-control @error('current_stock_liter') is-invalid @enderror"
                  id="current_stock_liter"
                  name="current_stock_liter"
                  value="{{ old('current_stock_liter', $machine->current_stock_liter) }}"
                  required />

                @error('current_stock_liter')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label for="volume_per_press_ml" class="form-label">
                  ต่อการกด ml <span class="text-danger">*</span>
                </label>
                <input
                  type="number"
                  step="0.01"
                  min="0"
                  class="form-control @error('volume_per_press_ml') is-invalid @enderror"
                  id="volume_per_press_ml"
                  name="volume_per_press_ml"
                  value="{{ old('volume_per_press_ml', $machine->volume_per_press_ml) }}"
                  required />

                @error('volume_per_press_ml')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label for="total_press_count" class="form-label">จำนวนการกดทั้งหมด</label>
                <input
                  type="number"
                  min="0"
                  class="form-control @error('total_press_count') is-invalid @enderror"
                  id="total_press_count"
                  name="total_press_count"
                  value="{{ old('total_press_count', $machine->total_press_count) }}" />

                @error('total_press_count')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <label for="note" class="form-label">หมายเหตุ</label>
                <textarea
                  class="form-control @error('note') is-invalid @enderror"
                  id="note"
                  name="note"
                  rows="3"
                  placeholder="รายละเอียดเพิ่มเติม">{{ old('note', $machine->note) }}</textarea>

                @error('note')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                  <button
                    type="button"
                    class="btn btn-label-danger"
                    onclick="confirmDeleteMachine()">
                    <i class="icon-base ti tabler-trash me-1"></i>
                    ลบตู้
                  </button>

                  <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
                      ยกเลิก
                    </a>

                    <button type="submit" class="btn btn-primary">
                      <i class="icon-base ti tabler-device-floppy me-1"></i>
                      บันทึกการแก้ไข
                    </button>
                  </div>
                </div>
              </div>

            </div>
          </form>

          <form id="deleteMachineForm" action="{{ route('machines.destroy', $machine) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  @vite('resources/assets/js/form-basic-inputs.js')

  <script>
    function confirmDeleteMachine() {
      const confirmed = confirm('ยืนยันการลบตู้รายการนี้?');

      if (confirmed) {
        document.getElementById('deleteMachineForm').submit();
      }
    }
  </script>
@endsection
