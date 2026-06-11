@extends('layouts/layoutMaster')

@section('title', 'เพิ่มรายการขาย')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">เพิ่มรายการขาย</h5>
            <p class="text-muted mb-0">
              บันทึกการกดน้ำยาและตัด Stock อัตโนมัติ
            </p>
          </div>

          <a href="{{ route('sales.index') }}" class="btn btn-label-secondary">
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

          <form method="GET" action="{{ route('sales.create') }}" class="mb-4">
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

          <form action="{{ route('sales.store') }}" method="POST">
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
                      data-volume="{{ $tank->volume_per_press_ml }}"
                      data-price="{{ $tank->price_per_press }}"
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
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  จำนวนครั้งที่กด <span class="text-danger">*</span>
                </label>

                <input
                  type="number"
                  min="1"
                  step="1"
                  name="press_count"
                  value="{{ old('press_count', 1) }}"
                  class="form-control @error('press_count') is-invalid @enderror"
                  required
                >

                @error('press_count')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  ช่องทางชำระเงิน <span class="text-danger">*</span>
                </label>

                @php
                  $selectedPaymentMethod = old('payment_method', 'cash');
                @endphp

                <select name="payment_method" class="form-select" required>
                  <option value="cash" {{ $selectedPaymentMethod === 'cash' ? 'selected' : '' }}>เงินสด</option>
                  <option value="qr" {{ $selectedPaymentMethod === 'qr' ? 'selected' : '' }}>QR Payment</option>
                  <option value="true_money" {{ $selectedPaymentMethod === 'true_money' ? 'selected' : '' }}>TrueMoney</option>
                  <option value="shopee_pay" {{ $selectedPaymentMethod === 'shopee_pay' ? 'selected' : '' }}>ShopeePay</option>
                  <option value="card" {{ $selectedPaymentMethod === 'card' ? 'selected' : '' }}>บัตรเครดิต</option>
                  <option value="free" {{ $selectedPaymentMethod === 'free' ? 'selected' : '' }}>ฟรี / ทดสอบ</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  สถานะชำระเงิน <span class="text-danger">*</span>
                </label>

                @php
                  $selectedPaymentStatus = old('payment_status', 'paid');
                @endphp

                <select name="payment_status" class="form-select" required>
                  <option value="paid" {{ $selectedPaymentStatus === 'paid' ? 'selected' : '' }}>ชำระแล้ว</option>
                  <option value="pending" {{ $selectedPaymentStatus === 'pending' ? 'selected' : '' }}>รอชำระ</option>
                  <option value="failed" {{ $selectedPaymentStatus === 'failed' ? 'selected' : '' }}>ชำระไม่สำเร็จ</option>
                  <option value="refunded" {{ $selectedPaymentStatus === 'refunded' ? 'selected' : '' }}>คืนเงินแล้ว</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">เลขอ้างอิงการชำระเงิน</label>

                <input
                  type="text"
                  name="transaction_ref"
                  value="{{ old('transaction_ref') }}"
                  class="form-control"
                  placeholder="เช่น TXN-123456"
                >
              </div>

              <div class="col-md-6">
                <label class="form-label">วันที่ขาย</label>

                <input
                  type="datetime-local"
                  name="sold_at"
                  value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}"
                  class="form-control"
                >
              </div>

              <div class="col-md-6">
                <label class="form-label">สรุปการขาย</label>

                <div class="border rounded p-3 bg-light">
                  <div class="d-flex justify-content-between">
                    <span class="text-muted">Stock ปัจจุบัน</span>
                    <span class="fw-medium" id="currentStockText">-</span>
                  </div>

                  <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">ปริมาณที่ใช้</span>
                    <span class="fw-medium" id="usedVolumeText">-</span>
                  </div>

                  <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">คงเหลือหลังขาย</span>
                    <span class="fw-medium" id="afterStockText">-</span>
                  </div>

                  <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">ยอดเงิน</span>
                    <span class="fw-medium" id="amountText">-</span>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">หมายเหตุ</label>

                <textarea
                  name="remark"
                  rows="6"
                  class="form-control"
                  placeholder="เช่น รายการทดสอบ / บันทึกย้อนหลัง"
                >{{ old('remark') }}</textarea>
              </div>

              <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('sales.index') }}" class="btn btn-label-secondary">
                  ยกเลิก
                </a>

                <button type="submit" class="btn btn-primary">
                  <i class="icon-base ti tabler-device-floppy me-1"></i>
                  บันทึกรายการขาย
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
      const pressInput = document.querySelector('input[name="press_count"]');

      const currentStockText = document.getElementById('currentStockText');
      const usedVolumeText = document.getElementById('usedVolumeText');
      const afterStockText = document.getElementById('afterStockText');
      const amountText = document.getElementById('amountText');

      function formatNumber(value, digit = 2) {
        return Number(value || 0).toLocaleString(undefined, {
          minimumFractionDigits: digit,
          maximumFractionDigits: digit
        });
      }

      function updatePreview() {
        const selectedOption = tankSelect.options[tankSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
          currentStockText.textContent = '-';
          usedVolumeText.textContent = '-';
          afterStockText.textContent = '-';
          amountText.textContent = '-';
          return;
        }

        const remaining = Number(selectedOption.dataset.remaining || 0);
        const volumePerPressMl = Number(selectedOption.dataset.volume || 0);
        const pricePerPress = Number(selectedOption.dataset.price || 0);
        const pressCount = Number(pressInput.value || 0);

        const usedLiters = (pressCount * volumePerPressMl) / 1000;
        const afterStock = Math.max(remaining - usedLiters, 0);
        const amount = pressCount * pricePerPress;

        currentStockText.textContent = `${formatNumber(remaining, 2)} L`;
        usedVolumeText.textContent = `${formatNumber(usedLiters, 3)} L`;
        afterStockText.textContent = `${formatNumber(afterStock, 3)} L`;
        amountText.textContent = `${formatNumber(amount, 2)} บาท`;
      }

      tankSelect.addEventListener('change', updatePreview);
      pressInput.addEventListener('input', updatePreview);

      updatePreview();
    });
  </script>
@endsection
