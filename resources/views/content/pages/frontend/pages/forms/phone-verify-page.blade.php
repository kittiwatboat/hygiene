@php
  $settings = $page->settings_json ?? [];
  $firstMedia = $page->media->first();

  $backIcon = old(
    'back_button_icon',
    $settings['back_button_icon'] ?? 'tabler-arrow-left'
  );

  $confirmIcon = old(
    'confirm_button_icon',
    $settings['confirm_button_icon'] ?? 'tabler-chevron-right'
  );

  $systemIcons = [
    'tabler-arrow-left' => 'Arrow Left',
    'tabler-chevron-left' => 'Chevron Left',
    'tabler-home' => 'Home',
    'tabler-circle-arrow-left' => 'Circle Arrow Left',
    'tabler-caret-left' => 'Caret Left',
  ];

  $confirmIcons = [
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-circle-arrow-right' => 'Circle Arrow Right',
    'tabler-login' => 'Login',
  ];
@endphp

<style>
  .phone-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .phone-preview-title {
    text-align: center;
    color: #6f63f6;
    font-weight: 800;
    font-size: 20px;
    margin-bottom: 2px;
  }

  .phone-preview-subtitle {
    text-align: center;
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 16px;
  }

  .phone-preview-step {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
  }

  .phone-step-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: #6f63f6;
    font-size: 18px;
    flex: 0 0 auto;
  }

  .phone-step-circle.done {
    background: #26c875;
  }

  .phone-step-circle.pending {
    background: #e8e8ed;
    color: #9295a0;
  }

  .phone-step-line {
    width: 52px;
    height: 2px;
    background: #6fbff0;
  }

  .phone-preview-content {
    display: grid;
    grid-template-columns: minmax(0, 1.65fr) minmax(280px, .75fr);
    gap: 18px;
    align-items: stretch;
  }

  .phone-banner-preview {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    min-height: 360px;
    border: 1px solid rgba(0, 132, 216, .08);
  }

  .phone-banner-preview img,
  .phone-banner-preview video {
    width: 100%;
    height: 100%;
    min-height: 360px;
    display: block;
    object-fit: cover;
  }

  .phone-banner-empty {
    min-height: 360px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #98a2b3;
    background: #fff;
    font-size: 15px;
  }

  .phone-keypad-panel {
    background: rgba(255, 255, 255, .76);
    border-radius: 12px;
    padding: 14px;
    display: flex;
    flex-direction: column;
  }

  .phone-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    color: #111827;
    font-weight: 700;
    font-size: 13px;
    margin-bottom: 8px;
  }

  .phone-number-box {
    background: #fff;
    border: 2px solid #0c8bd7;
    border-radius: 10px;
    min-height: 44px;
    padding: 8px 12px;
    color: #007ac8;
    font-size: 22px;
    line-height: 1;
    font-weight: 900;
    text-align: center;
    margin-bottom: 10px;
  }

  .phone-keypad-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
  }

  .phone-key {
    border: 0;
    border-radius: 8px;
    min-height: 48px;
    background: #fff;
    color: #0a72bd;
    font-size: 22px;
    font-weight: 900;
    box-shadow: 0 3px 8px rgba(14, 116, 190, .08);
  }

  .phone-key.is-empty {
    visibility: hidden;
  }

  .phone-key.is-delete {
    color: #0877c9;
    font-size: 18px;
  }

  .phone-actions {
    margin-top: auto;
    padding-top: 14px;
    display: grid;
    grid-template-columns: 1fr 1.15fr;
    gap: 10px;
  }

  .phone-back-button,
  .phone-confirm-button {
    border: 0;
    border-radius: 9px;
    min-height: 48px;
    padding: 10px 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
  }

  .phone-back-button {
    background: #fff;
    color: #0877c9;
  }

  .phone-confirm-button {
    background: #0877c9;
    color: #fff;
    font-size: 17px;
  }

  @media (max-width: 991.98px) {
    .phone-preview-content {
      grid-template-columns: 1fr;
    }

    .phone-banner-preview,
    .phone-banner-preview img,
    .phone-banner-preview video,
    .phone-banner-empty {
      min-height: 260px;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้ากรอกเบอร์โทร</h5>
      <p class="text-muted mb-0">
        ตั้งค่าหน้าจอกรอกเบอร์โทร ปุ่มกดตัวเลข และปุ่มนำทาง
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
            value="phone_verify_page.title"
            class="form-control"
            readonly
          >
          <div class="form-text">
            ข้อความหัวข้อจะถูกแปลอัตโนมัติตามภาษาที่ผู้ใช้เลือก
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key ช่องกรอกเบอร์โทร</label>
          <input
            type="text"
            value="phone_verify_page.phone_placeholder"
            class="form-control"
            readonly
          >
        </div>

        <hr class="my-4">

        <h6 class="mb-3">ตั้งค่าช่องเบอร์โทร</h6>

        <div class="mb-3">
          <label class="form-label">จำนวนหลักเบอร์โทรสูงสุด</label>
          <input
            type="number"
            name="phone_max_length"
            value="{{ old('phone_max_length', $settings['phone_max_length'] ?? 10) }}"
            class="form-control"
            min="1"
            max="20"
          >
        </div>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_keypad" value="0">
          <input
            type="checkbox"
            name="show_keypad"
            value="1"
            id="show_keypad"
            class="form-check-input"
            {{ old('show_keypad', $settings['show_keypad'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_keypad">
            แสดงปุ่มกดตัวเลข
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">รูปแบบปุ่มกด</label>
          <select name="keypad_layout" class="form-select">
            <option
              value="numeric"
              {{ old('keypad_layout', $settings['keypad_layout'] ?? 'numeric') === 'numeric' ? 'selected' : '' }}
            >
              ตัวเลข 0-9
            </option>
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Banner</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="left_banner_enabled" value="0">
          <input
            type="checkbox"
            name="left_banner_enabled"
            value="1"
            id="left_banner_enabled"
            class="form-check-input"
            {{ old('left_banner_enabled', $settings['left_banner_enabled'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="left_banner_enabled">
            แสดง Banner ด้านซ้าย
          </label>
        </div>

        <div class="alert alert-info">
          รูป Banner ด้านซ้ายให้เพิ่มในส่วน
          <strong>Banner / Media ด้านล่าง</strong>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">ปุ่มนำทาง</h6>

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
          <label class="form-check-label" for="show_back_button">
            แสดงปุ่มรับ OTP ใหม่ / ย้อนกลับ
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มย้อนกลับ</label>
          <select name="back_button_icon" class="form-select">
            @foreach ($systemIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $backIcon === $iconClass ? 'selected' : '' }}
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
            $settings['back_button_action'] ?? 'language_page'
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
            แสดงปุ่มส่ง OTP / ยืนยัน
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มตกลง</label>
          <select name="confirm_button_icon" class="form-select">
            @foreach ($confirmIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $confirmIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="confirm_button_action"
          value="{{ old(
            'confirm_button_action',
            $settings['confirm_button_action'] ?? 'select_product_page'
          ) }}"
        >

        <div class="mb-3">
          <label class="form-label">หมายเหตุ</label>
          <textarea
            name="remark"
            rows="3"
            class="form-control @error('remark') is-invalid @enderror"
          >{{ old('remark', $page->remark) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้ากรอกเบอร์โทร
        </button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">Banner / Media ด้านซ้าย</h5>
      <p class="text-muted mb-0">
        อัปโหลดได้ 1 รายการ หากอัปโหลดใหม่ ระบบจะแทนที่ไฟล์เดิม
      </p>
    </div>

    <div class="card-body">
      <form
        action="{{ route('frontend.pages.media.store', $page) }}"
        method="POST"
        enctype="multipart/form-data"
      >
        @csrf

        <div class="mb-3">
          <label class="form-label">
            ประเภทไฟล์ <span class="text-danger">*</span>
          </label>
          <select
            name="media_type"
            id="mediaType"
            class="form-select @error('media_type') is-invalid @enderror"
            required
          >
            <option value="image" {{ old('media_type', 'image') === 'image' ? 'selected' : '' }}>
              รูปภาพ
            </option>
            <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>
              วิดีโอ
            </option>
          </select>
        </div>

        <div class="mb-4">
          <label class="form-label">
            ไฟล์รูปภาพ / วิดีโอ <span class="text-danger">*</span>
          </label>
          <input
            type="file"
            name="file"
            id="mediaFileInput"
            class="form-control @error('file') is-invalid @enderror"
            required
          >
          <div class="form-text">
            รูปภาพ: JPG, PNG, WEBP, SVG / วิดีโอ: MP4, WEBM, MOV
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
      <h5 class="mb-1">Preview หน้ากรอกเบอร์โทร / OTP</h5>
      <p class="text-muted mb-0">
        ตัวอย่าง Layout เท่านั้น ข้อความจริงจะดึงจากระบบแปลภาษา
      </p>
    </div>

    <div class="card-body">
      <div class="phone-preview">
        <div class="phone-preview-title">
          phone_verify_page.title
        </div>

        <div class="phone-preview-subtitle">
          phone_verify_page.subtitle
        </div>

        <div class="phone-preview-step">
          <span class="phone-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span class="phone-step-line"></span>

          <span class="phone-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span class="phone-step-line"></span>

          <span class="phone-step-circle">
            <i class="icon-base ti tabler-phone"></i>
          </span>

          <span class="phone-step-line"></span>

          <span class="phone-step-circle pending">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="phone-preview-content">
          <div class="phone-banner-preview">
            @if (
              ($settings['left_banner_enabled'] ?? true)
              && $firstMedia
              && $firstMedia->media_type === 'video'
            )
              <video
                src="{{ $firstMedia->file_url }}"
                style="object-fit: {{ $firstMedia->object_fit ?? 'cover' }};"
                muted
                controls
              ></video>
            @elseif (
              ($settings['left_banner_enabled'] ?? true)
              && $firstMedia
              && $firstMedia->media_type === 'image'
            )
              <img
                src="{{ $firstMedia->file_url }}"
                alt="Banner"
                style="object-fit: {{ $firstMedia->object_fit ?? 'cover' }};"
              >
            @else
              <div class="phone-banner-empty">
                Banner / Media รูปภาพหรือวิดีโอ
              </div>
            @endif
          </div>

          <div class="phone-keypad-panel">
            <div class="phone-label">
              <i class="icon-base ti tabler-phone"></i>
              <span>phone_verify_page.phone_label</span>
            </div>

            <div class="phone-number-box">
              XXX-XXX-9999
            </div>

            @if ($settings['show_keypad'] ?? true)
              <div class="phone-keypad-grid">
                @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, '', 0, 'delete'] as $key)
                  @if ($key === '')
                    <button type="button" class="phone-key is-empty">-</button>
                  @elseif ($key === 'delete')
                    <button type="button" class="phone-key is-delete">
                      <i class="icon-base ti tabler-backspace"></i>
                    </button>
                  @else
                    <button type="button" class="phone-key">
                      {{ $key }}
                    </button>
                  @endif
                @endforeach
              </div>
            @endif

            <div class="phone-actions">
              @if ($settings['show_back_button'] ?? true)
                <button type="button" class="phone-back-button">
                  <i class="icon-base ti {{ $backIcon }}"></i>
                  <span>phone_verify_page.back_button</span>
                </button>
              @endif

              @if ($settings['show_confirm_button'] ?? true)
                <button type="button" class="phone-confirm-button">
                  <span>phone_verify_page.confirm_button</span>
                  <i class="icon-base ti {{ $confirmIcon }}"></i>
                </button>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
