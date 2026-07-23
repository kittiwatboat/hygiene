@php
  $settings = $page->settings_json ?? [];

  $emptyStateImage = $page->media->firstWhere('media_slot', 'empty_state_image');
  $popupPoster = $page->media->firstWhere('media_slot', 'popup_poster');
  $popupQr = $page->media->firstWhere('media_slot', 'popup_qr');

  $stepIcons = [
    'tabler-user-off' => 'User Off',
    'tabler-user-question' => 'User Question',
    'tabler-user-x' => 'User X',
    'tabler-user' => 'User',
    'tabler-alert-circle' => 'Alert',
  ];

  $buttonIcons = [
    'tabler-arrow-left' => 'Arrow Left',
    'tabler-chevron-left' => 'Chevron Left',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-user-plus' => 'User Plus',
    'tabler-player-track-next' => 'Track Next',
    'tabler-home' => 'Home',
  ];

  $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-user-off');
  $backButtonIcon = old('back_button_icon', $settings['back_button_icon'] ?? 'tabler-chevron-left');
  $registerButtonIcon = old('register_button_icon', $settings['register_button_icon'] ?? 'tabler-user-plus');
  $skipButtonIcon = old('skip_button_icon', $settings['skip_button_icon'] ?? 'tabler-player-track-next');

  $showBackButton = (bool) old('show_back_button', $settings['show_back_button'] ?? true);
  $showRegisterButton = (bool) old('show_register_button', $settings['show_register_button'] ?? true);
  $showSkipButton = (bool) old('show_skip_button', $settings['show_skip_button'] ?? true);
@endphp

