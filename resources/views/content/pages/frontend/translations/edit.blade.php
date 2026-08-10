@extends('layouts/layoutMaster')

@section('title', 'แก้ไขข้อความแปลหน้า HOME')

@section('page-style')
<style>
    .home-preview-card {
        border: 1px solid #e9e7fd;
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
    }

    .home-preview-wrap {
        position: relative;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .home-preview-image {
        display: block;
        width: 100%;
        height: auto;
    }

    .home-preview-pin {
        position: absolute;
        width: 52px;
        height: 52px;
        border-radius: 999px;
        background: #ff3b30;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(255, 59, 48, .28);
        border: 4px solid #fff;
        z-index: 5;
    }

    .home-preview-highlight {
        position: absolute;
        border: 4px solid #ff4d4f;
        border-radius: 4px;
        z-index: 4;
        box-shadow: 0 0 0 2px rgba(255,255,255,.65) inset;
    }

    /* ตำแหน่ง marker ตามภาพตัวอย่าง */
    .pin-header {
        top: 2.5%;
        left: 37%;
    }

    .highlight-header {
        top: 1.6%;
        left: 40.2%;
        width: 36.8%;
        height: 10%;
    }

    .pin-button {
        bottom: 8%;
        left: 35.6%;
    }

    .highlight-button {
        bottom: 4.4%;
        left: 39.3%;
        width: 32%;
        height: 14.3%;
        border-radius: 10px;
    }

    .home-legend-box {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        background: #fff;
        padding: 24px 20px;
        height: 100%;
    }

    .legend-number {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 999px;
        background: #ff3b30;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 22px;
        margin-right: 10px;
    }

    .translation-number-badge {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 999px;
        background: #ff3b30;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 20px;
    }

    .translation-key-pill {
        display: inline-block;
        padding: 3px 10px;
        background: #efecff;
        color: #7367f0;
        border-radius: 8px;
        font-family: monospace;
        font-size: .95rem;
    }

    .translation-edit-table th {
        white-space: nowrap;
        vertical-align: middle;
    }

    .translation-edit-table td {
        vertical-align: middle;
    }

    .translation-footer-bar {
        position: sticky;
        bottom: 0;
        z-index: 10;
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(4px);
        border-top: 1px solid #ebecef;
        padding-top: 16px;
        margin-top: 20px;
    }

    @media (max-width: 991.98px) {
        .pin-header {
            width: 42px;
            height: 42px;
            font-size: 22px;
        }

        .pin-button {
            width: 42px;
            height: 42px;
            font-size: 22px;
        }
    }
</style>
@endsection

@section('content')
@php
    $keyMap = [
        1 => [
            'key' => 'home.header_title',
            'title' => 'ข้อความบน Header',
            'description' => 'Header Title',
        ],
        2 => [
            'key' => 'home.select_button',
            'title' => 'ข้อความบนปุ่ม "เลือกเติมน้ำยา"',
            'description' => 'Select Refill Button',
        ],
    ];

    $translationKeysByKey = collect($translationKeys)->keyBy('key');

    $homeRows = collect($keyMap)->map(function ($item, $number) use ($translationKeysByKey, $translations) {
        $translationKey = $translationKeysByKey->get($item['key']);
        $translation = $translationKey ? ($translations[$translationKey->id] ?? null) : null;

        return [
            'number' => $number,
            'key' => $item['key'],
            'title' => $item['title'],
            'description' => $item['description'],
            'translationKey' => $translationKey,
            'translation' => $translation,
            'default_value' => $translationKey->default_value ?? '',
            'current_value' => old(
                'translations.' . ($translationKey->id ?? 'missing_' . $item['key']),
                $translation->value ?? ''
            ),
        ];
    });

    $previewImage = asset('assets/img/frontend/home/home-translation-preview.png');
@endphp

<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-label-primary fs-6">HOME</span>
                        <h4 class="mb-0">ข้อความแปลหน้าแรก (HOME)</h4>
                    </div>

                    <div class="text-muted fs-5">
                        ภาษา:
                        <strong>{{ $language->native_name ?? $language->name }}</strong>
                        <span class="mx-2">•</span>
                        รหัสภาษา:
                        <span class="text-danger fw-semibold">{{ $language->code }}</span>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <form action="{{ route('frontend.translations.sync', $language) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="icon-base ti tabler-refresh me-1"></i>
                            สร้างข้อความที่ยังขาด
                        </button>
                    </form>

                    <a href="{{ route('frontend.translations.index') }}" class="btn btn-outline-secondary">
                        <i class="icon-base ti tabler-arrow-left me-1"></i>
                        กลับ
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success mb-0">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="col-12">
            <div class="alert alert-danger mb-0">
                <div class="fw-semibold mb-2">กรุณาตรวจสอบข้อมูล</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="col-12">
        <form action="{{ route('frontend.translations.update', $language) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="HOME">

            <div class="card home-preview-card">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="icon-base ti tabler-eye text-muted"></i>
                        <h4 class="mb-0">ตัวอย่างหน้า HOME</h4>
                    </div>
                    <div class="text-muted mt-2">
                        คลิกที่หมายเลขเพื่อดูคำอธิบายรายการแก้ไข
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-10">
                            <div class="home-preview-wrap">
                                <img
                                    src="{{ $previewImage }}"
                                    alt="ตัวอย่างหน้า HOME"
                                    class="home-preview-image"
                                >

                                <div class="home-preview-pin pin-header">1</div>
                                <div class="home-preview-highlight highlight-header"></div>

                                <div class="home-preview-pin pin-button">2</div>
                                <div class="home-preview-highlight highlight-button"></div>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="home-legend-box">
                                <h5 class="fw-bold mb-4">หมายเหตุ</h5>
                                <p class="text-muted mb-4">
                                    เลขกำกับในภาพ คือข้อความที่คุณสามารถแก้ไขได้
                                </p>

                                <div class="mb-4">
                                    <div class="d-flex align-items-start">
                                        <span class="legend-number">1</span>
                                        <div>
                                            <div class="fw-semibold">ข้อความบน Header</div>
                                            <div class="text-muted mt-1">(home.header_title)</div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex align-items-start">
                                        <span class="legend-number">2</span>
                                        <div>
                                            <div class="fw-semibold">ข้อความบนปุ่ม</div>
                                            <div class="text-muted mt-1">(home.select_button)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <h4 class="mb-2">รายการข้อความที่สามารถแก้ไขได้ (HOME)</h4>
                        <div class="text-muted">
                            แก้ไขด้านล่างแล้วกดบันทึกด้านล่าง
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered translation-edit-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th style="width: 260px;">ตำแหน่งในหน้า</th>
                                    <th style="width: 260px;">Translation Key</th>
                                    <th>ข้อความเริ่มต้น (ไทย)</th>
                                    <th>แปลเป็นปัจจุบัน</th>
                                    <th style="width: 130px;" class="text-center">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($homeRows as $row)
                                    @php
                                        $translationKey = $row['translationKey'];
                                        $fieldName = 'translations.' . ($translationKey->id ?? 'missing_' . $row['key']);
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <span class="translation-number-badge">{{ $row['number'] }}</span>
                                        </td>

                                        <td>
                                            <div class="fw-semibold">{{ $row['title'] }}</div>
                                            <div class="text-muted mt-1">{{ $row['description'] }}</div>
                                        </td>

                                        <td>
                                            <div class="translation-key-pill">{{ $row['key'] }}</div>
                                        </td>

                                        <td>
                                            <div class="fw-medium">{{ $row['default_value'] ?: '-' }}</div>
                                        </td>

                                        <td>
                                            @if ($translationKey)
                                                <textarea
                                                    name="translations[{{ $translationKey->id }}]"
                                                    rows="2"
                                                    class="form-control @error($fieldName) is-invalid @enderror"
                                                    placeholder="{{ $row['default_value'] }}"
                                                >{{ $row['current_value'] }}</textarea>

                                                @error($fieldName)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            @else
                                                <div class="text-danger">
                                                    ไม่พบ Translation Key: <code>{{ $row['key'] }}</code>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if ($translationKey)
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-primary"
                                                    onclick="resetTranslationField('translation-field-{{ $row['number'] }}', @js($row['default_value']))"
                                                >
                                                    <i class="icon-base ti tabler-refresh me-1"></i>
                                                    รีเซ็ต
                                                </button>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        var field = document.querySelector('textarea[name="translations[{{ $translationKey->id }}]"]');
                                                        if (field) {
                                                            field.id = 'translation-field-{{ $row["number"] }}';
                                                        }
                                                    });
                                                </script>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-muted fs-5">
                        ทั้งหมด {{ $homeRows->count() }} รายการ
                    </div>

                    <div class="translation-footer-bar">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('frontend.translations.index') }}" class="btn btn-outline-secondary px-4">
                                ยกเลิก
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-script')
<script>
    function resetTranslationField(fieldId, defaultValue) {
        const target = document.getElementById(fieldId);
        if (target) {
            target.value = defaultValue || '';
        }
    }
</script>
@endsection
