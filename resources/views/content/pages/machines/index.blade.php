@extends('layouts/layoutMaster')

@section('title', 'จัดการตู้')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">จัดการตู้</h5>
            <p class="mb-0 text-muted">
              รายการตู้ทั้งหมดในระบบ สามารถดูรายละเอียด แก้ไข และลบข้อมูลตู้ได้
            </p>
          </div>

          <div>
            <a href="{{ route('machines.create') }}" class="btn btn-primary">
              <i class="icon-base ti tabler-plus me-1"></i>
              เพิ่มตู้
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success alert-dismissible mx-6 mt-2 mb-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible mx-6 mt-2 mb-0" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th style="width: 70px;">#</th>
                <th>รหัสตู้</th>
                <th>ชื่อตู้ / สถานที่</th>
                <th>Stock คงเหลือ</th>
                <th>ปริมาณต่อครั้ง</th>
                <th>จำนวนการกด</th>
                <th>สถานะ</th>
                <th style="width: 110px;">Actions</th>
              </tr>
            </thead>

            <tbody class="table-border-bottom-0">
              @forelse ($machines as $index => $machine)
                @php
                  $tankCapacity = (float) ($machine->tank_capacity_liter ?? 0);
                  $currentStock = (float) ($machine->current_stock_liter ?? 0);

                  $stockPercent = 0;

                  if ($tankCapacity > 0) {
                      $stockPercent = ($currentStock / $tankCapacity) * 100;
                      $stockPercent = min(max($stockPercent, 0), 100);
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
                @endphp

                <tr>
                  <td>
                    <span class="fw-medium">{{ $index + 1 }}</span>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      {{-- <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="icon-base ti tabler-device-desktop"></i>
                        </span>
                      </div> --}}

                      <div>
                        <span class="fw-medium">{{ $machine->machine_code }}</span>
                        <div class="text-muted small">
                          ID: {{ $machine->id }}
                        </div>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="fw-medium">{{ $machine->machine_name }}</div>

                    <div class="text-muted small">
                      {{ $machine->location_name ?: 'ไม่ระบุสถานที่' }}
                    </div>

                    @if (!empty($machine->address))
                      <div class="text-muted small text-truncate" style="max-width: 260px;">
                        {{ $machine->address }}
                      </div>
                    @endif
                  </td>

                  <td>
                    <div style="min-width: 180px;">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-medium">
                          {{ number_format($currentStock, 2) }} L
                        </span>
                        <small class="text-muted">
                          {{ number_format($stockPercent, 0) }}%
                        </small>
                      </div>

                      <div class="progress" style="height: 7px;">
                        <div
                          class="progress-bar"
                          role="progressbar"
                          style="width: {{ $stockPercent }}%;"
                          aria-valuenow="{{ $stockPercent }}"
                          aria-valuemin="0"
                          aria-valuemax="100">
                        </div>
                      </div>

                      <small class="text-muted">
                        ความจุ {{ number_format($tankCapacity, 2) }} L
                      </small>
                    </div>
                  </td>

                  <td>
                    <span class="fw-medium">
                      {{ number_format((float) ($machine->volume_per_press_ml ?? 0), 2) }}
                    </span>
                    <span class="text-muted">ml</span>
                  </td>

                  <td>
                    <span class="fw-medium">
                      {{ number_format((int) ($machine->total_press_count ?? 0)) }}
                    </span>
                    <span class="text-muted">ครั้ง</span>
                  </td>

                  <td>
                    <span class="badge {{ $statusClass }} me-1">
                      {{ $statusText }}
                    </span>
                  </td>

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                      </button>

                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('machines.show', $machine) }}">
                          <i class="icon-base ti tabler-eye me-1"></i>
                          ดูรายละเอียด
                        </a>

                        <a class="dropdown-item" href="{{ route('machines.edit', $machine) }}">
                          <i class="icon-base ti tabler-pencil me-1"></i>
                          แก้ไข
                        </a>

                        <form
                          action="{{ route('machines.destroy', $machine) }}"
                          method="POST"
                          class="machine-delete-form">
                          @csrf
                          @method('DELETE')

                          <button type="submit" class="dropdown-item text-danger">
                            <i class="icon-base ti tabler-trash me-1"></i>
                            ลบ
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <div class="mb-2">
                      <i class="icon-base ti tabler-device-desktop-off" style="font-size: 42px;"></i>
                    </div>
                    <h6 class="mb-1">ยังไม่มีข้อมูลตู้</h6>
                    <p class="text-muted mb-3">กดปุ่มเพิ่มตู้เพื่อเริ่มสร้างข้อมูลตู้ในระบบ</p>

                    <a href="{{ route('machines.create') }}" class="btn btn-primary">
                      <i class="icon-base ti tabler-plus me-1"></i>
                      เพิ่มตู้
                    </a>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const deleteForms = document.querySelectorAll('.machine-delete-form');

      deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
          const confirmed = confirm('ยืนยันการลบตู้รายการนี้?');

          if (!confirmed) {
            event.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
