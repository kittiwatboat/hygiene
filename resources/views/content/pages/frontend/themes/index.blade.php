@extends('layouts/layoutMaster')

@section('title', 'ธีมหน้าตู้')

@section('content')
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="mb-1">ธีมหน้าตู้</h5>
                <p class="text-muted mb-0">
                    จัดการรูปแบบ Theme สำหรับนำไปกำหนดให้แต่ละกลุ่มตู้ใช้งาน
                </p>
            </div>

            <a href="{{ route('frontend.themes.create') }}" class="btn btn-primary">
                <i class="icon-base ti tabler-plus me-1"></i>
                เพิ่มธีม
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success mx-4 mt-2 mb-0">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger mx-4 mt-2 mb-0">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>ชื่อธีม</th>
                        <th>สีหลัก</th>
                        <th>Logo</th>
                        <th>สถานะ</th>
                        <th style="width: 110px;">จัดการ</th>
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
                                            Theme สำรอง
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    @foreach ([$theme->background_color, $theme->text_color, $theme->button_color, $theme->button_text_color, $theme->button_hover_border_color] as $color)
                                        @if ($color)
                                            <span title="{{ $color }}"
                                                style="
                        display:inline-block;
                        width:24px;
                        height:24px;
                        border-radius:50%;
                        border:1px solid rgba(0,0,0,.15);
                        background: {{ $color }};
                      "></span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>

                            <td>
                                @if ($theme->header_logo_main)
                                    <img src="{{ asset('assets/img/frontend/themes/' . $theme->header_logo_main) }}"
                                        alt="{{ $theme->name }}" class="rounded border p-1"
                                        style="
                    width:90px;
                    height:46px;
                    object-fit:contain;
                  ">
                                @elseif ($theme->logo)
                                    <img src="{{ asset('assets/img/frontend/themes/' . $theme->logo) }}"
                                        alt="{{ $theme->name }}" class="rounded border p-1"
                                        style="
                    width:90px;
                    height:46px;
                    object-fit:contain;
                  ">
                                @else
                                    <span class="text-muted">
                                        ไม่มีโลโก้
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($theme->is_active)
                                    <span class="badge bg-label-success">
                                        เปิดใช้งาน
                                    </span>
                                @else
                                    <span class="badge bg-label-secondary">
                                        ปิดใช้งาน
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="icon-base ti tabler-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="{{ route('frontend.themes.edit', $theme) }}" class="dropdown-item">
                                            <i class="icon-base ti tabler-pencil me-2"></i>
                                            แก้ไข
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <form action="{{ route('frontend.themes.destroy', $theme) }}" method="POST"
                                            onsubmit="return confirm('ยืนยันการลบธีมนี้?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="dropdown-item text-danger"
                                                {{ $theme->is_default ? 'disabled' : '' }}>
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
                            <td colspan="6" class="text-center py-5">
                                <i class="icon-base ti tabler-palette-off text-muted mb-2" style="font-size:48px;"></i>

                                <h6 class="mt-2 mb-1">
                                    ยังไม่มีธีม
                                </h6>

                                <p class="text-muted mb-3">
                                    เพิ่ม Theme เพื่อกำหนดรูปแบบหน้าตู้
                                </p>

                                <a href="{{ route('frontend.themes.create') }}" class="btn btn-primary">
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
