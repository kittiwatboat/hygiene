@php
  $settings = $page->settings_json ?? [];

  $iconOptions = [
    'tabler-credit-card' => 'Credit Card',
    'tabler-qrcode' => 'QR Code',
    'tabler-wallet' => 'Wallet',
    'tabler-cash' => 'Cash',
    'tabler-receipt' => 'Receipt',
    'tabler-shopping-bag' => 'Shopping Bag',
    'tabler-discount' => 'Discount',
    'tabler-coins' => 'Coins',
    'tabler-home' => 'Home',
    'tabler-arrow-left' => 'Arrow Left',
    'tabler-chevron-left' => 'Chevron Left',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-device-mobile' => 'Mobile',
    'tabler-building-bank' => 'Bank',
  ];

  $paymentMethods = old('payment_methods', $settings['payment_methods'] ?? [
    [
      'code' => 'promptpay',
      'name' => 'พร้อมเพย์',
      'subtitle' => 'PromptPay',
      'icon' => 'tabler-qrcode',
      'is_active' => true,
      'sort_order' => 1,
    ],
    [
      'code' => 'credit_card',
      'name' => 'บัตรเครดิต / เดบิต',
      'subtitle' => 'VISA / Mastercard',
      'icon' => 'tabler-credit-card',
      'is_active' => true,
      'sort_order' => 2,
    ],
    [
      'code' => 'truemoney',
      'name' => 'ทรูมันนี่ วอลเล็ท',
      'subtitle' => 'TrueMoney Wallet',
      'icon' => 'tabler-wallet',
      'is_active' => true,
      'sort_order' => 3,
    ],
    [
      'code' => 'shopeepay',
      'name' => 'ShopeePay',
      'subtitle' => 'ShopeePay',
      'icon' => 'tabler-device-mobile',
      'is_active' => true,
      'sort_order' => 4,
    ],
  ]);
@endphp

