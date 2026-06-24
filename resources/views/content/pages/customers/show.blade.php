@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดสมาชิก')

@section('content')
<div class="row g-4">

  <div class="col-12">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
      <div>
        <h4 class="mb-1">{{ $customer->name }}</h4>
        <p class="text-muted mb-0">
          รหัสสมาชิก {{ $customer->member_code }}
        </p>
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
  </div>

  @if (session('success'))
    <div class="col-12">
      <div class="alert alert-success mb-0">
        {{ session('success') }}
      </div>
    </div>
  @endif

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body text-center">
        <div class="avatar avatar-xl mx-auto mb-3">
          <span class="avatar-initial rounded-circle bg-label-primary">
            <i class="icon-base ti tabler-coins"></i>
          </span>
        </div>

        <div class="text-muted mb-1">แต้มคงเหลือ</div>

        <h2 class="mb-2 text-primary">
          {{ number_format((int) $customer->points_balance) }}
        </h2>

        <span class="badge {{ $customer->status_class }}">
          {{ $customer->status_text }}
        </span>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0">ข้อมูลสมาชิก</h5>
      </div>

      <div class="card-body">
        <div class="row g-3">

          <div class="col-md-6">
            <small class="text-muted d-block">ชื่อสมาชิก</small>
            <span>{{ $customer->name }}</span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">เบอร์โทรศัพท์</small>
            <span>{{ $customer->phone ?: '-' }}</span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">อีเมล</small>
            <span>{{ $customer->email ?: '-' }}</span>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">ใช้งานล่าสุด</small>
            <span>
              {{ $customer->last_used_at
                  ? $customer->last_used_at->format('d/m/Y H:i')
                  : '-' }}
            </span>
          </div>

          <div class="col-12">
            <small class="text-muted d-block">หมายเหตุ</small>
            <span>{{ $customer->remark ?: '-' }}</span>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-1">ปรับแต้มสมาชิก</h5>
        <p class="text-muted mb-0">
          ใช้สำหรับกรณีแอดมินเพิ่มหรือลดแต้ม
        </p>
      </div>

      <div class="card-body">
        <form
          action="{{ route('customers.adjust-points', $customer) }}"
          method="POST"
        >
          @csrf

          <div class="mb-3">
            <label class="form-label">ประเภทการปรับแต้ม</label>

            <select
              name="adjustment_type"
              class="form-select @error('adjustment_type') is-invalid @enderror"
              required
            >
              <option value="add">เพิ่มแต้ม</option>
              <option value="deduct">ลดแต้ม</option>
            </select>

            @error('adjustment_type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">จำนวนแต้ม</label>

            <input
              type="number"
              name="points"
              min="1"
              value="{{ old('points') }}"
              class="form-control @error('points') is-invalid @enderror"
              required
            >

            @error('points')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">เหตุผล</label>

            <textarea
              name="description"
              rows="3"
              class="form-control @error('description') is-invalid @enderror"
              required
            >{{ old('description') }}</textarea>

            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100">
            บันทึกการปรับแต้ม
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">ประวัติแต้ม</h5>
      </div>

      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>วันที่</th>
              <th>ประเภท</th>
              <th>รายละเอียด</th>
              <th class="text-end">แต้ม</th>
              <th class="text-end">คงเหลือ</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($customer->pointTransactions as $transaction)
              <tr>
                <td>
                  {{ $transaction->created_at->format('d/m/Y H:i') }}
                </td>

                <td>
                  <span class="badge {{ $transaction->type_class }}">
                    {{ $transaction->type_text }}
                  </span>
                </td>

                <td>
                  <div>{{ $transaction->description ?: '-' }}</div>

                  @if ($transaction->reference_no)
                    <small class="text-muted">
                      {{ $transaction->reference_no }}
                    </small>
                  @endif
                </td>

                <td class="text-end">
                  <span class="{{ $transaction->points >= 0
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
                <td colspan="5" class="text-center py-4 text-muted">
                  ยังไม่มีประวัติแต้ม
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
