@php
  $settings = $page->settings_json ?? [];

  $stepIcons = [
    'tabler-credit-card' => 'Credit Card',
    'tabler-wallet' => 'Wallet',
    'tabler-cash' => 'Cash',
    'tabler-qrcode' => 'QR Code',
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
    'tabler-discount' => 'Discount',
  ];
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
    align-items: stretch;
  }

  .payment-summary-panel,
  .payment-method-panel {
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

  .payment-discount-card {
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

  .payment-method-item {
    background: #fff;
    border: 2px solid #d6e7f5;
    border-radius: 10px;
    min-height: 58px;
    padding: 10px 14px;
    margin-bottom: 10px;
    display: grid;
    grid-template-columns: 130px 1fr 32px;
    gap: 12px;
    align-items: center;
  }

  .payment-method-item.is-selected {
    border-color: #0084d8;
  }

  .payment-method-logo {
    max-width: 124px;
    max-height: 34px;
    object-fit: contain;
  }

  .payment-method-logo-empty {
    width: 124px;
    height: 34px;
    border-radius: 6px;
    background: #eef6ff;
    color: #7dbce8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
  }

  .payment-method-name {
    font-weight: 800;
    color: #006dcc;
    text-align: right;
    font-size: 14px;
  }

  .payment-method-subtitle {
    font-size: 11px;
    color: #0075c9;
    text-align: right;
  }

  .payment-footer {
    display: grid;
    grid-template-columns: 1fr 1fr 1.2fr;
    gap: 18px;
    margin-top: 18px;
    align-items: center;
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

  .payment-logo-preview {
    width: 120px;
    height: 44px;
    object-fit: contain;
    background: #fff;
  }

  @media (max-width: 991.98px) {
    .payment-content {
      grid-template-columns: 1fr;
    }

    .payment-footer {
      grid-template-columns: 1fr;
    }

    .payment-home-button,
    .payment-back-button,
    .payment-confirm-button {
      width: 100%;
    }

    .payment-method-item {
      grid-template-columns: 1fr;
      text-align: left;
    }

    .payment-method-name,
    .payment-method-subtitle {
      text-align: left;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าชำระเงิน</h5>
      <p class="text-muted mb-0">
        ตั้งค่า icon และปุ่มของหน้าชำระเงิน
      </p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">ชื่อหน้า <span class="text-danger">*</span></label>
          <input
            type="text"
            name="name"
            value="{{ old('name', $page->name) }}"
            class="form-control @error('name') is-invalid @enderror"
            required
          >
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key หัวข้อหน้า</label>
          <input type="text" value="payment_page.title" class="form-control" readonly>
          <div class="form-text">ข้อความจริงจะดึงจากระบบแปลภาษา</div>
        </div>

        <div class="mb-3">
          <label class="form-label">หมายเหตุ</label>
          <textarea name="remark" rows="3" class="form-control">{{ old('remark', $page->remark) }}</textarea>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon หน้าชำระเงิน</h6>

        <div class="mb-3">
          <label class="form-label">Icon Step ของหน้านี้</label>
          <select name="step_icon" class="form-select">
            @php $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-credit-card'); @endphp
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
            @php $orderSummaryIcon = old('order_summary_icon', $settings['order_summary_icon'] ?? 'tabler-shopping-cart'); @endphp
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
            @php $netTotalIcon = old('net_total_icon', $settings['net_total_icon'] ?? 'tabler-wallet'); @endphp
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $netTotalIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon หัวข้อช่องทางชำระเงิน</label>
          <select name="payment_section_icon" class="form-select">
            @php $paymentSectionIcon = old('payment_section_icon', $settings['payment_section_icon'] ?? 'tabler-credit-card'); @endphp
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $paymentSectionIcon === $iconClass ? 'selected' : '' }}>
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
            @php $homeButtonIcon = old('home_button_icon', $settings['home_button_icon'] ?? 'tabler-home'); @endphp
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $homeButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="show_home_button" value="1">
        <input type="hidden" name="home_button_action" value="{{ old('home_button_action', $settings['home_button_action'] ?? 'first_page') }}">

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มย้อนกลับ</label>
          <select name="back_button_icon" class="form-select">
            @php $backButtonIcon = old('back_button_icon', $settings['back_button_icon'] ?? 'tabler-chevron-left'); @endphp
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $backButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="show_back_button" value="1">
        <input type="hidden" name="back_button_action" value="{{ old('back_button_action', $settings['back_button_action'] ?? 'promotion_page') }}">

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มตกลง</label>
          <select name="confirm_button_icon" class="form-select">
            @php $confirmButtonIcon = old('confirm_button_icon', $settings['confirm_button_icon'] ?? 'tabler-chevron-right'); @endphp
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $confirmButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="show_confirm_button" value="1">
        <input type="hidden" name="confirm_button_action" value="{{ old('confirm_button_action', $settings['confirm_button_action'] ?? 'processing_payment_page') }}">

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้าชำระเงิน
        </button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">เพิ่มช่องทางการชำระเงิน</h5>
      <p class="text-muted mb-0">เพิ่ม/เปิดปิด/เปลี่ยนรูปช่องทางการชำระเงิน</p>
    </div>

    <div class="card-body">
      <form
        action="{{ route('frontend.payment-methods.store') }}"
        method="POST"
        enctype="multipart/form-data"
      >
        @csrf

        <div class="mb-3">
          <label class="form-label">Code <span class="text-danger">*</span></label>
          <input type="text" name="code" class="form-control" placeholder="promptpay" required>
          <div class="form-text">ใช้ภาษาอังกฤษ เช่น promptpay, credit_card, true_money</div>
        </div>

        <div class="mb-3">
          <label class="form-label">ชื่อช่องทาง <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="พร้อมเพย์" required>
        </div>

        <div class="mb-3">
          <label class="form-label">คำอธิบาย</label>
          <input type="text" name="subtitle" class="form-control" placeholder="PromptPay">
        </div>

        <div class="mb-3">
          <label class="form-label">Action Key</label>
          <input type="text" name="action_key" class="form-control" placeholder="promptpay">
        </div>

        <div class="mb-3">
          <label class="form-label">รูปช่องทางชำระเงิน</label>
          <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
        </div>

        <div class="mb-3">
          <label class="form-label">ลำดับ</label>
          <input type="number" name="sort_order" class="form-control" value="0" min="0">
        </div>

        <div class="form-check form-switch mb-4">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" value="1" id="payment_method_active" class="form-check-input" checked>
          <label class="form-check-label" for="payment_method_active">เปิดใช้งาน</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-plus me-1"></i>
          เพิ่มช่องทาง
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้าชำระเงิน</h5>
      <p class="text-muted mb-0">
        ตัวอย่าง layout เท่านั้น รายการสินค้าและยอดเงินจริงจะดึงจาก order/cart
      </p>
    </div>

    <div class="card-body">
      <div class="payment-preview">
        <div class="text-center mb-3">
          <div class="fw-bold text-primary fs-5">payment_page.title</div>
          <small class="text-muted">payment_page.subtitle</small>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
          <span class="badge rounded-pill bg-success p-2"><i class="icon-base ti tabler-check"></i></span>
          <span style="width: 42px; height: 2px; background: #7dbce8;"></span>
          <span class="badge rounded-pill bg-success p-2"><i class="icon-base ti tabler-check"></i></span>
          <span style="width: 42px; height: 2px; background: #7dbce8;"></span>
          <span class="badge rounded-pill bg-success p-2"><i class="icon-base ti tabler-check"></i></span>
          <span style="width: 42px; height: 2px; background: #7dbce8;"></span>
          <span class="badge rounded-pill bg-primary p-2">
            <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-credit-card' }}"></i>
          </span>
          <span style="width: 42px; height: 2px; background: #7dbce8;"></span>
          <span class="badge rounded-pill bg-label-secondary p-2"><i class="icon-base ti tabler-minus"></i></span>
        </div>

        <div class="payment-content">
          <div class="payment-summary-panel">
            <div class="payment-section-title">
              <i class="icon-base ti {{ $settings['order_summary_icon'] ?? 'tabler-shopping-cart' }}"></i>
              <span>payment_page.order_summary</span>
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

            <div class="payment-discount-card">
              <div>
                <i class="icon-base ti {{ $settings['net_total_icon'] ?? 'tabler-wallet' }} me-1"></i>
                payment_page.net_total
              </div>

              <div style="font-size: 30px;">
                100 <small>บาท</small>
              </div>
            </div>
          </div>

          <div class="payment-method-panel">
            <div class="payment-section-title">
              <i class="icon-base ti {{ $settings['payment_section_icon'] ?? 'tabler-credit-card' }}"></i>
              <span>payment_page.payment_method_title</span>
            </div>

            @forelse ($paymentMethods as $index => $method)
              <div class="payment-method-item {{ $index === 0 ? 'is-selected' : '' }}">
                <div>
                  @if ($method->logo_url)
                    <img src="{{ $method->logo_url }}" class="payment-method-logo" alt="{{ $method->name }}">
                  @else
                    <div class="payment-method-logo-empty">{{ $method->code }}</div>
                  @endif
                </div>

                <div>
                  <div class="payment-method-name">
                    {{ $method->name }}
                  </div>
                  @if ($method->subtitle)
                    <div class="payment-method-subtitle">
                      {{ $method->subtitle }}
                    </div>
                  @endif
                </div>

                <div class="text-primary text-center">
                  @if ($index === 0)
                    <i class="icon-base ti tabler-circle-check"></i>
                  @else
                    <i class="icon-base ti tabler-chevron-right"></i>
                  @endif
                </div>
              </div>
            @empty
              <div class="payment-method-item is-selected">
                <div><div class="payment-method-logo-empty">PromptPay</div></div>
                <div>
                  <div class="payment-method-name">พร้อมเพย์</div>
                  <div class="payment-method-subtitle">PromptPay</div>
                </div>
                <div class="text-primary text-center">
                  <i class="icon-base ti tabler-circle-check"></i>
                </div>
              </div>
            @endforelse
          </div>
        </div>

        <div class="payment-footer">
          <button type="button" class="payment-home-button">
            <i class="icon-base ti {{ $settings['home_button_icon'] ?? 'tabler-home' }}"></i>
            <span>payment_page.home_button</span>
          </button>

          <button type="button" class="payment-back-button">
            <i class="icon-base ti {{ $settings['back_button_icon'] ?? 'tabler-chevron-left' }}"></i>
            <span>payment_page.back_button</span>
          </button>

          <button type="button" class="payment-confirm-button">
            <span>payment_page.confirm_button</span>
            <i class="icon-base ti {{ $settings['confirm_button_icon'] ?? 'tabler-chevron-right' }}"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ช่องทางการชำระเงิน</h5>
      <p class="text-muted mb-0">
        เปิด/ปิด และเปลี่ยนรูปช่องทางการชำระเงิน
      </p>
    </div>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th style="width: 80px;">ลำดับ</th>
            <th style="width: 150px;">รูป</th>
            <th>ช่องทาง</th>
            <th style="width: 120px;">สถานะ</th>
            <th style="width: 170px;" class="text-center">จัดการ</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($paymentMethods as $method)
            <tr>
              <td>{{ number_format((int) $method->sort_order) }}</td>

              <td>
                @if ($method->logo_url)
                  <img src="{{ $method->logo_url }}" class="payment-logo-preview border rounded p-1" alt="{{ $method->name }}">
                @else
                  <span class="badge bg-label-secondary">ไม่มีรูป</span>
                @endif
              </td>

              <td>
                <div class="fw-medium">{{ $method->name }}</div>
                <small class="text-muted d-block">code: {{ $method->code }}</small>
                @if ($method->subtitle)
                  <small class="text-muted d-block">{{ $method->subtitle }}</small>
                @endif
              </td>

              <td>
                <span class="badge {{ $method->status_class }}">
                  {{ $method->status_text }}
                </span>
              </td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-sm btn-label-primary"
                  data-bs-toggle="collapse"
                  data-bs-target="#paymentMethodEdit{{ $method->id }}"
                >
                  แก้ไข
                </button>

                <form
                  action="{{ route('frontend.payment-methods.destroy', $method) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('ยืนยันการลบช่องทางนี้?')"
                >
                  @csrf
                  @method('DELETE')

                  <button type="submit" class="btn btn-sm btn-danger">
                    ลบ
                  </button>
                </form>
              </td>
            </tr>

            <tr class="collapse" id="paymentMethodEdit{{ $method->id }}">
              <td colspan="5">
                <form
                  action="{{ route('frontend.payment-methods.update', $method) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="row g-3 p-3 bg-light rounded"
                >
                  @csrf
                  @method('PUT')

                  <div class="col-md-3">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" value="{{ $method->code }}" class="form-control" required>
                  </div>

                  <div class="col-md-3">
                    <label class="form-label">ชื่อช่องทาง</label>
                    <input type="text" name="name" value="{{ $method->name }}" class="form-control" required>
                  </div>

                  <div class="col-md-3">
                    <label class="form-label">คำอธิบาย</label>
                    <input type="text" name="subtitle" value="{{ $method->subtitle }}" class="form-control">
                  </div>

                  <div class="col-md-3">
                    <label class="form-label">Action Key</label>
                    <input type="text" name="action_key" value="{{ $method->action_key }}" class="form-control">
                  </div>

                  <div class="col-md-3">
                    <label class="form-label">ลำดับ</label>
                    <input type="number" name="sort_order" value="{{ $method->sort_order }}" class="form-control" min="0">
                  </div>

                  <div class="col-md-5">
                    <label class="form-label">เปลี่ยนรูป</label>
                    <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
                  </div>

                  <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check form-switch">
                      <input type="hidden" name="is_active" value="0">
                      <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        id="is_active_{{ $method->id }}"
                        class="form-check-input"
                        {{ $method->is_active ? 'checked' : '' }}
                      >
                      <label class="form-check-label" for="is_active_{{ $method->id }}">
                        เปิดใช้งาน
                      </label>
                    </div>
                  </div>

                  <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                      บันทึก
                    </button>
                  </div>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-5">
                ยังไม่มีช่องทางการชำระเงิน
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
