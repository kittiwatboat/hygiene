@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดผู้ใช้งาน')

@section('content')
  <div class="row g-4">

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียดผู้ใช้งาน</h5>
            <p class="text-muted mb-0">{{ $user->full_name }} - {{ $user->email }}</p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>

            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
              <i class="icon-base ti tabler-pencil me-1"></i>
              แก้ไข
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="card">
        <h5 class="card-header">ข้อมูลผู้ใช้งาน</h5>

        <div class="card-body">
          <div class="row g-4">

            <div class="col-md-6">
              <small class="text-muted">ชื่อ - นามสกุล</small>
              <div class="fw-medium">{{ $user->full_name }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">อีเมล</small>
              <div class="fw-medium">{{ $user->email }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">เบอร์โทร</small>
              <div class="fw-medium">{{ $user->phone ?: '-' }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สิทธิ์ผู้ใช้งาน</small>
              <div>
                <span class="badge {{ $user->role_badge_class }}">
                  {{ $user->role_text }}
                </span>
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานะบัญชี</small>
              <div>
                <span class="badge {{ $user->status_badge_class }}">
                  {{ $user->status_text }}
                </span>
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">เปิดใช้งาน</small>
              <div>
                @if ($user->is_active)
                  <span class="badge bg-label-success">เปิดใช้งาน</span>
                @else
                  <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">เข้าสู่ระบบล่าสุด</small>
              <div class="fw-medium">
                {{ optional($user->last_login_at)->format('d/m/Y H:i') ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">วันที่สร้าง</small>
              <div class="fw-medium">
                {{ optional($user->created_at)->format('d/m/Y H:i') ?: '-' }}
              </div>
            </div>

            <div class="col-12">
              <small class="text-muted">หมายเหตุ</small>
              <div class="fw-medium">{{ $user->remark ?: '-' }}</div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card">
        <h5 class="card-header">สรุปบัญชี</h5>

        <div class="card-body">
          <div class="text-center mb-4">
            <div class="avatar avatar-xl mx-auto mb-3">
              <span class="avatar-initial rounded bg-label-primary fs-3">
                {{ mb_substr($user->full_name, 0, 1) }}
              </span>
            </div>

            <h5 class="mb-1">{{ $user->full_name }}</h5>
            <p class="text-muted mb-0">{{ $user->email }}</p>
          </div>

          <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">สิทธิ์</span>
            <span class="badge {{ $user->role_badge_class }}">
              {{ $user->role_text }}
            </span>
          </div>

          <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">สถานะ</span>
            <span class="badge {{ $user->status_badge_class }}">
              {{ $user->status_text }}
            </span>
          </div>

          <div class="d-flex justify-content-between">
            <span class="text-muted">ใช้งาน</span>
            <span>
              {{ $user->is_active ? 'เปิด' : 'ปิด' }}
            </span>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
