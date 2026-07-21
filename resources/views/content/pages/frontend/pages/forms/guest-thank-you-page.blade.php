@php
  $settings = $page->settings_json ?? [];

  $stepIcons = [
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-heart' => 'Heart',
    'tabler-leaf' => 'Leaf',
    'tabler-gift' => 'Gift',
    'tabler-receipt' => 'Receipt',
  ];

  $buttonIcons = [
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-printer' => 'Printer',
    'tabler-printer-off' => 'Printer Off',
    'tabler-shopping-cart-plus' => 'Cart Plus',
    'tabler-shopping-bag-plus' => 'Shopping Bag Plus',
    'tabler-home' => 'Home',
  ];

  $stepIcon = old(
    'step_icon',
    $settings['step_icon'] ?? 'tabler-circle-check'
  );

  $printReceiptIcon = old(
    'print_receipt_icon',
    $settings['print_receipt_icon'] ?? 'tabler-printer'
  );

  $noReceiptIcon = old(
    'no_receipt_icon',
    $settings['no_receipt_icon'] ?? 'tabler-printer-off'
  );

  $finishButtonIcon = old(
    'finish_button_icon',
    $settings['finish_button_icon'] ?? 'tabler-check'
  );

  $orderMoreButtonIcon = old(
    'order_more_button_icon',
    $settings['order_more_button_icon'] ?? 'tabler-shopping-cart-plus'
  );

  $showPrintReceipt = (bool) old(
    'show_print_receipt',
    $settings['show_print_receipt'] ?? true
  );

  $showNoReceipt = (bool) old(
    'show_no_receipt',
    $settings['show_no_receipt'] ?? true
  );

  $showFinishButton = (bool) old(
    'show_finish_button',
    $settings['show_finish_button'] ?? true
  );

  $showOrderMoreButton = (bool) old(
    'show_order_more_button',
    $settings['show_order_more_button'] ?? true
  );
@endphp

