@extends('layouts/layoutMaster')

@section('title', 'แก้ไขข้อความแปลหน้า HOME')

@section('content')
@php
    /*
    |--------------------------------------------------------------------------
    | HOME Translation Keys
    |--------------------------------------------------------------------------
    | หน้า HOME ตอนนี้ให้แก้เฉพาะ:
    | 1. ข้อความบน Header
    | 2. ข้อความบนปุ่ม
    |
    | รองรับทั้งกรณี group เป็น HOME / home
    | และกรณี key ขึ้นต้นด้วย home.
    */
    $homeKeys = $translationKeys
        ->filter(function ($item) {
            $group = strtoupper(trim((string) ($item->group ?? '')));
            $key = strtolower(trim((string) ($item->key ?? '')));

            return $group === 'HOME'
                || str_starts_with($key, 'home.');
        })
        ->filter(function ($item) {
            $key = strtolower((string) ($item->key ?? ''));
            $description = strtolower((string) ($item->description ?? ''));

            return str_contains($key, 'header')
                || str_contains($key, 'button')
                || str_contains($description, 'header')
                || str_contains($description, 'ปุ่ม');
        })
        ->values();

    $getFieldLabel = function ($translationKey) {
        $key = strtolower((string) ($translationKey->key ?? ''));
        $description = strtolower((string) ($translationKey->description ?? ''));

        if (
            str_contains($key, 'header')
            || str_contains($description, 'header')
        ) {
            return 'ข้อความบน Header';
        }

        if (
            str_contains($key, 'button')
            || str_contains($description, 'ปุ่ม')
        ) {
            return 'ข้อความบนปุ่ม';
        }

        return $translationKey->description ?: $translationKey->key;
    };
@endphp

<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-label-primary">HOME</span>

                        <h5 class="mb-0">
                            ข้อความแปลหน้าแรก
                        </h5>
                    </div>

                    <p class="text-muted mb-0">
                        ภาษา:
                        <strong>
                            {{ $language->native_name ?? $language->name }}
                        </strong>

                        <span class="mx-2">•</span>

                        รหัสภาษา:
                        <code>{{ $language->code }}</code>
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <form
                        action="{{ route('frontend.translations.sync', $language) }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-outline-primary"
                        >
                            <i class="icon-base ti tabler-refresh me-1"></i>
                            สร้างข้อความที่ยังขาด
                        </button>
                    </form>

                    <a
                        href="{{ route('frontend.translations.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="icon-base ti tabler-arrow-left me-1"></i>
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
        <form
            action="{{ route('frontend.translations.update', $language) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <input type="hidden" name="group" value="HOME">

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-1">
                        HOME
                    </h5>

                    <p class="text-muted mb-0">
                        กำหนดข้อความที่ใช้บนหน้าแรกของตู้
                    </p>
                </div>

                <div class="card-body">
                    @forelse ($homeKeys as $translationKey)
                        @php
                            $translation = $translations->get($translationKey->id);

                            $fieldName =
                                'translations.'
                                . $translationKey->id;

                            $currentValue = old(
                                $fieldName,
                                $translation?->value
                            );

                            $fieldLabel = $getFieldLabel($translationKey);
                        @endphp

                        <div class="border rounded p-4 mb-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h6 class="mb-1">
                                        {{ $fieldLabel }}
                                    </h6>

                                    <code>{{ $translationKey->key }}</code>
                                </div>

                                @if (filled($translationKey->default_value))
                                    <span class="badge bg-label-secondary">
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
                                        rows="3"
                                        class="form-control bg-light"
                                        readonly
                                    >{{ $translationKey->default_value }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        คำแปลภาษา
                                        {{ $language->native_name ?? $language->name }}
                                    </label>

                                    <textarea
                                        name="translations[{{ $translationKey->id }}]"
                                        rows="3"
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
                    @empty
                        <div class="alert alert-warning mb-0">
                            <div class="fw-semibold mb-1">
                                ยังไม่พบ Translation Key สำหรับหน้า HOME
                            </div>

                            <div>
                                หน้า HOME ต้องมีอย่างน้อย 2 รายการ:
                                ข้อความบน Header และข้อความบนปุ่ม
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($homeKeys->isNotEmpty())
                <div
                    class="position-sticky bottom-0 bg-body py-3"
                    style="z-index: 10;"
                >
                    <div class="card border-primary shadow">
                        <div class="card-body d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold">
                                    พร้อมบันทึกข้อความหน้า HOME
                                </div>

                                <small class="text-muted">
                                    ทั้งหมด
                                    {{ number_format($homeKeys->count()) }}
                                    รายการ
                                </small>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="icon-base ti tabler-device-floppy me-1"></i>
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
