@extends('layouts/layoutMaster')

@section('title', 'รายละเอียดสินค้า / น้ำยา')

@section('content')
  @php
    $typeText = match ($product->type) {
        'detergent' => 'น้ำยาซักผ้า',
        'softener' => 'น้ำยาปรับผ้านุ่ม',
        'disinfectant' => 'น้ำยาฆ่าเชื้อ',
        'other' => 'อื่น ๆ',
        default => '-',
    };

    $unitText = match ($product->unit) {
        'liter' => 'ลิตร',
        'ml' => 'มิลลิลิตร',
        'piece' => 'ชิ้น',
        default => $product->unit ?: '-',
    };
  @endphp

  <div class="row g-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">รายละเอียดสินค้า / น้ำยา</h5>
            <p class="text-muted mb-0">{{ $product->code ?: '-' }} - {{ $product->name }}</p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>

            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
              <i class="icon-base ti tabler-pencil me-1"></i>
              แก้ไข
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="card">
        <h5 class="card-header">ข้อมูลสินค้า / น้ำยา</h5>

        <div class="card-body">
          <div class="row g-4">
            <div class="col-md-6">
              <small class="text-muted">รหัส</small>
              <div class="fw-medium">{{ $product->code ?: '-' }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ชื่อสินค้า / น้ำยา</small>
              <div class="fw-medium">{{ $product->name }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">ประเภท</small>
              <div class="fw-medium">{{ $typeText }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">หน่วยนับ</small>
              <div class="fw-medium">{{ $unitText }}</div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">สถานะ</small>
              <div>
                @if ($product->is_active)
                  <span class="badge bg-label-success">เปิดใช้งาน</span>
                @else
                  <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <small class="text-muted">วันที่สร้าง</small>
              <div class="fw-medium">{{ optional($product->created_at)->format('d/m/Y H:i') ?: '-' }}</div>
            </div>

            <div class="col-12">
              <small class="text-muted">รายละเอียด</small>
              <div class="fw-medium">{{ $product->description ?: '-' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card">
        <h5 class="card-header">การใช้งานในตู้</h5>

        <div class="card-body">
          @forelse ($product->tanks as $tank)
            <div class="border-bottom pb-3 mb-3">
              <div class="fw-medium">
                {{ $tank->machine?->code ?: '-' }} - {{ $tank->machine?->name ?: '-' }}
              </div>
              <div class="text-muted small">
                ช่อง {{ $tank->tank_no }}: {{ $tank->tank_name ?: '-' }}
              </div>
              <div class="text-muted small">
                คงเหลือ {{ number_format((float) $tank->remaining_liters, 2) }} /
                {{ number_format((float) $tank->capacity_liters, 2) }} L
              </div>
            </div>
          @empty
            <p class="text-muted mb-0">ยังไม่มีตู้ที่ใช้น้ำยานี้</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
