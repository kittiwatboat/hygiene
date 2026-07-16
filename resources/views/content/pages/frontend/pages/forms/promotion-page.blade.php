@php
  $settings = $page->settings_json ?? [];
  $firstMedia = $page->media->first();

  $stepIcons = [
    'tabler-discount' => 'Discount',
    'tabler-ticket' => 'Ticket',
    'tabler-gift' => 'Gift',
    'tabler-coins' => 'Coins',
    'tabler-coin' => 'Coin',
    'tabler-percentage' => 'Percentage',
    'tabler-wallet' => 'Wallet',
    'tabler-star' => 'Star',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
  ];

  $buttonIcons = [
    'tabler-home' => 'Home',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-chevrons-right' => 'Chevrons Right',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-x' => 'X',
    'tabler-player-skip-forward' => 'Skip Forward',
    'tabler-discount' => 'Discount',
    'tabler-ticket' => 'Ticket',
    'tabler-coins' => 'Coins',
    'tabler-gift' => 'Gift',
  ];
@endphp

<style>
  .promotion-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .promotion-content {
    display: grid;
    grid-template-columns: 64% 36%;
    gap: 18px;
    align-items: stretch;
  }

  .promotion-media-panel {
    min-height: 280px;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
  }

  .promotion-media {
    width: 100%;
    height: 100%;
    min-height: 280px;
    object-fit: cover;
    display: block;
  }

  .promotion-media-empty {
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8c97a4;
    background: #fff;
  }

  .promotion-option-panel {
    background: rgba(255,255,255,.72);
    border-radius: 12px;
    padding: 16px;
  }

  .promotion-option-title {
    color: #0075c9;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
  }

  .point-option {
    min-height: 82px;
    border-radius: 12px;
    padding: 14px 16px;
    color: #fff;
    margin-bottom: 12px;
    display: grid;
    grid-template-columns: 54px 1fr 34px;
    align-items: center;
    gap: 10px;
  }

  .point-option:nth-child(2) {
    background: linear-gradient(90deg, #6a70ff, #427bff);
  }

  .point-option:nth-child(3) {
    background: linear-gradient(90deg, #2e8bff, #006bff);
  }

  .point-option:nth-child(4) {
    background: linear-gradient(90deg, #8d00ff, #ff4ed0);
  }

  .point-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,.18);
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .point-icon i {
    font-size: 28px;
  }

  .point-value {
    font-size: 22px;
    font-weight: 800;
    line-height: 1.1;
  }

  .point-discount {
    font-size: 13px;
    opacity: .95;
  }

  .point-action {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .point-action i {
    font-size: 24px;
  }

  .promotion-footer {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 18px;
    margin-top: 18px;
    align-items: center;
  }

  .promotion-home-button,
  .promotion-skip-button,
  .promotion-confirm-button {
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

  .promotion-home-button {
    background: #fff;
    color: #0877c9;
  }

  .promotion-skip-button {
    background: #fff;
    color: #0877c9;
  }

  .promotion-confirm-button {
    background: #0877c9;
    color: #fff;
    font-size: 18px;
  }

  .current-media-preview {
    width: 260px;
    height: 150px;
    object-fit: cover;
  }

  @media (max-width: 991.98px) {
    .promotion-content {
      grid-template-columns: 1fr;
    }

    .promotion-footer {
      grid-template-columns: 1fr;
    }

    .promotion-home-button,
    .promotion-skip-button,
    .promotion-confirm-button {
      width: 100%;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าใช้แต้ม / แลกส่วนลด</h5>
      <p class="text-muted mb-0">
        จัดการเฉพาะ icon และปุ่มของหน้านี้ ส่วนแต้มและส่วนลดจะดึงจากระบบ
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
          <input type="text" value="promotion_page.title" class="form-control" readonly>
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
          <label class="form-label">Icon Step หน้าใช้แต้ม</label>

          <select name="step_icon" class="form-select">
            @php
              $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-discount');
            @endphp

            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $stepIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon รายการแลกส่วนลด</h6>

        <div class="mb-3">
          <label class="form-label">Icon หัวข้อเลือกใช้แต้ม</label>

          <select name="point_section_icon" class="form-select">
            @php
              $pointSectionIcon = old('point_section_icon', $settings['point_section_icon'] ?? 'tabler-wallet');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $pointSectionIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon รายการแต้ม</label>

          <select name="point_option_icon" class="form-select">
            @php
              $pointOptionIcon = old('point_option_icon', $settings['point_option_icon'] ?? 'tabler-coins');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $pointOptionIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon รายการที่เลือก</label>

          <select name="selected_option_icon" class="form-select">
            @php
              $selectedOptionIcon = old('selected_option_icon', $settings['selected_option_icon'] ?? 'tabler-check');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $selectedOptionIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon รายการที่ยังไม่เลือก</label>

          <select name="next_option_icon" class="form-select">
            @php
              $nextOptionIcon = old('next_option_icon', $settings['next_option_icon'] ?? 'tabler-chevron-right');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $nextOptionIcon === $iconClass ? 'selected' : '' }}>
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
          <input type="hidden" name="show_skip_button" value="0">
          <input
            type="checkbox"
            name="show_skip_button"
            value="1"
            id="show_skip_button"
            class="form-check-input"
            {{ old('show_skip_button', $settings['show_skip_button'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_skip_button">
            แสดงปุ่มข้าม
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มข้าม</label>

          <select name="skip_button_icon" class="form-select">
            @php
              $skipButtonIcon = old('skip_button_icon', $settings['skip_button_icon'] ?? 'tabler-chevrons-right');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $skipButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="skip_button_action"
          value="{{ old('skip_button_action', $settings['skip_button_action'] ?? 'payment_page') }}"
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
          บันทึกหน้าใช้แต้ม
        </button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">Banner / Media ฝั่งซ้าย</h5>
      <p class="text-muted mb-0">
        อัปโหลดได้ 1 รายการ รองรับรูปภาพหรือวิดีโอ หากอัปโหลดใหม่ ระบบจะแทนที่ไฟล์เดิม
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

          @error('media_type')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label">
            ไฟล์ Banner / Media <span class="text-danger">*</span>
          </label>

          <input
            type="file"
            name="file"
            id="mediaFileInput"
            class="form-control @error('file') is-invalid @enderror"
            required
          >

          @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

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
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้าใช้แต้ม / แลกส่วนลด</h5>
      <p class="text-muted mb-0">
        ตัวอย่าง layout เท่านั้น แต้มและส่วนลดจริงจะดึงจากระบบ
      </p>
    </div>

    <div class="card-body">
      <div class="promotion-preview">
        <div class="text-center mb-3">
          <div class="fw-bold text-primary fs-5">
            promotion_page.title
          </div>
          <small class="text-muted">
            promotion_page.subtitle
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

          <span class="badge rounded-pill bg-success p-2">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span style="width: 50px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-primary p-2">
            <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-discount' }}"></i>
          </span>

          <span style="width: 50px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-label-secondary p-2">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="promotion-content">
          <div class="promotion-media-panel">
            @if ($firstMedia && $firstMedia->media_type === 'video')
              <video
                src="{{ $firstMedia->file_url }}"
                class="promotion-media"
                muted
                controls
              ></video>
            @elseif ($firstMedia && $firstMedia->media_type === 'image')
              <img
                src="{{ $firstMedia->file_url }}"
                alt="Promotion Banner"
                class="promotion-media"
              >
            @else
              <div class="promotion-media-empty">
                Banner / Media รูปภาพหรือวิดีโอ
              </div>
            @endif
          </div>

          <div class="promotion-option-panel">
            <div class="promotion-option-title">
              <i class="icon-base ti {{ $settings['point_section_icon'] ?? 'tabler-wallet' }}"></i>
              <span>promotion_page.option_title</span>
            </div>

            <div class="point-option">
              <div class="point-icon">
                <i class="icon-base ti {{ $settings['point_option_icon'] ?? 'tabler-coins' }}"></i>
              </div>

              <div>
                <div class="point-value">25 แต้ม</div>
                <div class="point-discount">ลด 15 บาท</div>
              </div>

              <div class="point-action">
                <i class="icon-base ti {{ $settings['selected_option_icon'] ?? 'tabler-check' }}"></i>
              </div>
            </div>

            <div class="point-option">
              <div class="point-icon">
                <i class="icon-base ti {{ $settings['point_option_icon'] ?? 'tabler-coins' }}"></i>
              </div>

              <div>
                <div class="point-value">50 แต้ม</div>
                <div class="point-discount">ลด 25 บาท</div>
              </div>

              <div class="point-action">
                <i class="icon-base ti {{ $settings['next_option_icon'] ?? 'tabler-chevron-right' }}"></i>
              </div>
            </div>

            <div class="point-option">
              <div class="point-icon">
                <i class="icon-base ti {{ $settings['point_option_icon'] ?? 'tabler-coins' }}"></i>
              </div>

              <div>
                <div class="point-value">100 แต้ม</div>
                <div class="point-discount">ลด 50 บาท</div>
              </div>

              <div class="point-action">
                <i class="icon-base ti {{ $settings['next_option_icon'] ?? 'tabler-chevron-right' }}"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="promotion-footer">
          @if ($settings['show_home_button'] ?? true)
            <button type="button" class="promotion-home-button">
              <i class="icon-base ti {{ $settings['home_button_icon'] ?? 'tabler-home' }}"></i>
              <span>promotion_page.home_button</span>
            </button>
          @endif

          @if ($settings['show_skip_button'] ?? true)
            <button type="button" class="promotion-skip-button">
              <span>promotion_page.skip_button</span>
              <i class="icon-base ti {{ $settings['skip_button_icon'] ?? 'tabler-chevrons-right' }}"></i>
            </button>
          @endif

          @if ($settings['show_confirm_button'] ?? true)
            <button type="button" class="promotion-confirm-button">
              <span>promotion_page.confirm_button</span>
              <i class="icon-base ti {{ $settings['confirm_button_icon'] ?? 'tabler-chevron-right' }}"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>

  @if ($firstMedia)
    <div class="card">
      <div class="card-header">
        <h5 class="mb-1">Banner / Media ปัจจุบัน</h5>
        <p class="text-muted mb-0">
          ไฟล์ที่ใช้งานอยู่ในหน้าใช้แต้ม
        </p>
      </div>

      <div class="card-body">
        <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
          <div class="border rounded overflow-hidden" style="width: 260px; height: 150px;">
            @if ($firstMedia->media_type === 'video')
              <video
                src="{{ $firstMedia->file_url }}"
                class="current-media-preview"
                muted
                controls
              ></video>
            @else
              <img
                src="{{ $firstMedia->file_url }}"
                alt="Promotion Banner"
                class="current-media-preview"
              >
            @endif
          </div>

          <div class="flex-grow-1">
            <span class="badge {{ $firstMedia->media_type === 'video' ? 'bg-label-danger' : 'bg-label-info' }}">
              {{ $firstMedia->media_type === 'video' ? 'Video' : 'Image' }}
            </span>

            <div class="text-muted mt-2">
              ถ้าอัปโหลดไฟล์ใหม่ ระบบจะแทนที่ไฟล์นี้ทันที
            </div>
          </div>

          <form
            action="{{ route('frontend.pages.media.destroy', $firstMedia) }}"
            method="POST"
            onsubmit="return confirm('ยืนยันการลบ Banner / Media นี้?')"
          >
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">
              ลบไฟล์
            </button>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
