@php
    $settings = $page->settings_json ?? [];

    $stepIcons = [
        'tabler-bottle' => 'Bottle',
        'tabler-list-details' => 'List Details',
        'tabler-clipboard-list' => 'Clipboard List',
        'tabler-receipt' => 'Receipt',
        'tabler-shopping-cart' => 'Shopping Cart',
        'tabler-check' => 'Check',
        'tabler-circle-check' => 'Circle Check',
    ];

    $buttonIcons = [
        'tabler-arrow-left' => 'Arrow Left',
        'tabler-chevron-left' => 'Chevron Left',
        'tabler-arrow-right' => 'Arrow Right',
        'tabler-chevron-right' => 'Chevron Right',
        'tabler-shopping-bag' => 'Shopping Bag',
        'tabler-shopping-cart' => 'Shopping Cart',
        'tabler-basket' => 'Basket',
        'tabler-bottle' => 'Bottle',
        'tabler-wallet' => 'Wallet',
        'tabler-credit-card' => 'Credit Card',
        'tabler-receipt' => 'Receipt',
        'tabler-discount' => 'Discount',
        'tabler-check' => 'Check',
        'tabler-circle-check' => 'Circle Check',
    ];

    $stepIcon = old(
        'step_icon',
        $settings['step_icon'] ?? 'tabler-bottle'
    );

    $orderSummaryIcon = old(
        'order_summary_icon',
        $settings['order_summary_icon'] ?? 'tabler-shopping-bag'
    );

    $netTotalIcon = old(
        'net_total_icon',
        $settings['net_total_icon'] ?? 'tabler-wallet'
    );

    $backButtonIcon = old(
        'back_button_icon',
        $settings['back_button_icon'] ?? 'tabler-chevron-left'
    );

    $confirmButtonIcon = old(
        'confirm_button_icon',
        $settings['confirm_button_icon'] ?? 'tabler-chevron-right'
    );

    $showBackButton = (bool) old(
        'show_back_button',
        $settings['show_back_button'] ?? true
    );

    $showConfirmButton = (bool) old(
        'show_confirm_button',
        $settings['show_confirm_button'] ?? true
    );
@endphp

<style>
    .summary-page-preview {
        position: relative;
        min-height: 520px;
        overflow: hidden;
        border-radius: 0;
        padding: 28px 24px 22px;
        background:
            radial-gradient(circle at 12% 61%, rgba(255,255,255,.8) 0 16px, transparent 17px),
            radial-gradient(circle at 16% 74%, rgba(255,255,255,.7) 0 9px, transparent 10px),
            radial-gradient(circle at 91% 59%, rgba(255,255,255,.78) 0 20px, transparent 21px),
            radial-gradient(circle at 87% 71%, rgba(255,255,255,.7) 0 10px, transparent 11px),
            linear-gradient(180deg, #dff4ff 0%, #eef9ff 74%, #bce6fb 100%);
    }

    .summary-page-preview::after {
        content: "";
        position: absolute;
        left: -5%;
        right: -5%;
        bottom: -34px;
        height: 90px;
        border-radius: 50% 50% 0 0;
        background: rgba(87, 184, 234, .28);
        transform: rotate(-1deg);
    }

    .summary-preview-inner {
        position: relative;
        z-index: 2;
    }

    .summary-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin-bottom: 10px;
    }

    .summary-progress-dot {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: #168fd4;
        font-size: 14px;
        box-shadow: 0 3px 7px rgba(0, 116, 190, .2);
        flex: 0 0 auto;
    }

    .summary-progress-dot.done {
        background: #34c759;
    }

    .summary-progress-line {
        width: 44px;
        height: 2px;
        background: #69bce8;
        position: relative;
    }

    .summary-progress-line::after {
        content: "";
        position: absolute;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #fff;
        top: -1px;
        left: 50%;
    }

    .summary-preview-title {
        text-align: center;
        font-weight: 800;
        color: #111827;
        font-size: 18px;
        margin-bottom: 38px;
    }

    .summary-box-wrap {
        width: 330px;
        max-width: 100%;
        margin: 0 auto;
        border-radius: 12px;
        padding: 10px;
        background: rgba(255,255,255,.64);
        box-shadow: 0 6px 18px rgba(40, 136, 195, .10);
    }

    .summary-list-card {
        background: #fff;
        border-radius: 8px;
        padding: 8px 10px 10px;
    }

    .summary-list-title {
        color: #0078c9;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 7px;
    }

    .summary-product-row {
        display: grid;
        grid-template-columns: 50px 1fr;
        gap: 8px;
        align-items: start;
    }

    .summary-product-image {
        width: 48px;
        height: 70px;
        border-radius: 6px;
        background: #f4f8fb;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7fbce2;
        font-size: 26px;
        overflow: hidden;
    }

    .summary-product-name {
        color: #0076c6;
        font-size: 10px;
        line-height: 1.25;
        margin-bottom: 5px;
    }

    .summary-product-detail {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        color: #111827;
        font-size: 11px;
        line-height: 1.45;
    }

    .summary-product-detail strong {
        font-weight: 800;
        white-space: nowrap;
    }

    .summary-product-detail.discount {
        color: #ff1f1f;
        font-weight: 700;
    }

    .summary-total-card {
        margin-top: 8px;
        min-height: 54px;
        border: 2px solid #b6e1fb;
        background: #fff;
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .summary-total-label {
        color: #0078c9;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .summary-total-icon {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        background: #168fd4;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .summary-total-value {
        color: #0084d8;
        font-size: 28px;
        line-height: 1;
        font-weight: 900;
        white-space: nowrap;
    }

    .summary-total-value small {
        font-size: 12px;
        font-weight: 800;
    }

    .summary-footer-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        margin-top: 38px;
    }

    .summary-back-btn,
    .summary-confirm-btn {
        border: 0;
        border-radius: 6px;
        min-width: 112px;
        min-height: 38px;
        padding: 7px 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 4px 10px rgba(0, 0, 0, .10);
    }

    .summary-back-btn {
        background: #fff;
        color: #0b80c9;
    }

    .summary-confirm-btn {
        background: #0b8ed6;
        color: #fff;
        font-size: 16px;
        min-width: 124px;
    }

    @media (max-width: 767.98px) {
        .summary-page-preview {
            min-height: auto;
        }

        .summary-footer-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .summary-back-btn,
        .summary-confirm-btn {
            width: 100%;
        }
    }
