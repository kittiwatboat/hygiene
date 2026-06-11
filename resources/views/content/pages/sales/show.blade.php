@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดรายการขาย')

@section('content')
  <div class="row g-4">

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียดรายการขาย</h5>
            <p class="text-muted mb-0">
              {{ $sale->machine?->code ?: '-' }} - {{ $sale->machine?->name ?: '-' }}
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('sales.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>

            <a href="{{ route('stock.show', $sale->machine_tank_id) }}" class="btn btn-label-primary">
              <i class="icon-base ti tabler-bottle me-1"></i>
              ดู Stock
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
        <h5 class="card-header">ข้อมูลรายการขาย</h5>

        <div class="card-body">
          <div class="row g-4">

            <div class="col-md-6">
              <small class="text-muted">วันที่ขาย</small>
              <div class="fw-medium">
                {{ optional($sale->sold_at)->format('d/m/Y H:i') ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ผู้บันทึก</small>
              <div class="fw-medium">
                {{ $sale->createdBy?->full_name ?: $sale->createdBy?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ตู้</small>
              <div class="fw-medium">
                {{ $sale->machine?->code ?: '-' }} - {{ $sale->machine?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานที่</small>
              <div class="fw-medium">
                {{ $sale->machine?->location?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ช่องน้ำยา</small>
              <div class="fw-medium">
                ช่อง {{ $sale->tank?->tank_no ?: '-' }}:
                {{ $sale->tank?->tank_name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สินค้า / น้ำยา</small>
              <div class="fw-medium">
                {{ $sale->product?->name ?: $sale->tank?->product?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-4">
              <small class="text-muted">จำนวนครั้งที่กด</small>
              <h5 class="mb-0">
                {{ number_format((int) $sale->press_count) }} ครั้ง
              </h5>
            </div>

            <div class="col-md-4">
              <small class="text-muted">ปริมาณที่ขาย</small>
              <h5 class="mb-0">
                {{ number_format((float) $sale->volume_liters, 3) }} L
              </h5>
            </div>

            <div class="col-md-4">
              <small class="text-muted">ยอดเงิน</small>
              <h5 class="mb-0 text-primary">
                {{ number_format((float) $sale->amount, 2) }} บาท
              </h5>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ปริมาณต่อครั้ง</small>
              <div class="fw-medium">
                {{ number_format((float) $sale->volume_per_press_ml, 2) }} ml/ครั้ง
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ราคาต่อครั้ง</small>
              <div class="fw-medium">
                {{ number_format((float) $sale->price_per_press, 2) }} บาท/ครั้ง
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ช่องทางชำระเงิน</small>
              <div class="fw-medium">
                {{ $sale->payment_method_text }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานะชำระเงิน</small>
              <div>
                <span class="badge {{ $sale->payment_status_badge_class }}">
                  {{ $sale->payment_status_text }}
                </span>
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">เลขอ้างอิงการชำระเงิน</small>
              <div class="fw-medium">
                {{ $sale->transaction_ref ?: '-' }}
              </div>
            </div>

            <div class="col-12">
              <small class="text-muted">หมายเหตุ</small>
              <div class="fw-medium">
                {{ $sale->remark ?: '-' }}
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card">
        <h5 class="card-header">Stock ปัจจุบัน</h5>

        <div class="card-body">
          <div class="mb-3">
            <small class="text-muted">คงเหลือปัจจุบัน</small>
            <h4 class="mb-0">
              {{ number_format((float) $sale->tank?->remaining_liters, 2) }} L
            </h4>
          </div>

          <div class="mb-3">
            <small class="text-muted">ความจุถัง</small>
            <div class="fw-medium">
              {{ number_format((float) $sale->tank?->capacity_liters, 2) }} L
            </div>
          </div>

          <div class="mb-0">
            <small class="text-muted">แจ้งเตือนเมื่อต่ำกว่า</small>
            <div class="fw-medium">
              {{ number_format((float) $sale->tank?->low_stock_liters, 2) }} L
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
