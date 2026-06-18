@extends('layouts/layoutMaster')

@section('title', 'จัดการแบนเนอร์')

@section('page-style')
<style>
  .banner-alert {
    margin: 0 1.5rem 1rem;
    padding: .75rem 1rem;
    border-radius: .5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
  }

  .banner-image {
    width: 180px;
    height: 76px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid rgba(67, 89, 113, .12);
    background: #f5f5f7;
  }

  .banner-image-empty {
    width: 180px;
    height: 76px;
    border-radius: 10px;
    background: rgba(75, 70, 92, .08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a8aaae;
  }

  .banner-title {
    max-width: 280px;
    white-space: normal;
  }

  .banner-link {
    max-width: 260px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .banner-table td {
    vertical-align: middle;
  }

  .banner-drag-icon {
    cursor: grab;
    color: #a8aaae;
  }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">

      <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
          <h5 class="mb-1">จัดการแบนเนอร์</h5>
          <p class="mb-0 text-muted">
            จัดการรูปแบนเนอร์ ลำดับการแสดง ช่วงเวลา และสถานะการใช้งาน
          </p>
        </div>

        <a href="{{ route('banners.create') }}" class="btn btn-primary">
          <i class="icon-base ti tabler-plus me-1"></i>
          เพิ่มแบนเนอร์
        </a>
      </div>

      @if (session('success'))
        <div class="alert alert-success banner-alert" role="alert">
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
        <div class="alert alert-danger banner-alert" role="alert">
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

      <div class="table-responsive">
        <table class="table table-hover banner-table">
          <thead class="table-light">
            <tr>
              <th style="width: 60px;">#</th>
              <th style="width: 210px;">รูปแบนเนอร์</th>
              <th>รายละเอียด</th>
              <th style="width: 100px;">ลำดับ</th>
              <th style="width: 220px;">ช่วงเวลาแสดง</th>
              <th style="width: 140px;">สถานะ</th>
              <th style="width: 100px;" class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($banners as $index => $banner)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <i class="icon-base ti tabler-grip-vertical banner-drag-icon"></i>
                    <span>{{ $index + 1 }}</span>
                  </div>
                </td>

                <td>
                  @if ($banner->image)
                    <a
                      href="{{ asset('assets/img/banners/' . $banner->image) }}"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      <img
                        src="{{ asset('assets/img/banners/' . $banner->image) }}"
                        alt="{{ $banner->title }}"
                        class="banner-image"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');"
                      >

                      <div class="banner-image-empty d-none">
                        <div class="text-center">
                          <i class="icon-base ti tabler-photo-off d-block mb-1"></i>
                          <small>ไม่พบรูปภาพ</small>
                        </div>
                      </div>
                    </a>
                  @else
                    <div class="banner-image-empty">
                      <div class="text-center">
                        <i class="icon-base ti tabler-photo-off d-block mb-1"></i>
                        <small>ไม่มีรูปภาพ</small>
                      </div>
                    </div>
                  @endif
                </td>

                <td>
                  <div class="banner-title">
                    <div class="fw-medium mb-1">
                      {{ $banner->title }}
                    </div>

                    @if ($banner->link_url)
                      <div class="banner-link text-muted small mb-1">
                        <i class="icon-base ti tabler-link me-1"></i>

                        @if (
                          \Illuminate\Support\Str::startsWith(
                            $banner->link_url,
                            ['http://', 'https://']
                          )
                        )
                          <a
                            href="{{ $banner->link_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                          >
                            {{ $banner->link_url }}
                          </a>
                        @else
                          <span>{{ $banner->link_url }}</span>
                        @endif
                      </div>
                    @else
                      <div class="text-muted small mb-1">
                        <i class="icon-base ti tabler-link-off me-1"></i>
                        ไม่ได้กำหนดลิงก์
                      </div>
                    @endif

                    @if ($banner->remark)
                      <div class="text-muted small">
                        {{ \Illuminate\Support\Str::limit($banner->remark, 90) }}
                      </div>
                    @endif
                  </div>
                </td>

                <td>
                  <span class="badge bg-label-primary">
                    {{ number_format((int) $banner->sort_order) }}
                  </span>
                </td>

                <td>
                  <div class="small">
                    <div class="mb-1">
                      <span class="text-muted">เริ่ม:</span>
                      <span class="fw-medium">
                        {{ $banner->start_at
                            ? $banner->start_at->format('d/m/Y H:i')
                            : 'แสดงทันที' }}
                      </span>
                    </div>

                    <div>
                      <span class="text-muted">สิ้นสุด:</span>
                      <span class="fw-medium">
                        {{ $banner->end_at
                            ? $banner->end_at->format('d/m/Y H:i')
                            : 'ไม่กำหนด' }}
                      </span>
                    </div>
                  </div>
                </td>

                <td>
                  <div class="mb-1">
                    <span class="badge {{ $banner->display_status_class }}">
                      {{ $banner->display_status_text }}
                    </span>
                  </div>

                  @if ($banner->is_active)
                    <small class="text-success">
                      <i class="icon-base ti tabler-circle-check me-1"></i>
                      เปิดใช้งาน
                    </small>
                  @else
                    <small class="text-muted">
                      <i class="icon-base ti tabler-circle-x me-1"></i>
                      ปิดใช้งาน
                    </small>
                  @endif
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
                      @if ($banner->image)
                        <a
                          class="dropdown-item"
                          href="{{ asset('assets/img/banners/' . $banner->image) }}"
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <i class="icon-base ti tabler-eye me-2"></i>
                          ดูรูปเต็ม
                        </a>
                      @endif

                      <a
                        class="dropdown-item"
                        href="{{ route('banners.edit', $banner) }}"
                      >
                        <i class="icon-base ti tabler-pencil me-2"></i>
                        แก้ไข
                      </a>

                      <div class="dropdown-divider"></div>

                      <form
                        action="{{ route('banners.destroy', $banner) }}"
                        method="POST"
                        class="banner-delete-form"
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
                <td colspan="7" class="text-center py-5">
                  <div class="mb-3">
                    <i
                      class="icon-base ti tabler-photo-off text-muted"
                      style="font-size: 48px;"
                    ></i>
                  </div>

                  <h6 class="mb-1">ยังไม่มีแบนเนอร์</h6>

                  <p class="text-muted mb-3">
                    เพิ่มรูปแบนเนอร์เพื่อใช้แสดงบนหน้าเครื่องหรือหน้าลูกค้า
                  </p>

                  <a href="{{ route('banners.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-1"></i>
                    เพิ่มแบนเนอร์
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
    const deleteForms = document.querySelectorAll('.banner-delete-form');

    deleteForms.forEach(function (form) {
      form.addEventListener('submit', function (event) {
        const confirmed = window.confirm(
          'ยืนยันการลบแบนเนอร์นี้? รูปภาพที่เกี่ยวข้องจะถูกลบออกด้วย'
        );

        if (!confirmed) {
          event.preventDefault();
        }
      });
    });
  });
</script>
@endsection
