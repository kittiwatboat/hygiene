@extends('layouts/layoutMaster')

@section('title', 'จัดการสมาชิก')

@section('content')
<div class="card">

  <div class="card-header d-flex flex-column flex-xl-row justify-content-between gap-3">
    <div>
      <h5 class="mb-1">สมาชิกและแต้มสะสม</h5>
      <p class="text-muted mb-0">
        จัดการข้อมูลสมาชิก ตรวจสอบแต้มคงเหลือ และประวัติการใช้งาน
      </p>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2">
      <a
        href="{{ route('customers.import-template') }}"
        class="btn btn-label-secondary"
      >
        <i class="icon-base ti tabler-file-download me-1"></i>
        ดาวน์โหลด Template
      </a>

      <button
        type="button"
        class="btn btn-label-info"
        data-bs-toggle="modal"
        data-bs-target="#importCustomerModal"
      >
        <i class="icon-base ti tabler-file-upload me-1"></i>
        Import Excel
      </button>

      <a
        href="{{ route('customers.export', request()->query()) }}"
        class="btn btn-label-success"
      >
        <i class="icon-base ti tabler-file-spreadsheet me-1"></i>
        Export Excel
      </a>

      <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-1"></i>
        เพิ่มสมาชิก
      </a>
    </div>
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


  @if (session('import_errors') && count(session('import_errors')))
    <div class="alert alert-warning mx-4">
      <div class="fw-semibold mb-2">
        รายการที่ Import ไม่สำเร็จ
      </div>

      <ul class="mb-0 ps-3">
        @foreach (session('import_errors') as $importError)
          <li>{{ $importError }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card-body border-top border-bottom">
    <form method="GET" action="{{ route('customers.index') }}">
      <div class="row g-3 align-items-end">

        <div class="col-xl-5 col-md-6">
          <label class="form-label">ค้นหา</label>
          <input
            type="text"
            name="keyword"
            value="{{ request('keyword') }}"
            class="form-control"
            placeholder="รหัสสมาชิก ชื่อ เบอร์โทร หรืออีเมล"
          >
        </div>

        <div class="col-xl-3 col-md-6">
          <label class="form-label">ประเภทสมาชิก</label>
          <select name="member_type" class="form-select">
            <option value="">ทั้งหมด</option>
            <option value="member" {{ request('member_type') === 'member' ? 'selected' : '' }}>
              Member
            </option>
            <option value="non_member" {{ request('member_type') === 'non_member' ? 'selected' : '' }}>
              Non-member
            </option>
            <option value="new_member" {{ request('member_type') === 'new_member' ? 'selected' : '' }}>
              New member
            </option>
          </select>
        </div>

        <div class="col-xl-2 col-md-6">
          <label class="form-label">เปิดใช้งาน</label>
          <select name="is_active" class="form-select">
            <option value="">ทั้งหมด</option>
            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>
              เปิด
            </option>
            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>
              ปิด
            </option>
          </select>
        </div>

        <div class="col-xl-2 col-md-6 d-grid">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-search me-1"></i>
            ค้นหา
          </button>
        </div>

      </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th style="width: 60px;">#</th>
          <th style="min-width: 150px;">วันที่สมัคร</th>
          <th style="min-width: 90px;">เวลา</th>
          <th style="min-width: 145px;">รหัสสมาชิก</th>
          <th style="min-width: 190px;">สมาชิก</th>
          <th style="min-width: 180px;">ข้อมูลติดต่อ</th>
          <th class="text-end" style="min-width: 130px;">แต้มคงเหลือ</th>
          <th style="min-width: 135px;">ประเภทสมาชิก</th>
          <th style="min-width: 130px;">สาขาตู้</th>
          <th style="min-width: 150px;">ใช้งานล่าสุด</th>
          <th class="text-end" style="min-width: 120px;">ยอดเติม</th>
          <th class="text-center" style="width: 90px;">จัดการ</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($customers as $customer)
          @php
            $registeredAt = $customer->registered_at
              ?? $customer->created_at
              ?? null;

            $memberType = $customer->member_type
              ?? $customer->customer_type
              ?? 'member';

            $memberTypeText = match ($memberType) {
              'new_member' => 'New member',
              'non_member' => 'Non-member',
              default => 'Member',
            };

            $memberTypeClass = match ($memberType) {
              'new_member' => 'bg-label-warning',
              'non_member' => 'bg-label-secondary',
              default => 'bg-label-success',
            };

            $branchName = data_get($customer, 'branch.name')
              ?? data_get($customer, 'kiosk.branch.name')
              ?? data_get($customer, 'machine.branch.name')
              ?? $customer->branch_name
              ?? '-';

            $totalTopup = $customer->total_topup
              ?? $customer->total_amount
              ?? $customer->total_spent
              ?? 0;
          @endphp

          <tr>
            <td>
              {{ $customers->firstItem() + $loop->index }}
            </td>

            <td>
              {{ $registeredAt
                  ? \Carbon\Carbon::parse($registeredAt)->format('d/m/Y')
                  : '-' }}
            </td>

            <td>
              {{ $registeredAt
                  ? \Carbon\Carbon::parse($registeredAt)->format('H:i')
                  : '-' }}
            </td>

            <td>
              <a
                href="{{ route('customers.show', $customer) }}"
                class="fw-medium"
              >
                {{ $customer->member_code ?: '-' }}
              </a>
            </td>

            <td>
              <div class="fw-medium">
                {{ $customer->name ?: '-' }}
              </div>

              @if ($customer->email)
                <small class="text-muted d-block">
                  {{ $customer->email }}
                </small>
              @endif
            </td>

            <td>
              <div>{{ $customer->phone ?: '-' }}</div>

              @if (!empty($customer->line_id))
                <small class="text-muted d-block">
                  LINE: {{ $customer->line_id }}
                </small>
              @endif
            </td>

            <td class="text-end">
              <span class="fw-bold text-primary">
                {{ number_format((int) ($customer->points_balance ?? 0)) }}
              </span>
              <small class="text-muted">แต้ม</small>
            </td>

            <td>
              <span class="badge {{ $memberTypeClass }}">
                {{ $memberTypeText }}
              </span>

              @if (!empty($customer->is_new_member_discount_used))
                <small class="text-muted d-block mt-1">
                  ใช้สิทธิ์สมาชิกใหม่แล้ว
                </small>
              @endif
            </td>

            <td>
              {{ $branchName }}
            </td>

            <td>
              {{ $customer->last_used_at
                  ? \Carbon\Carbon::parse($customer->last_used_at)->format('d/m/Y H:i')
                  : '-' }}
            </td>

            <td class="text-end fw-medium">
              {{ number_format((float) $totalTopup, 2) }}
            </td>

            <td class="text-center">
              <div class="dropdown">
                <button
                  type="button"
                  class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
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
            <td colspan="12" class="text-center py-5">
              <i
                class="icon-base ti tabler-users-off text-muted mb-2"
                style="font-size: 48px;"
              ></i>

              <h6 class="mt-2 mb-1">ยังไม่มีข้อมูลลูกค้า</h6>

              <p class="text-muted mb-3">
                เพิ่มสมาชิก หรือรอข้อมูลลูกค้าจากหน้าตู้
              </p>

              <a href="{{ route('customers.create') }}" class="btn btn-primary">
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
      {{ $customers->withQueryString()->links() }}
    </div>
  @endif

</div>

<div
  class="modal fade"
  id="importCustomerModal"
  tabindex="-1"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form
        action="{{ route('customers.import') }}"
        method="POST"
        enctype="multipart/form-data"
      >
        @csrf

        <div class="modal-header">
          <div>
            <h5 class="modal-title mb-1">
              Import สมาชิกผ่าน Excel
            </h5>
            <p class="text-muted small mb-0">
              เพิ่มสมาชิกหลายรายการจากไฟล์ Excel หรือ CSV
            </p>
          </div>

          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>

        <div class="modal-body">
          <div class="alert alert-info">
            <div class="fw-semibold mb-1">
              เงื่อนไขการ Import
            </div>

            <ul class="mb-0 ps-3">
              <li>รองรับไฟล์ .xlsx, .xls และ .csv</li>
              <li>ข้อมูลสมาชิกซ้ำจะถูกข้ามอัตโนมัติ</li>
              <li>ไม่สามารถกำหนดหรือแก้ไขแต้มผ่านไฟล์ Import</li>
              <li>สมาชิกใหม่จะได้รับแต้มเริ่มต้นจากระบบอัตโนมัติ</li>
            </ul>
          </div>

          <div class="mb-3">
            <label class="form-label">
              ไฟล์ Import
              <span class="text-danger">*</span>
            </label>

            <input
              type="file"
              name="import_file"
              class="form-control @error('import_file') is-invalid @enderror"
              accept=".xlsx,.xls,.csv"
              required
            >

            @error('import_file')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="d-flex align-items-start gap-2">
            <i class="icon-base ti tabler-info-circle text-primary mt-1"></i>

            <div class="small text-muted">
              แนะนำให้ดาวน์โหลด Template ก่อนกรอกข้อมูล
              เพื่อให้ชื่อคอลัมน์ตรงกับระบบ
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-label-secondary"
            data-bs-dismiss="modal"
          >
            ยกเลิก
          </button>

          <button
            type="submit"
            class="btn btn-primary"
          >
            <i class="icon-base ti tabler-upload me-1"></i>
            เริ่ม Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@if ($errors->has('import_file'))
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modalElement = document.getElementById('importCustomerModal');

      if (!modalElement) {
        return;
      }

      const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
      modal.show();
    });
  </script>
@endif

@endsection
