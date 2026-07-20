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
    'tabler-arrow-left' => 'Arrow Left',
    'tabler-chevron-left' => 'Chevron Left',
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
    position: relative;
    min-height: 520px;
    border-radius: 0;
    overflow: hidden;
    padding: 118px 34px 22px;
    background:
      radial-gradient(circle at 8% 74%, rgba(255,255,255,.9) 0 12px, rgba(255,255,255,.35) 13px 16px, transparent 17px),
      radial-gradient(circle at 92% 72%, rgba(255,255,255,.85) 0 18px, rgba(255,255,255,.35) 19px 23px, transparent 24px),
      linear-gradient(180deg, #f7fdff 0%, #d8f3ff 55%, #aee6fb 100%);
  }

  .select-product-preview::before {
    content: '';
    position: absolute;
    inset: 0 0 auto;
    height: 112px;
    background:
      linear-gradient(175deg, transparent 0 58%, rgba(255,255,255,.96) 59% 68%, transparent 69%),
      linear-gradient(180deg, #78d5fa 0%, #c9efff 100%);
    pointer-events: none;
  }

  .preview-topbar {
    position: absolute;
    top: 16px;
    left: 22px;
    right: 22px;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .preview-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #28218f;
    font-size: 18px;
    font-weight: 900;
  }

  .preview-brand-logo,
  .preview-ip-logo {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255,255,255,.95);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #0877c9;
    font-weight: 900;
    box-shadow: 0 3px 10px rgba(0, 108, 175, .18);
  }

  .preview-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .preview-mini-button,
  .preview-language {
    height: 34px;
    border: 0;
    border-radius: 7px;
    background: rgba(255,255,255,.95);
    color: #0877c9;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 0 10px;
    font-size: 12px;
    font-weight: 700;
  }

  .preview-language {
    width: 28px;
    padding: 0;
    border-radius: 50%;
    color: #fff;
  }

  .preview-language.th { background: #ef3340; }
  .preview-language.en { background: #1a47b8; }
  .preview-language.cn { background: #ef3340; }

  .preview-stepbar {
    position: absolute;
    top: 75px;
    left: 50%;
    z-index: 3;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
  }

  .preview-step {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #70bfe9;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 7px rgba(0, 107, 180, .22);
  }

  .preview-step.active { background: #0877c9; }
  .preview-step-line { width: 42px; height: 3px; background: rgba(255,255,255,.88); }

  .preview-screen-title {
    position: relative;
    z-index: 2;
    text-align: center;
    color: #111;
    font-size: 18px;
    font-weight: 900;
    margin-bottom: 16px;
  }

  .select-product-content {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    max-width: 640px;
    margin: 0 auto;
  }

  .product-category-panel {
    border-radius: 10px;
    padding: 14px 14px 12px;
    color: #fff;
    box-shadow: 0 6px 12px rgba(0, 78, 140, .13);
  }

  .product-category-panel.primary { background: #0089df; }
  .product-category-panel.secondary { background: #dc3d82; }

  .category-name {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    font-size: 17px;
    font-weight: 900;
    margin-bottom: 10px;
  }

  .product-list-preview {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 9px;
  }

  .product-card-preview {
    position: relative;
    min-height: 112px;
    border: 3px solid transparent;
    border-radius: 8px;
    background: #fff;
    color: #171717;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    padding: 8px 5px 7px;
    overflow: hidden;
    text-align: center;
    font-size: 10px;
    font-weight: 700;
  }

  .product-card-preview.is-selected { border-color: #00a4ef; }

  .product-placeholder-icon {
    font-size: 54px;
    color: #5ab5e5;
    margin-bottom: 5px;
  }

  .product-category-panel.secondary .product-placeholder-icon { color: #ee66a0; }

  .selected-icon-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 21px;
    height: 21px;
    border-radius: 50%;
    background: #0784cc;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
  }

  .amount-options {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    max-width: 640px;
    margin: 10px auto 0;
  }

  .amount-option {
    position: relative;
    min-height: 54px;
    border: 3px solid transparent;
    border-radius: 9px;
    background: rgba(255,255,255,.96);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 18px;
    box-shadow: 0 4px 10px rgba(0, 82, 140, .09);
  }

  .amount-option.is-selected { border-color: #078bd5; }
  .amount-value { color: #0068c9; font-size: 20px; font-weight: 900; }
  .amount-price { color: #111; font-size: 18px; font-weight: 900; }
  .amount-option small { font-size: 13px; font-weight: 800; }

  .select-product-footer {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-top: 14px;
  }

  .select-product-home-button,
  .select-product-confirm-button {
    height: 40px;
    border: 0;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 800;
    box-shadow: 0 4px 10px rgba(0, 70, 130, .15);
  }

  .select-product-home-button {
    min-width: 126px;
    background: #fff;
    color: #0784cc;
  }

  .select-product-confirm-button {
    min-width: 126px;
    background: #0784cc;
    color: #fff;
    font-size: 16px;
  }

  @media (max-width: 991.98px) {
    .select-product-preview { padding-left: 16px; padding-right: 16px; }
    .select-product-content,
    .amount-options { grid-template-columns: 1fr; }
    .preview-brand { font-size: 14px; }
    .preview-topbar { left: 12px; right: 12px; }
    .preview-toolbar .preview-language { display: none; }
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
          <input type="hidden" name="show_back_button" value="0">

<input
    type="checkbox"
    name="show_back_button"
    value="1"
    id="show_back_button"
    class="form-check-input"
    {{ old(
        'show_back_button',
        $settings['show_back_button'] ?? true
    ) ? 'checked' : '' }}
>

<label
    class="form-check-label"
    for="show_back_button"
>
    แสดงปุ่มย้อนกลับ
</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มหน้าหลัก</label>

          <select name="back_button_icon" class="form-select">
    @php
        $backButtonIcon = old(
            'back_button_icon',
            $settings['back_button_icon']
                ?? 'tabler-chevron-left'
        );
    @endphp

    @foreach ($buttonIcons as $iconClass => $iconLabel)
        <option
            value="{{ $iconClass }}"
            {{ $backButtonIcon === $iconClass
                ? 'selected'
                : '' }}
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
        $settings['back_button_action'] ?? 'member_page'
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
        ปรับ Layout ให้ใกล้เคียงหน้าจอเครื่องจริง โดยข้อมูลสินค้าและราคายังดึงจากระบบ
      </p>
    </div>

    <div class="card-body">
      <div class="select-product-preview">
        <div class="preview-topbar">
          <div class="preview-toolbar">
            <button type="button" class="preview-mini-button">
              <i class="icon-base ti tabler-home"></i>
              <span>หน้าหลัก</span>
            </button>
            <span class="preview-language th">TH</span>
            <span class="preview-language en">EN</span>
            <span class="preview-language cn">中</span>
          </div>

          <div class="preview-brand">
            <span class="preview-brand-logo">H</span>
            <span>ผู้เชี่ยวชาญการดูแลผ้าครบวงจร</span>
          </div>

          <span class="preview-ip-logo">IP</span>
        </div>

        <div class="preview-stepbar">
          <span class="preview-step active">
            <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-bottle' }}"></i>
          </span>
          <span class="preview-step-line"></span>
          <span class="preview-step"></span>
          <span class="preview-step-line"></span>
          <span class="preview-step"></span>
          <span class="preview-step-line"></span>
          <span class="preview-step"></span>
        </div>

        <div class="preview-screen-title">เลือกสินค้า</div>

        <div class="select-product-content">
          <div class="product-category-panel primary">
            <div class="category-name">
              <i class="icon-base ti {{ $settings['category_primary_icon'] ?? 'tabler-basket' }}"></i>
              <span>น้ำยาซักผ้า</span>
            </div>

            <div class="product-list-preview">
              <div class="product-card-preview">
                <i class="icon-base ti tabler-bottle product-placeholder-icon"></i>
                <div>กลิ่น เดลี่ ซัน</div>
              </div>

              <div class="product-card-preview is-selected">
                <span class="selected-icon-badge">
                  <i class="icon-base ti {{ $settings['selected_product_icon'] ?? 'tabler-check' }}"></i>
                </span>
                <i class="icon-base ti tabler-bottle product-placeholder-icon"></i>
                <div>กลิ่น ฟลอร่า บลูม</div>
              </div>
            </div>
          </div>

          <div class="product-category-panel secondary">
            <div class="category-name">
              <i class="icon-base ti {{ $settings['category_secondary_icon'] ?? 'tabler-droplet' }}"></i>
              <span>น้ำยาปรับผ้านุ่ม</span>
            </div>

            <div class="product-list-preview">
              <div class="product-card-preview">
                <i class="icon-base ti tabler-bottle product-placeholder-icon"></i>
                <div>กลิ่น มิลค์กี้ แคร์</div>
              </div>

              <div class="product-card-preview">
                <i class="icon-base ti tabler-bottle product-placeholder-icon"></i>
                <div>กลิ่น พิงค์ บลูม</div>
              </div>
            </div>
          </div>
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

        <div class="select-product-footer">
          @if ($settings['show_back_button'] ?? true)
    <button
        type="button"
        class="select-product-home-button"
    >
        <i class="icon-base ti {{
            $settings['back_button_icon']
                ?? 'tabler-chevron-left'
        }}"></i>

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
