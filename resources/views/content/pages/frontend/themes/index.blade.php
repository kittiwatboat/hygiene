@extends('layouts/layoutMaster')

@section('title', 'ธีมหน้าตู้')

@section('content')
<div class="card">

  <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
      <h5 class="mb-1">ธีมหน้าตู้</h5>
      <p class="text-muted mb-0">
        จัดการชุดสี ฟอนต์ และรูปแบบปุ่มของหน้าตู้
      </p>
    </div>

    <a href="{{ route('kiosk.themes.create') }}" class="btn btn-primary">
      <i class="icon-base ti tabler-plus me-1"></i>
      เพิ่มธีม
    </a>
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
          <th>#</th>
          <th>ธีม</th>
          <th>ชุดสี</th>
          <th>ปุ่ม / การ์ด</th>
          <th>โลโก้</th>
          <th>สถานะ</th>
          <th class="text-center">จัดการ</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($themes as $index => $theme)
          <tr>
            <td>{{ $index + 1 }}</td>

            <td>
              <div class="fw-medium">
                {{ $theme->name }}
              </div>

              <small class="text-muted">
                {{ $theme->slug }}
              </small>

              @if ($theme->is_default)
                <div class="mt-1">
                  <span class="badge bg-label-primary">
                    Default
                  </span>
                </div>
              @endif
            </td>

            <td>
              <div class="d-flex align-items-center gap-2 flex-wrap">
                @foreach ([
                  $theme->primary_color,
                  $theme->secondary_color,
                  $theme->accent_color,
                  $theme->background_color,
                  $theme->text_color,
                ] as $color)
                  <span
                    title="{{ $color }}"
                    style="
                      display:inline-block;
                      width:24px;
                      height:24px;
                      border-radius:50%;
                      border:1px solid rgba(0,0,0,.15);
                      background: {{ $color }};
                    "
                  ></span>
                @endforeach
              </div>
            </td>

            <td>
              <div class="small">
                ปุ่ม: {{ $theme->button_radius }}px
              </div>
              <div class="small text-muted">
                การ์ด: {{ $theme->card_radius }}px
              </div>
            </td>

            <td>
              @if ($theme->logo)
                <img
                  src="{{ asset('assets/img/kiosk/themes/' . $theme->logo) }}"
                  alt="{{ $theme->name }}"
                  class="rounded border p-1"
                  style="width:80px;height:42px;object-fit:contain;"
                >
              @else
                <span class="text-muted">ไม่มีโลโก้</span>
              @endif
            </td>

            <td>
              <span class="badge {{ $theme->status_class }}">
                {{ $theme->status_text }}
              </span>
            </td>

            <td class="text-center">
              <div class="dropdown">
                <button
                  type="button"
                  class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                  data-bs-toggle="dropdown"
                >
                  <i class="icon-base ti tabler-dots-vertical"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                  <a
                    href="{{ route('kiosk.themes.edit', $theme) }}"
                    class="dropdown-item"
                  >
                    <i class="icon-base ti tabler-pencil me-2"></i>
                    แก้ไข
                  </a>

                  <div class="dropdown-divider"></div>

                  <form
                    action="{{ route('kiosk.themes.destroy', $theme) }}"
                    method="POST"
                    onsubmit="return confirm('ยืนยันการลบธีมนี้?')"
                  >
                    @csrf
                    @method('DELETE')

                    <button
                      type="submit"
                      class="dropdown-item text-danger"
                      {{ $theme->is_default ? 'disabled' : '' }}
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
              <i
                class="icon-base ti tabler-palette-off text-muted mb-2"
                style="font-size: 48px;"
              ></i>

              <h6 class="mt-2 mb-1">ยังไม่มีธีม</h6>

              <p class="text-muted mb-3">
                เพิ่มธีมเพื่อกำหนดรูปแบบหน้าตู้
              </p>

              <a href="{{ route('kiosk.themes.create') }}" class="btn btn-primary">
                เพิ่มธีม
              </a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
