@extends('layouts/layoutMaster')

@section('title', 'รายละเอียด Stock น้ำยา')

@section('content')
  @php
    $capacity = (float) $tank->capacity_liters;
    $remaining = (float) $tank->remaining_liters;
    $percent = $tank->stock_percent;

    $progressClass = 'bg-success';

    if ($remaining <= (float) $tank->empty_stock_liters) {
        $progressClass = 'bg-danger';
    } elseif ($remaining <= (float) $tank->low_stock_liters) {
        $progressClass = 'bg-warning';
    }
  @endphp

  <div class="row g-4">

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียด Stock น้ำยา</h5>
            <p class="text-muted mb-0">
              {{ $tank->machine?->code ?: '-' }} - {{ $tank->machine?->name ?: '-' }}
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('stock.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>

            <a href="{{ route('machines.show', $tank->machine_id) }}" class="btn btn-label-primary">
              <i class="icon-base ti tabler-wash-machine me-1"></i>
              ดูตู้
            </a>
          </div>
        </div>
      </div>
    </div>

    @if (session('success'))
      <div class="col-12">
        <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">
          <div>
            <i class="icon-base ti tabler-circle-check me-1"></i>
            {{ session('success') }}
          </div>

          <button
            type="button"
            class="btn-close"
            onclick="this.closest('.alert').remove()"
            aria-label="Close">
          </button>
        </div>
      </div>
    @endif

    <div class="col-xl-8">
      <div class="card">
        <h5 class="card-header">สถานะ Stock</h5>

        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <h4 class="mb-0">
                {{ number_format($remaining, 2) }} L
              </h4>
              <small class="text-muted">
                จากความจุ {{ number_format($capacity, 2) }} L
              </small>
            </div>

            <span class="badge {{ $tank->stock_status_badge_class }}">
              {{ $tank->stock_status_text }}
            </span>
          </div>

          <div class="progress mb-3" style="height: 12px;">
            <div
              class="progress-bar {{ $progressClass }}"
              role="progressbar"
              style="width: {{ $percent }}%;"
              aria-valuenow="{{ $percent }}"
              aria-valuemin="0"
              aria-valuemax="100">
            </div>
          </div>

          <div class="row g-4">
            <div class="col-md-6">
              <small class="text-muted">เปอร์เซ็นต์คงเหลือ</small>
              <div class="fw-medium">{{ number_format($percent, 2) }}%</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">แจ้งเตือนเมื่อต่ำกว่า</small>
              <div class="fw-medium">{{ number_format((float) $tank->low_stock_liters, 2) }} L</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ถือว่าหมดเมื่อต่ำกว่า</small>
              <div class="fw-medium">{{ number_format((float) $tank->empty_stock_liters, 2) }} L</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ปริมาณต่อการกด</small>
              <div class="fw-medium">{{ number_format((float) $tank->volume_per_press_ml, 2) }} ml/ครั้ง</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ราคาต่อครั้ง</small>
              <div class="fw-medium">{{ number_format((float) $tank->price_per_press, 2) }} บาท</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานะช่องน้ำยา</small>
              <div>
                @if ($tank->is_active)
                  <span class="badge bg-label-success">เปิดใช้งาน</span>
                @else
                  <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card mb-4">
        <h5 class="card-header">ข้อมูลช่องน้ำยา</h5>

        <div class="card-body">
          <div class="mb-3">
            <small class="text-muted">ตู้</small>
            <div class="fw-medium">
              {{ $tank->machine?->code ?: '-' }} - {{ $tank->machine?->name ?: '-' }}
            </div>
          </div>

          <div class="mb-3">
            <small class="text-muted">สถานที่</small>
            <div class="fw-medium">
              {{ $tank->machine?->location?->name ?: '-' }}
            </div>
          </div>

          <div class="mb-3">
            <small class="text-muted">ช่องน้ำยา</small>
            <div class="fw-medium">
              ช่อง {{ $tank->tank_no }}: {{ $tank->tank_name ?: '-' }}
            </div>
          </div>

          <div class="mb-0">
            <small class="text-muted">สินค้า / น้ำยา</small>
            <div class="fw-medium">
              {{ $tank->product?->name ?: 'ยังไม่เลือกน้ำยา' }}
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <h5 class="card-header">ปรับยอด Stock</h5>

        <div class="card-body">
          <form action="{{ route('stock.adjust', $tank) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">
                น้ำยาคงเหลือ / ลิตร <span class="text-danger">*</span>
              </label>

              <input
                type="number"
                step="0.01"
                min="0"
                name="remaining_liters"
                value="{{ old('remaining_liters', $tank->remaining_liters) }}"
                class="form-control @error('remaining_liters') is-invalid @enderror"
                required
              >

              @error('remaining_liters')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">หมายเหตุ</label>
              <textarea
                name="remark"
                rows="3"
                class="form-control"
                placeholder="เช่น ปรับยอดหลังตรวจนับจริง"
              >{{ old('remark') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              บันทึกปรับยอด
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
@endsection
