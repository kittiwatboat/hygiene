@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดเครื่องปริ้น')

@section('content')
  <div class="row g-4">

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียดเครื่องปริ้น</h5>
            <p class="text-muted mb-0">{{ $printer->code ?: '-' }} - {{ $printer->name }}</p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('printers.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>

            <a href="{{ route('printers.edit', $printer) }}" class="btn btn-primary">
              <i class="icon-base ti tabler-pencil me-1"></i>
              แก้ไข
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="card">
        <h5 class="card-header">ข้อมูลเครื่องปริ้น</h5>

        <div class="card-body">
          <div class="row g-4">
            <div class="col-md-6">
              <small class="text-muted">รหัสเครื่องปริ้น</small>
              <div class="fw-medium">{{ $printer->code ?: '-' }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ชื่อเครื่องปริ้น</small>
              <div class="fw-medium">{{ $printer->name }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ตู้ที่เชื่อมต่อ</small>
              <div class="fw-medium">
                @if ($printer->machine)
                  {{ $printer->machine->code }} - {{ $printer->machine->name }}
                @else
                  -
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">Serial Number</small>
              <div class="fw-medium">{{ $printer->serial_number ?: '-' }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ยี่ห้อ</small>
              <div class="fw-medium">{{ $printer->brand ?: '-' }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">รุ่น</small>
              <div class="fw-medium">{{ $printer->model ?: '-' }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ประเภทการเชื่อมต่อ</small>
              <div class="fw-medium">{{ $printer->connection_type_text }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">IP / Port</small>
              <div class="fw-medium">
                {{ $printer->ip_address ?: '-' }}
                @if ($printer->port)
                  :{{ $printer->port }}
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ขนาดกระดาษ</small>
              <div class="fw-medium">{{ $printer->paper_size ?: '-' }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานะเครื่องปริ้น</small>
              <div>
                <span class="badge {{ $printer->status_badge_class }}">
                  {{ $printer->status_text }}
                </span>
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานะกระดาษ</small>
              <div>
                @if ($printer->paper_available)
                  <span class="badge bg-label-success">มีกระดาษ</span>
                @else
                  <span class="badge bg-label-warning">กระดาษหมด</span>
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">เปิดใช้งาน</small>
              <div>
                @if ($printer->is_active)
                  <span class="badge bg-label-success">เปิดใช้งาน</span>
                @else
                  <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ติดต่อครั้งล่าสุด</small>
              <div class="fw-medium">
                {{ optional($printer->last_seen_at)->format('d/m/Y H:i') ?: '-' }}
              </div>
            </div>

            <div class="col-12">
              <small class="text-muted">หมายเหตุ</small>
              <div class="fw-medium">{{ $printer->remark ?: '-' }}</div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card">
        <h5 class="card-header">สถานะใช้งาน</h5>

        <div class="card-body">
          <div class="mb-3">
            <small class="text-muted">เครื่องปริ้น</small>
            <h5 class="mb-0">{{ $printer->status_text }}</h5>
          </div>

          <div class="mb-3">
            <small class="text-muted">กระดาษ</small>
            <h5 class="mb-0">
              {{ $printer->paper_available ? 'พร้อมใช้งาน' : 'กระดาษหมด' }}
            </h5>
          </div>

          <div class="mb-0">
            <small class="text-muted">เชื่อมต่อกับตู้</small>
            <h5 class="mb-0">
              {{ $printer->machine ? $printer->machine->code : 'ยังไม่ผูกตู้' }}
            </h5>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
