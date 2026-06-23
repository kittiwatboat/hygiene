@extends('layouts/layoutMaster')

@section('title', 'จัดการโปรโมชัน')

@section('page-style')
<style>
  .promotion-alert {
    margin: 0 1.5rem 1rem;
    padding: .75rem 1rem;
    border-radius: .5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
  }

  .promotion-image {
    width: 110px;
    height: 76px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid rgba(67, 89, 113, .12);
    background: #f5f5f7;
  }

  .promotion-image-empty {
    width: 110px;
    height: 76px;
    border-radius: 10px;
    background: rgba(75, 70, 92, .08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a8aaae;
  }

  .promotion-title {
    min-width: 220px;
    white-space: normal;
  }

  .promotion-description {
    max-width: 320px;
    white-space: normal;
  }

  .promotion-table td {
    vertical-align: middle;
  }

  .promotion-progress {
    height: 7px;
    border-radius: 999px;
  }
</style>
@endsection

@section('content')
@php
  $totalPromotions = $promotions->count();

  $activePromotions = $promotions->filter(function ($promotion) {
      return $promotion->display_status_text === 'กำลังใช้งาน';
  })->count();

  $waitingPromotions = $promotions->filter(function ($promotion) {
      return $promotion->display_status_text === 'รอเริ่ม';
  })->count();

  $inactivePromotions = $promotions->filter(function ($promotion) {
      return in_array($promotion->display_status_text, [
          'ปิดใช้งาน',
          'หมดอายุ',
          'ครบจำนวนแล้ว',
      ]);
  })->count();
@endphp

<div class="row g-4">

  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
          <h5 class="mb-1">จัดการโปรโมชัน</h5>
          <p class="mb-0 text-muted">
            จัดการโปรโมชัน ส่วนลด การใช้แต้ม และเงื่อนไขการใช้งาน
          </p>
        </div>

        <a href="{{ route('promotions.create') }}" class="btn btn-primary">
          <i class="icon-base ti tabler-plus me-1"></i>
          เพิ่มโปรโมชัน
        </a>
      </div>

      @if (session('success'))
        <div class="alert alert-success promotion-alert" role="alert">
          <div class="d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-circle-check"></i>
            <span>{{ session('success') }}</span>
          </div>

          <button
            type="button"
            class="btn-close"
            onclick="this.closest('.alert').remove()"
            aria-label="Close">
          </button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger promotion-alert" role="alert">
          <div class="d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-alert-circle"></i>
            <span>{{ session('error') }}</span>
          </div>

          <button
            type="button"
            class="btn-close"
            onclick="this.closest('.alert').remove()"
            aria-label="Close">
          </button>
        </div>
      @endif
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar">
          <span class="avatar-initial rounded bg-label-primary">
            <i class="icon-base ti tabler-discount"></i>
          </span>
        </div>

        <div>
          <h5 class="mb-0">{{ number_format($totalPromotions) }}</h5>
          <small class="text-muted">โปรโมชันทั้งหมด</small>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar">
          <span class="avatar-initial rounded bg-label-success">
            <i class="icon-base ti tabler-circle-check"></i>
          </span>
        </div>

        <div>
          <h5 class="mb-0">{{ number_format($activePromotions) }}</h5>
          <small class="text-muted">กำลังใช้งาน</small>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar">
          <span class="avatar-initial rounded bg-label-info">
            <i class="icon-base ti tabler-clock"></i>
          </span>
        </div>

        <div>
          <h5 class="mb-0">{{ number_format($waitingPromotions) }}</h5>
          <small class="text-muted">รอเริ่ม</small>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar">
          <span class="avatar-initial rounded bg-label-secondary">
            <i class="icon-base ti tabler-circle-x"></i>
          </span>
        </div>

        <div>
          <h5 class="mb-0">{{ number_format($inactivePromotions) }}</h5>
          <small class="text-muted">ปิด/หมดอายุ</small>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">

      <div class="card-body border-bottom">
        <form method="GET" action="{{ route('promotions.index') }}">
          <div class="row g-3 align-items-end">

            <div class="col-md-5">
              <label class="form-label">ค้นหา</label>

              <input
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                class="form-control"
                placeholder="ค้นหาชื่อหรือรหัสโปรโมชัน"
              >
            </div>

            <div class="col-md-3">
              <label class="form-label">ประเภทโปรโมชัน</label>

              <select name="promotion_type" class="form-select">
                <option value="">ทั้งหมด</option>

                <option
                  value="earn_points"
                  {{ request('promotion_type') === 'earn_points' ? 'selected' : '' }}
                >
                  ซื้อแล้วได้รับแต้ม
                </option>

                <option
                  value="redeem_discount"
                  {{ request('promotion_type') === 'redeem_discount' ? 'selected' : '' }}
                >
                  ใช้แต้มแลกส่วนลด
                </option>

                <option
                  value="direct_discount"
                  {{ request('promotion_type') === 'direct_discount' ? 'selected' : '' }}
                >
                  ส่วนลดทันที
                </option>
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">สถานะเปิดใช้งาน</label>

              <select name="is_active" class="form-select">
                <option value="">ทั้งหมด</option>

                <option
                  value="1"
                  {{ request('is_active') === '1' ? 'selected' : '' }}
                >
                  เปิดใช้งาน
                </option>

                <option
                  value="0"
                  {{ request('is_active') === '0' ? 'selected' : '' }}
                >
                  ปิดใช้งาน
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
        <table class="table table-hover promotion-table">
          <thead class="table-light">
            <tr>
              <th style="width: 60px;">#</th>
              <th style="width: 140px;">รูป</th>
              <th>โปรโมชัน</th>
              <th>ประเภท / เงื่อนไข</th>
              <th>สินค้า</th>
              <th>จำนวนสิทธิ์</th>
              <th>ช่วงเวลา</th>
              <th>สถานะ</th>
              <th style="width: 100px;" class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($promotions as $index => $promotion)
              @php
                $usagePercent = 0;

                if (
                    $promotion->usage_limit !== null &&
                    (int) $promotion->usage_limit > 0
                ) {
                    $usagePercent = min(
                        100,
                        ((int) $promotion->used_count / (int) $promotion->usage_limit) * 100
                    );
                }
              @endphp

              <tr>
                <td>
                  {{ $index + 1 }}
                </td>

                <td>
                  @if ($promotion->image)
                    <a
                      href="{{ asset('assets/img/promotions/' . $promotion->image) }}"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      <img
                        src="{{ asset('assets/img/promotions/' . $promotion->image) }}"
                        alt="{{ $promotion->name }}"
                        class="promotion-image"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');"
                      >

                      <div class="promotion-image-empty d-none">
                        <div class="text-center">
                          <i class="icon-base ti tabler-photo-off d-block mb-1"></i>
                          <small>ไม่พบรูป</small>
                        </div>
                      </div>
                    </a>
                  @else
                    <div class="promotion-image-empty">
                      <div class="text-center">
                        <i class="icon-base ti tabler-photo-off d-block mb-1"></i>
                        <small>ไม่มีรูป</small>
                      </div>
                    </div>
                  @endif
                </td>

                <td>
                  <div class="promotion-title">
                    <div class="fw-medium mb-1">
                      {{ $promotion->name }}
                    </div>

                    <div class="text-muted small mb-1">
                      รหัส: {{ $promotion->code ?: '-' }}
                    </div>

                    @if ($promotion->description)
                      <div class="promotion-description text-muted small">
                        {{ \Illuminate\Support\Str::limit(
                            $promotion->description,
                            90
                        ) }}
                      </div>
                    @endif
                  </div>
                </td>

                <td>
                  <div class="mb-2">
                    <span class="badge bg-label-primary">
                      {{ $promotion->promotion_type_text }}
                    </span>
                  </div>

                  @if ($promotion->promotion_type === 'earn_points')
                    <div class="small">
                      รับ
                      <span class="fw-medium text-success">
                        {{ number_format((int) $promotion->points_reward) }} แต้ม
                      </span>
                    </div>

                  @elseif ($promotion->promotion_type === 'redeem_discount')
                    <div class="small mb-1">
                      ใช้
                      <span class="fw-medium">
                        {{ number_format((int) $promotion->points_required) }} แต้ม
                      </span>
                    </div>

                    <div class="small">
                      @if ($promotion->discount_type === 'percent')
                        ลด
                        <span class="fw-medium text-danger">
                          {{ number_format((float) $promotion->discount_value, 2) }}%
                        </span>
                      @else
                        ลด
                        <span class="fw-medium text-danger">
                          {{ number_format((float) $promotion->discount_value, 2) }} บาท
                        </span>
                      @endif
                    </div>

                  @elseif ($promotion->promotion_type === 'direct_discount')
                    <div class="small">
                      @if ($promotion->discount_type === 'percent')
                        ลดทันที
                        <span class="fw-medium text-danger">
                          {{ number_format((float) $promotion->discount_value, 2) }}%
                        </span>
                      @else
                        ลดทันที
                        <span class="fw-medium text-danger">
                          {{ number_format((float) $promotion->discount_value, 2) }} บาท
                        </span>
                      @endif
                    </div>
                  @endif

                  @if ((float) $promotion->minimum_amount > 0)
                    <div class="text-muted small mt-1">
                      ขั้นต่ำ {{ number_format((float) $promotion->minimum_amount, 2) }} บาท
                    </div>
                  @endif

                  @if (
                    $promotion->discount_type === 'percent' &&
                    $promotion->max_discount !== null
                  )
                    <div class="text-muted small">
                      ลดสูงสุด {{ number_format((float) $promotion->max_discount, 2) }} บาท
                    </div>
                  @endif
                </td>

                <td>
                  @if ($promotion->scope === 'all')
                    <span class="badge bg-label-info">
                      สินค้าทั้งหมด
                    </span>
                  @else
                    @if ($promotion->product)
                      <div class="fw-medium">
                        {{ $promotion->product->name }}
                      </div>

                      <div class="text-muted small">
                        {{ $promotion->product->code ?: '-' }}
                      </div>
                    @else
                      <span class="text-muted">
                        ไม่พบสินค้า
                      </span>
                    @endif
                  @endif
                </td>

                <td style="min-width: 150px;">
                  @if ($promotion->usage_limit === null)
                    <span class="badge bg-label-success">
                      ไม่จำกัด
                    </span>

                    <div class="text-muted small mt-1">
                      ใช้แล้ว {{ number_format((int) $promotion->used_count) }} ครั้ง
                    </div>
                  @else
                    <div class="d-flex justify-content-between small mb-1">
                      <span>
                        {{ number_format((int) $promotion->used_count) }}
                        /
                        {{ number_format((int) $promotion->usage_limit) }}
                      </span>

                      <span>
                        {{ number_format($usagePercent, 0) }}%
                      </span>
                    </div>

                    <div class="progress promotion-progress">
                      <div
                        class="progress-bar {{ $usagePercent >= 100 ? 'bg-danger' : 'bg-primary' }}"
                        role="progressbar"
                        style="width: {{ $usagePercent }}%;"
                        aria-valuenow="{{ $usagePercent }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                      </div>
                    </div>
                  @endif
                </td>

                <td style="min-width: 185px;">
                  <div class="small mb-1">
                    <span class="text-muted">เริ่ม:</span>

                    <span class="fw-medium">
                      {{ $promotion->start_at
                          ? $promotion->start_at->format('d/m/Y H:i')
                          : 'แสดงทันที' }}
                    </span>
                  </div>

                  <div class="small">
                    <span class="text-muted">สิ้นสุด:</span>

                    <span class="fw-medium">
                      {{ $promotion->end_at
                          ? $promotion->end_at->format('d/m/Y H:i')
                          : 'ไม่กำหนด' }}
                    </span>
                  </div>
                </td>

                <td>
                  <div class="mb-1">
                    <span class="badge {{ $promotion->display_status_class }}">
                      {{ $promotion->display_status_text }}
                    </span>
                  </div>

                  <div class="text-muted small">
                    ลำดับ {{ number_format((int) $promotion->sort_order) }}
                  </div>
                </td>

                <td class="text-center">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      <i class="icon-base ti tabler-dots-vertical"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end">
                      @if ($promotion->image)
                        <a
                          class="dropdown-item"
                          href="{{ asset('assets/img/promotions/' . $promotion->image) }}"
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <i class="icon-base ti tabler-eye me-2"></i>
                          ดูรูปเต็ม
                        </a>
                      @endif

                      <a
                        class="dropdown-item"
                        href="{{ route('promotions.edit', $promotion) }}"
                      >
                        <i class="icon-base ti tabler-pencil me-2"></i>
                        แก้ไข
                      </a>

                      <div class="dropdown-divider"></div>

                      <form
                        action="{{ route('promotions.destroy', $promotion) }}"
                        method="POST"
                        class="promotion-delete-form"
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
                <td colspan="9" class="text-center py-5">
                  <div class="mb-3">
                    <i
                      class="icon-base ti tabler-discount-off text-muted"
                      style="font-size: 48px;"
                    ></i>
                  </div>

                  <h6 class="mb-1">ยังไม่มีโปรโมชัน</h6>

                  <p class="text-muted mb-3">
                    เพิ่มโปรโมชันเพื่อกำหนดแต้มสะสมหรือส่วนลดให้ลูกค้า
                  </p>

                  <a
                    href="{{ route('promotions.create') }}"
                    class="btn btn-primary"
                  >
                    <i class="icon-base ti tabler-plus me-1"></i>
                    เพิ่มโปรโมชัน
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
    const deleteForms = document.querySelectorAll(
      '.promotion-delete-form'
    );

    deleteForms.forEach(function (form) {
      form.addEventListener('submit', function (event) {
        const confirmed = window.confirm(
          'ยืนยันการลบโปรโมชันนี้?'
        );

        if (!confirmed) {
          event.preventDefault();
        }
      });
    });
  });
</script>
@endsection
