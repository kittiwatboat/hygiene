@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดสมาชิก')

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
  <div>
    <h4 class="mb-1">
      รายละเอียดสมาชิก
    </h4>

    <div class="text-muted">
      {{ $customer->member_code }}
    </div>
  </div>

  <div class="d-flex gap-2">
    <a
      href="{{ route('customers.index') }}"
      class="btn btn-label-secondary"
    >
      กลับ
    </a>

    <a
      href="{{ route('customers.edit', $customer) }}"
      class="btn btn-primary"
    >
      <i class="icon-base ti tabler-pencil me-1"></i>
      แก้ไข
    </a>
  </div>
</div>

@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
@endif

<div class="row g-4">

  <div class="col-xl-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="text-muted mb-1">
          แต้มคงเหลือ
        </div>

        <h2 class="mb-2 text-primary">
          {{ number_format((int) ($customer->points_balance ?? 0)) }}
        </h2>

        <div class="d-flex align-items-center gap-2 flex-wrap">
          <span class="badge {{ $customer->status_class }}">
            {{ $customer->status_text }}
          </span>

          <span class="badge bg-label-info">
            {{ \App\Models\Customer::MEMBER_TYPE_OPTIONS[$customer->member_type] ?? $customer->member_type }}
          </span>
        </div>

        <div class="alert alert-info mt-4 mb-0">
          <div class="d-flex gap-2">
            <i class="icon-base ti tabler-info-circle mt-1"></i>

            <div>
              <div class="fw-semibold mb-1">
                แต้มสะสม
              </div>

              <div class="small">
                แต้มเป็นข้อมูลจากระบบ และไม่สามารถเพิ่ม ลด
                หรือแก้ไขจากหน้าจัดการสมาชิกได้
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-8">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0">
          ข้อมูลสมาชิก
        </h5>
      </div>

      <div class="card-body">
        <div class="row g-3">

          <div class="col-md-6">
            <small class="text-muted d-block">
              รหัสสมาชิก
            </small>

            <span>
              {{ $customer->member_code ?: '-' }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              ชื่อสมาชิก
            </small>

            <span>
              {{ $customer->name ?: '-' }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              เบอร์โทรศัพท์
            </small>

            <span>
              {{ $customer->phone ?: '-' }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              อีเมล
            </small>

            <span>
              {{ $customer->email ?: '-' }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              LINE ID
            </small>

            <span>
              {{ $customer->line_id ?: '-' }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              สาขาตู้
            </small>

            <span>
              {{ optional($customer->branch)->name
                  ?? $customer->branch_name
                  ?? '-' }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              วันที่สมัคร
            </small>

            <span>
              {{ $customer->registered_at
                  ? $customer->registered_at->format('d/m/Y H:i')
                  : ($customer->created_at
                      ? $customer->created_at->format('d/m/Y H:i')
                      : '-') }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              ใช้งานล่าสุด
            </small>

            <span>
              {{ $customer->last_used_at
                  ? $customer->last_used_at->format('d/m/Y H:i')
                  : '-' }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              ยอดเติมสะสม
            </small>

            <span>
              {{ number_format((float) ($customer->total_topup ?? 0), 2) }}
            </span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">
              สถานะสมาชิกใหม่
            </small>

            <span>
              {{ $customer->is_new_member_discount_used
                  ? 'ใช้สิทธิ์สมาชิกใหม่แล้ว'
                  : 'ยังไม่ใช้สิทธิ์สมาชิกใหม่' }}
            </span>
          </div>

          <div class="col-12">
            <small class="text-muted d-block">
              หมายเหตุ
            </small>

            <span>
              {{ $customer->remark ?: '-' }}
            </span>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between gap-3">
        <div>
          <h5 class="mb-1">
            ประวัติแต้ม
          </h5>

          <p class="text-muted mb-0">
            แสดงรายการแต้มที่เกิดจากการใช้งานและโปรโมชั่นของระบบ
          </p>
        </div>

        <span class="badge bg-label-secondary">
          {{ number_format($customer->pointTransactions->count()) }} รายการ
        </span>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="min-width: 150px;">วันที่</th>
              <th style="min-width: 120px;">ประเภท</th>
              <th style="min-width: 220px;">รายละเอียด</th>
              <th class="text-end" style="min-width: 100px;">แต้ม</th>
              <th class="text-end" style="min-width: 110px;">คงเหลือ</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($customer->pointTransactions as $transaction)
              <tr>
                <td>
                  {{ $transaction->created_at
                      ? $transaction->created_at->format('d/m/Y H:i')
                      : '-' }}
                </td>

                <td>
                  <span class="badge {{ $transaction->type_class }}">
                    {{ $transaction->type_text }}
                  </span>
                </td>

                <td>
                  <div>
                    {{ $transaction->description ?: '-' }}
                  </div>

                  @if ($transaction->reference_no)
                    <small class="text-muted">
                      {{ $transaction->reference_no }}
                    </small>
                  @endif
                </td>

                <td class="text-end">
                  <span
                    class="{{ $transaction->points >= 0
                        ? 'text-success'
                        : 'text-danger' }} fw-medium"
                  >
                    {{ $transaction->points >= 0 ? '+' : '' }}
                    {{ number_format((int) $transaction->points) }}
                  </span>
                </td>

                <td class="text-end fw-medium">
                  {{ number_format((int) $transaction->balance_after) }}
                </td>
              </tr>
            @empty
              <tr>
                <td
                  colspan="5"
                  class="text-center py-5 text-muted"
                >
                  <i
                    class="icon-base ti tabler-history-off mb-2"
                    style="font-size: 42px;"
                  ></i>

                  <div>
                    ยังไม่มีประวัติแต้ม
                  </div>
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