</style>

<div class="col-lg-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1">ตั้งค่าหน้าสรุปรายการ</h5>
            <p class="text-muted mb-0">
                ตั้งค่า Icon ของหน้าสรุปรายการที่ลูกค้าเลือก
            </p>
        </div>

        <div class="card-body">
            <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">
                        ชื่อหน้า <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $page->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Translation Key หัวข้อหน้า</label>
                    <input
                        type="text"
                        value="order_summary_page.title"
                        class="form-control"
                        readonly
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">หมายเหตุ</label>
                    <textarea
                        name="remark"
                        rows="3"
                        class="form-control @error('remark') is-invalid @enderror"
                    >{{ old('remark', $page->remark) }}</textarea>
                </div>

                <hr class="my-4">

                <h6 class="mb-3">Icon หน้าสรุปรายการ</h6>

                <div class="mb-3">
                    <label class="form-label">Icon Step ของหน้านี้</label>
                    <select name="step_icon" class="form-select">
                        @foreach ($stepIcons as $iconClass => $iconLabel)
                            <option
                                value="{{ $iconClass }}"
                                {{ $stepIcon === $iconClass ? 'selected' : '' }}
                            >
                                {{ $iconLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Icon รายการสินค้า</label>
                    <select name="order_summary_icon" class="form-select">
                        @foreach ($buttonIcons as $iconClass => $iconLabel)
                            <option
                                value="{{ $iconClass }}"
                                {{ $orderSummaryIcon === $iconClass ? 'selected' : '' }}
                            >
                                {{ $iconLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Icon ยอดรวมสุทธิ</label>
                    <select name="net_total_icon" class="form-select">
                        @foreach ($buttonIcons as $iconClass => $iconLabel)
                            <option
                                value="{{ $iconClass }}"
                                {{ $netTotalIcon === $iconClass ? 'selected' : '' }}
                            >
                                {{ $iconLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-4">

                <h6 class="mb-3">ปุ่มด้านล่าง</h6>

                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="show_back_button" value="0">
                    <input
                        type="checkbox"
                        name="show_back_button"
                        value="1"
                        id="show_back_button"
                        class="form-check-input"
                        {{ $showBackButton ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="show_back_button">
                        แสดงปุ่มย้อนกลับ
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Icon ปุ่มย้อนกลับ</label>
                    <select name="back_button_icon" class="form-select">
                        @foreach ($buttonIcons as $iconClass => $iconLabel)
                            <option
                                value="{{ $iconClass }}"
                                {{ $backButtonIcon === $iconClass ? 'selected' : '' }}
                            >
                                {{ $iconLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input
                    type="hidden"
                    name="back_button_action"
                    value="{{ old(
                        'back_button_action',
                        $settings['back_button_action'] ?? 'select_product_page'
                    ) }}"
                >

                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="show_confirm_button" value="0">
                    <input
                        type="checkbox"
                        name="show_confirm_button"
                        value="1"
                        id="show_confirm_button"
                        class="form-check-input"
                        {{ $showConfirmButton ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="show_confirm_button">
                        แสดงปุ่มตกลง
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Icon ปุ่มตกลง</label>
                    <select name="confirm_button_icon" class="form-select">
                        @foreach ($buttonIcons as $iconClass => $iconLabel)
                            <option
                                value="{{ $iconClass }}"
                                {{ $confirmButtonIcon === $iconClass ? 'selected' : '' }}
                            >
                                {{ $iconLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input
                    type="hidden"
                    name="confirm_button_action"
                    value="{{ old(
                        'confirm_button_action',
                        $settings['confirm_button_action'] ?? 'promotion_page'
                    ) }}"
                >

                <button type="submit" class="btn btn-primary w-100">
                    <i class="icon-base ti tabler-device-floppy me-1"></i>
                    บันทึกหน้าสรุปรายการ
                </button>
            </form>
        </div>
    </div>
</div>

<div class="col-lg-8">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1">Preview หน้าสรุปรายการ</h5>
            <p class="text-muted mb-0">
                ตัวอย่างตามหน้าจอ Kiosk จริง
            </p>
        </div>

        <div class="card-body p-0">
            <div class="summary-page-preview">
                <div class="summary-preview-inner">
                    <div class="summary-progress">
                        <span class="summary-progress-dot done">
                            <i class="icon-base ti tabler-check"></i>
                        </span>

                        <span class="summary-progress-line"></span>

                        <span class="summary-progress-dot">
                            <i class="icon-base ti {{ $stepIcon }}"></i>
                        </span>

                        <span class="summary-progress-line"></span>

                        <span class="summary-progress-dot" style="opacity: .25;">
                            <i class="icon-base ti tabler-minus"></i>
                        </span>
                    </div>

                    <div class="summary-preview-title">
                        สรุปรายการ
                    </div>

                    <div class="summary-box-wrap">
                        <div class="summary-list-card">
                            <div class="summary-list-title">
                                <i class="icon-base ti {{ $orderSummaryIcon }}"></i>
                                <span>รายการสินค้า</span>
                            </div>

                            <div class="summary-product-row">
                                <div class="summary-product-image">
                                    <i class="icon-base ti tabler-bottle"></i>
                                </div>

                                <div>
                                    <div class="summary-product-name">
                                        ไฮยีน เอ็กซ์เพิร์ทแคร์ น้ำยาปรับผ้านุ่ม
                                        กลิ่นมิลค์กี้ แคร์ ขนาด 1,250 มล.
                                    </div>

                                    <div class="summary-product-detail">
                                        <span>จำนวน 1 ถุง</span>
                                        <strong>115 บาท</strong>
                                    </div>

                                    <div class="summary-product-detail discount">
                                        <span>ส่วนลดโปรโมชั่น</span>
                                        <strong>0 บาท</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-total-card">
                            <div class="summary-total-label">
                                <span class="summary-total-icon">
                                    <i class="icon-base ti {{ $netTotalIcon }}"></i>
                                </span>
                                <span>ยอดรวมสุทธิ</span>
                            </div>

                            <div class="summary-total-value">
                                115 <small>บาท</small>
                            </div>
                        </div>
                    </div>

                    <div class="summary-footer-buttons">
                        @if ($showBackButton)
                            <button type="button" class="summary-back-btn">
                                <i class="icon-base ti {{ $backButtonIcon }}"></i>
                                <span>ย้อนกลับ</span>
                            </button>
                        @endif

                        @if ($showConfirmButton)
                            <button type="button" class="summary-confirm-btn">
                                <span>ตกลง</span>
                                <i class="icon-base ti {{ $confirmButtonIcon }}"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
