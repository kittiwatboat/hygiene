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
  .order-summary-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 24px 26px 20px;
    overflow: hidden;
    min-height: 520px;
  }

  .order-summary-preview-title {
    text-align: center;
    color: #6f63f6;
    font-weight: 800;
    font-size: 20px;
    margin-bottom: 2px;
  }

  .order-summary-preview-subtitle {
    text-align: center;
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 18px;
  }

  .order-summary-step {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
  }

  .order-summary-step-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: #6f63f6;
    font-size: 18px;
    flex: 0 0 auto;
  }

  .order-summary-step-circle.done {
    background: #26c875;
  }

  .order-summary-step-circle.pending {
    background: #e8e8ed;
    color: #9295a0;
  }

  .order-summary-step-line {
    width: 52px;
    height: 2px;
    background: #6fbff0;
  }

  .order-summary-layout {
    display: flex;
    justify-content: center;
  }

  .order-summary-panel {
    width: 390px;
    max-width: 100%;
    background: rgba(255, 255, 255, .72);
    border-radius: 12px;
    padding: 16px;
  }

  .order-summary-section {
    background: #fff;
    border-radius: 10px;
    padding: 14px;
  }

  .order-summary-section-title {
    color: #0077c9;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
  }

  .order-summary-product {
    display: grid;
    grid-template-columns: 68px 1fr;
    gap: 12px;
    align-items: center;
    background: #f8fbff;
    border-radius: 8px;
    padding: 10px;
  }

  .order-summary-product-image {
    width: 68px;
    height: 88px;
    border-radius: 8px;
    border: 1px solid #e3eef7;
    background: #fff;
    color: #72b9e5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
  }

  .order-summary-product-name {
    color: #0877c9;
    font-size: 12px;
    line-height: 1.4;
    margin-bottom: 7px;
  }

  .order-summary-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    font-size: 13px;
    margin-top: 5px;
  }

  .order-summary-row strong {
    color: #16181d;
    white-space: nowrap;
  }

  .order-summary-row.discount,
  .order-summary-row.discount strong {
    color: #ff2626;
  }

  .order-summary-total {
    margin-top: 12px;
    background: #fff;
    border: 2px solid #9bd8f7;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .order-summary-total-label {
    color: #0077c9;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .order-summary-total-icon {
    width: 30px;
    height: 30px;
    background: #078bd7;
    color: #fff;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .order-summary-total-value {
    color: #0086d8;
    font-weight: 900;
    font-size: 30px;
    line-height: 1;
    white-space: nowrap;
  }

  .order-summary-total-value small {
    font-size: 13px;
  }

  .order-summary-footer {
    display: flex;
    justify-content: center;
    gap: 18px;
    margin-top: 18px;
  }

  .order-summary-back-button,
  .order-summary-confirm-button {
    border: 0;
    border-radius: 10px;
    padding: 12px 22px;
    min-width: 150px;
    min-height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
  }

  .order-summary-back-button {
    background: #fff;
    color: #0877c9;
  }

  .order-summary-confirm-button {
    background: #0877c9;
    color: #fff;
    font-size: 17px;
  }

  @media (max-width: 991.98px) {
    .order-summary-footer {
      flex-direction: column;
    }

    .order-summary-back-button,
    .order-summary-confirm-button {
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
        ตัวอย่าง Layout เท่านั้น รายการสินค้าและยอดเงินจริงจะดึงจากระบบ
      </p>
    </div>

    <div class="card-body">
      <div class="order-summary-preview">
        <div class="order-summary-preview-title">
          order_summary_page.title
        </div>

        <div class="order-summary-preview-subtitle">
          order_summary_page.subtitle
        </div>

        <div class="order-summary-step">
          <span class="order-summary-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span class="order-summary-step-line"></span>

          <span class="order-summary-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span class="order-summary-step-line"></span>

          <span class="order-summary-step-circle">
            <i class="icon-base ti {{ $stepIcon }}"></i>
          </span>

          <span class="order-summary-step-line"></span>

          <span class="order-summary-step-circle pending">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="order-summary-layout">
          <div class="order-summary-panel">
            <div class="order-summary-section">
              <div class="order-summary-section-title">
                <i class="icon-base ti {{ $orderSummaryIcon }}"></i>
                <span>order_summary_page.order_list</span>
              </div>

              <div class="order-summary-product">
                <div class="order-summary-product-image">
                  <i class="icon-base ti tabler-bottle"></i>
                </div>

                <div>
                  <div class="order-summary-product-name">
                    ไฮยีน เอ็กซ์เพิร์ทแคร์ น้ำยาปรับผ้านุ่ม
                    กลิ่นมิลค์กี้ แคร์ ขนาด 1,250 มล.
                  </div>

                  <div class="order-summary-row">
                    <span>จำนวน 1 ถุง</span>
                    <strong>115 บาท</strong>
                  </div>

                  <div class="order-summary-row discount">
                    <span>ส่วนลดโปรโมชั่น</span>
                    <strong>0 บาท</strong>
                  </div>
                </div>
              </div>
            </div>

            <div class="order-summary-total">
              <div class="order-summary-total-label">
                <span class="order-summary-total-icon">
                  <i class="icon-base ti {{ $netTotalIcon }}"></i>
                </span>
                <span>order_summary_page.net_total</span>
              </div>

              <div class="order-summary-total-value">
                115 <small>บาท</small>
              </div>
            </div>
          </div>
        </div>

        <div class="order-summary-footer">
          @if ($showBackButton)
            <button type="button" class="order-summary-back-button">
              <i class="icon-base ti {{ $backButtonIcon }}"></i>
              <span>order_summary_page.back_button</span>
            </button>
          @endif

          @if ($showConfirmButton)
            <button type="button" class="order-summary-confirm-button">
              <span>order_summary_page.confirm_button</span>
              <i class="icon-base ti {{ $confirmButtonIcon }}"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
