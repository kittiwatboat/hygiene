@extends('layouts/layoutMaster')

@section('title', 'จัดการสถานที่')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">จัดการสถานที่</h5>
            <p class="mb-0 text-muted">
              รายการสถานที่ติดตั้งตู้ทั้งหมดในระบบ สามารถเพิ่ม แก้ไข ดูรายละเอียด และลบข้อมูลได้
            </p>
          </div>

          <div>
            <a href="{{ route('locations.create') }}" class="btn btn-primary">
              <i class="icon-base ti tabler-plus me-1"></i>
              เพิ่มสถานที่
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success alert-dismissible mx-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="card-body border-bottom">
          <form method="GET" action="{{ route('locations.index') }}" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">ค้นหา</label>
              <input
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                class="form-control"
                placeholder="ค้นหาชื่อสถานที่, รหัส, เบอร์โทร, จังหวัด"
              >
            </div>

            <div class="col-md-3">
              <label class="form-label">สถานะ</label>
              <select name="status" class="form-select">
                <option value="">ทั้งหมด</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>เปิดใช้งาน</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>ปิดใช้งาน</option>
              </select>
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="icon-base ti tabler-search me-1"></i>
                ค้นหา
              </button>

              <a href="{{ route('locations.index') }}" class="btn btn-label-secondary">
                ล้าง
              </a>
            </div>
          </form>
        </div>

        <div class="table-responsive text-nowrap">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th style="width: 70px;">#</th>
                <th>สถานที่</th>
                <th>ผู้ติดต่อ</th>
                <th>ที่อยู่</th>
                <th>พิกัด</th>
                <th class="text-center">สถานะ</th>
                <th class="text-end" style="width: 180px;">จัดการ</th>
              </tr>
            </thead>

            <tbody>
              @forelse ($locations as $location)
                <tr>
                  <td>
                    {{ $locations->firstItem() + $loop->index }}
                  </td>

                  <td>
                    <div class="fw-semibold">{{ $location->name }}</div>

                    @if ($location->code)
                      <small class="text-muted">รหัส: {{ $location->code }}</small>
                    @else
                      <small class="text-muted">ยังไม่มีรหัส</small>
                    @endif
                  </td>

                  <td>
                    @if ($location->contact_name || $location->contact_phone)
                      <div>{{ $location->contact_name ?: '-' }}</div>
                      <small class="text-muted">{{ $location->contact_phone ?: '-' }}</small>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>

                  <td style="min-width: 260px;">
                    @if ($location->full_address)
                      <div class="text-wrap" style="max-width: 320px;">
                        {{ $location->full_address }}
                      </div>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>

                  <td>
                    @if ($location->latitude && $location->longitude)
                      <div>{{ $location->latitude }}</div>
                      <small class="text-muted">{{ $location->longitude }}</small>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>

                  <td class="text-center">
                    @if ($location->is_active)
                      <span class="badge bg-label-success">เปิดใช้งาน</span>
                    @else
                      <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                    @endif
                  </td>

                  <td class="text-end">
                    <div class="d-inline-flex gap-1">
                      <a href="{{ route('locations.show', $location) }}" class="btn btn-sm btn-icon btn-label-info">
                        <i class="icon-base ti tabler-eye"></i>
                      </a>

                      <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-icon btn-label-warning">
                        <i class="icon-base ti tabler-edit"></i>
                      </a>

                      <form
                        action="{{ route('locations.destroy', $location) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('ยืนยันการลบสถานที่นี้หรือไม่?')"
                      >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-sm btn-icon btn-label-danger">
                          <i class="icon-base ti tabler-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <div class="text-muted">
                      <i class="icon-base ti tabler-map-pin-off d-block mb-2" style="font-size: 42px;"></i>
                      ยังไม่มีข้อมูลสถานที่
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if ($locations->hasPages())
          <div class="card-footer">
            {{ $locations->links() }}
          </div>
        @endif

      </div>
    </div>
  </div>
@endsection
