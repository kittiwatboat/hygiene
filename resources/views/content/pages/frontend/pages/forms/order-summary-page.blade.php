@php
  $settings = $page->settings_json ?? [];

  $stepIcons = [
    'tabler-list-details' => 'List Details',
    'tabler-clipboard-list' => 'Clipboard List',
    'tabler-receipt' => 'Receipt',
    'tabler-shopping-cart' => 'Shopping Cart',
    'tabler-package' => 'Package',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
  ];

  $buttonIcons = [
    'tabler-arrow-left' => 'Arrow Left',
    'tabler-chevron-left' => 'Chevron Left',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-list-details' => 'List Details',
    'tabler-clipboard-list' => 'Clipboard List',
    'tabler-shopping-cart' => 'Shopping Cart',
    'tabler-package' => 'Package',
    'tabler-bottle' => 'Bottle',
    'tabler-wallet' => 'Wallet',
    'tabler-cash' => 'Cash',
    'tabler-receipt' => 'Receipt',
    'tabler-discount' => 'Discount',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
  ];

  $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-list-details');
  $orderSummaryIcon = old('order_summary_icon', $settings['order_summary_icon'] ?? 'tabler-shopping-cart');
  $discountSummaryIcon = old('discount_summary_icon', $settings['discount_summary_icon'] ?? 'tabler-discount');
  $netTotalIcon = old('net_total_icon', $settings['net_total_icon'] ?? 'tabler-wallet');
  $backButtonIcon = old('back_button_icon', $settings['back_button_icon'] ?? 'tabler-chevron-left');
  $confirmButtonIcon = old('confirm_button_icon', $settings['confirm_button_icon'] ?? 'tabler-chevron-right');
  $showBackButton = (bool) old('show_back_button', $settings['show_back_button'] ?? true);
  $showConfirmButton = (bool) old('show_confirm_button', $settings['show_confirm_button'] ?? true);
@endphp

