@php
  $settings = $page->settings_json ?? [];

  $stepIcons = [
    'tabler-bottle' => 'Bottle',
    'tabler-droplet' => 'Droplet',
    'tabler-shopping-cart' => 'Shopping Cart',
    'tabler-shopping-bag' => 'Shopping Bag',
    'tabler-package' => 'Package',
    'tabler-basket' => 'Basket',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
  ];

  $categoryIcons = [
    'tabler-basket' => 'Basket',
    'tabler-wash' => 'Wash',
    'tabler-bottle' => 'Bottle',
    'tabler-droplet' => 'Droplet',
    'tabler-sparkles' => 'Sparkles',
    'tabler-shirt' => 'Shirt',
    'tabler-package' => 'Package',
  ];

  $buttonIcons = [
    'tabler-home' => 'Home',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-shopping-cart' => 'Shopping Cart',
    'tabler-bottle' => 'Bottle',
    'tabler-droplet' => 'Droplet',
    'tabler-wallet' => 'Wallet',
    'tabler-cash' => 'Cash',
  ];
@endphp

<style>
  .select-product-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .select-product-preview-title {
    text-align: center;
    color: #6f63f6;
    font-weight: 800;
    font-size: 20px;
    margin-bottom: 2px;
  }

  .select-product-preview-subtitle {
    text-align: center;
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 16px;
  }

  .select-product-step {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
  }

  .select-product-step-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: #6f63f6;
    font-size: 18px;
  }

  .select-product-step-circle.pending {
    background: #e8e8ed;
    color: #9295a0;
  }

  .select-product-step-line {
    width: 52px;
    height: 2px;
    background: #6fbff0;
  }

  .select-product-layout {
    display: grid;
    grid-template-columns: minmax(190px, .9fr) minmax(220px, .85fr) minmax(240px, 1fr);
    gap: 16px;
    align-items: stretch;
  }

  .select-product-column {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .select-product-category {
    min-height: 94px;
    border: 2px solid transparent;
    border-radius: 12px;
    background: #fff;
    color: #0877c9;
    padding: 14px 16px;
    display: grid;
    grid-template-columns: 46px 1fr 28px;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: 900;
    box-shadow: 0 4px 12px rgba(0,0,0,.06);
  }

  .select-product-category.is-selected {
    background: #0b8bd3;
    color: #fff;
    border-color: #0b8bd3;
  }

  .select-product-category-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(8, 119, 201, .10);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
  }

  .select-product-category.is-selected .select-product-category-icon {
    background: rgba(255,255,255,.16);
  }

  .select-product-category-check {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
  }

  .select-product-item {
    position: relative;
    min-height: 170px;
    border: 3px solid transparent;
    border-radius: 12px;
    background: #fff;
    padding: 14px 12px 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,.06);
  }

  .select-product-item.is-selected {
    border-color: #0b8bd3;
  }

  .select-product-item-check {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #0b8bd3;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .select-product-image {
    width: 86px;
    height: 112px;
    border-radius: 8px;
    background: #f4f8fb;
    color: #72b9e5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 44px;
  }

  .select-product-name {
    color: #0877c9;
    font-size: 14px;
    line-height: 1.35;
    font-weight: 800;
    margin-top: 8px;
  }

  .select-product-amount {
    min-height: 94px;
    border: 3px solid transparent;
    border-radius: 12px;
    background: #fff;
    padding: 16px 20px;
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,.06);
  }

  .select-product-amount.is-selected {
    border-color: #0b8bd3;
  }

  .select-product-amount-value {
    color: #075db8;
    font-size: 30px;
    font-weight: 900;
    line-height: 1;
  }

  .select-product-amount-price {
    color: #111827;
    font-size: 26px;
    font-weight: 900;
    line-height: 1;
  }

  .select-product-amount-unit {
    font-size: 16px;
    font-weight: 800;
    margin-left: 4px;
  }

  .select-product-amount-check {
    grid-column: 1 / -1;
    justify-self: end;
    margin-top: -30px;
    color: #0b8bd3;
    font-size: 24px;
  }

  .select-product-footer {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    margin-top: 18px;
  }

  .select-product-back-button,
  .select-product-confirm-button {
    border: 0;
    border-radius: 9px;
    min-width: 160px;
    min-height: 48px;
    padding: 10px 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-weight: 900;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
  }

  .select-product-back-button {
    background: #fff;
    color: #0877c9;
  }

  .select-product-confirm-button {
    background: #0877c9;
    color: #fff;
    font-size: 18px;
  }

  @media (max-width: 1199.98px) {
    .select-product-layout {
      grid-template-columns: 1fr;
    }

    .select-product-footer {
      justify-content: center;
    }
  }

  @media (max-width: 575.98px) {
    .select-product-footer {
      flex-direction: column;
    }

    .select-product-back-button,
    .select-product-confirm-button {
      width: 100%;
    }
  }
