@extends('layouts/layoutMaster')

@section('title', 'แก้ไขข้อความแปลหน้าตู้')

@section('page-style')
    <style>
        .translation-page-card {
            border: 1px solid #e9e7fd;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
        }

        .translation-preview-stage {
            max-width: 920px;
            margin: 0 auto;
        }

        .translation-preview-wrap {
            position: relative;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .translation-preview-image {
            display: block;
            width: 100%;
            height: auto;
        }

        .translation-preview-pin {
            position: absolute;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            background: #ff3b30;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
            box-shadow: 0 8px 20px rgba(255, 59, 48, .22);
            border: 3px solid #fff;
            z-index: 5;
        }

        .translation-preview-highlight {
            position: absolute;
            border: 3px solid #ff4d4f;
            border-radius: 10px;
            z-index: 4;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, .65) inset;
        }

        /* HOME */
        .home-pin-button {
            bottom: 8.5%;
            left: 25%;
        }

        .home-highlight-button {
            bottom: 3%;
            left: 35%;
            width: 28.5%;
            height: 13.5%;
        }

        /* SELECT PRODUCT */
        .select-pin-detergent {
            top: 19%;
            left: 31%;
        }

        .select-highlight-detergent {
            top: 23%;
            left: 2.5%;
            width: 31%;
            height: 15%;
        }

        .select-pin-softener {
            top: 34%;
            left: 31%;
        }

        .select-highlight-softener {
            top: 39%;
            left: 2.5%;
            width: 31%;
            height: 15%;
        }

        .select-pin-volume {
            top: 23%;
            right: 5%;
        }

        .select-highlight-volume {
            top: 28%;
            right: 8%;
            width: 6%;
            height: 9.5%;
        }

        .select-pin-currency {
            top: 55%;
            right: 4.5%;
        }

        .select-highlight-currency {
            top: 61.5%;
            right: 7.8%;
            width: 7%;
            height: 9%;
        }

        .select-pin-back {
            bottom: 10%;
            left: 15%;
        }

        .select-highlight-back {
            bottom: 2%;
            left: 1%;
            width: 17%;
            height: 11%;
        }

        .select-pin-confirm {
            bottom: 10%;
            right: 15%;
        }

        .select-highlight-confirm {
            bottom: 2%;
            right: 1%;
            width: 17%;
            height: 11%;
        }

        .translation-legend-box {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            background: #fff;
            padding: 18px 16px;
            height: 100%;
            font-size: 11px;
            line-height: 1.4;
        }

        .translation-legend-box h5 {
            font-size: 14px;
            line-height: 1.25;
            margin-bottom: 12px !important;
        }

        .translation-legend-box p {
            font-size: 10px;
            line-height: 1.45;
            margin-bottom: 14px !important;
        }

        .translation-legend-box .fw-semibold {
            font-size: 11px;
            line-height: 1.3;
        }

        .translation-legend-box .text-muted {
            font-size: 10px;
            line-height: 1.3;
        }

        .legend-number {
            width: 28px;
            height: 28px;
            min-width: 28px;
            border-radius: 999px;
            background: #ff3b30;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            margin-right: 7px;
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
            font-size: .9rem;
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
            background: rgba(255, 255, 255, .94);
            backdrop-filter: blur(4px);
            border-top: 1px solid #ebecef;
            padding: 16px 0;
            margin-top: 20px;
        }

        .page-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
@endsection

