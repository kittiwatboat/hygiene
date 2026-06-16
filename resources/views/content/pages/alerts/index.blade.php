@extends('layouts/layoutMaster')

@section('title', 'แจ้งเตือน')

@section('page-style')
<style>
  .alert-summary-card {
    border: 1px solid rgba(67, 89, 113, .12);
    border-radius: 16px;
    height: 100%;
  }

  .alert-list-card {
    border: 1px solid rgba(67, 89, 113, .12);
    border-radius: 16px;
    transition: all .2s ease;
  }

  .alert-list-card:hover {
    border-color: rgba(0, 127, 196, .3);
    box-shadow: 0 8px 20px rgba(67, 89, 113, .08);
  }

  .alert-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .alert-level-urgent {
    color: #ea5455;
    background: rgba(234, 84, 85, .14);
  }

  .alert-level-danger {
    color: #ea5455;
    background: rgba(234, 84, 85, .10);
  }

  .alert-level-warning {
    color: #ff9f43;
    background: rgba(255, 159, 67, .14);
  }

  .alert-level-info {
    color: #00cfe8;
    background: rgba(0, 207, 232, .14);
  }
</style>
@endsection

@section('content')
<div class="row g-4">

  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h5 class="mb-1">ศูนย์แจ้งเตือน</h5>
          <p class="text-muted mb-0">
            รวมการแจ้งเตือนจาก Stock ตู้ เครื่องปริ้น และงานซ่อมไว้ในหน้าเดียว
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card alert-summary-card">
      <div class="card-body">
        <small class="text-muted">แจ้งเตือนทั้งหมด</small>
        <h4 class="mb-0 mt-1">{{ number_format($summary['total']) }}</h4>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card alert-summary-card">
      <div class="card-body">
        <small class="text-muted">ด่วนมาก</small>
        <h4 class="mb-0 mt-1 text-danger">
          {{ number_format($summary['urgent']) }}
        </h4>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card alert-summary-card">
      <div class="card-body">
        <small class="text-muted">ผิดปกติ</small>
        <h4 class="mb-0 mt-1 text-danger">
          {{ number_format($summary['danger']) }}
        </h4>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card alert-summary-card">
      <div class="card-body">
        <small class="text-muted">ควรตรวจสอบ</small>
        <h4 class="mb-0 mt-1 text-warning">
          {{ number_format($summary['warning']) }}
        </h4>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <form method="GET" action="{{ route('alerts.index') }}">
          <div class="row g-3 align-items-end">
            <div class="col-md-5">
              <label class="form-label">ค้นหา</label>
              <input
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                class="form-control"
                placeholder="ค้นหาตู้ สถานที่ หรือรายละเอียดแจ้งเตือน"
              >
            </div>

            <div class="col-md-3">
              <label class="form-label">ประเภท</label>
              <select name="type" class="form-select">
                <option value="">ทั้งหมด</option>
                <option value="stock" {{ request('type') === 'stock' ? 'selected' : '' }}>
                  Stock น้ำยา
                </option>
                <option value="machine" {{ request('type') === 'machine' ? 'selected' : '' }}>
                  ตู้กดน้ำยา
                </option>
                <option value="printer" {{ request('type') === 'printer' ? 'selected' : '' }}>
                  เครื่องปริ้น
                </option>
                <option value="maintenance" {{ request('type') === 'maintenance' ? 'selected' : '' }}>
                  งานซ่อม
                </option>
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">ระดับ</label>
              <select name="level" class="form-select">
                <option value="">ทั้งหมด</option>
                <option value="urgent" {{ request('level') === 'urgent' ? 'selected' : '' }}>
                  ด่วนมาก
                </option>
                <option value="danger" {{ request('level') === 'danger' ? 'selected' : '' }}>
                  ผิดปกติ
                </option>
                <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>
                  ควรตรวจสอบ
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

  @forelse ($alerts as $alert)
    <div class="col-12">
      <div class="card alert-list-card">
        <div class="card-body">
          <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">

            <div class="alert-icon-box alert-level-{{ $alert['level'] }}">
              <i class="icon-base ti {{ $alert['icon'] }}"></i>
            </div>

            <div class="flex-grow-1">
              <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                <h6 class="mb-0">{{ $alert['title'] }}</h6>

                @if ($alert['level'] === 'urgent')
                  <span class="badge bg-label-danger">ด่วนมาก</span>
                @elseif ($alert['level'] === 'danger')
                  <span class="badge bg-label-danger">ผิดปกติ</span>
                @elseif ($alert['level'] === 'warning')
                  <span class="badge bg-label-warning">ควรตรวจสอบ</span>
                @else
                  <span class="badge bg-label-info">ข้อมูล</span>
                @endif
              </div>

              <div class="text-muted mb-2">
                {{ $alert['description'] }}
              </div>

              <div class="d-flex flex-wrap gap-3 small text-muted">
                @if (!empty($alert['machine_code']))
                  <span>
                    <i class="icon-base ti tabler-wash-machine me-1"></i>
                    {{ $alert['machine_code'] }}
                    @if (!empty($alert['machine_name']))
                      - {{ $alert['machine_name'] }}
                    @endif
                  </span>
                @endif

                @if (!empty($alert['location']))
                  <span>
                    <i class="icon-base ti tabler-map-pin me-1"></i>
                    {{ $alert['location'] }}
                  </span>
                @endif
              </div>
            </div>

            <div>
              <a href="{{ $alert['url'] }}" class="btn btn-label-primary">
                ดูรายละเอียด
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12">
      <div class="card">
        <div class="card-body text-center py-5">
          <div class="mb-3">
            <i class="icon-base ti tabler-bell-check" style="font-size: 48px;"></i>
          </div>

          <h5 class="mb-1">ไม่มีรายการแจ้งเตือน</h5>
          <p class="text-muted mb-0">
            ขณะนี้ระบบทำงานปกติ ไม่มีรายการที่ต้องตรวจสอบ
          </p>
        </div>
      </div>
    </div>
  @endforelse

</div>
@endsection
