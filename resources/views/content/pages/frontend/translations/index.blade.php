@extends('layouts/layoutMaster')

@section('title', 'จัดการข้อความแปล')

@section('content')
<div class="card">
    <div
        class="card-header d-flex align-items-center justify-content-between"
    >
        <div>
            <h5 class="mb-1">ข้อความแปลหน้าตู้</h5>

            <p class="text-muted mb-0">
                เลือกภาษาเพื่อแก้ไขข้อความที่แสดงในแต่ละหน้า
            </p>
        </div>

        <span class="badge bg-label-primary">
            {{ number_format($activeKeyCount) }} ข้อความ
        </span>
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
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 80px;">#</th>
                    <th>ภาษา</th>
                    <th>รหัสภาษา</th>
                    <th>Locale</th>
                    <th>ข้อความที่มีแล้ว</th>
                    <th>สถานะ</th>
                    <th style="width: 220px;">จัดการ</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($languages as $language)
                    @php
                        $completed = min(
                            $language->translations_count,
                            $activeKeyCount
                        );

                        $percent = $activeKeyCount > 0
                            ? round(
                                ($completed / $activeKeyCount) * 100
                            )
                            : 0;
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($language->flag_url ?? null)
                                    <img
                                        src="{{ $language->flag_url }}"
                                        alt="{{ $language->name }}"
                                        width="32"
                                        height="24"
                                        class="rounded border"
                                        style="object-fit: cover;"
                                    >
                                @endif

                                <div>
                                    <div class="fw-semibold">
                                        {{ $language->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $language->native_name
                                            ?? $language->name }}
                                    </small>
                                </div>

                                {{-- @if ($language->is_default)
                                    <span class="badge bg-label-primary">
                                        ภาษาเริ่มต้น
                                    </span>
                                @endif --}}
                            </div>
                        </td>

                        <td>
                            <code>{{ $language->code }}</code>
                        </td>

                        <td>
                            {{ $language->locale }}
                        </td>

                        <td style="min-width: 220px;">
                            <div
                                class="d-flex justify-content-between mb-1"
                            >
                                <small>
                                    {{ number_format($completed) }}
                                    /
                                    {{ number_format($activeKeyCount) }}
                                </small>

                                <small>{{ $percent }}%</small>
                            </div>

                            <div
                                class="progress"
                                style="height: 6px;"
                            >
                                <div
                                    class="progress-bar"
                                    role="progressbar"
                                    style="width: {{ $percent }}%;"
                                ></div>
                            </div>
                        </td>

                        <td>
                            @if ($language->is_active)
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
                            <div class="d-flex gap-2">
                                <a
                                    href="{{ route(
                                        'frontend.translations.edit',
                                        $language
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    <i
                                        class="icon-base ti tabler-language me-1"
                                    ></i>
                                    แก้ข้อความ
                                </a>

                                <form
                                    action="{{ route(
                                        'frontend.translations.sync',
                                        $language
                                    ) }}"
                                    method="POST"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-secondary"
                                        onclick="return confirm(
                                            'สร้างรายการข้อความที่ยังขาดของภาษานี้?'
                                        )"
                                    >
                                        <i
                                            class="icon-base ti tabler-refresh"
                                        ></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="7"
                            class="text-center text-muted py-5"
                        >
                            ยังไม่มีภาษาในระบบ
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
