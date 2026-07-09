@extends('layouts/layoutMaster')

@section('title', 'จัดการหน้าบ้าน')

@section('content')
<div class="card">

  <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
    <div>
      <h5 class="mb-1">จัดการหน้าบ้าน</h5>
      <p class="text-muted mb-0">
        จัดการหน้าแรก หน้าเลือกภาษา หน้าเลือกสินค้า และหน้าต่าง ๆ ของหน้าบ้าน
      </p>
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

  <div class="table-responsive">
    <table class="table table-hover">
      <thead class="table-light">
        <tr>
          <th style="width: 70px;">#</th>
          <th>หน้า</th>
          <th>Page Key</th>
          <th class="text-center">จำนวนสไลด์</th>
          <th>สถานะ</th>
          <th style="width: 120px;" class="text-center">จัดการ</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($pages as $index => $page)
          <tr>
            <td>{{ $index + 1 }}</td>

            <td>
              <div class="fw-medium">
                {{ $page->name }}
              </div>

              @if ($page->title)
                <small class="text-muted">
                  {{ $page->title }}
                </small>
              @endif
            </td>

            <td>
              <span class="badge bg-label-primary">
                {{ $page->page_key }}
              </span>
            </td>

            <td class="text-center">
              <span class="fw-medium">
                {{ number_format((int) $page->media_count) }}
              </span>
              <small class="text-muted">รายการ</small>
            </td>

            <td>
              <span class="badge {{ $page->status_class }}">
                {{ $page->status_text }}
              </span>
            </td>

            <td class="text-center">
              <a
                href="{{ route('frontend.pages.edit', $page) }}"
                class="btn btn-sm btn-primary"
              >
                <i class="icon-base ti tabler-pencil me-1"></i>
                แก้ไข
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-5">
              <div class="mb-2">
                <i
                  class="icon-base ti tabler-device-desktop-off text-muted"
                  style="font-size: 48px;"
                ></i>
              </div>

              <h6 class="mb-1">ยังไม่มีหน้า</h6>

              <p class="text-muted mb-0">
                กรุณารัน seeder เพื่อสร้างหน้าเริ่มต้น
              </p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection
