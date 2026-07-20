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
  .order-summary-kiosk-preview {
    position: relative;
    min-height: 560px;
    overflow: hidden;
    background: linear-gradient(180deg, #dfeefa 0%, #dcebfa 16%, #dbeaf7 46%, #d7e8f7 100%);
    border-radius: 0;
  }

  .order-summary-kiosk-preview::before {
    content: "";
    position: absolute;
    left: -4%;
    right: -4%;
    top: 42px;
    height: 44px;
    background: #f6fbff;
    border-radius: 0 0 50% 50% / 0 0 100% 100%;
    opacity: .96;
  }

  .order-summary-kiosk-preview::after {
    content: "";
    position: absolute;
    left: -10%;
    right: -10%;
    bottom: -24px;
    height: 96px;
    background:
      radial-gradient(80% 100% at 18% 100%, rgba(87, 181, 231, .55) 0%, rgba(87,181,231,.55) 42%, transparent 43%) left bottom / 48% 100% no-repeat,
      radial-gradient(85% 100% at 50% 100%, rgba(117, 196, 238, .38) 0%, rgba(117,196,238,.38) 42%, transparent 43%) center bottom / 46% 100% no-repeat,
      radial-gradient(80% 100% at 82% 100%, rgba(87, 181, 231, .50) 0%, rgba(87,181,231,.50) 42%, transparent 43%) right bottom / 48% 100% no-repeat;
  }

  .order-summary-kiosk-inner {
    position: relative;
    z-index: 2;
    padding: 14px 26px 18px;
  }

  .kiosk-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: linear-gradient(90deg, #49b5f0 0%, #42aef0 35%, #4db2ee 100%);
    border-radius: 0;
    padding: 8px 18px;
    min-height: 54px;
  }

  .kiosk-topbar-left {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 180px;
  }

  .kiosk-home-pill {
    background: #fff;
    border-radius: 6px;
    color: #1183cf;
    font-size: 10px;
    font-weight: 700;
    padding: 6px 10px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,.08);
  }

  .kiosk-flag {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
  }

  .kiosk-flag.th { background: linear-gradient(180deg, #e73c3c 0 33%, #fff 33% 66%, #2958c6 66% 100%); }
  .kiosk-flag.gb { background: #2048b3; }
  .kiosk-flag.cn { background: #e11d2e; }

  .kiosk-brand-title {
    flex: 1;
    text-align: center;
    font-size: 18px;
    font-weight: 900;
    color: #482d96;
    text-shadow:
      -1px -1px 0 #fff,
       1px -1px 0 #fff,
      -1px  1px 0 #fff,
       1px  1px 0 #fff;
    letter-spacing: .1px;
    white-space: nowrap;
  }

  .kiosk-topbar-right {
    min-width: 120px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
  }

  .kiosk-logo-circle,
  .kiosk-logo-square {
    background: rgba(255,255,255,.95);
    color: #1e7db8;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-weight: 800;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
  }

  .kiosk-logo-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    font-size: 10px;
    line-height: 1.1;
  }

  .kiosk-logo-square {
    width: 48px;
    height: 44px;
    font-size: 10px;
    line-height: 1.05;
  }

  .kiosk-step-wrap {
    margin-top: 10px;
    display: flex;
    justify-content: center;
  }

  .kiosk-step-row {
    display: flex;
    align-items: center;
    gap: 0;
  }

  .kiosk-step-icon {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,.12);
  }

  .kiosk-step-icon.done {
    background: #4dc45e;
  }

  .kiosk-step-icon.current {
    width: 30px;
    height: 30px;
    background: #0f88d2;
    font-size: 14px;
  }

  .kiosk-step-line {
    width: 34px;
    height: 2px;
    background: #5fbbe9;
    position: relative;
  }

  .kiosk-step-line::after {
    content: "";
    position: absolute;
    left: 50%;
    top: -1px;
    width: 4px;
    height: 4px;
    margin-left: -2px;
    border-radius: 50%;
    background: #fff;
  }

  .kiosk-page-title {
    text-align: center;
    margin-top: 8px;
    margin-bottom: 18px;
    color: #111827;
    font-size: 16px;
    font-weight: 900;
  }

  .kiosk-summary-center {
    width: 246px;
    max-width: 100%;
    margin: 0 auto;
    background: rgba(255,255,255,.55);
    border-radius: 10px;
    padding: 7px;
  }

  .kiosk-summary-card {
    background: #fff;
    border-radius: 6px;
    padding: 8px;
  }

  .kiosk-summary-title {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #007bcb;
    font-weight: 900;
    font-size: 12px;
    margin-bottom: 8px;
  }

  .kiosk-summary-title i {
    font-size: 13px;
  }

  .kiosk-product-box {
    background: #fafcfb;
    border-radius: 6px;
    padding: 8px;
    display: grid;
    grid-template-columns: 42px 1fr;
    gap: 8px;
    align-items: start;
  }

  .kiosk-product-thumb {
    width: 40px;
    height: 58px;
    border-radius: 4px;
    background: linear-gradient(180deg, #f9fbff 0%, #ecf4ff 100%);
    border: 1px solid #e0ebf5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #72b9e4;
    font-size: 20px;
  }

  .kiosk-product-text {
    color: #2d6fb0;
    font-size: 9px;
    line-height: 1.35;
    margin-bottom: 5px;
  }

  .kiosk-product-row {
    display: flex;
    justify-content: space-between;
    gap: 6px;
    font-size: 10px;
    line-height: 1.45;
    color: #111827;
    font-weight: 700;
  }

  .kiosk-product-row strong {
    font-weight: 900;
    white-space: nowrap;
  }

  .kiosk-product-row.discount {
    color: #ff1b1b;
  }

  .kiosk-total-card {
    margin-top: 10px;
    background: #fff;
    border: 2px solid #8bcff2;
    border-radius: 7px;
    padding: 9px 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
  }

  .kiosk-total-left {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #007bcb;
    font-size: 11px;
    font-weight: 900;
  }

  .kiosk-total-icon {
    width: 22px;
    height: 22px;
    border-radius: 5px;
    background: #0f88d2;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
  }

  .kiosk-total-value {
    display: flex;
    align-items: baseline;
    gap: 3px;
    color: #0c88d2;
    font-weight: 900;
    line-height: 1;
  }

  .kiosk-total-value .number {
    font-size: 28px;
  }

  .kiosk-total-value .unit {
    font-size: 12px;
  }

  .kiosk-total-check {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #0f88d2;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    margin-left: 4px;
  }

  .kiosk-bottom-actions {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 10px;
  }

  .kiosk-back-btn,
  .kiosk-confirm-btn {
    border: 0;
    min-width: 94px;
    height: 31px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-weight: 800;
    box-shadow: 0 3px 8px rgba(0,0,0,.12);
  }

  .kiosk-back-btn {
    background: #fff;
    color: #0b82cd;
    font-size: 10px;
    padding: 0 12px;
  }

  .kiosk-confirm-btn {
    background: #0b8ad2;
    color: #fff;
    font-size: 15px;
    padding: 0 16px;
    min-width: 100px;
  }

  @media (max-width: 991.98px) {
    .kiosk-topbar {
      flex-wrap: wrap;
      justify-content: center;
      gap: 8px;
      padding: 10px;
    }

    .kiosk-topbar-left,
    .kiosk-topbar-right {
      min-width: auto;
      justify-content: center;
    }

    .kiosk-brand-title {
      order: 3;
      width: 100%;
      font-size: 16px;
      white-space: normal;
    }
  }

  @media (max-width: 575.98px) {
    .order-summary-kiosk-inner {
      padding: 12px;
    }

    .kiosk-bottom-actions {
      flex-direction: column;
      align-items: stretch;
    }

    .kiosk-back-btn,
    .kiosk-confirm-btn {
      width: 100%;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าสรุปรายการ</h5>
      <p class="text-muted mb-0">
        ตั้งค่าเฉพาะ Icon และปุ่มของหน้าสรุปรายการ
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
          <div class="form-text">ข้อความจริงจะดึงจากระบบแปลภาษา</div>
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
              <option value="{{ $iconClass }}" {{ $stepIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon รายการสินค้า</label>
          <select name="order_summary_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $orderSummaryIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ยอดรวมสุทธิ</label>
          <select name="net_total_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $netTotalIcon === $iconClass ? 'selected' : '' }}>
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
              <option value="{{ $iconClass }}" {{ $backButtonIcon === $iconClass ? 'selected' : '' }}>
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
              <option value="{{ $iconClass }}" {{ $confirmButtonIcon === $iconClass ? 'selected' : '' }}>
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
        ตัวอย่างหน้าจอให้ใกล้เคียงแบบในรูปอ้างอิง
      </p>
    </div>

    <div class="card-body p-0">
      <div class="order-summary-kiosk-preview">
        <div class="order-summary-kiosk-inner">
          <div class="kiosk-topbar">
            <div class="kiosk-topbar-left">
              <span class="kiosk-home-pill">
                <i class="icon-base ti tabler-home"></i>
                หน้าหลัก
              </span>

              <span class="kiosk-flag th"></span>
              <span class="kiosk-flag gb">EN</span>
              <span class="kiosk-flag cn">中</span>
            </div>

            <div class="kiosk-brand-title">
              ผู้เชี่ยวชาญการดูแลผ้าครบวงจร
            </div>

            <div class="kiosk-topbar-right">
              <span class="kiosk-logo-circle">Hygiene</span>
              <span class="kiosk-logo-square">IP<br>PASSIONMATELY</span>
            </div>
          </div>

          <div class="kiosk-step-wrap">
            <div class="kiosk-step-row">
              <span class="kiosk-step-icon done">
                <i class="icon-base ti tabler-check"></i>
              </span>

              <span class="kiosk-step-line"></span>

              <span class="kiosk-step-icon current">
                <i class="icon-base ti {{ $stepIcon }}"></i>
              </span>

              <span class="kiosk-step-line"></span>
              <span class="kiosk-step-line"></span>
              <span class="kiosk-step-line"></span>
            </div>
          </div>

          <div class="kiosk-page-title">สรุปรายการ</div>

          <div class="kiosk-summary-center">
            <div class="kiosk-summary-card">
              <div class="kiosk-summary-title">
                <i class="icon-base ti {{ $orderSummaryIcon }}"></i>
                <span>รายการสินค้า</span>
              </div>

              <div class="kiosk-product-box">
                <div class="kiosk-product-thumb">
                  <i class="icon-base ti tabler-bottle"></i>
                </div>

                <div>
                  <div class="kiosk-product-text">
                    ไฮยีน เอ็กซ์เพิร์ทแคร์ น้ำยาปรับผ้านุ่ม กลิ่น
                    มิลค์กี้ แคร์ สีฟ้า ขนาด 1,250 มล.
                  </div>

                  <div class="kiosk-product-row">
                    <span>จำนวน 1 ถุง</span>
                    <strong>115 บาท</strong>
                  </div>

                  <div class="kiosk-product-row discount">
                    <span>ส่วนลดโปรโมชั่น</span>
                    <strong>0 บาท</strong>
                  </div>
                </div>
              </div>
            </div>

            <div class="kiosk-total-card">
              <div class="kiosk-total-left">
                <span class="kiosk-total-icon">
                  <i class="icon-base ti {{ $netTotalIcon }}"></i>
                </span>
                <span>ยอดรวมสุทธิ</span>
              </div>

              <div style="display:flex; align-items:center; gap:6px;">
                <div class="kiosk-total-value">
                  <span class="number">115</span>
                  <span class="unit">บาท</span>
                </div>

                <span class="kiosk-total-check">
                  <i class="icon-base ti tabler-check"></i>
                </span>
              </div>
            </div>
          </div>

          <div class="kiosk-bottom-actions">
            @if ($showBackButton)
              <button type="button" class="kiosk-back-btn">
                <i class="icon-base ti {{ $backButtonIcon }}"></i>
                <span>ย้อนกลับ</span>
              </button>
            @endif

            @if ($showConfirmButton)
              <button type="button" class="kiosk-confirm-btn">
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
