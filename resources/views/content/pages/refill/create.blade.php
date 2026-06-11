@extends('layouts/layoutMaster')

@section('title', 'บันทึกเติมน้ำยา')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">บันทึกเติมน้ำยา</h5>
            <p class="text-muted mb-0">
              เลือกตู้และช่องน้ำยาที่ต้องการเติม ระบบจะอัปเดต Stock ให้อัตโนมัติ
            </p>
          </div>

          <a href="{{ route('refills.index') }}" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-arrow-left me-1"></i>
            กลับ
          </a>
        </div>

        <div class="card-body">

          @if ($errors->any())
            <div class="alert alert-danger">
              <div class="fw-medium mb-1">กรุณาตรวจสอบข้อมูล</div>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="GET" action="{{ route('refills.create') }}" class="mb-4">
            <div class="row g-3 align-items-end">
              <div class="col-md-10">
                <label class="form-label">เลือกตู้</label>
                <select name="machine_id" class="form-select">
                  <option value="">-- แสดงทุกตู้ --</option>

                  @foreach ($machines as $machine)
                    <option
                      value="{{ $machine->id }}"
                      {{ (string) $selectedMachineId === (string) $machine->id ? 'selected' : '' }}
                    >
                      {{ $machine->code }} - {{ $machine->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-label-primary">
                  เลือก
                </button>
              </div>
            </div>
          </form>

          <form action="{{ route('refills.store') }}" method="POST">
            @csrf

            <div class="row g-4">

              <div class="col-md-6">
                <label class="form-label">
                  ช่องน้ำยา <span class="text-danger">*</span>
                </label>

                <select
                  name="machine_tank_id"
                  class="form-select @error('machine_tank_id') is-invalid @enderror"
                  required
                >
                  <option value="">-- เลือกช่องน้ำยา --</option>

                  @foreach ($tanks as $tank)
                    <option
                      value="{{ $tank->id }}"
                      data-remaining="{{ $tank->remaining_liters }}"
                      data-capacity="{{ $tank->capacity_liters }}"
                      {{ (string) old('machine_tank_id') === (string) $tank->id ? 'selected' : '' }}
                    >
                      {{ $tank->machine?->code }} - {{ $tank->machine?->name }}
                      | ช่อง {{ $tank->tank_no }}: {{ $tank->tank_name }}
                      | {{ $tank->product?->name ?: 'ยังไม่เลือกน้ำยา' }}
                      | คงเหลือ {{ number_format((float) $tank->remaining_liters, 2) }} L
                    </option>
                  @endforeach
                </select>

                @error('machine_tank_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="form-text">
                  เลือกช่องน้ำยาของตู้ที่ต้องการเติม
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  จำนวนที่เติม / ลิตร <span class="text-danger">*</span>
                </label>

                <input
                  type="number"
                  step="0.01"
                  min="0.01"
                  name="refill_liters"
                  value="{{ old('refill_liters') }}"
                  class="form-control @error('refill_liters') is-invalid @enderror"
                  placeholder="เช่น 5"
                  required
                >

                @error('refill_liters')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">วันที่เติม</label>

                <input
                  type="datetime-local"
                  name="refill_at"
                  value="{{ old('refill_at', now()->format('Y-m-d\TH:i')) }}"
                  class="form-control @error('refill_at') is-invalid @enderror"
                >

                @error('refill_at')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">ข้อมูล Stock ปัจจุบัน</label>

                <div class="border rounded p-3 bg-light">
                  <div class="d-flex justify-content-between">
                    <span class="text-muted">คงเหลือ</span>
                    <span class="fw-medium" id="currentRemainingText">-</span>
                  </div>

                  <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">ความจุ</span>
                    <span class="fw-medium" id="capacityText">-</span>
                  </div>

                  <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">หลังเติมโดยประมาณ</span>
                    <span class="fw-medium" id="afterRefillText">-</span>
                  </div>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">หมายเหตุ</label>

                <textarea
                  name="remark"
                  rows="3"
                  class="form-control @error('remark') is-invalid @enderror"
                  placeholder="เช่น เติมน้ำยาจาก Lot ใหม่ / ตรวจนับหน้างานแล้ว"
                >{{ old('remark') }}</textarea>

                @error('remark')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('refills.index') }}" class="btn btn-label-secondary">
                  ยกเลิก
                </a>

                <button type="submit" class="btn btn-primary">
                  <i class="icon-base ti tabler-device-floppy me-1"></i>
                  บันทึกเติมน้ำยา
                </button>
              </div>

            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tankSelect = document.querySelector('select[name="machine_tank_id"]');
      const refillInput = document.querySelector('input[name="refill_liters"]');

      const currentRemainingText = document.getElementById('currentRemainingText');
      const capacityText = document.getElementById('capacityText');
      const afterRefillText = document.getElementById('afterRefillText');

      function formatNumber(value) {
        return Number(value || 0).toLocaleString(undefined, {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
      }

      function updateStockPreview() {
        const selectedOption = tankSelect.options[tankSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
          currentRemainingText.textContent = '-';
          capacityText.textContent = '-';
          afterRefillText.textContent = '-';
          return;
        }

        const remaining = Number(selectedOption.dataset.remaining || 0);
        const capacity = Number(selectedOption.dataset.capacity || 0);
        const refill = Number(refillInput.value || 0);

        let after = remaining + refill;

        if (capacity > 0 && after > capacity) {
          after = capacity;
        }

        currentRemainingText.textContent = `${formatNumber(remaining)} L`;
        capacityText.textContent = `${formatNumber(capacity)} L`;
        afterRefillText.textContent = `${formatNumber(after)} L`;
      }

      tankSelect.addEventListener('change', updateStockPreview);
      refillInput.addEventListener('input', updateStockPreview);

      updateStockPreview();
    });
  </script>
@endsection
