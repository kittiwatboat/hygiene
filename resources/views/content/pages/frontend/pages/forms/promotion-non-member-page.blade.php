@php
  $settings = $page->settings_json ?? [];
  $firstMedia = $page->media->first();

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
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-home' => 'Home',
  ];

  $stepIcon = old(
    'step_icon',
    $settings['step_icon'] ?? 'tabler-user-off'
  );

  $backButtonIcon = old(
    'back_button_icon',
    $settings['back_button_icon'] ?? 'tabler-chevron-left'
  );

  $registerButtonIcon = old(
    'register_button_icon',
    $settings['register_button_icon'] ?? 'tabler-user-plus'
  );

  $skipButtonIcon = old(
    'skip_button_icon',
    $settings['skip_button_icon'] ?? 'tabler-player-track-next'
  );

  $showBackButton = (bool) old(
    'show_back_button',
    $settings['show_back_button'] ?? true
  );

  $showRegisterButton = (bool) old(
    'show_register_button',
    $settings['show_register_button'] ?? true
  );

  $showSkipButton = (bool) old(
    'show_skip_button',
    $settings['show_skip_button'] ?? true
  );
@endphp

<style>
  .member-empty-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .member-empty-title {
    text-align: center;
    color: #6f63f6;
    font-weight: 800;
    font-size: 20px;
    margin-bottom: 2px;
  }

  .member-empty-subtitle {
    text-align: center;
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 16px;
  }

  .member-empty-step {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
  }

  .member-empty-step-circle {
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

  .member-empty-step-circle.done {
    background: #26c875;
  }

  .member-empty-step-circle.pending {
    background: #e8e8ed;
    color: #9295a0;
  }

  .member-empty-step-line {
    width: 52px;
    height: 2px;
    background: #6fbff0;
  }

  .member-empty-content {
    display: grid;
    grid-template-columns: 44% 56%;
    gap: 18px;
    align-items: stretch;
  }

  .member-empty-message {
    background: rgba(255,255,255,.5);
    border-radius: 14px;
    min-height: 270px;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .member-empty-icon-wrap {
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
    margin-bottom: 14px;
  }

  .member-empty-icon-wrap::after {
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

  .member-empty-heading {
    color: #111827;
    font-size: 17px;
    font-weight: 900;
    margin-bottom: 4px;
  }

  .member-empty-description {
    color: #667085;
    font-size: 12px;
  }

  .member-empty-banner-panel {
    background: #fff;
    border-radius: 14px;
    padding: 10px;
    min-height: 270px;
    overflow: hidden;
  }

  .member-empty-banner {
    width: 100%;
    height: 100%;
    min-height: 250px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
  }

  .member-empty-banner-placeholder {
    min-height: 250px;
    border-radius: 10px;
    background:
      linear-gradient(135deg, #0d8bd7 0%, #65c7f7 35%, #fff 35%, #fff 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #075db8;
    text-align: center;
    padding: 20px;
  }

  .member-empty-banner-placeholder strong {
    font-size: 28px;
    line-height: 1.1;
  }

  .member-empty-banner-placeholder span {
    margin-top: 8px;
    font-size: 13px;
  }

  .member-empty-footer {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 18px;
    flex-wrap: wrap;
  }

  .member-empty-back-button,
  .member-empty-skip-button,
  .member-empty-register-button {
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

  .member-empty-back-button {
    background: #fff;
    color: #0877c9;
  }

  .member-empty-skip-button {
    background: #eef8ff;
    color: #0877c9;
    border: 1px solid #8fd0f4;
  }

  .member-empty-register-button {
    background: #0877c9;
    color: #fff;
    font-size: 17px;
  }

  .member-register-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1055;
    padding: 16px;
  }

  .member-register-modal.is-open {
    display: flex;
  }

  .member-register-modal-box {
    background: #fff;
    border-radius: 16px;
    width: 680px;
    max-width: 96%;
    padding: 16px;
    text-align: center;
    box-shadow: 0 18px 40px rgba(0,0,0,.22);
  }

  .member-register-popup-grid {
    display: grid;
    grid-template-columns: 1.3fr .8fr;
    gap: 14px;
    align-items: stretch;
  }

  .member-register-poster {
    min-height: 330px;
    border-radius: 12px;
    background:
      linear-gradient(180deg, rgba(255,255,255,.15), rgba(255,255,255,.15)),
      linear-gradient(135deg, #e7f7ff 0%, #ffffff 55%, #d8f2ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    color: #0877c9;
    font-size: 30px;
    font-weight: 900;
    padding: 20px;
  }

  .member-register-qr-panel {
    min-height: 330px;
    border-radius: 12px;
    background: #f4fbff;
    border: 1px solid #cfeaff;
    padding: 18px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .member-register-popup-actions {
    display: grid;
    grid-template-columns: 1fr 1.25fr;
    gap: 14px;
    margin-top: 14px;
  }

  .member-register-popup-actions button {
    border: 0;
    border-radius: 9px;
    min-height: 46px;
    font-weight: 800;
  }

  .member-register-popup-skip {
    background: #fff;
    color: #0877c9;
    border: 1px solid #d4eafb !important;
  }

  .member-register-popup-register {
    background: #0877c9;
    color: #fff;
  }

  .member-register-modal.preview-mode {
    position: absolute;
    inset: 0;
  }

  .member-register-modal-title {
    font-size: 14px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 12px;
  }

  .member-register-qr {
    width: 160px;
    height: 160px;
    margin: 0 auto;
    border: 8px solid #f3f6fa;
    border-radius: 8px;
    background:
      linear-gradient(90deg, #000 12px, transparent 12px) 0 0/40px 40px,
      linear-gradient(#000 12px, transparent 12px) 0 0/40px 40px,
      linear-gradient(90deg, transparent 28px, #000 28px 40px, transparent 40px) 0 0/40px 40px,
      linear-gradient(transparent 28px, #000 28px 40px, transparent 40px) 0 0/40px 40px,
      #fff;
    box-shadow: inset 0 0 0 10px #fff;
  }

  .member-register-modal-note {
    font-size: 11px;
    color: #667085;
    margin-top: 12px;
  }

  .member-register-modal-close {
    margin-top: 12px;
    border: 0;
    border-radius: 8px;
    min-height: 40px;
    padding: 8px 14px;
    background: #0877c9;
    color: #fff;
    font-weight: 800;
  }

  @media (max-width: 991.98px) {
    .member-empty-content {
      grid-template-columns: 1fr;
    }

    .member-empty-footer {
      flex-direction: column;
    }

    .member-empty-back-button,
    .member-empty-skip-button,
    .member-empty-register-button {
      width: 100%;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าไม่พบข้อมูลสมาชิก</h5>
      <p class="text-muted mb-0">
        ใช้ในกรณีค้นหาเบอร์โทรแล้วไม่พบข้อมูลสมาชิก
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
          <label class="form-label">Translation Key หัวข้อ</label>
          <input
            type="text"
            value="member_page.not_found_title"
            class="form-control"
            readonly
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key คำอธิบาย</label>
          <input
            type="text"
            value="member_page.not_found_subtitle"
            class="form-control"
            readonly
          >
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
          <label class="form-check-label" for="show_back_button">
            แสดงปุ่มย้อนกลับ
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มย้อนกลับ</label>
          <select name="back_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $backButtonIcon === $iconClass ? 'selected' : '' }}
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
            $settings['back_button_action'] ?? 'phone_verify_page'
          ) }}"
        >

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
          <label class="form-check-label" for="show_skip_button">
            แสดงปุ่มข้าม
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มข้าม</label>
          <select name="skip_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $skipButtonIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="skip_button_action"
          value="{{ old(
            'skip_button_action',
            $settings['skip_button_action'] ?? 'select_product_page'
          ) }}"
        >

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
          <label class="form-check-label" for="show_register_button">
            แสดงปุ่มสมัครสมาชิก
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มสมัครสมาชิก</label>
          <select name="register_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $registerButtonIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="register_button_action"
          value="{{ old(
            'register_button_action',
            $settings['register_button_action'] ?? 'register_member'
          ) }}"
        >

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้าไม่พบสมาชิก
        </button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">Banner สมัครสมาชิก</h5>
      <p class="text-muted mb-0">
        รูปที่แสดงด้านขวาเมื่อไม่พบข้อมูลสมาชิก
      </p>
    </div>

    <div class="card-body">
      <form
        action="{{ route('frontend.pages.media.store', $page) }}"
        method="POST"
        enctype="multipart/form-data"
      >
        @csrf

        <input type="hidden" name="media_type" value="image">

        <div class="mb-4">
          <label class="form-label">
            รูป Banner <span class="text-danger">*</span>
          </label>

          <input
            type="file"
            name="file"
            class="form-control @error('file') is-invalid @enderror"
            accept=".jpg,.jpeg,.png,.webp,.svg"
            required
          >

          <div class="form-text">
            รองรับ JPG, PNG, WEBP และ SVG
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-upload me-1"></i>
          บันทึก / เปลี่ยน Banner
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview Popup สมัครสมาชิก</h5>
      <p class="text-muted mb-0">
        ตัวอย่าง Popup ที่จะแสดงเมื่อกดปุ่มสมัครสมาชิก
      </p>
    </div>

    <div class="card-body">
      <div class="member-empty-preview">
        <div class="member-empty-title">
          member_page.not_found_title
        </div>

        <div class="member-empty-subtitle">
          member_page.not_found_subtitle
        </div>

        <div class="member-empty-step">
          <span class="member-empty-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span class="member-empty-step-line"></span>

          <span class="member-empty-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span class="member-empty-step-line"></span>

          <span class="member-empty-step-circle">
            <i class="icon-base ti {{ $stepIcon }}"></i>
          </span>

          <span class="member-empty-step-line"></span>

          <span class="member-empty-step-circle pending">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="member-empty-content">
          <div class="member-empty-message">
            <div class="member-empty-heading">
              ไม่พบข้อมูลสมาชิก
            </div>

            <div class="member-empty-description">
              กรุณาตรวจสอบหมายเลขโทรศัพท์อีกครั้ง
            </div>

            <div class="member-empty-icon-wrap">
              <i class="icon-base ti {{ $stepIcon }}"></i>
            </div>
          </div>

          <div class="member-empty-banner-panel">
            @if ($firstMedia && $firstMedia->media_type === 'image')
              <img
                src="{{ $firstMedia->file_url }}"
                alt="สมัครสมาชิก"
                class="member-empty-banner"
              >
            @else
              <div class="member-empty-banner-placeholder">
                <strong>สมัครสมาชิกวันนี้</strong>
                <span>รับส่วนลดทันที</span>
              </div>
            @endif
          </div>
        </div>

        <div class="member-empty-footer">
          @if ($showBackButton)
            <button type="button" class="member-empty-back-button">
              <i class="icon-base ti {{ $backButtonIcon }}"></i>
              <span>ย้อนกลับ</span>
            </button>
          @endif

          @if ($showSkipButton)
            <button type="button" class="member-empty-skip-button">
              <span>ข้าม</span>
              <i class="icon-base ti {{ $skipButtonIcon }}"></i>
            </button>
          @endif

          @if ($showRegisterButton)
            <button
              type="button"
              class="member-empty-register-button"
              id="openRegisterMemberModal"
            >
              <span>สมัครสมาชิก</span>
              <i class="icon-base ti {{ $registerButtonIcon }}"></i>
            </button>
          @endif
        </div>

        <div class="member-register-modal is-open preview-mode" id="registerMemberModal">
          <div class="member-register-modal-box">
            <div class="member-register-popup-grid">
              <div class="member-register-poster">
                สมัครสมาชิกวันนี้<br>
                รับส่วนลดทันที 20 บาท
              </div>

              <div class="member-register-qr-panel">
                <div class="member-register-modal-title">
                  สแกน QR Code<br>
                  เพื่อสมัครสมาชิก
                </div>

                <div class="member-register-qr"></div>

                <div class="member-register-modal-note">
                  สแกนด้วยโทรศัพท์มือถือเพื่อสมัครสมาชิก
                </div>
              </div>
            </div>

            <div class="member-register-popup-actions">
              <button
                type="button"
                class="member-register-popup-skip"
                id="closeRegisterMemberModal"
              >
                ข้าม
              </button>

              <button
                type="button"
                class="member-register-popup-register"
              >
                สมัครสมาชิก
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const openButton = document.getElementById('openRegisterMemberModal');
  const closeButton = document.getElementById('closeRegisterMemberModal');
  const modal = document.getElementById('registerMemberModal');

  openButton?.addEventListener('click', function () {
    modal?.classList.add('is-open');
  });

  closeButton?.addEventListener('click', function () {
    modal?.classList.remove('is-open');
  });

  modal?.addEventListener('click', function (event) {
    if (event.target === modal) {
      modal.classList.remove('is-open');
    }
  });
});
</script>
