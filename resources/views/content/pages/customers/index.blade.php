@extends('layouts/layoutMaster')

@section('title', 'จัดการสมาชิก')

@section('content')
<div class="card">

  <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
    <div>
      <h5 class="mb-1">สมาชิกและแต้มสะสม</h5>
      <p class="text-muted mb-0">
        จัดการข้อมูลสมาชิกและตรวจสอบแต้มคงเหลือ
      </p>
    </div>

    <a href="{{ route('customers.create') }}" class="btn btn-primary">
      <i class="icon-base ti tabler-plus me-1"></i>
      เพิ่มสมาชิก
    </a>
  </div>

  @if (session('success'))
    <div class="alert alert-success mx-4">
      {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger mx-4">
      {{ session('error') }}
    </div>
  @endif

  <div class="card-body border-top border-bottom">
    <form
      method="GET"
      action="{{ route('customers.index') }}"
    >
      <div class="row g-3 align-items-end">

        <div class="col-md-5">
          <label class="form-label">ค้นหา</label>

          <input
            type="text"
            name="keyword"
            value="{{ request('keyword') }}"
            class="form-control"
            placeholder="รหัสสมาชิก ชื่อ เบอร์โทร หรืออีเมล"
          >
        </div>

        <div class="col-md-3">
          <label class="form-label">สถานะสมาชิก</label>

          <select name="status" class="form-select">
            <option value="">ทั้งหมด</option>

            <option
              value="active"
              {{ request('status') === 'active' ? 'selected' : '' }}
            >
              ใช้งานปกติ
            </option>

            <option
              value="suspended"
              {{ request('status') === 'suspended' ? 'selected' : '' }}
            >
              ระงับการใช้งาน
            </option>

            <option
              value="blocked"
              {{ request('status') === 'blocked' ? 'selected' : '' }}
            >
              บล็อก
            </option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">เปิดใช้งาน</label>

          <select name="is_active" class="form-select">
            <option value="">ทั้งหมด</option>

            <option
              value="1"
              {{ request('is_active') === '1' ? 'selected' : '' }}
            >
              เปิด
            </option>

            <option
              value="0"
              {{ request('is_active') === '0' ? 'selected' : '' }}
            >
              ปิด
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

  <div class="table-responsive">
    <table class="table table-hover">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>รหัสสมาชิก</th>
          <th>สมาชิก</th>
          <th>ข้อมูลติดต่อ</th>
          <th class="text-end">แต้มคงเหลือ</th>
          <th>สถานะ</th>
          <th>ใช้งานล่าสุด</th>
          <th class="text-center">จัดการ</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($customers as $customer)
          <tr>
            <td>
              {{ $customers->firstItem() + $loop->index }}
            </td>

            <td>
              <a
                href="{{ route('customers.show', $customer) }}"
                class="fw-medium"
              >
                {{ $customer->member_code }}
              </a>
            </td>

            <td>
              <div class="fw-medium">{{ $customer->name }}</div>

              @if ($customer->email)
                <small class="text-muted">
                  {{ $customer->email }}
                </small>
              @endif
            </td>

            <td>
              {{ $customer->phone ?: '-' }}
            </td>

            <td class="text-end">
              <span class="fw-bold text-primary">
                {{ number_format((int) $customer->points_balance) }}
              </span>
              <small class="text-muted">แต้ม</small>
            </td>

            <td>
              <span class="badge {{ $customer->status_class }}">
                {{ $customer->status_text }}
              </span>
            </td>

            <td>
              {{ $customer->last_used_at
                  ? $customer->last_used_at->format('d/m/Y H:i')
                  : '-' }}
            </td>

            <td class="text-center">
              <div class="dropdown">
                <button
                  type="button"
                  class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                  data-bs-toggle="dropdown"
                >
                  <i class="icon-base ti tabler-dots-vertical"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                  <a
                    href="{{ route('customers.show', $customer) }}"
                    class="dropdown-item"
                  >
                    <i class="icon-base ti tabler-eye me-2"></i>
                    รายละเอียด
                  </a>

                  <a
                    href="{{ route('customers.edit', $customer) }}"
                    class="dropdown-item"
                  >
                    <i class="icon-base ti tabler-pencil me-2"></i>
                    แก้ไข
                  </a>

                  <div class="dropdown-divider"></div>

                  <form
                    action="{{ route('customers.destroy', $customer) }}"
                    method="POST"
                    onsubmit="return confirm('ยืนยันการลบสมาชิกนี้?')"
                  >
                    @csrf
                    @method('DELETE')

                    <button
                      type="submit"
                      class="dropdown-item text-danger"
                    >
                      <i class="icon-base ti tabler-trash me-2"></i>
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
              <i
                class="icon-base ti tabler-users-off text-muted mb-2"
                style="font-size: 48px;"
              ></i>

              <h6 class="mt-2 mb-1">ยังไม่มีสมาชิก</h6>

              <p class="text-muted mb-3">
                เพิ่มสมาชิกเพื่อเริ่มใช้งานระบบสะสมแต้ม
              </p>

              <a
                href="{{ route('customers.create') }}"
                class="btn btn-primary"
              >
                เพิ่มสมาชิก
              </a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($customers->hasPages())
    <div class="card-footer">
      {{ $customers->links() }}
    </div>
  @endif

</div>
@endsection
