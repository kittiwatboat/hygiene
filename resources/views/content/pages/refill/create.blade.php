@extends('layouts/layoutMaster')

@section('title', 'บันทึกเติมน้ำยา')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h5 class="mb-1">บันทึกเติมน้ำยา</h5>
          <p class="text-muted mb-0">เลือกกลุ่มตู้ → เลือกตู้ → เลือกช่อง/น้ำยา → กรอกจำนวนที่เติม</p>
        </div>
        <a href="{{ route('refills.index') }}" class="btn btn-label-secondary">
          <i class="icon-base ti tabler-arrow-left me-1"></i>กลับ
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

        <form action="{{ route('refills.store') }}" method="POST">
          @csrf
          <div class="row g-4">

            <div class="col-md-6">
              <label class="form-label">กลุ่มตู้ <span class="text-danger">*</span></label>
              <select id="machineGroupSelect" class="form-select" required>
                <option value="">-- เลือกกลุ่มตู้ --</option>
                @foreach ($machineGroups as $group)
                  <option value="{{ $group->id }}">{{ $group->code }} - {{ $group->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">ตู้ <span class="text-danger">*</span></label>
              <select id="machineSelect" class="form-select" disabled required>
                <option value="">-- เลือกตู้ --</option>
                @foreach ($machines as $machine)
                  <option value="{{ $machine->id }}" data-group-id="{{ $machine->machine_group_id }}">
                    {{ $machine->code }} - {{ $machine->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">ช่องน้ำยา / สินค้า <span class="text-danger">*</span></label>
              <select
                name="machine_tank_id"
                id="tankSelect"
                class="form-select @error('machine_tank_id') is-invalid @enderror"
                disabled
                required
              >
                <option value="">-- เลือกช่องน้ำยา --</option>
                @foreach ($tanks as $tank)
                  <option
                    value="{{ $tank->id }}"
                    data-machine-id="{{ $tank->machine_id }}"
                    data-product-type="{{ $tank->product?->type }}"
                    data-remaining="{{ $tank->remaining_liters }}"
                    data-capacity="{{ $tank->capacity_liters }}"
                    {{ (string) old('machine_tank_id') === (string) $tank->id ? 'selected' : '' }}
                  >
                    ช่อง {{ $tank->tank_no }}: {{ $tank->tank_name }}
                    | {{ $tank->product?->name ?: 'ยังไม่เลือกน้ำยา' }}
                    | คงเหลือ {{ number_format((float) $tank->remaining_liters, 2) }} L
                  </option>
                @endforeach
              </select>
              @error('machine_tank_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">จำนวนที่เติม / ลิตร <span class="text-danger">*</span></label>
              <input
                type="number"
                step="0.01"
                min="0.01"
                name="refill_liters"
                id="refillLitersInput"
                value="{{ old('refill_liters') }}"
                class="form-control @error('refill_liters') is-invalid @enderror"
                placeholder="เช่น 5"
                required
              >
              @error('refill_liters')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 d-none" id="productionLotWrapper">
              <label class="form-label">LOT การผลิต <span class="text-danger">*</span></label>
              <input
                type="text"
                name="production_lot"
                id="productionLotInput"
                value="{{ old('production_lot') }}"
                class="form-control @error('production_lot') is-invalid @enderror"
                placeholder="เช่น LOT-20260818-001"
              >
              @error('production_lot')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">กรอกเฉพาะกรณีเป็นน้ำยาซักผ้า</div>
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
              <a href="{{ route('refills.index') }}" class="btn btn-label-secondary">ยกเลิก</a>
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
  const groupSelect = document.getElementById('machineGroupSelect');
  const machineSelect = document.getElementById('machineSelect');
  const tankSelect = document.getElementById('tankSelect');
  const refillInput = document.getElementById('refillLitersInput');
  const lotWrapper = document.getElementById('productionLotWrapper');
  const lotInput = document.getElementById('productionLotInput');
  const currentRemainingText = document.getElementById('currentRemainingText');
  const capacityText = document.getElementById('capacityText');
  const afterRefillText = document.getElementById('afterRefillText');

  const machineOptions = Array.from(machineSelect.options).filter(o => o.value);
  const tankOptions = Array.from(tankSelect.options).filter(o => o.value);

  const formatNumber = value => Number(value || 0).toLocaleString(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });

  function rebuildSelect(select, options, placeholder) {
    select.innerHTML = '';
    const first = document.createElement('option');
    first.value = '';
    first.textContent = placeholder;
    select.appendChild(first);
    options.forEach(option => select.appendChild(option.cloneNode(true)));
  }

  function resetStock() {
    currentRemainingText.textContent = '-';
    capacityText.textContent = '-';
    afterRefillText.textContent = '-';
  }

  function resetLot() {
    lotWrapper.classList.add('d-none');
    lotInput.required = false;
    lotInput.value = '';
  }

  function filterMachines() {
    const groupId = groupSelect.value;

    rebuildSelect(machineSelect, [], '-- เลือกตู้ --');
    rebuildSelect(tankSelect, [], '-- เลือกช่องน้ำยา --');

    machineSelect.disabled = true;
    tankSelect.disabled = true;
    resetStock();
    resetLot();

    if (!groupId) return;

    const filtered = machineOptions.filter(
      option => String(option.dataset.groupId) === String(groupId)
    );

    rebuildSelect(machineSelect, filtered, '-- เลือกตู้ --');
    machineSelect.disabled = filtered.length === 0;
  }

  function filterTanks() {
    const machineId = machineSelect.value;

    rebuildSelect(tankSelect, [], '-- เลือกช่องน้ำยา --');
    tankSelect.disabled = true;
    resetStock();
    resetLot();

    if (!machineId) return;

    const filtered = tankOptions.filter(
      option => String(option.dataset.machineId) === String(machineId)
    );

    rebuildSelect(tankSelect, filtered, '-- เลือกช่องน้ำยา --');
    tankSelect.disabled = filtered.length === 0;
  }

  function updateLot() {
    const option = tankSelect.options[tankSelect.selectedIndex];

    if (!option || !option.value) {
      resetLot();
      return;
    }

    if ((option.dataset.productType || '') === 'detergent') {
      lotWrapper.classList.remove('d-none');
      lotInput.required = true;
    } else {
      resetLot();
    }
  }

  function updateStock() {
    const option = tankSelect.options[tankSelect.selectedIndex];

    if (!option || !option.value) {
      resetStock();
      return;
    }

    const remaining = Number(option.dataset.remaining || 0);
    const capacity = Number(option.dataset.capacity || 0);
    const refill = Number(refillInput.value || 0);

    let after = remaining + refill;
    if (capacity > 0 && after > capacity) after = capacity;

    currentRemainingText.textContent = `${formatNumber(remaining)} L`;
    capacityText.textContent = `${formatNumber(capacity)} L`;
    afterRefillText.textContent = `${formatNumber(after)} L`;
  }

  groupSelect.addEventListener('change', filterMachines);
  machineSelect.addEventListener('change', filterTanks);
  tankSelect.addEventListener('change', function () {
    updateLot();
    updateStock();
  });
  refillInput.addEventListener('input', updateStock);

  filterMachines();
});
</script>
@endsection
