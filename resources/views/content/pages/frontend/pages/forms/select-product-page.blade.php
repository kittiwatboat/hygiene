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

  .select-product-content {
    display: grid;
    grid-template-columns: 56% 44%;
    gap: 18px;
    align-items: stretch;
  }

  .product-category-panel {
    display: grid;
    grid-template-columns: 38% 62%;
    min-height: 122px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 12px;
    border: 2px solid transparent;
  }

  .product-category-panel.is-active {
    border-color: #0084d8;
  }

  .category-info {
    color: #fff;
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .category-info i {
    font-size: 46px;
    margin-top: 8px;
  }

  .category-name {
    font-size: 18px;
    font-weight: 800;
  }

  .product-list-preview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    background: #fff;
    padding: 8px;
  }

  .product-card-preview {
    position: relative;
    background: #f7fbff;
    border-radius: 8px;
    border: 2px solid #d6e7f5;
    min-height: 104px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    font-size: 12px;
    overflow: hidden;
  }

  .product-card-preview.is-selected {
    border-color: #0084d8;
  }

  .product-card-preview i.product-placeholder-icon {
    font-size: 38px;
    color: #7dbce8;
  }

  .selected-icon-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    background: #0084d8;
    color: #fff;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
  }

  .amount-panel {
    background: rgba(255,255,255,.85);
    border-radius: 12px;
    padding: 16px;
    min-height: 100%;
  }

  .amount-title {
    color: #0075c9;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
  }

  .amount-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }

  .amount-option {
    background: #fff;
    border: 2px solid #d6e7f5;
    border-radius: 10px;
    padding: 14px;
    position: relative;
    min-height: 100px;
  }

  .amount-option.is-selected {
    border-color: #0084d8;
  }

  .amount-value {
    font-size: 26px;
    color: #006dcc;
    font-weight: 800;
    line-height: 1;
  }

  .amount-price {
    font-size: 24px;
    font-weight: 800;
    color: #1f2d3d;
    margin-top: 8px;
  }

  .total-card {
    margin-top: 14px;
    background: #fff;
    border-radius: 10px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 800;
  }

  .total-card-label {
    color: #006dcc;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .total-card-price {
    font-size: 28px;
    color: #0084d8;
  }

  .select-product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
    gap: 14px;
  }

  .select-product-home-button {
    border: 0;
    background: #fff;
    color: #0877c9;
    border-radius: 8px;
    padding: 10px 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(0, 0, 0, .08);
  }

  .select-product-confirm-button {
    border: 0;
    background: #0877c9;
    color: #fff;
    border-radius: 10px;
    padding: 12px 34px;
    min-width: 160px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 800;
    box-shadow: 0 6px 14px rgba(0, 90, 160, .28);
  }

  @media (max-width: 991.98px) {
    .select-product-content {
      grid-template-columns: 1fr;
    }

    .product-category-panel {
      grid-template-columns: 1fr;
    }

    .select-product-footer {
      flex-direction: column;
      align-items: stretch;
    }

    .select-product-home-button,
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
        ตัวอย่าง layout เท่านั้น ข้อมูลสินค้าและปริมาณจะดึงจากระบบจริง
      </p>
    </div>

    <div class="card-body">
      <div class="select-product-preview">
        <div class="text-center mb-3">
          <div class="fw-bold text-primary fs-5">
            select_product_page.title
          </div>
          <small class="text-muted">
            select_product_page.subtitle
          </small>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
          <span class="badge rounded-pill bg-success p-2">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span style="width: 50px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-success p-2">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span style="width: 50px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-primary p-2">
            <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-bottle' }}"></i>
          </span>

          <span style="width: 50px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-label-secondary p-2">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="select-product-content">
          <div>
            <div class="product-category-panel is-active">
              <div class="category-info" style="background: #43b8e8;">
                <div class="category-name">น้ำยาซักผ้า</div>
                <i class="icon-base ti {{ $settings['category_primary_icon'] ?? 'tabler-basket' }}"></i>
              </div>

              <div class="product-list-preview">
                <div class="product-card-preview">
                  <i class="icon-base ti tabler-bottle product-placeholder-icon"></i>
                  <div>กลิ่นไฮจีน สีฟ้า</div>
                </div>

                <div class="product-card-preview">
                  <i class="icon-base ti tabler-bottle product-placeholder-icon"></i>
                  <div>กลิ่นฟลอร่า บลูม</div>
                </div>
              </div>
            </div>

            <div class="product-category-panel" style="border-color: #f84cc6;">
              <div class="category-info" style="background: #f49bdb;">
                <div class="category-name">น้ำยาปรับผ้านุ่ม</div>
                <i class="icon-base ti {{ $settings['category_secondary_icon'] ?? 'tabler-droplet' }}"></i>
              </div>

              <div class="product-list-preview">
                <div class="product-card-preview is-selected">
                  <span class="selected-icon-badge">
                    <i class="icon-base ti {{ $settings['selected_product_icon'] ?? 'tabler-check' }}"></i>
                  </span>
                  <i class="icon-base ti tabler-bottle product-placeholder-icon"></i>
                  <div>กลิ่นมิลค์กี้ แคร์</div>
                </div>

                <div class="product-card-preview">
                  <i class="icon-base ti tabler-bottle product-placeholder-icon"></i>
                  <div>กลิ่นพิ้งค์ บลูม</div>
                </div>
              </div>
            </div>
          </div>

          <div class="amount-panel">
            <div class="amount-title">
              <i class="icon-base ti {{ $settings['amount_section_icon'] ?? 'tabler-basket' }}"></i>
              <span>select_product_page.amount_title</span>
            </div>

            <div class="amount-options">
              <div class="amount-option is-selected">
                <span class="selected-icon-badge">
                  <i class="icon-base ti {{ $settings['selected_product_icon'] ?? 'tabler-check' }}"></i>
                </span>

                <div class="amount-value">1,250 <small>มล.</small></div>
                <div class="amount-price">115 <small>บาท</small></div>
              </div>

              <div class="amount-option">
                <div class="amount-value">2,500 <small>มล.</small></div>
                <div class="amount-price">220 <small>บาท</small></div>
              </div>
            </div>

            <div class="total-card">
              <div class="total-card-label">
                <i class="icon-base ti {{ $settings['total_price_icon'] ?? 'tabler-wallet' }}"></i>
                <span>select_product_page.total_price</span>
              </div>

              <div class="total-card-price">
                115 <small>บาท</small>
              </div>
            </div>
          </div>
        </div>

        <div class="select-product-footer">
          @if ($settings['show_home_button'] ?? true)
            <button type="button" class="select-product-home-button">
              <i class="icon-base ti {{ $settings['home_button_icon'] ?? 'tabler-home' }}"></i>
              <span>select_product_page.home_button</span>
            </button>
          @endif

          @if ($settings['show_confirm_button'] ?? true)
            <button type="button" class="select-product-confirm-button">
              <span>select_product_page.confirm_button</span>
              <i class="icon-base ti {{ $settings['confirm_button_icon'] ?? 'tabler-chevron-right' }}"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