</style>


<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าเลือกสินค้า</h5>
      <p class="text-muted mb-0">
        จัดการเฉพาะ icon ของหน้าเลือกสินค้า ส่วนสินค้าและราคาจะดึงจากระบบ
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
          <input type="text" value="select_product_page.title" class="form-control" readonly>
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
          <label class="form-label">Icon Step หน้าเลือกสินค้า</label>

          <select name="step_icon" class="form-select">
            @php
              $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-bottle');
            @endphp

            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $stepIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon หมวดสินค้า</h6>

        <div class="mb-3">
          <label class="form-label">Icon หมวดที่ 1</label>

          <select name="category_primary_icon" class="form-select">
            @php
              $categoryPrimaryIcon = old('category_primary_icon', $settings['category_primary_icon'] ?? 'tabler-basket');
            @endphp

            @foreach ($categoryIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $categoryPrimaryIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>

          <div class="form-text">ตัวอย่างเช่น น้ำยาซักผ้า</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon หมวดที่ 2</label>

          <select name="category_secondary_icon" class="form-select">
            @php
              $categorySecondaryIcon = old('category_secondary_icon', $settings['category_secondary_icon'] ?? 'tabler-droplet');
            @endphp

            @foreach ($categoryIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $categorySecondaryIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>

          <div class="form-text">ตัวอย่างเช่น น้ำยาปรับผ้านุ่ม</div>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon การเลือก</h6>

        <div class="mb-3">
          <label class="form-label">Icon สินค้าที่ถูกเลือก</label>

          <select name="selected_product_icon" class="form-select">
            @php
              $selectedProductIcon = old('selected_product_icon', $settings['selected_product_icon'] ?? 'tabler-check');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $selectedProductIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon หัวข้อเลือกปริมาณ</label>

          <select name="amount_section_icon" class="form-select">
            @php
              $amountSectionIcon = old('amount_section_icon', $settings['amount_section_icon'] ?? 'tabler-basket');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $amountSectionIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ราคารวม</label>

          <select name="total_price_icon" class="form-select">
            @php
              $totalPriceIcon = old('total_price_icon', $settings['total_price_icon'] ?? 'tabler-wallet');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $totalPriceIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon ปุ่มด้านล่าง</h6>

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
          <label class="form-check-label" for="show_home_button">
            แสดงปุ่มหน้าหลัก
          </label>
        </div>

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

        <input
          type="hidden"
          name="home_button_action"
          value="{{ old('home_button_action', $settings['home_button_action'] ?? 'first_page') }}"
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
          <label class="form-check-label" for="show_confirm_button">
            แสดงปุ่มตกลง
          </label>
        </div>

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

        <input
          type="hidden"
          name="confirm_button_action"
          value="{{ old('confirm_button_action', $settings['confirm_button_action'] ?? 'payment_page') }}"
        >

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้าเลือกสินค้า
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้าเลือกสินค้า</h5>
      <p class="text-muted mb-0">
        ตัวอย่าง Layout ตามแบบใหม่ สินค้า รูป ราคา และปริมาณจริงจะดึงจากระบบ
      </p>
    </div>

    <div class="card-body">
      <div class="select-product-preview">
        <div class="select-product-preview-title">
          select_product_page.title
        </div>

        <div class="select-product-preview-subtitle">
          select_product_page.subtitle
        </div>

        <div class="select-product-step">
          <span class="select-product-step-circle">
            <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-bottle' }}"></i>
          </span>

          <span class="select-product-step-line"></span>

          <span class="select-product-step-circle pending">
            <i class="icon-base ti tabler-minus"></i>
          </span>

          <span class="select-product-step-line"></span>

          <span class="select-product-step-circle pending">
            <i class="icon-base ti tabler-minus"></i>
          </span>

          <span class="select-product-step-line"></span>

          <span class="select-product-step-circle pending">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="select-product-layout">
          <div class="select-product-column">
            <div class="select-product-category is-selected">
              <span class="select-product-category-icon">
                <i class="icon-base ti {{ $settings['category_primary_icon'] ?? 'tabler-basket' }}"></i>
              </span>

              <span>น้ำยาซักผ้า</span>

              <span class="select-product-category-check">
                <i class="icon-base ti {{ $settings['selected_product_icon'] ?? 'tabler-check' }}"></i>
              </span>
            </div>

            <div class="select-product-category">
              <span class="select-product-category-icon">
                <i class="icon-base ti {{ $settings['category_secondary_icon'] ?? 'tabler-droplet' }}"></i>
              </span>

              <span>น้ำยาปรับผ้านุ่ม</span>

              <span class="select-product-category-check">
                <i class="icon-base ti tabler-circle"></i>
              </span>
            </div>
          </div>

          <div class="select-product-column">
            <div class="select-product-item is-selected">
              <span class="select-product-item-check">
                <i class="icon-base ti {{ $settings['selected_product_icon'] ?? 'tabler-check' }}"></i>
              </span>

              <div class="select-product-image">
                <i class="icon-base ti tabler-bottle"></i>
              </div>

              <div class="select-product-name">
                กลิ่น มิลค์กี้ แคร์
              </div>
            </div>

            <div class="select-product-item">
              <div class="select-product-image">
                <i class="icon-base ti tabler-bottle"></i>
              </div>

              <div class="select-product-name">
                กลิ่น ฟลอร่า บลูม
              </div>
            </div>
          </div>

          <div class="select-product-column">
            <div class="select-product-amount is-selected">
              <div>
                <span class="select-product-amount-value">1,250</span>
                <span class="select-product-amount-unit">มล.</span>
              </div>

              <div>
                <span class="select-product-amount-price">115</span>
                <span class="select-product-amount-unit">บาท</span>
              </div>

              <span class="select-product-amount-check">
                <i class="icon-base ti {{ $settings['selected_product_icon'] ?? 'tabler-check' }}"></i>
              </span>
            </div>

            <div class="select-product-amount">
              <div>
                <span class="select-product-amount-value">2,500</span>
                <span class="select-product-amount-unit">มล.</span>
              </div>

              <div>
                <span class="select-product-amount-price">220</span>
                <span class="select-product-amount-unit">บาท</span>
              </div>
            </div>
          </div>
        </div>

        <div class="select-product-footer">
          @if ($settings['show_home_button'] ?? true)
            <button type="button" class="select-product-back-button">
              <i class="icon-base ti {{ $settings['home_button_icon'] ?? 'tabler-chevron-left' }}"></i>
              <span>ย้อนกลับ</span>
            </button>
          @endif

          @if ($settings['show_confirm_button'] ?? true)
            <button type="button" class="select-product-confirm-button">
              <span>ตกลง</span>
              <i class="icon-base ti {{ $settings['confirm_button_icon'] ?? 'tabler-chevron-right' }}"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
