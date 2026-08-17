@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดตู้')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
@endsection

@section('content')
  <div class="row">
    <div class="col-12">

      <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียดตู้</h5>
            <p class="mb-0 text-muted">
              ข้อมูลตู้ กลุ่มตู้ น้ำยาใน Tank และสถานที่ติดตั้ง
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('machines.edit', $machine) }}" class="btn btn-warning">
              <i class="icon-base ti tabler-edit me-1"></i>
              แก้ไข
            </a>

            <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>
          </div>
        </div>
      </div>

      <div class="row g-4">

        <div class="col-lg-8">

          <div class="card mb-4">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="icon-base ti tabler-device-desktop me-1"></i>
                ข้อมูลตู้
              </h5>
            </div>

            <div class="card-body">
              <div class="row g-4">

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">ชื่อตู้</small>
                  <div class="fw-semibold">{{ $machine->name ?: '-' }}</div>
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">รหัสตู้</small>
                  <div class="fw-semibold">{{ $machine->code ?: '-' }}</div>
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">Serial Number</small>
                  <div>{{ $machine->serial_number ?: '-' }}</div>
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">รุ่นตู้</small>
                  <div>{{ $machine->model ?: '-' }}</div>
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">กลุ่มตู้</small>

                  @if ($machine->group)
                    <div class="fw-semibold">
                      {{ $machine->group->name }}
                      @if (!empty($machine->group->code))
                        <span class="text-muted">({{ $machine->group->code }})</span>
                      @endif
                    </div>
                  @else
                    <span class="text-danger">ยังไม่ได้กำหนดกลุ่มตู้</span>
                  @endif
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">Theme</small>
                  <div>
                    {{ optional(optional($machine->group)->theme)->name ?: '-' }}
                  </div>
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">สถานะตู้</small>

                  @if ($machine->status === 'active')
                    <span class="badge bg-label-success">พร้อมใช้งาน</span>
                  @elseif ($machine->status === 'maintenance')
                    <span class="badge bg-label-warning">ซ่อมบำรุง</span>
                  @elseif ($machine->status === 'inactive')
                    <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                  @elseif ($machine->status === 'offline')
                    <span class="badge bg-label-dark">ออฟไลน์</span>
                  @elseif ($machine->status === 'error')
                    <span class="badge bg-label-danger">มีปัญหา</span>
                  @else
                    <span class="badge bg-label-secondary">{{ $machine->status ?: '-' }}</span>
                  @endif
                </div>

                <div class="col-md-6">
                  <small class="text-muted d-block mb-1">การเปิดใช้งาน</small>

                  @if ($machine->is_active)
                    <span class="badge bg-label-success">เปิดใช้งาน</span>
                  @else
                    <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                  @endif
                </div>

                <div class="col-12">
                  <small class="text-muted d-block mb-1">หมายเหตุ</small>
                  <div>{{ $machine->remark ?: '-' }}</div>
                </div>

              </div>
            </div>
          </div>

          <div class="card mb-4">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="icon-base ti tabler-droplet me-1"></i>
                น้ำยาในตู้
              </h5>
            </div>

            <div class="card-body">
              <div class="row g-4">

                @forelse ($machine->tanks->sortBy('tank_no') as $tank)
                  @php
                    $priceOptions = $machine->group
                        ? $machine->group->productPrices
                            ->where('product_id', $tank->product_id)
                            ->where('is_active', true)
                            ->sortBy('sort_order')
                        : collect();
                  @endphp

                  <div class="col-md-6">
                    <div class="card border shadow-none h-100">
                      <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                          <h6 class="mb-0">
                            Tank {{ $tank->tank_no }}
                          </h6>
                          <small class="text-muted">
                            {{ $tank->tank_name ?: '-' }}
                          </small>
                        </div>

                        @if ($tank->is_active)
                          <span class="badge bg-label-success">เปิดใช้งาน</span>
                        @else
                          <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                        @endif
                      </div>

                      <div class="card-body">
                        <div class="mb-3">
                          <small class="text-muted d-block mb-1">สินค้า / น้ำยา</small>
                          <div class="fw-semibold">
                            {{ optional($tank->product)->name ?: '-' }}
                            @if (!empty(optional($tank->product)->code))
                              <span class="text-muted">
                                ({{ $tank->product->code }})
                              </span>
                            @endif
                          </div>
                        </div>

                        <div class="row g-3 mb-3">
                          <div class="col-6">
                            <small class="text-muted d-block mb-1">ความจุ</small>
                            <div>
                              {{ $tank->capacity_liters !== null
                                  ? number_format((float) $tank->capacity_liters, 2) . ' ลิตร'
                                  : '-' }}
                            </div>
                          </div>

                          <div class="col-6">
                            <small class="text-muted d-block mb-1">คงเหลือ</small>
                            <div>
                              {{ $tank->remaining_liters !== null
                                  ? number_format((float) $tank->remaining_liters, 2) . ' ลิตร'
                                  : '-' }}
                            </div>
                          </div>

                          <div class="col-6">
                            <small class="text-muted d-block mb-1">แจ้งเตือนต่ำกว่า</small>
                            <div>
                              {{ $tank->low_stock_liters !== null
                                  ? number_format((float) $tank->low_stock_liters, 2) . ' ลิตร'
                                  : '-' }}
                            </div>
                          </div>

                          <div class="col-6">
                            <small class="text-muted d-block mb-1">ถือว่าหมดต่ำกว่า</small>
                            <div>
                              {{ $tank->empty_stock_liters !== null
                                  ? number_format((float) $tank->empty_stock_liters, 2) . ' ลิตร'
                                  : '-' }}
                            </div>
                          </div>
                        </div>

                        <hr>

                        <small class="text-muted d-block mb-2">
                          ราคาตาม Machine Group
                        </small>

                        @if ($tank->product_id && $priceOptions->isNotEmpty())
                          <div class="d-flex flex-column gap-2">
                            @foreach ($priceOptions as $price)
                              <div class="d-flex align-items-center justify-content-between border rounded px-3 py-2">
                                <div class="fw-semibold">
                                  {{ number_format((int) $price->amount_ml) }} ml
                                </div>

                                <div class="text-end">
                                  @if ($price->special_price !== null)
                                    <div>
                                      <span class="text-decoration-line-through text-muted me-1">
                                        {{ number_format((float) $price->price, 2) }}
                                      </span>
                                      <span class="fw-semibold text-success">
                                        {{ number_format((float) $price->special_price, 2) }} บาท
                                      </span>
                                    </div>
                                  @else
                                    <span class="fw-semibold">
                                      {{ number_format((float) $price->price, 2) }} บาท
                                    </span>
                                  @endif
                                </div>
                              </div>
                            @endforeach
                          </div>
                        @elseif ($tank->product_id)
                          <div class="alert alert-warning mb-0 py-2">
                            สินค้านี้ยังไม่มีราคาเปิดใช้งานในกลุ่มตู้
                          </div>
                        @else
                          <div class="text-muted">
                            ยังไม่ได้เลือกสินค้าใน Tank
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="col-12">
                    <div class="text-center py-5 text-muted">
                      ยังไม่มีข้อมูล Tank
                    </div>
                  </div>
                @endforelse

              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">

          <div class="card mb-4">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="icon-base ti tabler-map-pin me-1"></i>
                สถานที่ติดตั้ง
              </h5>
            </div>

            <div class="card-body">
              @if ($machine->location)
                <div class="mb-3">
                  <small class="text-muted d-block mb-1">ชื่อสถานที่</small>
                  <div class="fw-semibold">{{ $machine->location->name }}</div>
                </div>

                <div class="mb-3">
                  <small class="text-muted d-block mb-1">รหัสสถานที่</small>
                  <div>{{ $machine->location->code ?: '-' }}</div>
                </div>

                <div class="mb-3">
                  <small class="text-muted d-block mb-1">ผู้ติดต่อ</small>
                  <div>{{ $machine->location->contact_name ?: '-' }}</div>
                </div>

                <div class="mb-3">
                  <small class="text-muted d-block mb-1">เบอร์โทร</small>
                  <div>{{ $machine->location->contact_phone ?: '-' }}</div>
                </div>

                <div class="mb-3">
                  <small class="text-muted d-block mb-1">ที่อยู่</small>
                  <div>{{ $machine->location->full_address ?: '-' }}</div>
                </div>

                <div class="row g-3 mb-3">
                  <div class="col-6">
                    <small class="text-muted d-block mb-1">Latitude</small>
                    <div>{{ $machine->location->latitude ?: '-' }}</div>
                  </div>

                  <div class="col-6">
                    <small class="text-muted d-block mb-1">Longitude</small>
                    <div>{{ $machine->location->longitude ?: '-' }}</div>
                  </div>
                </div>

                @if ($machine->location->latitude && $machine->location->longitude)
                  <a
                    href="https://www.google.com/maps?q={{ $machine->location->latitude }},{{ $machine->location->longitude }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-label-primary w-100"
                  >
                    <i class="icon-base ti tabler-map-2 me-1"></i>
                    เปิดใน Google Maps
                  </a>
                @endif
              @else
                <div class="text-center py-4 text-muted">
                  <i class="icon-base ti tabler-map-pin-off d-block mb-2" style="font-size: 38px;"></i>
                  ยังไม่ได้เลือกสถานที่ติดตั้ง
                </div>
              @endif
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="icon-base ti tabler-clock me-1"></i>
                ข้อมูลระบบ
              </h5>
            </div>

            <div class="card-body">
              <div class="mb-3">
                <small class="text-muted d-block mb-1">วันที่สร้าง</small>
                <div>{{ $machine->created_at ? $machine->created_at->format('d/m/Y H:i') : '-' }}</div>
              </div>

              <div>
                <small class="text-muted d-block mb-1">อัปเดตล่าสุด</small>
                <div>{{ $machine->updated_at ? $machine->updated_at->format('d/m/Y H:i') : '-' }}</div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
@endsection
