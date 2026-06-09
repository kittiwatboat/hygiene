@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดตู้')

@section('content')
  <style>
    .machine-info-card {
      height: 100%;
    }

    .machine-detail-label {
      color: #6f6b7d;
      font-size: 0.8125rem;
      margin-bottom: 0.25rem;
    }

    .machine-detail-value {
      color: #444050;
      font-weight: 600;
      margin-bottom: 0;
      word-break: break-word;
    }

    .machine-stat-card {
      border: 1px solid rgba(75, 70, 92, 0.12);
      border-radius: 0.75rem;
      padding: 1rem;
      height: 100%;
      background: #fff;
    }

    .machine-stat-icon {
      width: 42px;
      height: 42px;
      border-radius: 0.625rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
    }

    .machine-stock-progress {
      height: 8px;
      border-radius: 30px;
    }

    .machine-actions .btn {
      white-space: nowrap;
    }
  </style>

  @php
    $tankCapacity = (float) ($machine->tank_capacity_liter ?? 0);
    $currentStock = (float) ($machine->current_stock_liter ?? 0);
    $volumePerPressMl = (float) ($machine->volume_per_press_ml ?? 0);
    $totalPressCount = (int) ($machine->total_press_count ?? 0);

    $stockPercent = 0;

    if ($tankCapacity > 0) {
        $stockPercent = ($currentStock / $tankCapacity) * 100;
        $stockPercent = min(max($stockPercent, 0), 100);
    }

    $remainingPressCount = 0;

    if ($volumePerPressMl > 0 && $currentStock > 0) {
        $remainingPressCount = floor(($currentStock * 1000) / $volumePerPressMl);
    }

    $statusText = match ($machine->status) {
        'active' => 'ใช้งานปกติ',
        'inactive' => 'ปิดใช้งาน',
        'maintenance' => 'ซ่อมบำรุง',
        'out_of_stock' => 'น้ำยาหมด',
        default => 'ไม่ทราบสถานะ',
    };

    $statusClass = match ($machine->status) {
        'active' => 'bg-label-success',
        'inactive' => 'bg-label-secondary',
        'maintenance' => 'bg-label-warning',
        'out_of_stock' => 'bg-label-danger',
        default => 'bg-label-dark',
    };

    $stockClass = 'bg-primary';

    if ($stockPercent <= 20) {
        $stockClass = 'bg-danger';
    } elseif ($stockPercent <= 50) {
        $stockClass = 'bg-warning';
    }
  @endphp

  <div class="row g-6">

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <div class="d-flex align-items-center gap-3">
              <div class="avatar avatar-md">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="icon-base ti tabler-wash-machine"></i>
                </span>
              </div>

              <div>
                <h5 class="mb-1">รายละเอียดตู้</h5>
                <p class="mb-0 text-muted">
                  {{ $machine->machine_code }} - {{ $machine->machine_name }}
                </p>
              </div>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2 machine-actions">
            <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>

            <a href="{{ route('machines.edit', $machine) }}" class="btn btn-primary">
              <i class="icon-base ti tabler-pencil me-1"></i>
              แก้ไข
            </a>

            <button type="button" class="btn btn-label-danger" onclick="confirmDeleteMachine()">
              <i class="icon-base ti tabler-trash me-1"></i>
              ลบ
            </button>
          </div>
        </div>
      </div>
    </div>

    @if (session('success'))
      <div class="col-12">
        <div class="alert alert-success alert-dismissible mb-0" role="alert">
          <i class="icon-base ti tabler-circle-check me-1"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    @if (session('error'))
      <div class="col-12">
        <div class="alert alert-danger alert-dismissible mb-0" role="alert">
          <i class="icon-base ti tabler-alert-circle me-1"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <div class="col-xl-3 col-md-6">
      <div class="machine-stat-card">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="mb-1 text-muted">Stock คงเหลือ</p>
            <h4 class="mb-0">{{ number_format($currentStock, 2) }} L</h4>
          </div>

          <div class="machine-stat-icon bg-label-primary">
            <i class="icon-base ti tabler-droplet"></i>
          </div>
        </div>

        <div class="mt-3">
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">คงเหลือ</small>
            <small class="text-muted">{{ number_format($stockPercent, 0) }}%</small>
          </div>

          <div class="progress machine-stock-progress">
            <div
              class="progress-bar {{ $stockClass }}"
              role="progressbar"
              style="width: {{ $stockPercent }}%;"
              aria-valuenow="{{ $stockPercent }}"
              aria-valuemin="0"
              aria-valuemax="100">
            </div>
          </div>

          <small class="text-muted d-block mt-1">
            ความจุถัง {{ number_format($tankCapacity, 2) }} L
          </small>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="machine-stat-card">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="mb-1 text-muted">จำนวนการกดทั้งหมด</p>
            <h4 class="mb-0">{{ number_format($totalPressCount) }}</h4>
          </div>

          <div class="machine-stat-icon bg-label-info">
            <i class="icon-base ti tabler-hand-click"></i>
          </div>
        </div>

        <small class="text-muted d-block mt-3">
          รวมจำนวนครั้งที่ตู้บันทึกไว้
        </small>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="machine-stat-card">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="mb-1 text-muted">ปริมาณต่อครั้ง</p>
            <h4 class="mb-0">{{ number_format($volumePerPressMl, 2) }} ml</h4>
          </div>

          <div class="machine-stat-icon bg-label-success">
            <i class="icon-base ti tabler-ruler-measure"></i>
          </div>
        </div>

        <small class="text-muted d-block mt-3">
          ปริมาณน้ำยาที่จ่ายต่อการกด 1 ครั้ง
        </small>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="machine-stat-card">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="mb-1 text-muted">กดได้อีกประมาณ</p>
            <h4 class="mb-0">{{ number_format($remainingPressCount) }}</h4>
          </div>

          <div class="machine-stat-icon bg-label-warning">
            <i class="icon-base ti tabler-calculator"></i>
          </div>
        </div>

        <small class="text-muted d-block mt-3">
          คำนวณจาก stock คงเหลือและ ml ต่อครั้ง
        </small>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="card machine-info-card">
        <h5 class="card-header">ข้อมูลตู้</h5>

        <div class="card-body">
          <div class="row g-4">

            <div class="col-md-6">
              <div class="machine-detail-label">รหัสตู้</div>
              <p class="machine-detail-value">{{ $machine->machine_code }}</p>
            </div>

            <div class="col-md-6">
              <div class="machine-detail-label">ชื่อตู้</div>
              <p class="machine-detail-value">{{ $machine->machine_name }}</p>
            </div>

            <div class="col-md-6">
              <div class="machine-detail-label">สถานะ</div>
              <p class="machine-detail-value">
                <span class="badge {{ $statusClass }}">
                  {{ $statusText }}
                </span>
              </p>
            </div>

            <div class="col-md-6">
              <div class="machine-detail-label">ID ในระบบ</div>
              <p class="machine-detail-value">#{{ $machine->id }}</p>
            </div>

            <div class="col-md-6">
              <div class="machine-detail-label">ชื่อสถานที่ติดตั้ง</div>
              <p class="machine-detail-value">
                {{ $machine->location_name ?: '-' }}
              </p>
            </div>

            <div class="col-md-6">
              <div class="machine-detail-label">วันที่สร้างข้อมูล</div>
              <p class="machine-detail-value">
                {{ optional($machine->created_at)->format('d/m/Y H:i') ?: '-' }}
              </p>
            </div>

            <div class="col-md-6">
              <div class="machine-detail-label">แก้ไขล่าสุด</div>
              <p class="machine-detail-value">
                {{ optional($machine->updated_at)->format('d/m/Y H:i') ?: '-' }}
              </p>
            </div>

            <div class="col-md-6">
              <div class="machine-detail-label">ตำแหน่งพิกัด</div>
              <p class="machine-detail-value">
                @if (!empty($machine->latitude) && !empty($machine->longitude))
                  {{ $machine->latitude }}, {{ $machine->longitude }}
                @else
                  -
                @endif
              </p>
            </div>

            <div class="col-12">
              <div class="machine-detail-label">ที่อยู่</div>
              <p class="machine-detail-value">
                {{ $machine->address ?: '-' }}
              </p>
            </div>

            <div class="col-12">
              <div class="machine-detail-label">หมายเหตุ</div>
              <p class="machine-detail-value">
                {{ $machine->note ?: '-' }}
              </p>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card machine-info-card">
        <h5 class="card-header">ข้อมูล Stock</h5>

        <div class="card-body">
          <div class="mb-4">
            <div class="machine-detail-label">ความจุถังทั้งหมด</div>
            <p class="machine-detail-value">{{ number_format($tankCapacity, 2) }} L</p>
          </div>

          <div class="mb-4">
            <div class="machine-detail-label">น้ำยาคงเหลือ</div>
            <p class="machine-detail-value">{{ number_format($currentStock, 2) }} L</p>
          </div>

          <div class="mb-4">
            <div class="machine-detail-label">เปอร์เซ็นต์คงเหลือ</div>
            <p class="machine-detail-value">{{ number_format($stockPercent, 2) }}%</p>
          </div>

          <div class="mb-4">
            <div class="machine-detail-label">ปริมาณต่อการกด 1 ครั้ง</div>
            <p class="machine-detail-value">{{ number_format($volumePerPressMl, 2) }} ml</p>
          </div>

          <div class="mb-4">
            <div class="machine-detail-label">จำนวนการกดทั้งหมด</div>
            <p class="machine-detail-value">{{ number_format($totalPressCount) }} ครั้ง</p>
          </div>

          <div>
            <div class="machine-detail-label">จำนวนครั้งที่กดได้อีกโดยประมาณ</div>
            <p class="machine-detail-value">{{ number_format($remainingPressCount) }} ครั้ง</p>
          </div>
        </div>
      </div>
    </div>

    @if (!empty($machine->latitude) && !empty($machine->longitude))
      <div class="col-12">
        <div class="card">
          <h5 class="card-header">ตำแหน่งติดตั้ง</h5>

          <div class="card-body">
            <div class="ratio ratio-21x9 rounded overflow-hidden">
              <iframe
                src="https://maps.google.com/maps?q={{ $machine->latitude }},{{ $machine->longitude }}&hl=th&z=16&output=embed"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          </div>
        </div>
      </div>
    @endif

  </div>

  <form id="deleteMachineForm" action="{{ route('machines.destroy', $machine) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
  </form>
@endsection

@section('page-script')
  <script>
    function confirmDeleteMachine() {
      const confirmed = confirm('ยืนยันการลบตู้รายการนี้?');

      if (confirmed) {
        document.getElementById('deleteMachineForm').submit();
      }
    }
  </script>
@endsection