<style>
  .order-summary-preview {
    min-height: 520px;
    overflow: hidden;
    border-radius: 16px;
    padding: 28px 34px 24px;
    background: linear-gradient(180deg, #dff5ff 0%, #eefaff 60%, #c8ecff 100%);
  }
  .summary-title { text-align: center; font-weight: 800; font-size: 20px; color: #1e293b; margin-bottom: 18px; }
  .summary-step { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 20px; }
  .summary-step-dot { width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #0d8bd7; color: #fff; box-shadow: 0 3px 8px rgba(13,139,215,.25); }
  .summary-step-dot.done { background: #31c968; }
  .summary-step-line { width: 56px; height: 3px; border-radius: 999px; background: #8fcff0; }
  .summary-card { width: min(520px, 100%); margin: 0 auto; padding: 14px; border-radius: 14px; background: rgba(255,255,255,.74); box-shadow: 0 10px 28px rgba(28,115,174,.12); }
  .summary-section { border-radius: 12px; background: #fff; padding: 14px; }
  .summary-section + .summary-section { margin-top: 12px; }
  .summary-section-title { color: #0075c9; font-weight: 800; display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
  .summary-product { display: grid; grid-template-columns: 72px 1fr; gap: 14px; align-items: center; }
  .summary-product-image { width: 72px; height: 92px; border-radius: 10px; background: #f2f8ff; display: flex; align-items: center; justify-content: center; color: #69b7e9; font-size: 34px; }
  .summary-product-name { color: #0075c9; font-size: 13px; line-height: 1.45; margin-bottom: 8px; }
  .summary-row { display: flex; justify-content: space-between; gap: 16px; font-size: 14px; margin-top: 6px; }
  .summary-row strong { color: #111827; }
  .summary-row.discount, .summary-row.discount strong { color: #ff2b2b; }
  .summary-net-total { border: 2px solid #b8e3ff; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
  .summary-net-total-label { color: #0075c9; font-weight: 800; display: flex; align-items: center; gap: 8px; }
  .summary-net-total-price { color: #0084d8; font-size: 34px; line-height: 1; font-weight: 900; }
  .summary-net-total-price small { font-size: 15px; }
  .summary-footer { display: flex; justify-content: center; align-items: center; gap: 16px; margin-top: 20px; }
  .summary-back-button, .summary-confirm-button { min-width: 150px; min-height: 48px; border: 0; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; gap: 10px; font-weight: 800; font-size: 16px; box-shadow: 0 5px 14px rgba(0,0,0,.12); }
  .summary-back-button { color: #0877c9; background: #fff; }
  .summary-confirm-button { color: #fff; background: #0877c9; }
  @media (max-width: 991.98px) {
    .summary-footer { flex-direction: column; align-items: stretch; }
    .summary-back-button, .summary-confirm-button { width: 100%; }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าสรุปรายการที่เลือก</h5>
      <p class="text-muted mb-0">ตั้งค่า Icon และปุ่มของหน้าสรุปรายการก่อนดำเนินการต่อ</p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">ชื่อหน้า <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name', $page->name) }}" class="form-control @error('name') is-invalid @enderror" required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key หัวข้อหน้า</label>
          <input type="text" value="order_summary_page.title" class="form-control" readonly>
          <div class="form-text">ข้อความจริงจะดึงจากระบบแปลภาษา</div>
        </div>

        <div class="mb-3">
          <label class="form-label">หมายเหตุ</label>
          <textarea name="remark" rows="3" class="form-control">{{ old('remark', $page->remark) }}</textarea>
        </div>

        <hr class="my-4">
        <h6 class="mb-3">Icon หน้าสรุปรายการ</h6>

        <div class="mb-3">
          <label class="form-label">Icon Step ของหน้านี้</label>
          <select name="step_icon" class="form-select">
            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $stepIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon หัวข้อรายการสินค้า</label>
          <select name="order_summary_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $orderSummaryIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ส่วนลดโปรโมชั่น</label>
          <select name="discount_summary_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $discountSummaryIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ยอดรวมสุทธิ</label>
          <select name="net_total_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $netTotalIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">
        <h6 class="mb-3">ปุ่มด้านล่าง</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_back_button" value="0">
          <input type="checkbox" name="show_back_button" value="1" id="show_back_button" class="form-check-input" {{ $showBackButton ? 'checked' : '' }}>
          <label class="form-check-label" for="show_back_button">แสดงปุ่มย้อนกลับ</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มย้อนกลับ</label>
          <select name="back_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $backButtonIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="back_button_action" value="{{ old('back_button_action', $settings['back_button_action'] ?? 'select_product_page') }}">

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_confirm_button" value="0">
          <input type="checkbox" name="show_confirm_button" value="1" id="show_confirm_button" class="form-check-input" {{ $showConfirmButton ? 'checked' : '' }}>
          <label class="form-check-label" for="show_confirm_button">แสดงปุ่มตกลง</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มตกลง</label>
          <select name="confirm_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $confirmButtonIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="confirm_button_action" value="{{ old('confirm_button_action', $settings['confirm_button_action'] ?? 'promotion_page') }}">

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
      <h5 class="mb-1">Preview หน้าสรุปรายการที่เลือก</h5>
      <p class="text-muted mb-0">ข้อมูลสินค้า จำนวน ราคา และส่วนลดจะดึงจากรายการที่ลูกค้าเลือกจริง</p>
    </div>

    <div class="card-body">
      <div class="order-summary-preview">
        <div class="summary-step">
          <span class="summary-step-dot done"><i class="icon-base ti tabler-check"></i></span>
          <span class="summary-step-line"></span>
          <span class="summary-step-dot"><i class="icon-base ti {{ $stepIcon }}"></i></span>
          <span class="summary-step-line"></span>
          <span class="summary-step-dot" style="opacity: .35;"><i class="icon-base ti tabler-minus"></i></span>
        </div>

        <div class="summary-title">order_summary_page.title</div>

        <div class="summary-card">
          <div class="summary-section">
            <div class="summary-section-title">
              <i class="icon-base ti {{ $orderSummaryIcon }}"></i>
              <span>order_summary_page.order_list</span>
            </div>

            <div class="summary-product">
              <div class="summary-product-image"><i class="icon-base ti tabler-bottle"></i></div>
              <div>
                <div class="summary-product-name">ไฮยีน น้ำยาซักผ้า ปรับผ้านุ่ม กลิ่นมิลค์กี้ แคร์ ขนาด 1,250 มล.</div>
                <div class="summary-row"><span>จำนวน 1 ถุง</span><strong>115 บาท</strong></div>
                <div class="summary-row discount">
                  <span><i class="icon-base ti {{ $discountSummaryIcon }} me-1"></i>ส่วนลดโปรโมชั่น</span>
                  <strong>0 บาท</strong>
                </div>
              </div>
            </div>
          </div>

          <div class="summary-section summary-net-total">
            <div class="summary-net-total-label">
              <i class="icon-base ti {{ $netTotalIcon }}"></i>
              <span>order_summary_page.net_total</span>
            </div>
            <div class="summary-net-total-price">115 <small>บาท</small></div>
          </div>
        </div>

        <div class="summary-footer">
          @if ($showBackButton)
            <button type="button" class="summary-back-button">
              <i class="icon-base ti {{ $backButtonIcon }}"></i>
              <span>order_summary_page.back_button</span>
            </button>
          @endif

          @if ($showConfirmButton)
            <button type="button" class="summary-confirm-button">
              <span>order_summary_page.confirm_button</span>
              <i class="icon-base ti {{ $confirmButtonIcon }}"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
