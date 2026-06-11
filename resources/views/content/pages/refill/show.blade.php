@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดการเติมน้ำยา')

@section('content')
  <div class="row g-4">

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียดการเติมน้ำยา</h5>
            <p class="text-muted mb-0">
              {{ $refill->machine?->code ?: '-' }} - {{ $refill->machine?->name ?: '-' }}
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('refills.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>

            <a href="{{ route('stock.show', $refill->machine_tank_id) }}" class="btn btn-label-primary">
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
        <h5 class="card-header">ข้อมูลการเติมน้ำยา</h5>

        <div class="card-body">
          <div class="row g-4">

            <div class="col-md-6">
              <small class="text-muted">วันที่เติม</small>
              <div class="fw-medium">
                {{ optional($refill->refill_at)->format('d/m/Y H:i') ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ผู้บันทึก</small>
              <div class="fw-medium">
                {{ $refill->refillBy?->full_name ?: $refill->refillBy?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ตู้</small>
              <div class="fw-medium">
                {{ $refill->machine?->code ?: '-' }} - {{ $refill->machine?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานที่</small>
              <div class="fw-medium">
                {{ $refill->machine?->location?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ช่องน้ำยา</small>
              <div class="fw-medium">
                ช่อง {{ $refill->tank?->tank_no ?: '-' }}:
                {{ $refill->tank?->tank_name ?: '-' }}
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สินค้า / น้ำยา</small>
              <div class="fw-medium">
                {{ $refill->product?->name ?: $refill->tank?->product?->name ?: '-' }}
              </div>
            </div>

            <div class="col-md-4">
              <small class="text-muted">ก่อนเติม</small>
              <h5 class="mb-0">
                {{ number_format((float) $refill->before_liters, 2) }} L
              </h5>
            </div>

            <div class="col-md-4">
              <small class="text-muted">จำนวนที่เติม</small>
              <h5 class="mb-0 text-primary">
                +{{ number_format((float) $refill->refill_liters, 2) }} L
              </h5>
            </div>

            <div class="col-md-4">
              <small class="text-muted">หลังเติม</small>
              <h5 class="mb-0">
                {{ number_format((float) $refill->after_liters, 2) }} L
              </h5>
            </div>

            <div class="col-12">
              <small class="text-muted">หมายเหตุ</small>
              <div class="fw-medium">
                {{ $refill->remark ?: '-' }}
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
              {{ number_format((float) $refill->tank?->remaining_liters, 2) }} L
            </h4>
          </div>

          <div class="mb-3">
            <small class="text-muted">ความจุถัง</small>
            <div class="fw-medium">
              {{ number_format((float) $refill->tank?->capacity_liters, 2) }} L
            </div>
          </div>

          <div class="mb-0">
            <small class="text-muted">แจ้งเตือนเมื่อต่ำกว่า</small>
            <div class="fw-medium">
              {{ number_format((float) $refill->tank?->low_stock_liters, 2) }} L
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
