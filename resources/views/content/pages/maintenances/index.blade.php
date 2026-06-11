@extends('layouts/layoutMaster')

@section('title', 'ซ่อมบำรุง')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])

  <style>
    .maintenance-alert {
      margin: 0 1.5rem 1rem 1.5rem;
      padding: 0.65rem 0.85rem;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .maintenance-alert-content {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      line-height: 1.3;
      font-weight: 500;
    }

    .maintenance-alert-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1rem;
      line-height: 1;
      padding: 0.25rem;
      cursor: pointer;
      opacity: 0.7;
    }

    .maintenance-alert-close:hover {
      opacity: 1;
    }
  </style>
@endsection

@section('content')
  @php
    $totalCount = $maintenances->count();
    $openCount = $maintenances->whereIn('status', ['reported', 'assigned', 'repairing'])->count();
    $completedCount = $maintenances->where('status', 'completed')->count();
    $urgentCount = $maintenances->where('priority', 'urgent')->count();
  @endphp

  <div class="row g-4">

    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">ซ่อมบำรุง</h5>
            <p class="mb-0 text-muted">
              จัดการงานซ่อมบำรุงของตู้ เครื่องปริ้น และอุปกรณ์ที่เกี่ยวข้อง
            </p>
          </div>

          <div>
            <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
              <i class="icon-base ti tabler-plus me-1"></i>
              เปิดงานซ่อม
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success maintenance-alert" role="alert">
            <div class="maintenance-alert-content">
              <i class="icon-base ti tabler-circle-check"></i>
              <span>{{ session('success') }}</span>
            </div>

            <button
              type="button"
              class="maintenance-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger maintenance-alert" role="alert">
            <div class="maintenance-alert-content">
              <i class="icon-base ti tabler-alert-circle"></i>
              <span>{{ session('error') }}</span>
            </div>

            <button
              type="button"
              class="maintenance-alert-close"
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
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="icon-base ti tabler-tools"></i>
            </span>
          </div>
          <div>
            <h5 class="mb-0">{{ number_format($totalCount) }}</h5>
            <small class="text-muted">งานทั้งหมด</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="icon-base ti tabler-clock"></i>
            </span>
          </div>
          <div>
            <h5 class="mb-0">{{ number_format($openCount) }}</h5>
            <small class="text-muted">กำลังดำเนินการ</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="icon-base ti tabler-circle-check"></i>
            </span>
          </div>
          <div>
            <h5 class="mb-0">{{ number_format($completedCount) }}</h5>
            <small class="text-muted">เสร็จแล้ว</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="icon-base ti tabler-alert-triangle"></i>
            </span>
          </div>
          <div>
            <h5 class="mb-0">{{ number_format($urgentCount) }}</h5>
            <small class="text-muted">ด่วนมาก</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body border-bottom">
          <form method="GET" action="{{ route('maintenances.index') }}">
            <div class="row g-3 align-items-end">
              <div class="col-md-3">
                <label class="form-label">ค้นหา</label>
                <input
                  type="text"
                  name="keyword"
                  value="{{ request('keyword') }}"
                  class="form-control"
                  placeholder="รหัสงาน / ตู้ / ปัญหา"
                >
              </div>

              <div class="col-md-3">
                <label class="form-label">ตู้</label>
                <select name="machine_id" class="form-select">
                  <option value="">ทั้งหมด</option>
                  @foreach ($machines as $machine)
                    <option
                      value="{{ $machine->id }}"
                      {{ (string) request('machine_id') === (string) $machine->id ? 'selected' : '' }}
                    >
                      {{ $machine->code }} - {{ $machine->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-2">
                <label class="form-label">ประเภท</label>
                <select name="type" class="form-select">
                  <option value="">ทั้งหมด</option>
                  <option value="machine_error" {{ request('type') === 'machine_error' ? 'selected' : '' }}>เครื่องขัดข้อง</option>
                  <option value="printer_error" {{ request('type') === 'printer_error' ? 'selected' : '' }}>เครื่องปริ้น</option>
                  <option value="network_error" {{ request('type') === 'network_error' ? 'selected' : '' }}>Network</option>
                  <option value="cleaning" {{ request('type') === 'cleaning' ? 'selected' : '' }}>ทำความสะอาด</option>
                  <option value="refill_issue" {{ request('type') === 'refill_issue' ? 'selected' : '' }}>เติมน้ำยา</option>
                  <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>อื่น ๆ</option>
                </select>
              </div>

              <div class="col-md-2">
                <label class="form-label">สถานะ</label>
                <select name="status" class="form-select">
                  <option value="">ทั้งหมด</option>
                  <option value="reported" {{ request('status') === 'reported' ? 'selected' : '' }}>แจ้งปัญหา</option>
                  <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>มอบหมายแล้ว</option>
                  <option value="repairing" {{ request('status') === 'repairing' ? 'selected' : '' }}>กำลังซ่อม</option>
                  <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>เสร็จแล้ว</option>
                  <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
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

        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th style="width: 70px;">#</th>
                <th>รหัสงาน</th>
                <th>ตู้</th>
                <th>ประเภท</th>
                <th>ความสำคัญ</th>
                <th>สถานะ</th>
                <th>ผู้รับผิดชอบ</th>
                <th>วันที่แจ้ง</th>
                <th style="width: 110px;">Actions</th>
              </tr>
            </thead>

            <tbody class="table-border-bottom-0">
              @forelse ($maintenances as $index => $maintenance)
                <tr>
                  <td>{{ $index + 1 }}</td>

                  <td>
                    <div class="fw-medium">{{ $maintenance->code ?: '-' }}</div>
                    <div class="text-muted small text-truncate" style="max-width: 260px;">
                      {{ $maintenance->problem ?: '-' }}
                    </div>
                  </td>

                  <td>
                    <div class="fw-medium">{{ $maintenance->machine?->code ?: '-' }}</div>
                    <div class="text-muted small">{{ $maintenance->machine?->name ?: '-' }}</div>
                  </td>

                  <td>{{ $maintenance->type_text }}</td>

                  <td>
                    <span class="badge {{ $maintenance->priority_badge_class }}">
                      {{ $maintenance->priority_text }}
                    </span>
                  </td>

                  <td>
                    <span class="badge {{ $maintenance->status_badge_class }}">
                      {{ $maintenance->status_text }}
                    </span>
                  </td>

                  <td>
                    {{ $maintenance->assignedTo?->full_name ?: $maintenance->assignedTo?->name ?: '-' }}
                  </td>

                  <td>
                    <div class="fw-medium">
                      {{ optional($maintenance->reported_at)->format('d/m/Y') ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      {{ optional($maintenance->reported_at)->format('H:i') ?: '' }}
                    </div>
                  </td>

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                      </button>

                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('maintenances.show', $maintenance) }}">
                          <i class="icon-base ti tabler-eye me-1"></i>
                          ดูรายละเอียด
                        </a>

                        <a class="dropdown-item" href="{{ route('maintenances.edit', $maintenance) }}">
                          <i class="icon-base ti tabler-pencil me-1"></i>
                          แก้ไข
                        </a>

                        <form
                          action="{{ route('maintenances.destroy', $maintenance) }}"
                          method="POST"
                          class="maintenance-delete-form">
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
                  <td colspan="9" class="text-center py-5">
                    <i class="icon-base ti tabler-tools-off mb-2" style="font-size: 42px;"></i>
                    <h6 class="mb-1">ยังไม่มีงานซ่อมบำรุง</h6>
                    <p class="text-muted mb-3">กดปุ่มเปิดงานซ่อมเพื่อเริ่มใช้งาน</p>

                    <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
                      <i class="icon-base ti tabler-plus me-1"></i>
                      เปิดงานซ่อม
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
      const deleteForms = document.querySelectorAll('.maintenance-delete-form');

      deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
          const confirmed = confirm('ยืนยันการลบงานซ่อมบำรุงนี้?');

          if (!confirmed) {
            event.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
