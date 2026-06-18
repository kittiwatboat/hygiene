@extends('layouts/layoutMaster')

@section('title', 'สินค้า / น้ำยา')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])

  <style>
    .product-alert {
      margin: 0 1.5rem 1rem 1.5rem;
      padding: 0.65rem 0.85rem;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .product-alert-content {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      line-height: 1.3;
      font-weight: 500;
    }

    .product-alert-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1rem;
      line-height: 1;
      padding: 0.25rem;
      cursor: pointer;
      opacity: 0.7;
    }

    .product-alert-close:hover {
      opacity: 1;
    }
  </style>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">สินค้า / น้ำยา</h5>
            <p class="mb-0 text-muted">
              จัดการรายการน้ำยาที่ใช้กับตู้ เช่น น้ำยาซักผ้า น้ำยาปรับผ้านุ่ม และน้ำยาฆ่าเชื้อ
            </p>
          </div>

          <div>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
              <i class="icon-base ti tabler-plus me-1"></i>
              เพิ่มสินค้า / น้ำยา
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success product-alert" role="alert">
            <div class="product-alert-content">
              <i class="icon-base ti tabler-circle-check"></i>
              <span>{{ session('success') }}</span>
            </div>

            <button
              type="button"
              class="product-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger product-alert" role="alert">
            <div class="product-alert-content">
              <i class="icon-base ti tabler-alert-circle"></i>
              <span>{{ session('error') }}</span>
            </div>

            <button
              type="button"
              class="product-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif

        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th style="width: 70px;">#</th>
                <th>รหัส</th>
                <th style="width: 90px;">รูป</th>
                <th>ชื่อสินค้า / น้ำยา</th>
                <th>ประเภท</th>
                <th>หน่วย</th>
                <th>สถานะ</th>
                <th style="width: 110px;">Actions</th>
              </tr>
            </thead>

            <tbody class="table-border-bottom-0">
              @forelse ($products as $index => $product)
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

                <tr>
                  <td>
                    <span class="fw-medium">{{ $index + 1 }}</span>
                  </td>

                  <td>
                    <span class="fw-medium">{{ $product->code ?: '-' }}</span>

                  </td>
<td>
  @if ($product->image)
       <img src="{{ asset('assets/img/products/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-width: 120px; max-height: 120px;">
  @else
    <div
      class="rounded bg-label-secondary d-flex align-items-center justify-content-center"
      style="width: 56px; height: 56px;"
    >
      <i class="icon-base ti tabler-photo-off"></i>
    </div>
  @endif
</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="icon-base ti tabler-droplet"></i>
                        </span>
                      </div>

                      <div>
                        <div class="fw-medium">{{ $product->name }}</div>

                        @if (!empty($product->description))
                          <div class="text-muted small text-truncate" style="max-width: 320px;">
                            {{ $product->description }}
                          </div>
                        @endif
                      </div>
                    </div>
                  </td>

                  <td>{{ $typeText }}</td>

                  <td>{{ $unitText }}</td>

                  <td>
                    @if ($product->is_active)
                      <span class="badge bg-label-success">เปิดใช้งาน</span>
                    @else
                      <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                    @endif
                  </td>

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                      </button>

                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('products.show', $product) }}">
                          <i class="icon-base ti tabler-eye me-1"></i>
                          ดูรายละเอียด
                        </a>

                        <a class="dropdown-item" href="{{ route('products.edit', $product) }}">
                          <i class="icon-base ti tabler-pencil me-1"></i>
                          แก้ไข
                        </a>

                        <form
                          action="{{ route('products.destroy', $product) }}"
                          method="POST"
                          class="product-delete-form">
                          @csrf
                          @method('DELETE')

                          <button type="submit" class="dropdown-item text-danger">
                            <i class="icon-base ti tabler-trash me-1"></i>
                            ลบ
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <div class="mb-2">
                      <i class="icon-base ti tabler-droplet-off" style="font-size: 42px;"></i>
                    </div>

                    <h6 class="mb-1">ยังไม่มีสินค้า / น้ำยา</h6>
                    <p class="text-muted mb-3">กดปุ่มเพิ่มสินค้า/น้ำยาเพื่อเริ่มใช้งาน</p>

                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                      <i class="icon-base ti tabler-plus me-1"></i>
                      เพิ่มสินค้า / น้ำยา
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
      const deleteForms = document.querySelectorAll('.product-delete-form');

      deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
          const confirmed = confirm('ยืนยันการลบสินค้า/น้ำยารายการนี้?');

          if (!confirmed) {
            event.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