<style>
  .member-reference-preview {
    position: relative;
    background: linear-gradient(180deg, #ddf4ff 0%, #d6f0ff 100%);
    border-radius: 14px;
    padding: 20px 22px 22px;
    overflow: hidden;
    min-height: 700px;
  }
  .member-reference-preview::before,
  .member-reference-preview::after {
    content: "";
    position: absolute;
    left: -4%;
    width: 108%;
    border-radius: 50%;
    pointer-events: none;
  }
  .member-reference-preview::before {
    top: 66px;
    height: 70px;
    background: rgba(255, 255, 255, .75);
  }
  .member-reference-preview::after {
    bottom: -40px;
    height: 120px;
    background: rgba(110, 192, 245, .28);
  }
  .member-ref-topbar {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
  }
  .member-ref-topbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .member-ref-home-button {
    min-height: 42px;
    padding: 8px 14px;
    border-radius: 10px;
    background: #fff;
    color: #0877c9;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
  }
  .member-ref-lang {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #ff4d4f;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
  }
  .member-ref-brand {
    text-align: center;
    flex: 1;
    color: #2e2a8f;
    font-size: 18px;
    font-weight: 900;
    line-height: 1.2;
  }
  .member-ref-logos {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #0877c9;
    font-weight: 900;
    font-size: 12px;
  }
  .member-ref-logo-dot {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
  }
  .member-ref-step {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 12px 0 24px;
  }
  .member-ref-step-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    background: #6f63f6;
  }
  .member-ref-step-circle.done { background: #26c875; }
  .member-ref-step-circle.pending {
    background: #e8e8ed;
    color: #9295a0;
  }
  .member-ref-step-line {
    width: 48px;
    height: 2px;
    background: #6fbff0;
  }
  .member-ref-empty-area {
    position: relative;
    z-index: 1;
    min-height: 420px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #111827;
  }
  .member-ref-empty-title {
    font-size: 18px;
    font-weight: 900;
    margin-bottom: 18px;
  }
  .member-ref-empty-image {
    width: 200px;
    max-width: 100%;
    display: block;
    object-fit: contain;
  }
  .member-ref-empty-placeholder {
    width: 190px;
    height: 190px;
    border-radius: 28px;
    background: rgba(195, 213, 250, .34);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7484a7;
    font-size: 74px;
  }
  .member-ref-empty-placeholder::after {
    content: "⊘";
    position: absolute;
    right: 18px;
    bottom: 12px;
    color: #ff5a58;
    font-size: 44px;
    font-weight: 900;
  }
  .member-ref-popup-overlay {
    position: absolute;
    inset: 0;
    background: rgba(18, 25, 41, .58);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 28px;
    z-index: 20;
  }
  .member-ref-popup-box {
    width: 720px;
    max-width: 100%;
    background: #fff;
    border-radius: 18px;
    padding: 12px;
    box-shadow: 0 22px 40px rgba(0,0,0,.24);
  }
  .member-ref-popup-grid {
    display: grid;
    grid-template-columns: 1.35fr .85fr;
    gap: 12px;
    align-items: stretch;
  }
  .member-ref-popup-poster {
    min-height: 355px;
    border-radius: 14px;
    overflow: hidden;
    background: linear-gradient(180deg, #eef9ff 0%, #d8f1ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .member-ref-popup-poster img {
    width: 100%;
    height: 100%;
    min-height: 355px;
    object-fit: cover;
    display: block;
  }
  .member-ref-popup-poster-placeholder {
    width: 100%;
    min-height: 355px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #0b79c9;
    font-weight: 900;
    font-size: 34px;
    line-height: 1.15;
    padding: 26px;
  }
  .member-ref-popup-side {
    min-height: 355px;
    border-radius: 14px;
    background: #f6fbff;
    border: 1px solid #d7eeff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px 14px;
    text-align: center;
  }
  .member-ref-popup-side-title {
    color: #0f172a;
    font-size: 17px;
    font-weight: 900;
    line-height: 1.45;
    margin-bottom: 14px;
  }
  .member-ref-popup-qr {
    width: 170px;
    height: 170px;
    border-radius: 10px;
    overflow: hidden;
    border: 8px solid #fff;
    background:
      linear-gradient(90deg, #000 12px, transparent 12px) 0 0/42px 42px,
      linear-gradient(#000 12px, transparent 12px) 0 0/42px 42px,
      linear-gradient(90deg, transparent 30px, #000 30px 42px, transparent 42px) 0 0/42px 42px,
      linear-gradient(transparent 30px, #000 30px 42px, transparent 42px) 0 0/42px 42px,
      #fff;
    box-shadow: 0 8px 18px rgba(0,0,0,.08);
  }
  .member-ref-popup-qr img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  .member-ref-popup-note {
    margin-top: 12px;
    color: #ff6b6b;
    font-size: 11px;
    line-height: 1.4;
  }
  .member-ref-popup-actions {
    display: grid;
    grid-template-columns: 1fr 1.25fr;
    gap: 12px;
    margin-top: 12px;
  }
  .member-ref-popup-skip,
  .member-ref-popup-register {
    border: 0;
    border-radius: 10px;
    min-height: 48px;
    font-weight: 800;
  }
  .member-ref-popup-skip {
    background: #fff;
    color: #0877c9;
    border: 1px solid #d2e9fb;
  }
  .member-ref-popup-register {
    background: #0a89d7;
    color: #fff;
  }
  @media (max-width: 991.98px) {
    .member-ref-topbar { flex-direction: column; align-items: stretch; }
    .member-ref-topbar-left,
    .member-ref-logos { justify-content: center; }
    .member-ref-popup-grid,
    .member-ref-popup-actions { grid-template-columns: 1fr; }
    .member-ref-popup-side,
    .member-ref-popup-poster img,
    .member-ref-popup-poster-placeholder { min-height: 280px; }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าไม่พบข้อมูลสมาชิก</h5>
      <p class="text-muted mb-0">ใช้ในกรณีค้นหาเบอร์โทรแล้วไม่พบข้อมูลสมาชิก</p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">ชื่อหน้า <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name', $page->name) }}" class="form-control @error('name') is-invalid @enderror" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key หัวข้อ</label>
          <input type="text" value="member_page.not_found_title" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key คำอธิบาย</label>
          <input type="text" value="member_page.not_found_subtitle" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">หมายเหตุ</label>
          <textarea name="remark" rows="3" class="form-control @error('remark') is-invalid @enderror">{{ old('remark', $page->remark) }}</textarea>
        </div>

        <hr class="my-4">
        <h6 class="mb-3">Icon หน้านี้</h6>

        <div class="mb-3">
          <label class="form-label">Icon Step</label>
          <select name="step_icon" class="form-select">
            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $stepIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">
        <h6 class="mb-3">ปุ่ม Popup</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_back_button" value="0">
          <input type="checkbox" name="show_back_button" value="1" id="show_back_button" class="form-check-input" {{ $showBackButton ? 'checked' : '' }}>
          <label class="form-check-label" for="show_back_button">แสดงปุ่มย้อนกลับหน้าเบื้องหลัง</label>
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

        <input type="hidden" name="back_button_action" value="{{ old('back_button_action', $settings['back_button_action'] ?? 'phone_verify_page') }}">

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_skip_button" value="0">
          <input type="checkbox" name="show_skip_button" value="1" id="show_skip_button" class="form-check-input" {{ $showSkipButton ? 'checked' : '' }}>
          <label class="form-check-label" for="show_skip_button">แสดงปุ่มข้ามใน Popup</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มข้าม</label>
          <select name="skip_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $skipButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="skip_button_action" value="{{ old('skip_button_action', $settings['skip_button_action'] ?? 'select_product_page') }}">

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_register_button" value="0">
          <input type="checkbox" name="show_register_button" value="1" id="show_register_button" class="form-check-input" {{ $showRegisterButton ? 'checked' : '' }}>
          <label class="form-check-label" for="show_register_button">แสดงปุ่มสมัครสมาชิกใน Popup</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มสมัครสมาชิก</label>
          <select name="register_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $registerButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="register_button_action" value="{{ old('register_button_action', $settings['register_button_action'] ?? 'register_member') }}">

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้าไม่พบสมาชิก
        </button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">จัดการรูปแสดงผล</h5>
      <p class="text-muted mb-0">อัปโหลดรูปหน้าไม่พบสมาชิก และรูป Popup ชวนสมัครสมาชิก</p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.media.store', $page) }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="empty_state_image">

        <label class="form-label">รูปหน้าไม่พบสมาชิก</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">บันทึก / เปลี่ยนรูปหน้าไม่พบสมาชิก</button>
      </form>

      <form action="{{ route('frontend.pages.media.store', $page) }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="popup_poster">

        <label class="form-label">รูป Poster Popup</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">บันทึก / เปลี่ยน Poster Popup</button>
      </form>

      <form action="{{ route('frontend.pages.media.store', $page) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="popup_qr">

        <label class="form-label">รูป QR Code Popup</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">บันทึก / เปลี่ยน QR Code Popup</button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้าไม่พบสมาชิก + Popup</h5>
      <p class="text-muted mb-0">พื้นหลังเป็นหน้าไม่พบสมาชิก และมี Popup สมัครสมาชิกซ้อนขึ้นมาตามแบบอ้างอิง</p>
    </div>

    <div class="card-body">
      <div class="member-reference-preview">
        <div class="member-ref-topbar">
          <div class="member-ref-topbar-left">
            <div class="member-ref-home-button">
              <i class="icon-base ti tabler-home"></i>
              <span>หน้าหลัก</span>
            </div>

            <span class="member-ref-lang">TH</span>
            <span class="member-ref-lang" style="background:#315efb;">EN</span>
            <span class="member-ref-lang" style="background:#ff4d4f;">中</span>
          </div>

          <div class="member-ref-brand">ผู้เชี่ยวชาญการดูแลผ้าครบวงจร</div>

          <div class="member-ref-logos">
            <span class="member-ref-logo-dot">H</span>
            <span class="member-ref-logo-dot">IP</span>
          </div>
        </div>

        <div class="member-ref-step">
          <span class="member-ref-step-circle done"><i class="icon-base ti tabler-check"></i></span>
          <span class="member-ref-step-line"></span>
          <span class="member-ref-step-circle done"><i class="icon-base ti tabler-check"></i></span>
          <span class="member-ref-step-line"></span>
          <span class="member-ref-step-circle done"><i class="icon-base ti tabler-check"></i></span>
          <span class="member-ref-step-line"></span>
          <span class="member-ref-step-circle"><i class="icon-base ti {{ $stepIcon }}"></i></span>
          <span class="member-ref-step-line"></span>
          <span class="member-ref-step-circle pending"><i class="icon-base ti tabler-minus"></i></span>
        </div>

        <div class="member-ref-empty-area">
          <div class="member-ref-empty-title">ไม่พบข้อมูลสมาชิก</div>

          @if ($emptyStateImage && $emptyStateImage->media_type === 'image')
            <img src="{{ $emptyStateImage->file_url }}" alt="ไม่พบข้อมูลสมาชิก" class="member-ref-empty-image">
          @else
            <div class="member-ref-empty-placeholder">
              <i class="icon-base ti {{ $stepIcon }}"></i>
            </div>
          @endif
        </div>

        <div class="member-ref-popup-overlay">
          <div class="member-ref-popup-box">
            <div class="member-ref-popup-grid">
              <div class="member-ref-popup-poster">
                @if ($popupPoster && $popupPoster->media_type === 'image')
                  <img src="{{ $popupPoster->file_url }}" alt="Poster Popup">
                @else
                  <div class="member-ref-popup-poster-placeholder">สมัครสมาชิกวันนี้<br>รับส่วนลดทันที 20 บาท</div>
                @endif
              </div>

              <div class="member-ref-popup-side">
                <div class="member-ref-popup-side-title">
                  สแกน QR Code<br>เพื่อสมัครสมาชิก
                </div>

                <div class="member-ref-popup-qr">
                  @if ($popupQr && $popupQr->media_type === 'image')
                    <img src="{{ $popupQr->file_url }}" alt="QR Code Popup">
                  @endif
                </div>

                <div class="member-ref-popup-note">
                  สแกนด้วยโทรศัพท์มือถือเพื่อสมัครสมาชิก
                </div>
              </div>
            </div>

            <div class="member-ref-popup-actions">
              <button type="button" class="member-ref-popup-skip">ข้าม</button>
              <button type="button" class="member-ref-popup-register">สมัครสมาชิก</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
