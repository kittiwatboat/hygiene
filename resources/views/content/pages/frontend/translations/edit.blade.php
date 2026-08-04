@extends('layouts/layoutMaster')

@section('title', 'แก้ไขข้อความแปล')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div
                class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3"
            >
                <div>
                    <h5 class="mb-1">
                        แก้ไขข้อความภาษา
                        {{ $language->native_name
                            ?? $language->name }}
                    </h5>

                    <p class="text-muted mb-0">
                        รหัสภาษา:
                        <code>{{ $language->code }}</code>

                        <span class="mx-2">•</span>

                        Locale:
                        <code>{{ $language->locale }}</code>
                    </p>
                </div>

                <div class="d-flex gap-2">
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
                            class="btn btn-outline-primary"
                        >
                            <i
                                class="icon-base ti tabler-refresh me-1"
                            ></i>
                            สร้างข้อความที่ยังขาด
                        </button>
                    </form>

                    <a
                        href="{{ route(
                            'frontend.translations.index'
                        ) }}"
                        class="btn btn-outline-secondary"
                    >
                        <i
                            class="icon-base ti tabler-arrow-left me-1"
                        ></i>
                        กลับ
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success mb-0">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="col-12">
            <div class="alert alert-danger mb-0">
                <div class="fw-semibold mb-2">
                    กรุณาตรวจสอบข้อมูล
                </div>

                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form
                    method="GET"
                    action="{{ route(
                        'frontend.translations.edit',
                        $language
                    ) }}"
                    class="row g-3"
                >
                    <div class="col-md-6">
                        <label class="form-label">
                            ค้นหาข้อความ
                        </label>

                        <input
                            type="text"
                            name="keyword"
                            value="{{ $keyword }}"
                            class="form-control"
                            placeholder="ค้นหา Key, คำอธิบาย หรือข้อความเริ่มต้น"
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            หน้าจอ
                        </label>

                        <select
                            name="group"
                            class="form-select"
                        >
                            <option value="">
                                ทุกหน้าจอ
                            </option>

                            @foreach ($groups as $groupName)
                                <option
                                    value="{{ $groupName }}"
                                    {{ $group === $groupName
                                        ? 'selected'
                                        : '' }}
                                >
                                    {{ $groupName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i
                                class="icon-base ti tabler-search me-1"
                            ></i>
                            ค้นหา
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <form
            action="{{ route(
                'frontend.translations.update',
                $language
            ) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <input
                type="hidden"
                name="keyword"
                value="{{ $keyword }}"
            >

            <input
                type="hidden"
                name="group"
                value="{{ $group }}"
            >

            @forelse (
                $translationKeys->groupBy('group')
                as $groupName => $keys
            )
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-1">
                            {{ $groupName ?: 'ทั่วไป' }}
                        </h5>

                        <p class="text-muted mb-0">
                            {{ number_format($keys->count()) }}
                            ข้อความ
                        </p>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            @foreach ($keys as $translationKey)
                                @php
                                    $translation =
                                        $translations->get(
                                            $translationKey->id
                                        );

                                    $fieldName =
                                        'translations.'
                                        . $translationKey->id;

                                    $currentValue = old(
                                        $fieldName,
                                        $translation?->value
                                    );
                                @endphp

                                <div class="col-12">
                                    <div
                                        class="border rounded p-3"
                                    >
                                        <div
                                            class="d-flex flex-wrap justify-content-between gap-2 mb-3"
                                        >
                                            <div>
                                                <div class="fw-semibold">
                                                    {{ $translationKey->description
                                                        ?: $translationKey->key }}
                                                </div>

                                                <code>
                                                    {{ $translationKey->key }}
                                                </code>
                                            </div>

                                            @if (
                                                filled(
                                                    $translationKey
                                                        ->default_value
                                                )
                                            )
                                                <span
                                                    class="badge bg-label-secondary"
                                                >
                                                    มีค่าเริ่มต้น
                                                </span>
                                            @endif
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    ข้อความเริ่มต้น
                                                </label>

                                                <textarea
                                                    rows="2"
                                                    class="form-control bg-light"
                                                    readonly
                                                >{{ $translationKey->default_value }}</textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    ข้อความภาษา
                                                    {{ $language->native_name
                                                        ?? $language->name }}
                                                </label>

                                                <textarea
                                                    name="translations[{{ $translationKey->id }}]"
                                                    rows="2"
                                                    class="form-control @error($fieldName) is-invalid @enderror"
                                                    placeholder="{{ $translationKey->default_value }}"
                                                >{{ $currentValue }}</textarea>

                                                @error($fieldName)
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                                @if (blank($currentValue))
                                                    <div class="form-text">
                                                        ยังไม่ได้แปล ระบบจะใช้ข้อความเริ่มต้นแทน
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        ไม่พบข้อความตามเงื่อนไขที่ค้นหา
                    </div>
                </div>
            @endforelse

            @if ($translationKeys->isNotEmpty())
                <div
                    class="position-sticky bottom-0 bg-body py-3"
                    style="z-index: 10;"
                >
                    <div
                        class="card border-primary shadow"
                    >
                        <div
                            class="card-body d-flex align-items-center justify-content-between gap-3"
                        >
                            <div>
                                <div class="fw-semibold">
                                    พร้อมบันทึกข้อความ
                                </div>

                                <small class="text-muted">
                                    ทั้งหมด
                                    {{ number_format(
                                        $translationKeys->count()
                                    ) }}
                                    รายการ
                                </small>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i
                                    class="icon-base ti tabler-device-floppy me-1"
                                ></i>
                                บันทึกข้อความแปล
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
