@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดงานซ่อม')

@section('content')
  <div class="row g-4">

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียดงานซ่อม</h5>
            <p class="text-muted mb-0">
              {{ $maintenance->code ?: '-' }} |
              {{ $maintenance->machine?->code ?: '-' }} - {{ $maintenance->machine?->name ?: '-' }}
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('maintenances.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>

            <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-primary">
              <i class="icon-base ti tabler-pencil me-1"></i>
              แก้ไข
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
        <h5 class="card-header">ข้อมูลงานซ่อม</h5>

        <div class="card-body">
          <div class="row g-4">

            <div class="col-md-6">
              <small class="text-muted">รหัสงาน</small>
              <div class="fw-medium">{{ $maintenance->code ?: '-' }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ตู้</small>
              <div class="fw-medium">
                {{ $maintenance->machine?->code ?: '-' }} - {{ $maintenance->machine?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานที่</small>
              <div class="fw-medium">
                {{ $maintenance->machine?->location?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ประเภท</small>
              <div class="fw-medium">{{ $maintenance->type_text }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ความสำคัญ</small>
              <div>
                <span class="badge {{ $maintenance->priority_badge_class }}">
                  {{ $maintenance->priority_text }}
                </span>
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานะงาน</small>
              <div>
                <span class="badge {{ $maintenance->status_badge_class }}">
                  {{ $maintenance->status_text }}
                </span>
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ผู้แจ้ง</small>
              <div class="fw-medium">
                {{ $maintenance->reportedBy?->full_name ?: $maintenance->reportedBy?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ผู้รับผิดชอบ</small>
              <div class="fw-medium">
                {{ $maintenance->assignedTo?->full_name ?: $maintenance->assignedTo?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-4">
              <small class="text-muted">วันที่แจ้ง</small>
              <div class="fw-medium">
                {{ optional($maintenance->reported_at)->format('d/m/Y H:i') ?: '-' }}
              </div>
            </div>

            <div class="col-md-4">
              <small class="text-muted">เริ่มซ่อม</small>
              <div class="fw-medium">
                {{ optional($maintenance->started_at)->format('d/m/Y H:i') ?: '-' }}
              </div>
            </div>

            <div class="col-md-4">
              <small class="text-muted">ซ่อมเสร็จ</small>
              <div class="fw-medium">
                {{ optional($maintenance->finished_at)->format('d/m/Y H:i') ?: '-' }}
              </div>
            </div>

            <div class="col-12">
              <small class="text-muted">รายละเอียดปัญหา</small>
              <div class="fw-medium">
                {{ $maintenance->problem ?: '-' }}
              </div>
            </div>

            <div class="col-12">
              <small class="text-muted">วิธีแก้ไข / ผลการซ่อม</small>
              <div class="fw-medium">
                {{ $maintenance->solution ?: '-' }}
              </div>
            </div>

            <div class="col-12">
              <small class="text-muted">หมายเหตุ</small>
              <div class="fw-medium">
                {{ $maintenance->remark ?: '-' }}
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card">
        <h5 class="card-header">สรุปสถานะ</h5>

        <div class="card-body">
          <div class="mb-3">
            <small class="text-muted">สถานะงาน</small>
            <h5 class="mb-0">{{ $maintenance->status_text }}</h5>
          </div>

          <div class="mb-3">
            <small class="text-muted">ความสำคัญ</small>
            <h5 class="mb-0">{{ $maintenance->priority_text }}</h5>
          </div>

          <div class="mb-3">
            <small class="text-muted">ตู้</small>
            <h5 class="mb-0">
              {{ $maintenance->machine?->code ?: '-' }}
            </h5>
          </div>

          <div class="mb-0">
            <small class="text-muted">สถานะตู้ปัจจุบัน</small>
            <h5 class="mb-0">
              {{ $maintenance->machine?->status_text ?? $maintenance->machine?->status ?? '-' }}
            </h5>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