<style>
  .guest-thankyou-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .guest-thankyou-title {
    text-align: center;
    color: #4b2a96;
    font-size: 22px;
    line-height: 1.35;
    font-weight: 900;
    margin-bottom: 18px;
  }

  .guest-thankyou-panel {
    width: min(760px, 100%);
    margin: 0 auto;
    background: rgba(255, 255, 255, .82);
    border-radius: 14px;
    padding: 14px;
  }

  .guest-thankyou-banner {
    background: linear-gradient(135deg, #effaff 0%, #ffffff 60%, #dff4ff 100%);
    border-radius: 10px;
    min-height: 250px;
    padding: 24px;
    display: grid;
    grid-template-columns: 1fr 220px;
    gap: 18px;
    align-items: center;
    overflow: hidden;
  }

  .guest-thankyou-banner-text {
    text-align: center;
  }

  .guest-thankyou-headline {
    font-size: 46px;
    line-height: 1;
    font-weight: 900;
    color: #0d57a1;
  }

  .guest-thankyou-headline span {
    color: #4caf50;
  }

  .guest-thankyou-subtitle {
    margin-top: 12px;
    color: #1767a5;
    font-size: 15px;
    font-weight: 700;
  }

  .guest-thankyou-description {
    margin-top: 12px;
    color: #4a6280;
    font-size: 12px;
    line-height: 1.6;
  }

  .guest-thankyou-art {
    min-height: 190px;
    border-radius: 12px;
    background:
      radial-gradient(circle at 50% 25%, #ffd77a 0 30px, transparent 31px),
      linear-gradient(180deg, #dff7ff 0%, #bce7ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0877c9;
    font-size: 88px;
  }

  .guest-receipt-question {
    text-align: center;
    color: #075db8;
    font-size: 16px;
    font-weight: 900;
    margin: 16px 0 10px;
  }

  .guest-receipt-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .guest-receipt-button {
    border: 1px solid #9dd7f5;
    border-radius: 9px;
    min-height: 48px;
    padding: 10px 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    background: #eef9ff;
    color: #0877c9;
    font-weight: 800;
  }

  .guest-receipt-button.is-muted {
    background: #f5f5f5;
    border-color: #e3e3e3;
    color: #9a9a9a;
  }

  .guest-thankyou-footer {
    display: flex;
    justify-content: center;
    gap: 14px;
    margin-top: 18px;
  }

  .guest-finish-button,
  .guest-order-more-button {
    border: 0;
    border-radius: 9px;
    min-width: 170px;
    min-height: 48px;
    padding: 10px 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-weight: 900;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
  }

  .guest-finish-button {
    background: #fff;
    color: #0877c9;
  }

  .guest-order-more-button {
    background: #0877c9;
    color: #fff;
    font-size: 18px;
  }

  @media (max-width: 991.98px) {
    .guest-thankyou-banner {
      grid-template-columns: 1fr;
    }

    .guest-receipt-options {
      grid-template-columns: 1fr;
    }

    .guest-thankyou-footer {
      flex-direction: column;
    }

    .guest-finish-button,
    .guest-order-more-button {
      width: 100%;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าขอบคุณแบบไม่ใช่สมาชิก</h5>
      <p class="text-muted mb-0">
        ใช้สำหรับลูกค้าที่ไม่ได้เข้าสู่ระบบสมาชิก
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
            value="guest_thank_you_page.title"
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

        <h6 class="mb-3">Icon หน้าขอบคุณ</h6>

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

        <hr class="my-4">

        <h6 class="mb-3">ตัวเลือกใบเสร็จ</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_print_receipt" value="0">
          <input
            type="checkbox"
            name="show_print_receipt"
            value="1"
            id="show_print_receipt"
            class="form-check-input"
            {{ $showPrintReceipt ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_print_receipt">
            แสดงตัวเลือกรับใบเสร็จ
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon รับใบเสร็จ</label>
          <select name="print_receipt_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $printReceiptIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_no_receipt" value="0">
          <input
            type="checkbox"
            name="show_no_receipt"
            value="1"
            id="show_no_receipt"
            class="form-check-input"
            {{ $showNoReceipt ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_no_receipt">
            แสดงตัวเลือกไม่รับใบเสร็จ
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ไม่รับใบเสร็จ</label>
          <select name="no_receipt_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $noReceiptIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">ปุ่มด้านล่าง</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_finish_button" value="0">
          <input
            type="checkbox"
            name="show_finish_button"
            value="1"
            id="show_finish_button"
            class="form-check-input"
            {{ $showFinishButton ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_finish_button">
            แสดงปุ่มเสร็จสิ้น
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มเสร็จสิ้น</label>
          <select name="finish_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $finishButtonIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="finish_button_action"
          value="{{ old(
            'finish_button_action',
            $settings['finish_button_action'] ?? 'first_page'
          ) }}"
        >

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_order_more_button" value="0">
          <input
            type="checkbox"
            name="show_order_more_button"
            value="1"
            id="show_order_more_button"
            class="form-check-input"
            {{ $showOrderMoreButton ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_order_more_button">
            แสดงปุ่มสั่งซื้อเพิ่ม
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มสั่งซื้อเพิ่ม</label>
          <select name="order_more_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $orderMoreButtonIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="order_more_button_action"
          value="{{ old(
            'order_more_button_action',
            $settings['order_more_button_action'] ?? 'select_product_page'
          ) }}"
        >

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึก
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้าขอบคุณแบบไม่ใช่สมาชิก</h5>
      <p class="text-muted mb-0">
        ไม่มีส่วนคะแนนสะสมของสมาชิก
      </p>
    </div>

    <div class="card-body">
      <div class="guest-thankyou-preview">
        <div class="guest-thankyou-title">
          ขอบคุณที่เป็นส่วนหนึ่ง<br>
          ของการลดใช้พลาสติกเพื่อโลกของเรา
        </div>

        <div class="guest-thankyou-panel">
          <div class="guest-thankyou-banner">
            <div class="guest-thankyou-banner-text">
              <div class="guest-thankyou-headline">
                THANK <span>YOU</span>
              </div>

              <div class="guest-thankyou-subtitle">
                ขอบคุณทุกแรงบันดาลใจค่ะ
              </div>

              <div class="guest-thankyou-description">
                เติมสะอาด ใช้ง่าย ใส่ใจโลก<br>
                เพิ่มคุณค่าให้ทุกการใช้งานอย่างยั่งยืน
              </div>
            </div>

            <div class="guest-thankyou-art">
              <i class="icon-base ti {{ $stepIcon }}"></i>
            </div>
          </div>

          <div class="guest-receipt-question">
            ต้องการรับใบเสร็จหรือไม่
          </div>

          <div class="guest-receipt-options">
            @if ($showPrintReceipt)
              <button type="button" class="guest-receipt-button">
                <i class="icon-base ti {{ $printReceiptIcon }}"></i>
                <span>รับใบเสร็จ</span>
              </button>
            @endif

            @if ($showNoReceipt)
              <button type="button" class="guest-receipt-button is-muted">
                <i class="icon-base ti {{ $noReceiptIcon }}"></i>
                <span>ไม่รับใบเสร็จ</span>
              </button>
            @endif
          </div>
        </div>

        <div class="guest-thankyou-footer">
          @if ($showFinishButton)
            <button type="button" class="guest-finish-button">
              <i class="icon-base ti {{ $finishButtonIcon }}"></i>
              <span>เสร็จสิ้น</span>
            </button>
          @endif

          @if ($showOrderMoreButton)
            <button type="button" class="guest-order-more-button">
              <span>สั่งซื้อเพิ่ม</span>
              <i class="icon-base ti {{ $orderMoreButtonIcon }}"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