@section('content')
    @php
        $keyMap = [
            'HOME' => [
                [
                    'number' => 1,
                    'key' => 'home.select_button',
                    'title' => 'ข้อความบนปุ่ม "เลือกเติมน้ำยา"',
                    'description' => 'Select Refill Button',
                ],
            ],

            'SELECT_PRODUCT' => [
                [
                    'number' => 1,
                    'key' => 'select_product_page.detergent_option',
                    'title' => 'ตัวเลือกน้ำยาซักผ้า',
                    'description' => 'Detergent Option',
                ],
                [
                    'number' => 2,
                    'key' => 'select_product_page.softener_option',
                    'title' => 'ตัวเลือกน้ำยาปรับผ้านุ่ม',
                    'description' => 'Softener Option',
                ],
                [
                    'number' => 3,
                    'key' => 'select_product_page.volume_unit',
                    'title' => 'หน่วยปริมาตร',
                    'description' => 'Volume Unit',
                ],
                [
                    'number' => 4,
                    'key' => 'select_product_page.currency_unit',
                    'title' => 'หน่วยราคา',
                    'description' => 'Currency Unit',
                ],
                [
                    'number' => 5,
                    'key' => 'select_product_page.home_button',
                    'title' => 'ข้อความปุ่มย้อนกลับ',
                    'description' => 'Back Button',
                ],
                [
                    'number' => 6,
                    'key' => 'select_product_page.confirm_button',
                    'title' => 'ข้อความปุ่มตกลง',
                    'description' => 'Confirm Button',
                ],
            ],

            'ORDER_SUMMARY' => [
                [
                    'number' => 1,
                    'key' => 'order_summary_page.title',
                    'title' => 'หัวข้อหน้าสรุปรายการ',
                    'description' => 'Summary Title',
                ],
                [
                    'number' => 2,
                    'key' => 'order_summary_page.product_list_title',
                    'title' => 'หัวข้อรายการสินค้า',
                    'description' => 'Product List Title',
                ],
                [
                    'number' => 3,
                    'key' => 'order_summary_page.quantity_label',
                    'title' => 'ข้อความจำนวนสินค้า',
                    'description' => 'Quantity Label',
                ],
                [
                    'number' => 4,
                    'key' => 'order_summary_page.promotion_discount_label',
                    'title' => 'ข้อความส่วนลดโปรโมชั่น',
                    'description' => 'Promotion Discount Label',
                ],
                [
                    'number' => 5,
                    'key' => 'order_summary_page.net_total_label',
                    'title' => 'ข้อความยอดรวมสุทธิ',
                    'description' => 'Net Total Label',
                ],
                [
                    'number' => 6,
                    'key' => 'order_summary_page.currency_unit',
                    'title' => 'หน่วยราคา',
                    'description' => 'Currency Unit',
                ],
                [
                    'number' => 7,
                    'key' => 'order_summary_page.back_button',
                    'title' => 'ข้อความปุ่มย้อนกลับ',
                    'description' => 'Back Button',
                ],
                [
                    'number' => 8,
                    'key' => 'order_summary_page.confirm_button',
                    'title' => 'ข้อความปุ่มตกลง',
                    'description' => 'Confirm Button',
                ],
            ],

            'PHONE_VERIFY' => [
                [
                    'number' => 1,
                    'key' => 'phone_verify_page.title',
                    'title' => 'หัวข้อกรอกเบอร์โทรศัพท์',
                    'description' => 'Phone Page Title',
                ],
                [
                    'number' => 2,
                    'key' => 'phone_verify_page.phone_label',
                    'title' => 'ข้อความกรุณากรอกหมายเลขโทรศัพท์',
                    'description' => 'Phone Number Label',
                ],
                [
                    'number' => 3,
                    'key' => 'phone_verify_page.send_otp_button',
                    'title' => 'ข้อความปุ่มส่ง OTP',
                    'description' => 'Send OTP Button',
                ],
                [
                    'number' => 4,
                    'key' => 'phone_verify_page.resend_otp_button',
                    'title' => 'ข้อความปุ่มขอ OTP ใหม่',
                    'description' => 'Resend OTP Button',
                ],
                [
                    'number' => 5,
                    'key' => 'phone_verify_page.confirm_button',
                    'title' => 'ข้อความปุ่มยืนยัน',
                    'description' => 'Confirm Button',
                ],
            ],
        ];

        $translationKeysByKey = collect($translationKeys)->keyBy('key');

        $buildRows = function ($items) use ($translationKeysByKey, $translations) {
            return collect($items)->map(function ($item) use ($translationKeysByKey, $translations) {
                $translationKey = $translationKeysByKey->get($item['key']);
                $translation = $translationKey ? $translations[$translationKey->id] ?? null : null;

                return [
                    ...$item,
                    'translationKey' => $translationKey,
                    'translation' => $translation,
                    'default_value' => $translationKey->default_value ?? '',
                    'current_value' => old(
                        'translations.' . ($translationKey->id ?? 'missing_' . $item['key']),
                        $translation->value ?? '',
                    ),
                ];
            });
        };

        $homeRows = $buildRows($keyMap['HOME']);
        $selectRows = $buildRows($keyMap['SELECT_PRODUCT']);
        $summaryRows = $buildRows($keyMap['ORDER_SUMMARY']);
        $phoneRows = $buildRows($keyMap['PHONE_VERIFY']);

        $homePreviewImage = asset('assets/img/frontend/home/home-translation-preview.png');
        $selectPreviewImage = asset('assets/img/frontend/product/select-product-translation-preview.png');
        $summaryPreviewImage = asset('assets/img/frontend-preview/order-summary-preview-only.png');
        $phonePreviewImage = asset('assets/img/frontend-preview/phone-otp-4screens-preview.png');
    @endphp

    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h4 class="mb-2">ข้อความแปลหน้าตู้</h4>

                        <div class="text-muted">
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

                {{-- =========================================================
                HOME
            ========================================================== --}}
                <div class="card translation-page-card mb-4">
                    <div class="card-header">
                        <div class="page-section-title">
                            <span class="badge bg-label-primary">HOME</span>
                            <h4 class="mb-0">หน้าแรก</h4>
                        </div>

                        <div class="text-muted mt-2">
                            แก้ไขเฉพาะข้อความบนปุ่มเลือกเติมน้ำยา
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-lg-9">
                                <div class="translation-preview-stage">
                                    <div class="translation-preview-wrap">
                                        <img src="{{ $homePreviewImage }}" alt="ตัวอย่างหน้า HOME"
                                            class="translation-preview-image">

                                        <div class="translation-preview-pin home-pin-button">1</div>
                                        <div class="translation-preview-highlight home-highlight-button"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="translation-legend-box">
                                    <h5 class="fw-bold">หมายเหตุ</h5>

                                    <p class="text-muted">
                                        เลขกำกับในภาพ คือข้อความบนปุ่มที่สามารถแก้ไขได้
                                    </p>

                                    <div class="d-flex align-items-start">
                                        <span class="legend-number">1</span>
                                        <div>
                                            <div class="fw-semibold">ข้อความบนปุ่ม</div>
                                            <div class="text-muted mt-1">(home.select_button)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        @include('content.pages.frontend.translations.partials.translation-table', [
                            'rows' => $homeRows,
                        ])
                    </div>
                </div>

                {{-- =========================================================
                SELECT PRODUCT
            ========================================================== --}}
                <div class="card translation-page-card">
                    <div class="card-header">
                        <div class="page-section-title">
                            <span class="badge bg-label-info">SELECT PRODUCT</span>
                            <h4 class="mb-0">หน้าเลือกน้ำยา</h4>
                        </div>

                        <div class="text-muted mt-2">
                            แก้ไขตัวเลือกน้ำยา หน่วยปริมาตร หน่วยราคา และข้อความปุ่ม
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-lg-9">
                                <div class="translation-preview-stage">
                                    <div class="translation-preview-wrap">
                                        <img src="{{ $selectPreviewImage }}" alt="ตัวอย่างหน้าเลือกน้ำยา"
                                            class="translation-preview-image">

                                        <div class="translation-preview-pin select-pin-detergent">1</div>
                                        <div class="translation-preview-highlight select-highlight-detergent"></div>

                                        <div class="translation-preview-pin select-pin-softener">2</div>
                                        <div class="translation-preview-highlight select-highlight-softener"></div>

                                        <div class="translation-preview-pin select-pin-volume">3</div>
                                        <div class="translation-preview-highlight select-highlight-volume"></div>

                                        <div class="translation-preview-pin select-pin-currency">4</div>
                                        <div class="translation-preview-highlight select-highlight-currency"></div>

                                        <div class="translation-preview-pin select-pin-back">5</div>
                                        <div class="translation-preview-highlight select-highlight-back"></div>

                                        <div class="translation-preview-pin select-pin-confirm">6</div>
                                        <div class="translation-preview-highlight select-highlight-confirm"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="translation-legend-box">
                                    <h5 class="fw-bold">หมายเหตุ</h5>

                                    <p class="text-muted">
                                        เลขกำกับในภาพ คือข้อความบนปุ่มที่สามารถแก้ไขได้
                                    </p>

                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex align-items-start">
                                            <span class="legend-number">1</span>
                                            <div>
                                                <div class="fw-semibold">น้ำยาซักผ้า</div>
                                                <div class="text-muted mt-1">(select_product_page.detergent_option)</div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start">
                                            <span class="legend-number">2</span>
                                            <div>
                                                <div class="fw-semibold">น้ำยาปรับผ้านุ่ม</div>
                                                <div class="text-muted mt-1">(select_product_page.softener_option)</div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start">
                                            <span class="legend-number">3</span>
                                            <div>
                                                <div class="fw-semibold">หน่วย มล.</div>
                                                <div class="text-muted mt-1">(select_product_page.volume_unit)</div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start">
                                            <span class="legend-number">4</span>
                                            <div>
                                                <div class="fw-semibold">หน่วย บาท</div>
                                                <div class="text-muted mt-1">(select_product_page.currency_unit)</div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start">
                                            <span class="legend-number">5</span>
                                            <div>
                                                <div class="fw-semibold">ปุ่มย้อนกลับ</div>
                                                <div class="text-muted mt-1">(select_product_page.home_button)</div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start">
                                            <span class="legend-number">6</span>
                                            <div>
                                                <div class="fw-semibold">ปุ่มตกลง</div>
                                                <div class="text-muted mt-1">(select_product_page.confirm_button)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        @include('content.pages.frontend.translations.partials.translation-table', [
                            'rows' => $selectRows,
                        ])
                    </div>
                </div>


                {{-- =========================================================
                ORDER SUMMARY
                ========================================================== --}}
                <div class="card translation-page-card mt-4">
                    <div class="card-header">
                        <div class="page-section-title">
                            <span class="badge bg-label-warning">ORDER SUMMARY</span>
                            <h4 class="mb-0">หน้าสรุปรายการ</h4>
                        </div>

                        <div class="text-muted mt-2">
                            แก้ไขหัวข้อ รายการสินค้า จำนวน ส่วนลด ยอดรวม หน่วยราคา และข้อความปุ่ม
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-lg-9">
                                <div class="translation-preview-stage">
                                    <div class="translation-preview-wrap">
                                        <img src="{{ $summaryPreviewImage }}"
                                            alt="ตัวอย่างหน้าสรุปรายการ"
                                            class="translation-preview-image">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="translation-legend-box">
                                    <h5 class="fw-bold">หมายเหตุ</h5>

                                    <p class="text-muted">
                                        เลขกำกับในภาพ คือข้อความที่สามารถแก้ไขได้
                                    </p>

                                    <div class="d-flex flex-column gap-3">
                                        @foreach ($summaryRows as $row)
                                            <div class="d-flex align-items-start">
                                                <span class="legend-number">{{ $row['number'] }}</span>
                                                <div>
                                                    <div class="fw-semibold">{{ $row['title'] }}</div>
                                                    <div class="text-muted mt-1">({{ $row['key'] }})</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        @include('content.pages.frontend.translations.partials.translation-table', [
                            'rows' => $summaryRows,
                        ])
                    </div>
                </div>


                {{-- =========================================================
                PHONE VERIFY
                ========================================================== --}}
                <div class="card translation-page-card mt-4">
                    <div class="card-header">
                        <div class="page-section-title">
                            <span class="badge bg-label-success">PHONE VERIFY</span>
                            <h4 class="mb-0">ยืนยันเบอร์โทรศัพท์ / OTP</h4>
                        </div>

                        <div class="text-muted mt-2">
                            แก้ไขหัวข้อกรอกเบอร์โทร ข้อความกำกับช่องเบอร์โทร ปุ่มส่ง OTP ปุ่มขอ OTP ใหม่ และปุ่มยืนยัน
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-lg-9">
                                <div class="translation-preview-stage">
                                    <div class="translation-preview-wrap">
                                        <img
                                            src="{{ $phonePreviewImage }}"
                                            alt="ตัวอย่างหน้ากรอกเบอร์โทรศัพท์ ยืนยัน OTP และผลลัพธ์ OTP"
                                            class="translation-preview-image"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="translation-legend-box">
                                    <h5 class="fw-bold">หมายเหตุ</h5>

                                    <p class="text-muted">
                                        เลขกำกับในภาพ คือข้อความที่สามารถแก้ไขได้
                                    </p>

                                    <div class="d-flex flex-column gap-3">
                                        @foreach ($phoneRows as $row)
                                            <div class="d-flex align-items-start">
                                                <span class="legend-number">{{ $row['number'] }}</span>
                                                <div>
                                                    <div class="fw-semibold">{{ $row['title'] }}</div>
                                                    <div class="text-muted mt-1">({{ $row['key'] }})</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        @include('content.pages.frontend.translations.partials.translation-table', [
                            'rows' => $phoneRows,
                        ])
                    </div>
                </div>

                <div class="translation-footer-bar">
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <div class="text-muted">
                            ทั้งหมด {{ $homeRows->count() + $selectRows->count() + $summaryRows->count() + $phoneRows->count() }} รายการ
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('frontend.translations.index') }}" class="btn btn-outline-secondary px-4">
                                ยกเลิก
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                <i class="icon-base ti tabler-device-floppy me-1"></i>
                                บันทึกการเปลี่ยนแปลง
                            </button>
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
