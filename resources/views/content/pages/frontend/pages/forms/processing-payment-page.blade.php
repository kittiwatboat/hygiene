@php
  $settings = $page->settings_json ?? [];

  $stepIcons = [
    'tabler-qrcode' => 'QR Code',
    'tabler-credit-card' => 'Credit Card',
    'tabler-wallet' => 'Wallet',
    'tabler-cash' => 'Cash',
    'tabler-receipt' => 'Receipt',
    'tabler-shopping-cart' => 'Cart',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
  ];

  $buttonIcons = [
    'tabler-home' => 'Home',
    'tabler-arrow-left' => 'Arrow Left',
    'tabler-chevron-left' => 'Chevron Left',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-credit-card' => 'Credit Card',
    'tabler-wallet' => 'Wallet',
    'tabler-receipt' => 'Receipt',
    'tabler-qrcode' => 'QR Code',
  ];
@endphp

<style>
  .processing-payment-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .processing-payment-content {
    display: grid;
    grid-template-columns: 38% 62%;
    gap: 18px;
    align-items: stretch;
  }

  .payment-summary-panel,
  .qr-payment-panel {
    background: rgba(255,255,255,.78);
    border-radius: 12px;
    padding: 16px;
  }

  .payment-section-title {
    color: #0075c9;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
  }

  .payment-product-card {
    background: #fff;
    border-radius: 10px;
    padding: 12px;
    display: grid;
    grid-template-columns: 64px 1fr;
    gap: 12px;
    margin-bottom: 12px;
  }

  .payment-product-img {
    width: 64px;
    height: 84px;
    border-radius: 8px;
    background: #f2f8ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7dbce8;
    font-size: 30px;
  }

  .payment-product-title {
    font-weight: 700;
    color: #0075c9;
    font-size: 13px;
  }

  .payment-product-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    margin-top: 6px;
  }

  .payment-net-card {
    background: #fff;
    border-radius: 10px;
    border: 2px solid #b9e2ff;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #0075c9;
    font-weight: 800;
  }

  .qr-payment-panel {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 260px;
  }

  .qr-box {
    background: #fff;
    border-radius: 12px;
    padding: 18px;
    width: 100%;
    max-width: 420px;
    text-align: center;
  }

  .qr-title {
    font-size: 13px;
    font-weight: 700;
    color: #1f2d3d;
    margin-bottom: 8px;
  }

  .qr-brand-box {
    width: 180px;
    height: 34px;
    background: #102f4d;
    color: #fff;
    border-radius: 4px;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
  }

  .qr-placeholder {
    width: 140px;
    height: 140px;
    background:
      linear-gradient(90deg, #111 10px, transparent 10px) 0 0 / 20px 20px,
      linear-gradient(#111 10px, transparent 10px) 0 0 / 20px 20px,
      #fff;
    border: 8px solid #fff;
    outline: 2px solid #111;
    margin: 0 auto 14px;
  }

  .qr-note {
    font-size: 12px;
    color: #333;
  }

  .qr-countdown {
    color: #ff3b30;
    font-weight: 800;
    margin-left: 8px;
  }

  .processing-payment-footer {
    display: grid;
    grid-template-columns: 1fr 1fr 1.2fr;
    gap: 18px;
    margin-top: 18px;
    align-items: center;
  }

  .processing-home-button,
  .processing-back-button,
  .processing-confirm-button {
    border: 0;
    border-radius: 10px;
    padding: 12px 18px;
    min-height: 54px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
  }

  .processing-home-button,
  .processing-back-button {
    background: #fff;
    color: #0877c9;
  }

  .processing-confirm-button {
    background: #0877c9;
    color: #fff;
    font-size: 18px;
  }

  @media (max-width: 991.98px) {
    .processing-payment-content {
      grid-template-columns: 1fr;
    }

    .processing-payment-footer {
      grid-template-columns: 1fr;
    }

    .processing-home-button,
    .processing-back-button,
    .processing-confirm-button {
      width: 100%;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้ารอชำระเงิน</h5>
      <p class="text-muted mb-0">
        ตั้งค่า icon และปุ่มของหน้ารอรับ QR / ข้อมูลชำระเงินจาก API
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
          <input type="text" value="processing_payment_page.title" class="form-control" readonly>
          <div class="form-text">ข้อความจริงจะดึงจากระบบแปลภาษา</div>
        </div>

        <div class="mb-3">
          <label class="form-label">หมายเหตุ</label>
          <textarea
            name="remark"
            rows="3"
            class="form-control @error('remark') is-invalid @enderror"
          >{{ old('remark', $page->remark) }}</textarea>

          @error('remark')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon หน้ารอชำระเงิน</h6>

        <div class="mb-3">
          <label class="form-label">Icon Step ของหน้านี้</label>

          <select name="step_icon" class="form-select">
            @php
              $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-qrcode');
            @endphp

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
            @php
              $orderSummaryIcon = old('order_summary_icon', $settings['order_summary_icon'] ?? 'tabler-shopping-cart');
            @endphp

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
            @php
              $netTotalIcon = old('net_total_icon', $settings['net_total_icon'] ?? 'tabler-wallet');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $netTotalIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon ปุ่มด้านล่าง</h6>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มหน้าหลัก</label>

          <select name="home_button_icon" class="form-select">
            @php
              $homeButtonIcon = old('home_button_icon', $settings['home_button_icon'] ?? 'tabler-home');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $homeButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="show_home_button" value="1">
        <input
          type="hidden"
          name="home_button_action"
          value="{{ old('home_button_action', $settings['home_button_action'] ?? 'first_page') }}"
        >

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มย้อนกลับ</label>

          <select name="back_button_icon" class="form-select">
            @php
              $backButtonIcon = old('back_button_icon', $settings['back_button_icon'] ?? 'tabler-chevron-left');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $backButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="show_back_button" value="1">
        <input
          type="hidden"
          name="back_button_action"
          value="{{ old('back_button_action', $settings['back_button_action'] ?? 'payment_page') }}"
        >

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มตกลง</label>

          <select name="confirm_button_icon" class="form-select">
            @php
              $confirmButtonIcon = old('confirm_button_icon', $settings['confirm_button_icon'] ?? 'tabler-chevron-right');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $confirmButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="show_confirm_button" value="1">
        <input
          type="hidden"
          name="confirm_button_action"
          value="{{ old('confirm_button_action', $settings['confirm_button_action'] ?? 'thank_you_page') }}"
        >

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้ารอชำระเงิน
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้ารอชำระเงิน</h5>
      <p class="text-muted mb-0">
        ตัวอย่าง layout เท่านั้น รูป QR / ข้อมูลจ่ายเงินจริงจะรับจาก API
      </p>
    </div>

    <div class="card-body">
      <div class="processing-payment-preview">
        <div class="text-center mb-3">
          <div class="fw-bold text-primary fs-5">
            processing_payment_page.title
          </div>
          <small class="text-muted">
            processing_payment_page.subtitle
          </small>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
          <span class="badge rounded-pill bg-success p-2"><i class="icon-base ti tabler-check"></i></span>
          <span style="width: 42px; height: 2px; background: #7dbce8;"></span>
          <span class="badge rounded-pill bg-success p-2"><i class="icon-base ti tabler-check"></i></span>
          <span style="width: 42px; height: 2px; background: #7dbce8;"></span>
          <span class="badge rounded-pill bg-success p-2"><i class="icon-base ti tabler-check"></i></span>
          <span style="width: 42px; height: 2px; background: #7dbce8;"></span>
          <span class="badge rounded-pill bg-success p-2"><i class="icon-base ti tabler-check"></i></span>
          <span style="width: 42px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-primary p-2">
            <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-qrcode' }}"></i>
          </span>
        </div>

        <div class="processing-payment-content">
          <div class="payment-summary-panel">
            <div class="payment-section-title">
              <i class="icon-base ti {{ $settings['order_summary_icon'] ?? 'tabler-shopping-cart' }}"></i>
              <span>processing_payment_page.order_summary</span>
            </div>

            <div class="payment-product-card">
              <div class="payment-product-img">
                <i class="icon-base ti tabler-bottle"></i>
              </div>

              <div>
                <div class="payment-product-title">
                  ไฮยีน น้ำยาซักผ้า ปรับผ้านุ่ม กลิ่นมิลค์กี้ แคร์
                </div>

                <div class="payment-product-row">
                  <span>จำนวน 1 ถุง</span>
                  <strong>115 บาท</strong>
                </div>

                <div class="payment-product-row text-danger">
                  <span>ส่วนลดโปรโมชั่น</span>
                  <strong>-15 บาท</strong>
                </div>
              </div>
            </div>

            <div class="payment-net-card">
              <div>
                <i class="icon-base ti {{ $settings['net_total_icon'] ?? 'tabler-wallet' }} me-1"></i>
                processing_payment_page.net_total
              </div>

              <div style="font-size: 30px;">
                100 <small>บาท</small>
              </div>
            </div>
          </div>

          <div class="qr-payment-panel">
            <div class="qr-box">
              <div class="qr-title">
                payment_api.qr_title
              </div>

              <div class="qr-brand-box">
                QR / Payment Logo from API
              </div>

              <div class="qr-placeholder"></div>

              <div class="qr-note">
                payment_api.countdown_label
                <span class="qr-countdown">04:59 นาที</span>
              </div>
            </div>
          </div>
        </div>

        <div class="processing-payment-footer">
          <button type="button" class="processing-home-button">
            <i class="icon-base ti {{ $settings['home_button_icon'] ?? 'tabler-home' }}"></i>
          </button>

          <button type="button" class="processing-back-button">
            <i class="icon-base ti {{ $settings['back_button_icon'] ?? 'tabler-chevron-left' }}"></i>
          </button>

          <button type="button" class="processing-confirm-button">
            <i class="icon-base ti {{ $settings['confirm_button_icon'] ?? 'tabler-chevron-right' }}"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
