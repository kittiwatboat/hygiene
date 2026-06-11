@extends('layouts/layoutMaster')

@section('title', 'รายการขาย')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])

  <style>
    .sale-alert {
      margin: 0 1.5rem 1rem 1.5rem;
      padding: 0.65rem 0.85rem;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .sale-alert-content {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      line-height: 1.3;
      font-weight: 500;
    }

    .sale-alert-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1rem;
      line-height: 1;
      padding: 0.25rem;
      cursor: pointer;
      opacity: 0.7;
    }

    .sale-alert-close:hover {
      opacity: 1;
    }
  </style>
@endsection

@section('content')
  @php
    $totalSales = $sales->count();
    $paidSales = $sales->where('payment_status', 'paid');
    $totalAmount = $paidSales->sum('amount');
    $totalPress = $paidSales->sum('press_count');
    $totalVolume = $paidSales->sum('volume_liters');
  @endphp

  <div class="row g-4">

    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายการขาย</h5>
            <p class="mb-0 text-muted">
              ตรวจสอบรายการขายจากตู้ และยอดการกดน้ำยาแต่ละช่อง
            </p>
          </div>

          <div>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
              <i class="icon-base ti tabler-plus me-1"></i>
              เพิ่มรายการขาย
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success sale-alert" role="alert">
            <div class="sale-alert-content">
              <i class="icon-base ti tabler-circle-check"></i>
              <span>{{ session('success') }}</span>
            </div>

            <button
              type="button"
              class="sale-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger sale-alert" role="alert">
            <div class="sale-alert-content">
              <i class="icon-base ti tabler-alert-circle"></i>
              <span>{{ $errors->first() }}</span>
            </div>

            <button
              type="button"
              class="sale-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="icon-base ti tabler-receipt"></i>
            </span>
          </div>
          <div>
            <h5 class="mb-0">{{ number_format($totalSales) }}</h5>
            <small class="text-muted">รายการทั้งหมด</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="icon-base ti tabler-cash"></i>
            </span>
          </div>
          <div>
            <h5 class="mb-0">{{ number_format($totalAmount, 2) }}</h5>
            <small class="text-muted">ยอดขาย / บาท</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-info">
              <i class="icon-base ti tabler-hand-click"></i>
            </span>
          </div>
          <div>
            <h5 class="mb-0">{{ number_format($totalPress) }}</h5>
            <small class="text-muted">จำนวนการกด</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="icon-base ti tabler-droplet"></i>
            </span>
          </div>
          <div>
            <h5 class="mb-0">{{ number_format($totalVolume, 3) }}</h5>
            <small class="text-muted">น้ำยาที่ขาย / ลิตร</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">

        <div class="card-body border-bottom">
          <form method="GET" action="{{ route('sales.index') }}">
            <div class="row g-3 align-items-end">
              <div class="col-md-3">
                <label class="form-label">ค้นหา</label>
                <input
                  type="text"
                  name="keyword"
                  value="{{ request('keyword') }}"
                  class="form-control"
                  placeholder="ตู้ / น้ำยา / ref"
                >
              </div>

              <div class="col-md-3">
                <label class="form-label">ตู้</label>
                <select name="machine_id" class="form-select">
                  <option value="">ทั้งหมด</option>
                  @foreach ($machines as $machine)
                    <option
                      value="{{ $machine->id }}"
                      {{ (string) request('machine_id') === (string) $machine->id ? 'selected' : '' }}
                    >
                      {{ $machine->code }} - {{ $machine->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-2">
                <label class="form-label">ช่องทาง</label>
                <select name="payment_method" class="form-select">
                  <option value="">ทั้งหมด</option>
                  <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>เงินสด</option>
                  <option value="qr" {{ request('payment_method') === 'qr' ? 'selected' : '' }}>QR</option>
                  <option value="true_money" {{ request('payment_method') === 'true_money' ? 'selected' : '' }}>TrueMoney</option>
                  <option value="shopee_pay" {{ request('payment_method') === 'shopee_pay' ? 'selected' : '' }}>ShopeePay</option>
                  <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>บัตรเครดิต</option>
                  <option value="free" {{ request('payment_method') === 'free' ? 'selected' : '' }}>ฟรี / ทดสอบ</option>
                </select>
              </div>

              <div class="col-md-2">
                <label class="form-label">สถานะ</label>
                <select name="payment_status" class="form-select">
                  <option value="">ทั้งหมด</option>
                  <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>ชำระแล้ว</option>
                  <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>รอชำระ</option>
                  <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>ไม่สำเร็จ</option>
                  <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>คืนเงินแล้ว</option>
                </select>
              </div>

              <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                  <i class="icon-base ti tabler-search me-1"></i>
                  ค้นหา
                </button>
              </div>

              <div class="col-md-3">
                <label class="form-label">วันที่เริ่ม</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
              </div>

              <div class="col-md-3">
                <label class="form-label">วันที่สิ้นสุด</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
              </div>
            </div>
          </form>
        </div>

        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th style="width: 70px;">#</th>
                <th>วันที่ขาย</th>
                <th>ตู้ / ช่อง</th>
                <th>น้ำยา</th>
                <th>จำนวนกด</th>
                <th>ปริมาณ</th>
                <th>ยอดเงิน</th>
                <th>ชำระเงิน</th>
                <th style="width: 110px;">Actions</th>
              </tr>
            </thead>

            <tbody class="table-border-bottom-0">
              @forelse ($sales as $index => $sale)
                <tr>
                  <td>{{ $index + 1 }}</td>

                  <td>
                    <div class="fw-medium">
                      {{ optional($sale->sold_at)->format('d/m/Y') ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      {{ optional($sale->sold_at)->format('H:i') ?: '' }}
                    </div>
                  </td>

                  <td>
                    <div class="fw-medium">
                      {{ $sale->machine?->code ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      {{ $sale->machine?->name ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      ช่อง {{ $sale->tank?->tank_no ?: '-' }}:
                      {{ $sale->tank?->tank_name ?: '-' }}
                    </div>
                  </td>

                  <td>
                    <div class="fw-medium">
                      {{ $sale->product?->name ?: $sale->tank?->product?->name ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      {{ $sale->product?->code ?: $sale->tank?->product?->code ?: '' }}
                    </div>
                  </td>

                  <td>
                    {{ number_format((int) $sale->press_count) }} ครั้ง
                  </td>

                  <td>
                    {{ number_format((float) $sale->volume_liters, 3) }} L
                    <div class="text-muted small">
                      {{ number_format((float) $sale->volume_per_press_ml, 2) }} ml/ครั้ง
                    </div>
                  </td>

                  <td>
                    <span class="fw-medium">
                      {{ number_format((float) $sale->amount, 2) }}
                    </span>
                    <span class="text-muted">บาท</span>
                  </td>

                  <td>
                    <div class="mb-1">
                      {{ $sale->payment_method_text }}
                    </div>
                    <span class="badge {{ $sale->payment_status_badge_class }}">
                      {{ $sale->payment_status_text }}
                    </span>
                  </td>

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                      </button>

                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('sales.show', $sale) }}">
                          <i class="icon-base ti tabler-eye me-1"></i>
                          ดูรายละเอียด
                        </a>

                        <form
                          action="{{ route('sales.destroy', $sale) }}"
                          method="POST"
                          class="sale-delete-form">
                          @csrf
                          @method('DELETE')

                          <button type="submit" class="dropdown-item text-danger">
                            <i class="icon-base ti tabler-trash me-1"></i>
                            ลบ / คืน Stock
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center py-5">
                    <i class="icon-base ti tabler-receipt-off mb-2" style="font-size: 42px;"></i>
                    <h6 class="mb-1">ยังไม่มีรายการขาย</h6>
                    <p class="text-muted mb-3">กดปุ่มเพิ่มรายการขายเพื่อเริ่มบันทึกยอด</p>

                    <a href="{{ route('sales.create') }}" class="btn btn-primary">
                      <i class="icon-base ti tabler-plus me-1"></i>
                      เพิ่มรายการขาย
                    </a>
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

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const deleteForms = document.querySelectorAll('.sale-delete-form');

      deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
          const confirmed = confirm('ยืนยันการลบรายการขายนี้? ระบบจะคืน Stock น้ำยากลับ');

          if (!confirmed) {
            event.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
