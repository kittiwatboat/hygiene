@extends('layouts/layoutMaster')

@section('title', 'Stock น้ำยา')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])

  <style>
    .stock-alert {
      margin: 0 1.5rem 1rem 1.5rem;
      padding: 0.65rem 0.85rem;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .stock-alert-content {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      line-height: 1.3;
      font-weight: 500;
    }

    .stock-alert-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1rem;
      line-height: 1;
      padding: 0.25rem;
      cursor: pointer;
      opacity: 0.7;
    }

    .stock-alert-close:hover {
      opacity: 1;
    }

    .stock-card {
      border: 1px solid rgba(75, 70, 92, 0.12);
      border-radius: 0.75rem;
      padding: 1rem;
      height: 100%;
      transition: all 0.2s ease;
    }

    .stock-card:hover {
      box-shadow: 0 0.25rem 1rem rgba(75, 70, 92, 0.08);
    }

    .stock-progress {
      height: 8px;
      border-radius: 30px;
    }

    .stock-filter-card {
      margin-bottom: 1rem;
    }
  </style>
@endsection

@section('content')
  @php
    $totalTanks = $tanks->count();

    $normalCount = $tanks->filter(function ($tank) {
        return (float) $tank->remaining_liters > (float) $tank->low_stock_liters;
    })->count();

    $lowCount = $tanks->filter(function ($tank) {
        return (float) $tank->remaining_liters <= (float) $tank->low_stock_liters
            && (float) $tank->remaining_liters > (float) $tank->empty_stock_liters;
    })->count();

    $emptyCount = $tanks->filter(function ($tank) {
        return (float) $tank->remaining_liters <= (float) $tank->empty_stock_liters;
    })->count();
  @endphp

  <div class="row g-4">

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">Stock น้ำยา</h5>
            <p class="mb-0 text-muted">
              ตรวจสอบปริมาณน้ำยาคงเหลือแยกตามตู้และช่องน้ำยา
            </p>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success stock-alert" role="alert">
            <div class="stock-alert-content">
              <i class="icon-base ti tabler-circle-check"></i>
              <span>{{ session('success') }}</span>
            </div>

            <button
              type="button"
              class="stock-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="icon-base ti tabler-bottle"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ number_format($totalTanks) }}</h5>
              <small class="text-muted">ช่องน้ำยาทั้งหมด</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-success">
                <i class="icon-base ti tabler-circle-check"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ number_format($normalCount) }}</h5>
              <small class="text-muted">ปกติ</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-warning">
                <i class="icon-base ti tabler-alert-triangle"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ number_format($lowCount) }}</h5>
              <small class="text-muted">ใกล้หมด</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-danger">
                <i class="icon-base ti tabler-droplet-off"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ number_format($emptyCount) }}</h5>
              <small class="text-muted">หมด</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card stock-filter-card">
        <div class="card-body">
          <form method="GET" action="{{ route('stock.index') }}">
            <div class="row g-3 align-items-end">
              <div class="col-md-6">
                <label class="form-label">ค้นหา</label>
                <input
                  type="text"
                  name="keyword"
                  value="{{ request('keyword') }}"
                  class="form-control"
                  placeholder="ค้นหาด้วยรหัสตู้ ชื่อตู้ ชื่อน้ำยา หรือชื่อช่อง"
                >
              </div>

              <div class="col-md-4">
                <label class="form-label">สถานะ Stock</label>
                <select name="stock_status" class="form-select">
                  <option value="">ทั้งหมด</option>
                  <option value="normal" {{ request('stock_status') === 'normal' ? 'selected' : '' }}>
                    ปกติ
                  </option>
                  <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>
                    ใกล้หมด
                  </option>
                  <option value="empty" {{ request('stock_status') === 'empty' ? 'selected' : '' }}>
                    หมด
                  </option>
                </select>
              </div>

              <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                  <i class="icon-base ti tabler-search me-1"></i>
                  ค้นหา
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    @forelse ($tanks as $tank)
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

      <div class="col-md-6 col-xl-4">
        <div class="stock-card bg-white">
          <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
            <div>
              <h6 class="mb-1">
                {{ $tank->machine?->code ?: '-' }} - ช่อง {{ $tank->tank_no }}
              </h6>
              <div class="text-muted small">
                {{ $tank->machine?->name ?: '-' }}
              </div>
            </div>

            <span class="badge {{ $tank->stock_status_badge_class }}">
              {{ $tank->stock_status_text }}
            </span>
          </div>

          <div class="mb-3">
            <small class="text-muted">น้ำยา</small>
            <div class="fw-medium">
              {{ $tank->product?->name ?: 'ยังไม่เลือกน้ำยา' }}
            </div>
            <div class="text-muted small">
              {{ $tank->tank_name ?: 'ช่องน้ำยาที่ ' . $tank->tank_no }}
            </div>
          </div>

          <div class="d-flex justify-content-between mb-1">
            <span class="fw-medium">
              {{ number_format($remaining, 2) }} L
            </span>
            <span class="text-muted small">
              {{ number_format($percent, 0) }}%
            </span>
          </div>

          <div class="progress stock-progress mb-2">
            <div
              class="progress-bar {{ $progressClass }}"
              role="progressbar"
              style="width: {{ $percent }}%;"
              aria-valuenow="{{ $percent }}"
              aria-valuemin="0"
              aria-valuemax="100">
            </div>
          </div>

          <div class="d-flex justify-content-between text-muted small mb-3">
            <span>ความจุ {{ number_format($capacity, 2) }} L</span>
            <span>เตือน ≤ {{ number_format((float) $tank->low_stock_liters, 2) }} L</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
              {{ number_format((float) $tank->volume_per_press_ml, 2) }} ml/ครั้ง
            </div>

            <a href="{{ route('stock.show', $tank) }}" class="btn btn-sm btn-label-primary">
              ดูรายละเอียด
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="icon-base ti tabler-bottle-off mb-2" style="font-size: 42px;"></i>
            <h6 class="mb-1">ยังไม่มีข้อมูล Stock น้ำยา</h6>
            <p class="text-muted mb-0">
              กรุณาเพิ่มตู้และตั้งค่าช่องน้ำยาก่อน
            </p>
          </div>
        </div>
      </div>
    @endforelse

  </div>
@endsection
