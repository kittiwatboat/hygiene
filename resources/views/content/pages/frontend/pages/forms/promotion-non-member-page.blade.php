@php
  $settings = $page->settings_json ?? [];

  $emptyStateImage = $page->media->firstWhere('media_slot', 'empty_state_image');
  $popupPoster = $page->media->firstWhere('media_slot', 'popup_poster');
  $popupQr = $page->media->firstWhere('media_slot', 'popup_qr');

  // fallback รองรับข้อมูลเก่า
  $legacyImage = $page->media->first();
  if (!$emptyStateImage) {
      $emptyStateImage = $legacyImage;
  }

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
  .member-notfound-preview {
    position: relative;
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 24px 20px;
    overflow: hidden;
    min-height: 620px;
  }

  .member-notfound-title {
    text-align: center;
    color: #6f63f6;
    font-weight: 800;
    font-size: 20px;
    margin-bottom: 2px;
  }

  .member-notfound-subtitle {
    text-align: center;
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 18px;
  }

  .member-notfound-step {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
  }

  .member-notfound-step-circle {
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

  .member-notfound-step-circle.done {
    background: #26c875;
  }

  .member-notfound-step-circle.pending {
    background: #e8e8ed;
    color: #9295a0;
  }

  .member-notfound-step-line {
    width: 52px;
    height: 2px;
    background: #6fbff0;
  }

  .member-notfound-background {
    background: rgba(255,255,255,.18);
    border-radius: 14px;
    padding: 18px 20px 22px;
    min-height: 450px;
  }

  .member-notfound-background-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    align-items: stretch;
  }

  .member-notfound-message {
    background: rgba(255,255,255,.55);
    border-radius: 14px;
    min-height: 250px;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .member-notfound-message h4 {
    color: #111827;
    font-size: 17px;
    font-weight: 900;
    margin-bottom: 4px;
  }

  .member-notfound-message p {
    color: #667085;
    font-size: 12px;
    margin-bottom: 16px;
  }

  .member-notfound-icon-wrap {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: #e8f2ff;
    color: #7f91b0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 54px;
    position: relative;
  }

  .member-notfound-icon-wrap::after {
    content: "×";
    position: absolute;
    right: 4px;
    bottom: 4px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #ff5252;
    color: #fff;
    border: 4px solid #dff8ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 900;
  }

  .member-notfound-banner-panel {
    background: #fff;
    border-radius: 14px;
    padding: 10px;
    min-height: 250px;
    overflow: hidden;
  }

  .member-notfound-banner {
    width: 100%;
    min-height: 230px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
  }

  .member-notfound-banner-placeholder {
    min-height: 230px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0d8bd7 0%, #65c7f7 35%, #fff 35%, #fff 100%);
    color: #075db8;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px;
    font-weight: 900;
    font-size: 26px;
    line-height: 1.2;
  }

  .member-notfound-footer {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 18px;
    flex-wrap: wrap;
  }

  .member-notfound-back-button,
  .member-notfound-skip-button,
  .member-notfound-register-button {
    border: 0;
    border-radius: 9px;
    min-width: 150px;
    min-height: 48px;
    padding: 10px 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
  }

  .member-notfound-back-button {
    background: #fff;
    color: #0877c9;
  }

  .member-notfound-skip-button {
    background: #eef8ff;
    color: #0877c9;
    border: 1px solid #8fd0f4;
  }

  .member-notfound-register-button {
    background: #0877c9;
    color: #fff;
  }

  .popup-demo-overlay {
    position: absolute;
    inset: 0;
    background: rgba(17, 24, 39, .55);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    z-index: 20;
  }

  .popup-demo-box {
    width: 680px;
    max-width: 96%;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 20px 40px rgba(0,0,0,.24);
    padding: 16px;
  }

  .popup-demo-grid {
    display: grid;
    grid-template-columns: 1.25fr .85fr;
    gap: 14px;
    align-items: stretch;
  }

  .popup-demo-poster {
    border-radius: 14px;
    overflow: hidden;
    background: linear-gradient(135deg, #effaff 0%, #ffffff 60%, #d9f1ff 100%);
    min-height: 340px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
  }

  .popup-demo-poster img {
    width: 100%;
    height: 100%;
    min-height: 316px;
    object-fit: cover;
    border-radius: 12px;
    display: block;
  }

  .popup-demo-poster-placeholder {
    width: 100%;
    min-height: 316px;
    border-radius: 12px;
    background: linear-gradient(180deg, #f4fbff 0%, #dbf1ff 100%);
    color: #0b79c9;
    font-weight: 900;
    font-size: 34px;
    line-height: 1.15;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 24px;
  }

  .popup-demo-qr-panel {
    border-radius: 14px;
    background: #f4fbff;
    border: 1px solid #d2ebff;
    min-height: 340px;
    padding: 16px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .popup-demo-qr-title {
    color: #0f172a;
    font-size: 18px;
    line-height: 1.4;
    font-weight: 900;
    margin-bottom: 14px;
    text-align: center;
  }

  .popup-demo-qr {
    width: 170px;
    height: 170px;
    border: 8px solid #fff;
    border-radius: 10px;
    background:
      linear-gradient(90deg, #000 12px, transparent 12px) 0 0/42px 42px,
      linear-gradient(#000 12px, transparent 12px) 0 0/42px 42px,
      linear-gradient(90deg, transparent 30px, #000 30px 42px, transparent 42px) 0 0/42px 42px,
      linear-gradient(transparent 30px, #000 30px 42px, transparent 42px) 0 0/42px 42px,
      #fff;
    box-shadow: inset 0 0 0 10px #fff;
    overflow: hidden;
  }

  .popup-demo-qr img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .popup-demo-qr-note {
    margin-top: 12px;
    color: #667085;
    font-size: 11px;
    text-align: center;
  }

  .popup-demo-actions {
    display: grid;
    grid-template-columns: 1fr 1.25fr;
    gap: 14px;
    margin-top: 14px;
  }

  .popup-demo-actions button {
    border: 0;
    border-radius: 9px;
    min-height: 46px;
    font-weight: 800;
  }

  .popup-demo-skip {
    background: #fff;
    color: #0877c9;
    border: 1px solid #d4eafb;
  }

  .popup-demo-register {
    background: #0877c9;
    color: #fff;
  }

  @media (max-width: 991.98px) {
    .member-notfound-background-content,
    .popup-demo-grid,
    .popup-demo-actions {
      grid-template-columns: 1fr;
    }

    .member-notfound-footer {
      flex-direction: column;
    }

    .member-notfound-back-button,
    .member-notfound-skip-button,
    .member-notfound-register-button {
      width: 100%;
    }
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
          <label class="form-label">Translation Key หัวข้อ</label>
          <input type="text" value="member_page.not_found_title" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key คำอธิบาย</label>
          <input type="text" value="member_page.not_found_subtitle" class="form-control" readonly>
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
          <label class="form-check-label" for="show_back_button">แสดงปุ่มย้อนกลับ</label>
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
          <input
            type="checkbox"
            name="show_skip_button"
            value="1"
            id="show_skip_button"
            class="form-check-input"
            {{ $showSkipButton ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_skip_button">แสดงปุ่มข้าม</label>
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
          <input
            type="checkbox"
            name="show_register_button"
            value="1"
            id="show_register_button"
            class="form-check-input"
            {{ $showRegisterButton ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_register_button">แสดงปุ่มสมัครสมาชิก</label>
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
      <p class="text-muted mb-0">อัปโหลดรูปพื้นหลังหน้าไม่พบสมาชิก และรูป Popup ชวนสมัครสมาชิก</p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.media.store', $page) }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="empty_state_image">

        <label class="form-label">รูปด้านหลังหน้าไม่พบสมาชิก</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">
          บันทึก / เปลี่ยนรูปด้านหลัง
        </button>
      </form>

      <form action="{{ route('frontend.pages.media.store', $page) }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="popup_poster">

        <label class="form-label">รูป Poster Popup</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">
          บันทึก / เปลี่ยน Poster Popup
        </button>
      </form>

      <form action="{{ route('frontend.pages.media.store', $page) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="popup_qr">

        <label class="form-label">รูป QR Code Popup</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">
          บันทึก / เปลี่ยน QR Code Popup
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้าไม่พบสมาชิก + Popup</h5>
      <p class="text-muted mb-0">ตัวอย่างหน้าหลังเมื่อไม่พบสมาชิก และมี Popup ชวนสมัครสมาชิกแสดงซ้อนขึ้นมา</p>
    </div>

    <div class="card-body">
      <div class="member-notfound-preview">
        <div class="member-notfound-title">member_page.not_found_title</div>
        <div class="member-notfound-subtitle">member_page.not_found_subtitle</div>

        <div class="member-notfound-step">
          <span class="member-notfound-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>
          <span class="member-notfound-step-line"></span>
          <span class="member-notfound-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>
          <span class="member-notfound-step-line"></span>
          <span class="member-notfound-step-circle">
            <i class="icon-base ti {{ $stepIcon }}"></i>
          </span>
          <span class="member-notfound-step-line"></span>
          <span class="member-notfound-step-circle pending">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="member-notfound-background">
          <div class="member-notfound-background-content">
            <div class="member-notfound-message">
              <h4>ไม่พบข้อมูลสมาชิก</h4>
              <p>กรุณาตรวจสอบหมายเลขโทรศัพท์อีกครั้ง</p>

              <div class="member-notfound-icon-wrap">
                <i class="icon-base ti {{ $stepIcon }}"></i>
              </div>
            </div>

            <div class="member-notfound-banner-panel">
              @if ($emptyStateImage && $emptyStateImage->media_type === 'image')
                <img src="{{ $emptyStateImage->file_url }}" alt="ไม่พบสมาชิก" class="member-notfound-banner">
              @else
                <div class="member-notfound-banner-placeholder">
                  ไม่พบข้อมูลสมาชิก
                </div>
              @endif
            </div>
          </div>

          <div class="member-notfound-footer">
            @if ($showBackButton)
              <button type="button" class="member-notfound-back-button">
                <i class="icon-base ti {{ $backButtonIcon }}"></i>
                <span>ย้อนกลับ</span>
              </button>
            @endif

            @if ($showSkipButton)
              <button type="button" class="member-notfound-skip-button">
                <span>ข้าม</span>
                <i class="icon-base ti {{ $skipButtonIcon }}"></i>
              </button>
            @endif

            @if ($showRegisterButton)
              <button type="button" class="member-notfound-register-button">
                <span>สมัครสมาชิก</span>
                <i class="icon-base ti {{ $registerButtonIcon }}"></i>
              </button>
            @endif
          </div>
        </div>

        <div class="popup-demo-overlay">
          <div class="popup-demo-box">
            <div class="popup-demo-grid">
              <div class="popup-demo-poster">
                @if ($popupPoster && $popupPoster->media_type === 'image')
                  <img src="{{ $popupPoster->file_url }}" alt="Poster Popup">
                @else
                  <div class="popup-demo-poster-placeholder">
                    สมัครสมาชิกวันนี้<br>รับส่วนลดทันที 20 บาท
                  </div>
                @endif
              </div>

              <div class="popup-demo-qr-panel">
                <div class="popup-demo-qr-title">
                  สแกน QR Code<br>
                  เพื่อสมัครสมาชิก
                </div>

                <div class="popup-demo-qr">
                  @if ($popupQr && $popupQr->media_type === 'image')
                    <img src="{{ $popupQr->file_url }}" alt="QR Code Popup">
                  @endif
                </div>

                <div class="popup-demo-qr-note">
                  สแกนด้วยโทรศัพท์มือถือเพื่อสมัครสมาชิก
                </div>
              </div>
            </div>

            <div class="popup-demo-actions">
              <button type="button" class="popup-demo-skip">
                ข้าม
              </button>

              <button type="button" class="popup-demo-register">
                สมัครสมาชิก
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
