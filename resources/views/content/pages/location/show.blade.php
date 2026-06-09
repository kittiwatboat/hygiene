@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดสถานที่')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
@endsection

@section('content')
  <div class="row">
    <div class="col-12">

      <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียดสถานที่</h5>
            <p class="mb-0 text-muted">
              ข้อมูลสถานที่ติดตั้งตู้หรือจุดให้บริการ
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('locations.edit', $location) }}" class="btn btn-warning">
              <i class="icon-base ti tabler-edit me-1"></i>
              แก้ไข
            </a>

            <a href="{{ route('locations.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>
          </div>
        </div>
      </div>

      <div class="row g-4">

        <div class="col-lg-8">
          <div class="card h-100">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="icon-base ti tabler-map-pin me-1"></i>
                ข้อมูลสถานที่
              </h5>
            </div>

            <div class="card-body">
              <div class="row g-4">

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">ชื่อสถานที่</small>
                  <div class="fw-semibold">{{ $location->name }}</div>
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">รหัสสถานที่</small>
                  <div class="fw-semibold">{{ $location->code ?: '-' }}</div>
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">ผู้ติดต่อ</small>
                  <div class="fw-semibold">{{ $location->contact_name ?: '-' }}</div>
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">เบอร์โทร</small>
                  <div class="fw-semibold">{{ $location->contact_phone ?: '-' }}</div>
                </div>

                <div class="col-12">
                  <small class="text-muted d-block mb-1">ที่อยู่เต็ม</small>
                  <div class="fw-semibold">
                    {{ $location->full_address ?: '-' }}
                  </div>
                </div>

                <div class="col-md-3">
                  <small class="text-muted d-block mb-1">จังหวัด</small>
                  <div>{{ $location->province ?: '-' }}</div>
                </div>

                <div class="col-md-3">
                  <small class="text-muted d-block mb-1">อำเภอ/เขต</small>
                  <div>{{ $location->district ?: '-' }}</div>
                </div>

                <div class="col-md-3">
                  <small class="text-muted d-block mb-1">ตำบล/แขวง</small>
                  <div>{{ $location->sub_district ?: '-' }}</div>
                </div>

                <div class="col-md-3">
                  <small class="text-muted d-block mb-1">รหัสไปรษณีย์</small>
                  <div>{{ $location->postcode ?: '-' }}</div>
                </div>

                <div class="col-12">
                  <small class="text-muted d-block mb-1">หมายเหตุ</small>
                  <div>
                    {{ $location->remark ?: '-' }}
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="icon-base ti tabler-info-circle me-1"></i>
                สถานะ
              </h5>
            </div>

            <div class="card-body">
              <div class="mb-3">
                <small class="text-muted d-block mb-1">สถานะการใช้งาน</small>

                @if ($location->is_active)
                  <span class="badge bg-label-success">เปิดใช้งาน</span>
                @else
                  <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                @endif
              </div>

              <div class="mb-3">
                <small class="text-muted d-block mb-1">วันที่สร้าง</small>
                <div>{{ $location->created_at?->format('d/m/Y H:i') ?: '-' }}</div>
              </div>

              <div>
                <small class="text-muted d-block mb-1">อัปเดตล่าสุด</small>
                <div>{{ $location->updated_at?->format('d/m/Y H:i') ?: '-' }}</div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="icon-base ti tabler-current-location me-1"></i>
                พิกัด
              </h5>
            </div>

            <div class="card-body">
              <div class="mb-3">
                <small class="text-muted d-block mb-1">Latitude</small>
                <div class="fw-semibold">{{ $location->latitude ?: '-' }}</div>
              </div>

              <div class="mb-3">
                <small class="text-muted d-block mb-1">Longitude</small>
                <div class="fw-semibold">{{ $location->longitude ?: '-' }}</div>
              </div>

              @if ($location->latitude && $location->longitude)
                <a
                  href="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="btn btn-label-primary w-100"
                >
                  <i class="icon-base ti tabler-map-2 me-1"></i>
                  เปิดใน Google Maps
                </a>
              @else
                <button type="button" class="btn btn-label-secondary w-100" disabled>
                  ยังไม่มีพิกัด
                </button>
              @endif
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
@endsection
