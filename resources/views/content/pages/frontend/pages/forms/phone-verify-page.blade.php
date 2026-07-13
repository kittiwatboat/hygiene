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

        @php
          $settings = $page->settings_json ?? [];
        @endphp

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

        <h6 class="mb-3">Banner / QR ด้านซ้าย</h6>

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
            แสดง Banner / QR ด้านซ้าย
          </label>
        </div>

        <div class="alert alert-info">
          รูป Banner / QR ด้านซ้าย ให้เพิ่มในส่วน <strong>Banner / Image ด้านล่าง</strong>
          โดยระบบจะใช้รายการที่เปิดใช้งานตามลำดับ
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
            แสดงปุ่มกลับ
          </label>
        </div>

        <div class="mb-3">
  <label class="form-label">Icon ปุ่มกลับ</label>

  <select name="back_button_icon" class="form-select">
    @php
      $backIcon = old('back_button_icon', $settings['back_button_icon'] ?? 'tabler-arrow-left');

      $systemIcons = [
        'tabler-arrow-left' => 'Arrow Left',
        'tabler-chevron-left' => 'Chevron Left',
        'tabler-home' => 'Home',
        'tabler-circle-arrow-left' => 'Circle Arrow Left',
        'tabler-caret-left' => 'Caret Left',
      ];
    @endphp

    @foreach ($systemIcons as $iconClass => $iconLabel)
      <option value="{{ $iconClass }}" {{ $backIcon === $iconClass ? 'selected' : '' }}>
        {{ $iconLabel }}
      </option>
    @endforeach
  </select>

  <div class="form-text">
    เลือก icon สำหรับปุ่มกลับ
  </div>
</div>

        <input
          type="hidden"
          name="back_button_action"
          value="{{ old('back_button_action', $settings['back_button_action'] ?? 'language_page') }}"
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
      $confirmIcon = old('confirm_button_icon', $settings['confirm_button_icon'] ?? 'tabler-check');

      $confirmIcons = [
        'tabler-check' => 'Check',
        'tabler-circle-check' => 'Circle Check',
        'tabler-arrow-right' => 'Arrow Right',
        'tabler-chevron-right' => 'Chevron Right',
        'tabler-circle-arrow-right' => 'Circle Arrow Right',
        'tabler-login' => 'Login',
      ];
    @endphp

    @foreach ($confirmIcons as $iconClass => $iconLabel)
      <option value="{{ $iconClass }}" {{ $confirmIcon === $iconClass ? 'selected' : '' }}>
        {{ $iconLabel }}
      </option>
    @endforeach
  </select>

  <div class="form-text">
    เลือก icon สำหรับปุ่มตกลง
  </div>
</div>

        <input
          type="hidden"
          name="confirm_button_action"
          value="{{ old('confirm_button_action', $settings['confirm_button_action'] ?? 'select_product_page') }}"
        >

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
      อัปโหลดได้ 1 รายการเท่านั้น รองรับรูปภาพหรือวิดีโอ หากอัปโหลดใหม่ ระบบจะแทนที่ไฟล์เดิม
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
          ไฟล์รูปภาพ / วิดีโอ <span class="text-danger">*</span>
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
      <h5 class="mb-1">Preview โครงหน้ากรอกเบอร์โทร</h5>
      <p class="text-muted mb-0">
        ตัวอย่างโครงหน้าจอ โดยข้อความจริงจะดึงจากระบบแปลภาษาอัตโนมัติ
      </p>
    </div>

    <div class="card-body">
      @php
        $settings = $page->settings_json ?? [];
        $firstMedia = $page->media->first();
      @endphp

      <div class="border rounded p-3" style="background: #dff8ff;">
        <div class="text-center mb-3">
          <div class="fw-bold text-primary">
            phone_verify_page.title
          </div>
          <small class="text-muted">
            phone_verify_page.subtitle
          </small>
        </div>

        <div class="row g-3">
          <div class="col-md-7">
            <div class="border rounded bg-white overflow-hidden" style="height: 260px;">
              @if ($firstMedia && $firstMedia->media_type === 'video')
                <video
                  src="{{ $firstMedia->file_url }}"
                  style="width: 100%; height: 100%; object-fit: {{ $firstMedia->object_fit ?? 'cover' }};"
                  muted
                  controls
                ></video>
              @elseif ($firstMedia && $firstMedia->media_type === 'image')
                <img
                  src="{{ $firstMedia->file_url }}"
                  alt="Banner"
                  style="width: 100%; height: 100%; object-fit: {{ $firstMedia->object_fit ?? 'cover' }};"
                >
              @else
                <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                  Banner / Video ด้านซ้าย
                </div>
              @endif
            </div>
          </div>

          <div class="col-md-5">
            <div class="border rounded bg-white p-3">
              <label class="form-label small mb-1">
                phone_verify_page.phone_label
              </label>

              <div class="form-control mb-2 text-center">
                090-990-XXXX
              </div>

              <div class="row g-2">
                @foreach ([1,2,3,4,5,6,7,8,9,'',0,'⌫'] as $key)
                  <div class="col-4">
                    <button type="button" class="btn btn-label-primary w-100">
                      {{ $key }}
                    </button>
                  </div>
                @endforeach
              </div>

              <div class="row g-2 mt-2">
                <div class="col-6">
                  <button type="button" class="btn btn-outline-primary w-100">
                    <i class="icon-base ti {{ $settings['back_button_icon'] ?? 'tabler-arrow-left' }} me-1"></i>
                  </button>
                </div>

                <div class="col-6">
                  <button type="button" class="btn btn-primary w-100">
                    <i class="icon-base ti {{ $settings['confirm_button_icon'] ?? 'tabler-check' }} me-1"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


</div>