<style>
  .payment-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .payment-content {
    display: grid;
    grid-template-columns: 38% 62%;
    gap: 18px;
    align-items: start;
  }

  .payment-left-card,
  .payment-method-panel {
    background: rgba(255, 255, 255, .78);
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

  .payment-product-item {
    display: grid;
    grid-template-columns: 58px 1fr auto;
    gap: 10px;
    align-items: center;
    background: #fff;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 12px;
  }

  .payment-product-thumb {
    width: 54px;
    height: 54px;
    border-radius: 8px;
    background: #f3f8ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #74aee8;
  }

  .payment-discount-card {
    background: #eaf7ff;
    border: 2px solid #9bd7ff;
    border-radius: 10px;
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #0075c9;
    font-weight: 800;
  }

  .payment-method-list {
    display: grid;
    gap: 10px;
  }

  .payment-method-item {
    background: #fff;
    border: 2px solid #d6e7f5;
    border-radius: 10px;
    min-height: 64px;
    padding: 10px 14px;
    display: grid;
    grid-template-columns: 58px 1fr 34px;
    align-items: center;
    gap: 10px;
  }

  .payment-method-item.is-selected {
    border-color: #0075c9;
  }

  .payment-method-icon {
    width: 48px;
    height: 42px;
    border-radius: 8px;
    background: #f3f8ff;
    color: #0075c9;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .payment-method-icon i {
    font-size: 26px;
  }

  .payment-method-name {
    font-weight: 800;
    color: #0075c9;
  }

  .payment-method-subtitle {
    font-size: 12px;
    color: #607d99;
  }

  .payment-footer {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 18px;
    margin-top: 18px;
  }

  .payment-home-button,
  .payment-back-button,
  .payment-confirm-button {
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

  .payment-home-button,
  .payment-back-button {
    background: #fff;
    color: #0877c9;
  }

  .payment-confirm-button {
    background: #0877c9;
    color: #fff;
    font-size: 18px;
  }

  .payment-method-admin-row {
    border: 1px solid rgba(67, 89, 113, .18);
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 14px;
    background: #fff;
  }

  @media (max-width: 991.98px) {
    .payment-content {
      grid-template-columns: 1fr;
    }

    .payment-footer {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าชำระเงิน</h5>
      <p class="text-muted mb-0">
        จัดการ icon และช่องทางชำระเงินของหน้าตู้
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
          <input type="text" value="payment_page.title" class="form-control" readonly>
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

        <h6 class="mb-3">Icon Step ของหน้านี้</h6>

        <div class="mb-3">
          <label class="form-label">Icon Step หน้าชำระเงิน</label>

          <select name="step_icon" class="form-select">
            @php
              $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-wallet');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $stepIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon ส่วนสรุปรายการ</h6>

        <div class="mb-3">
          <label class="form-label">Icon รายการสินค้า</label>

          <select name="order_summary_icon" class="form-select">
            @php
              $orderSummaryIcon = old('order_summary_icon', $settings['order_summary_icon'] ?? 'tabler-shopping-bag');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $orderSummaryIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ยอดรวม / ส่วนลด</label>

          <select name="discount_summary_icon" class="form-select">
            @php
              $discountSummaryIcon = old('discount_summary_icon', $settings['discount_summary_icon'] ?? 'tabler-wallet');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $discountSummaryIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon ช่องทางชำระเงิน</h6>

        <div class="mb-3">
          <label class="form-label">Icon หัวข้อช่องทางชำระเงิน</label>

          <select name="payment_section_icon" class="form-select">
            @php
              $paymentSectionIcon = old('payment_section_icon', $settings['payment_section_icon'] ?? 'tabler-credit-card');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $paymentSectionIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ช่องทางที่เลือก</label>

          <select name="selected_payment_icon" class="form-select">
            @php
              $selectedPaymentIcon = old('selected_payment_icon', $settings['selected_payment_icon'] ?? 'tabler-check');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $selectedPaymentIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ช่องทางที่ยังไม่เลือก</label>

          <select name="next_payment_icon" class="form-select">
            @php
              $nextPaymentIcon = old('next_payment_icon', $settings['next_payment_icon'] ?? 'tabler-chevron-right');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $nextPaymentIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">ปุ่มด้านล่าง</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_home_button" value="0">
          <input
            type="checkbox"
            name="show_home_button"
            value="1"
            id="show_home_button"
            class="form-check-input"
            {{ old('show_home_button', $settings['show_home_button'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_home_button">แสดงปุ่มหน้าหลัก</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มหน้าหลัก</label>

          <select name="home_button_icon" class="form-select">
            @php
              $homeButtonIcon = old('home_button_icon', $settings['home_button_icon'] ?? 'tabler-home');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $homeButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="home_button_action"
          value="{{ old('home_button_action', $settings['home_button_action'] ?? 'first_page') }}"
        >

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_back_button" value="0">
          <input
            type="checkbox"
            name="show_back_button"
            value="1"
            id="show_back_button"
            class="form-check-input"
            {{ old('show_back_button', $settings['show_back_button'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_back_button">แสดงปุ่มย้อนกลับ</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มย้อนกลับ</label>

          <select name="back_button_icon" class="form-select">
            @php
              $backButtonIcon = old('back_button_icon', $settings['back_button_icon'] ?? 'tabler-chevron-left');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $backButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="back_button_action"
          value="{{ old('back_button_action', $settings['back_button_action'] ?? 'promotion_page') }}"
        >

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_confirm_button" value="0">
          <input
            type="checkbox"
            name="show_confirm_button"
            value="1"
            id="show_confirm_button"
            class="form-check-input"
            {{ old('show_confirm_button', $settings['show_confirm_button'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_confirm_button">แสดงปุ่มตกลง</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มตกลง</label>

          <select name="confirm_button_icon" class="form-select">
            @php
              $confirmButtonIcon = old('confirm_button_icon', $settings['confirm_button_icon'] ?? 'tabler-chevron-right');
            @endphp

            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $confirmButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="confirm_button_action"
          value="{{ old('confirm_button_action', $settings['confirm_button_action'] ?? 'waiting_payment_page') }}"
        >

        <hr class="my-4">

        <h6 class="mb-3">ช่องทางชำระเงิน</h6>

        <div id="paymentMethodsWrapper">
          @foreach ($paymentMethods as $index => $method)
            <div class="payment-method-admin-row">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <strong>ช่องทางชำระเงิน #{{ $index + 1 }}</strong>

                <button type="button" class="btn btn-sm btn-label-danger js-remove-payment-method">
                  ลบ
                </button>
              </div>

              <div class="mb-3">
                <label class="form-label">รหัสช่องทาง</label>
                <input
                  type="text"
                  name="payment_methods[{{ $index }}][code]"
                  value="{{ $method['code'] ?? '' }}"
                  class="form-control"
                  placeholder="เช่น promptpay"
                >
              </div>

              <div class="mb-3">
                <label class="form-label">ชื่อช่องทาง</label>
                <input
                  type="text"
                  name="payment_methods[{{ $index }}][name]"
                  value="{{ $method['name'] ?? '' }}"
                  class="form-control"
                  placeholder="เช่น พร้อมเพย์"
                >
              </div>

              <div class="mb-3">
                <label class="form-label">คำอธิบาย / ชื่ออังกฤษ</label>
                <input
                  type="text"
                  name="payment_methods[{{ $index }}][subtitle]"
                  value="{{ $method['subtitle'] ?? '' }}"
                  class="form-control"
                  placeholder="เช่น PromptPay"
                >
              </div>

              <div class="mb-3">
                <label class="form-label">Icon ช่องทาง</label>
                <select name="payment_methods[{{ $index }}][icon]" class="form-select">
                  @php
                    $methodIcon = $method['icon'] ?? 'tabler-wallet';
                  @endphp

                  @foreach ($iconOptions as $iconClass => $iconLabel)
                    <option value="{{ $iconClass }}" {{ $methodIcon === $iconClass ? 'selected' : '' }}>
                      {{ $iconLabel }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">ลำดับ</label>
                <input
                  type="number"
                  name="payment_methods[{{ $index }}][sort_order]"
                  value="{{ $method['sort_order'] ?? ($index + 1) }}"
                  class="form-control"
                  min="0"
                >
              </div>

              <div class="form-check form-switch">
                <input type="hidden" name="payment_methods[{{ $index }}][is_active]" value="0">
                <input
                  type="checkbox"
                  name="payment_methods[{{ $index }}][is_active]"
                  value="1"
                  id="payment_method_active_{{ $index }}"
                  class="form-check-input"
                  {{ !empty($method['is_active']) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="payment_method_active_{{ $index }}">
                  เปิดใช้งานช่องทางนี้
                </label>
              </div>
            </div>
          @endforeach
        </div>

        <button type="button" class="btn btn-label-primary w-100 mb-3" id="addPaymentMethodBtn">
          <i class="icon-base ti tabler-plus me-1"></i>
          เพิ่มช่องทางชำระเงิน
        </button>

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้าชำระเงิน
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้าชำระเงิน</h5>
      <p class="text-muted mb-0">
        ตัวอย่าง layout เท่านั้น ข้อมูลสินค้า ราคา และยอดเงินจริงจะดึงจากระบบ
      </p>
    </div>

    <div class="card-body">
      <div class="payment-preview">
        <div class="text-center mb-3">
          <div class="fw-bold text-primary fs-5">
            payment_page.title
          </div>
          <small class="text-muted">
            payment_page.subtitle
          </small>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
          <span class="badge rounded-pill bg-success p-2">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span style="width: 46px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-success p-2">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span style="width: 46px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-success p-2">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span style="width: 46px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-primary p-2">
            <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-wallet' }}"></i>
          </span>

          <span style="width: 46px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-label-secondary p-2">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="payment-content">
          <div>
            <div class="payment-left-card">
              <div class="payment-section-title">
                <i class="icon-base ti {{ $settings['order_summary_icon'] ?? 'tabler-shopping-bag' }}"></i>
                <span>payment_page.order_summary</span>
              </div>

              <div class="payment-product-item">
                <div class="payment-product-thumb">
                  <i class="icon-base ti tabler-bottle"></i>
                </div>

                <div>
                  <div class="fw-bold text-primary small">ไฮจีน น้ำยาปรับผ้านุ่ม</div>
                  <div class="small text-muted">จำนวน 1 ถุง</div>
                  <div class="text-danger small">ส่วนลดโปรโมชั่น</div>
                </div>

                <div class="text-end small">
                  <div class="fw-bold">115 บาท</div>
                  <div class="text-danger">-15 บาท</div>
                </div>
              </div>

              <div class="payment-discount-card">
                <div>
                  <i class="icon-base ti {{ $settings['discount_summary_icon'] ?? 'tabler-wallet' }} me-1"></i>
                  ยอดรวมสุทธิ
                </div>

                <div class="fs-3">
                  100 <small>บาท</small>
                </div>
              </div>
            </div>
          </div>

          <div class="payment-method-panel">
            <div class="payment-section-title">
              <i class="icon-base ti {{ $settings['payment_section_icon'] ?? 'tabler-credit-card' }}"></i>
              <span>payment_page.payment_method_title</span>
            </div>

            <div class="payment-method-list">
              @php
                $activePaymentMethods = collect($paymentMethods)
                  ->filter(fn ($method) => !empty($method['is_active']))
                  ->sortBy(fn ($method) => (int) ($method['sort_order'] ?? 0))
                  ->values();
              @endphp

              @forelse ($activePaymentMethods as $methodIndex => $method)
                <div class="payment-method-item {{ $methodIndex === 0 ? 'is-selected' : '' }}">
                  <div class="payment-method-icon">
                    <i class="icon-base ti {{ $method['icon'] ?? 'tabler-wallet' }}"></i>
                  </div>

                  <div>
                    <div class="payment-method-name">
                      {{ $method['name'] ?? '-' }}
                    </div>

                    <div class="payment-method-subtitle">
                      {{ $method['subtitle'] ?? '' }}
                    </div>
                  </div>

                  <div class="text-primary">
                    @if ($methodIndex === 0)
                      <i class="icon-base ti {{ $settings['selected_payment_icon'] ?? 'tabler-check' }}"></i>
                    @else
                      <i class="icon-base ti {{ $settings['next_payment_icon'] ?? 'tabler-chevron-right' }}"></i>
                    @endif
                  </div>
                </div>
              @empty
                <div class="text-center text-muted py-4">
                  ยังไม่มีช่องทางชำระเงินที่เปิดใช้งาน
                </div>
              @endforelse
            </div>
          </div>
        </div>

        <div class="payment-footer">
          @if ($settings['show_home_button'] ?? true)
            <button type="button" class="payment-home-button">
              <i class="icon-base ti {{ $settings['home_button_icon'] ?? 'tabler-home' }}"></i>
              <span>payment_page.home_button</span>
            </button>
          @endif

          @if ($settings['show_back_button'] ?? true)
            <button type="button" class="payment-back-button">
              <i class="icon-base ti {{ $settings['back_button_icon'] ?? 'tabler-chevron-left' }}"></i>
              <span>payment_page.back_button</span>
            </button>
          @endif

          @if ($settings['show_confirm_button'] ?? true)
            <button type="button" class="payment-confirm-button">
              <span>payment_page.confirm_button</span>
              <i class="icon-base ti {{ $settings['confirm_button_icon'] ?? 'tabler-chevron-right' }}"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const wrapper = document.getElementById('paymentMethodsWrapper');
  const addBtn = document.getElementById('addPaymentMethodBtn');

  if (!wrapper || !addBtn) return;

  const iconOptionsHtml = `{!! collect($iconOptions)->map(fn($label, $value) => '<option value="' . e($value) . '">' . e($label) . '</option>')->implode('') !!}`;

  function bindRemoveButtons() {
    wrapper.querySelectorAll('.js-remove-payment-method').forEach(function (btn) {
      btn.onclick = function () {
        const row = btn.closest('.payment-method-admin-row');
        if (row && confirm('ยืนยันการลบช่องทางชำระเงินนี้?')) {
          row.remove();
        }
      };
    });
  }

  addBtn.addEventListener('click', function () {
    const index = Date.now();

    const html = `
      <div class="payment-method-admin-row">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <strong>ช่องทางชำระเงินใหม่</strong>
          <button type="button" class="btn btn-sm btn-label-danger js-remove-payment-method">ลบ</button>
        </div>

        <div class="mb-3">
          <label class="form-label">รหัสช่องทาง</label>
          <input type="text" name="payment_methods[${index}][code]" class="form-control" placeholder="เช่น promptpay">
        </div>

        <div class="mb-3">
          <label class="form-label">ชื่อช่องทาง</label>
          <input type="text" name="payment_methods[${index}][name]" class="form-control" placeholder="เช่น พร้อมเพย์">
        </div>

        <div class="mb-3">
          <label class="form-label">คำอธิบาย / ชื่ออังกฤษ</label>
          <input type="text" name="payment_methods[${index}][subtitle]" class="form-control" placeholder="เช่น PromptPay">
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ช่องทาง</label>
          <select name="payment_methods[${index}][icon]" class="form-select">
            ${iconOptionsHtml}
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">ลำดับ</label>
          <input type="number" name="payment_methods[${index}][sort_order]" value="0" class="form-control" min="0">
        </div>

        <div class="form-check form-switch">
          <input type="hidden" name="payment_methods[${index}][is_active]" value="0">
          <input type="checkbox" name="payment_methods[${index}][is_active]" value="1" class="form-check-input" id="payment_method_active_${index}" checked>
          <label class="form-check-label" for="payment_method_active_${index}">
            เปิดใช้งานช่องทางนี้
          </label>
        </div>
      </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    bindRemoveButtons();
  });

  bindRemoveButtons();
});
</script>
