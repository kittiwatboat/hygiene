@csrf

<div class="row g-4">

  <div class="col-12">
    <div class="card border shadow-none mb-0">
      <div class="card-header pb-2">
        <h5 class="mb-1">ตัวอย่างตำแหน่งเมนูบนหน้าตู้</h5>
        <p class="text-muted mb-0">
          ปุ่มกลับหน้าแรกและปุ่มเลือกภาษาจะแสดงบริเวณด้านซ้ายของ Header ตามตัวอย่าง
        </p>
      </div>

      <div class="card-body pt-2">
        <div
          class="rounded border bg-light overflow-hidden"
          style="min-height: 180px;"
        >
          <img
            src="{{ asset('assets/img/frontend/theme-kiosk-example.png') }}"
            alt="ตัวอย่างหน้าตู้พร้อมปุ่มหน้าแรกและเลือกภาษา"
            class="w-100 d-block"
            style="
              max-height: 360px;
              object-fit: contain;
              object-position: center;
            "
          >
        </div>

        <div class="form-text mt-2">
          ให้นำไฟล์รูปตัวอย่างไปไว้ที่
          <code>public/assets/img/frontend/theme-kiosk-example.png</code>
        </div>
      </div>
    </div>
  </div>


  <div class="col-md-6">
    <label class="form-label">
      ชื่อธีม <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="name"
      value="{{ old('name', $theme->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น Hygiene Blue"
      required
    >

    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Slug</label>

    <input
      type="text"
      name="slug"
      value="{{ old('slug', $theme->slug ?? '') }}"
      class="form-control @error('slug') is-invalid @enderror"
      placeholder="เช่น hygiene-blue"
    >

    @error('slug')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="form-text">
      ถ้าไม่กรอก ระบบจะสร้างจากชื่อธีมให้อัตโนมัติ
    </div>
  </div>

  <div class="col-12">
    <hr class="my-2">
    <h6 class="mb-1">สีและพื้นหลัง</h6>
    <p class="text-muted mb-0">
      กำหนดสีตัวอักษร และเลือกพื้นหลังเป็นสี รูปภาพ หรือวิดีโอ
    </p>
  </div>

  <div class="col-md-4">
    <label class="form-label">สีตัวอักษร</label>

    <div class="input-group">
      <input
        type="color"
        value="{{ old('text_color', $theme->text_color ?? '#111827') }}"
        class="form-control form-control-color theme-color-picker"
        data-target="text_color"
        style="max-width: 64px;"
      >

      <input
        type="text"
        name="text_color"
        id="text_color"
        value="{{ old('text_color', $theme->text_color ?? '#111827') }}"
        class="form-control @error('text_color') is-invalid @enderror"
        placeholder="#111827"
      >
    </div>

    @error('text_color')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">
      ประเภทพื้นหลัง <span class="text-danger">*</span>
    </label>

    @php
      $backgroundType = old(
          'background_type',
          $theme->background_type ?? 'color'
      );
    @endphp

    <select
      name="background_type"
      id="backgroundType"
      class="form-select @error('background_type') is-invalid @enderror"
      required
    >
      <option value="color" {{ $backgroundType === 'color' ? 'selected' : '' }}>
        สีพื้นหลัง
      </option>

      <option value="image" {{ $backgroundType === 'image' ? 'selected' : '' }}>
        รูปภาพพื้นหลัง
      </option>

      <option value="video" {{ $backgroundType === 'video' ? 'selected' : '' }}>
        วิดีโอพื้นหลัง
      </option>
    </select>

    @error('background_type')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4 background-color-field">
    <label class="form-label">สีพื้นหลัง</label>

    <div class="input-group">
      <input
        type="color"
        value="{{ old('background_color', $theme->background_color ?? '#FFFFFF') }}"
        class="form-control form-control-color theme-color-picker"
        data-target="background_color"
        style="max-width: 64px;"
      >

      <input
        type="text"
        name="background_color"
        id="background_color"
        value="{{ old('background_color', $theme->background_color ?? '#FFFFFF') }}"
        class="form-control @error('background_color') is-invalid @enderror"
        placeholder="#FFFFFF"
      >
    </div>

    @error('background_color')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6 background-image-field">
    <label class="form-label">รูปภาพพื้นหลัง</label>

    <input
      type="file"
      name="background_image"
      id="backgroundImageInput"
      class="form-control @error('background_image') is-invalid @enderror"
      accept=".jpg,.jpeg,.png,.webp,.svg"
    >

    @error('background_image')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="form-text">
      ใช้เมื่อเลือกประเภทพื้นหลังเป็น “รูปภาพ”
    </div>
  </div>

  <div class="col-md-6 background-video-field">
    <label class="form-label">วิดีโอพื้นหลัง</label>

    <input
      type="file"
      name="background_video"
      id="backgroundVideoInput"
      class="form-control @error('background_video') is-invalid @enderror"
      accept=".mp4,.webm,.mov"
    >

    @error('background_video')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="form-text">
      ใช้เมื่อเลือกประเภทพื้นหลังเป็น “วิดีโอ”
    </div>
  </div>

  @if (isset($theme) && ($theme->background_image || $theme->background_video))
    <div class="col-12">
      <div class="card border shadow-none mb-0">
        <div class="card-body">
          <label class="form-label d-block">ไฟล์พื้นหลังปัจจุบัน</label>

          @if ($theme->background_image)
            <div class="mb-3">
              <img
                src="{{ asset('assets/img/frontend/themes/' . $theme->background_image) }}"
                alt="Background Image"
                class="rounded border"
                style="max-width: 260px; max-height: 160px; object-fit: cover;"
              >
            </div>

            <div class="form-check">
              <input
                type="checkbox"
                name="remove_background_image"
                value="1"
                id="remove_background_image"
                class="form-check-input"
              >

              <label class="form-check-label" for="remove_background_image">
                ลบรูปภาพพื้นหลังเดิม
              </label>
            </div>
          @endif

          @if ($theme->background_video)
            <div class="mb-3">
              <video
                src="{{ asset('assets/videos/frontend/themes/' . $theme->background_video) }}"
                controls
                muted
                style="max-width: 320px; max-height: 180px;"
                class="rounded border"
              ></video>
            </div>

            <div class="form-check">
              <input
                type="checkbox"
                name="remove_background_video"
                value="1"
                id="remove_background_video"
                class="form-check-input"
              >

              <label class="form-check-label" for="remove_background_video">
                ลบวิดีโอพื้นหลังเดิม
              </label>
            </div>
          @endif
        </div>
      </div>
    </div>
  @endif

  <div class="col-12">
    <hr class="my-2">
    <h6 class="mb-1">ปุ่ม</h6>
    <p class="text-muted mb-0">
      กำหนดสีปุ่ม สีตัวอักษรปุ่ม และสีเส้นตอน hover
    </p>
  </div>

  <div class="col-md-4">
    <label class="form-label">สีปุ่ม</label>

    <div class="input-group">
      <input
        type="color"
        value="{{ old('button_color', $theme->button_color ?? '#00AEEF') }}"
        class="form-control form-control-color theme-color-picker"
        data-target="button_color"
        style="max-width: 64px;"
      >

      <input
        type="text"
        name="button_color"
        id="button_color"
        value="{{ old('button_color', $theme->button_color ?? '#00AEEF') }}"
        class="form-control @error('button_color') is-invalid @enderror"
        placeholder="#00AEEF"
      >
    </div>

    @error('button_color')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">สีตัวอักษรปุ่ม</label>

    <div class="input-group">
      <input
        type="color"
        value="{{ old('button_text_color', $theme->button_text_color ?? '#FFFFFF') }}"
        class="form-control form-control-color theme-color-picker"
        data-target="button_text_color"
        style="max-width: 64px;"
      >

      <input
        type="text"
        name="button_text_color"
        id="button_text_color"
        value="{{ old('button_text_color', $theme->button_text_color ?? '#FFFFFF') }}"
        class="form-control @error('button_text_color') is-invalid @enderror"
        placeholder="#FFFFFF"
      >
    </div>

    @error('button_text_color')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">สีเส้นตอน Hover</label>

    <div class="input-group">
      <input
        type="color"
        value="{{ old('button_hover_border_color', $theme->button_hover_border_color ?? '#00AEEF') }}"
        class="form-control form-control-color theme-color-picker"
        data-target="button_hover_border_color"
        style="max-width: 64px;"
      >

      <input
        type="text"
        name="button_hover_border_color"
        id="button_hover_border_color"
        value="{{ old('button_hover_border_color', $theme->button_hover_border_color ?? '#00AEEF') }}"
        class="form-control @error('button_hover_border_color') is-invalid @enderror"
        placeholder="#00AEEF"
      >
    </div>

    @error('button_hover_border_color')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <hr class="my-2">
    <h6 class="mb-1">ตัวอย่างธีม</h6>
  </div>

  <div class="col-12">
    <div
      id="themePreview"
      class="rounded border p-4"
      style="
        min-height: 220px;
        color: {{ old('text_color', $theme->text_color ?? '#111827') }};
        background: {{ old('background_color', $theme->background_color ?? '#FFFFFF') }};
      "
    >
      <h5 id="previewTitle" class="mb-2">
        ตัวอย่างหน้าตู้
      </h5>

      <p id="previewText" class="mb-4">
        ข้อความตัวอย่างจะแสดงตามสีตัวอักษรของธีม
      </p>

      <button
        type="button"
        id="previewButton"
        style="
          background: {{ old('button_color', $theme->button_color ?? '#00AEEF') }};
          color: {{ old('button_text_color', $theme->button_text_color ?? '#FFFFFF') }};
          border: 2px solid transparent;
          border-radius: 999px;
          padding: .75rem 1.75rem;
          font-weight: 600;
        "
      >
        ปุ่มตัวอย่าง
      </button>
    </div>
  </div>
    <div class="col-12">
  <hr class="my-2">
  <h6 class="mb-1">Header ด้านบน</h6>
  <p class="text-muted mb-0">
    ตั้งค่าพื้นหลัง header และโลโก้ที่ซ้อนอยู่ด้านบน
  </p>
</div>

@php
  $headerType = old('header_type', $theme->header_type ?? 'none');
@endphp

<div class="col-md-4">
  <label class="form-label">ประเภท Header</label>
  <select name="header_type" id="headerType" class="form-select">
    <option value="none" {{ $headerType === 'none' ? 'selected' : '' }}>ไม่ใช้</option>
    <option value="color" {{ $headerType === 'color' ? 'selected' : '' }}>สีพื้นหลัง</option>
    <option value="image" {{ $headerType === 'image' ? 'selected' : '' }}>รูปภาพ</option>
    <option value="video" {{ $headerType === 'video' ? 'selected' : '' }}>วิดีโอ</option>
  </select>
</div>

<div class="col-md-4 header-color-field">
  <label class="form-label">สีพื้นหลัง Header</label>
  <div class="input-group">
    <input
      type="color"
      value="{{ old('header_background_color', $theme->header_background_color ?? '#1EB5F0') }}"
      class="form-control form-control-color theme-color-picker"
      data-target="header_background_color"
      style="max-width:64px;"
    >
    <input
      type="text"
      name="header_background_color"
      id="header_background_color"
      value="{{ old('header_background_color', $theme->header_background_color ?? '#1EB5F0') }}"
      class="form-control"
      placeholder="#1EB5F0"
    >
  </div>
</div>

<div class="col-md-4">
  <label class="form-label">ความสูง Header (px)</label>
  <input
    type="number"
    name="header_height"
    value="{{ old('header_height', $theme->header_height ?? 82) }}"
    class="form-control"
    min="40"
    max="300"
  >
</div>

<div class="col-md-6 header-image-field">
  <label class="form-label">รูปภาพ Header</label>
  <input type="file" name="header_background_image" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
</div>

<div class="col-md-6 header-video-field">
  <label class="form-label">วิดีโอ Header</label>
  <input type="file" name="header_background_video" class="form-control" accept=".mp4,.webm,.mov">
</div>

<div class="col-md-4">
  <label class="form-label">โลโก้หลัก</label>
  <input type="file" name="header_logo_main" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
</div>

<div class="col-md-4">
  <label class="form-label">โลโก้ขวา 1</label>
  <input type="file" name="header_logo_right_1" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
</div>

<div class="col-md-4">
  <label class="form-label">โลโก้ขวา 2</label>
  <input type="file" name="header_logo_right_2" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
</div>

<div class="col-12">
  <hr class="my-2">
  <h6 class="mb-1">เมนูด้านบนของหน้าตู้</h6>
  <p class="text-muted mb-0">
    แสดงปุ่มกลับหน้าแรกและปุ่มเลือกภาษาใน Header โดยไม่ต้องใช้หน้าเลือกภาษาแยก
  </p>
</div>

@php
  $enabledLanguages = old(
      'enabled_languages',
      isset($theme) && $theme->enabled_languages
          ? (is_array($theme->enabled_languages)
              ? $theme->enabled_languages
              : json_decode($theme->enabled_languages, true))
          : ['th', 'en', 'zh']
  );

  $enabledLanguages = is_array($enabledLanguages)
      ? $enabledLanguages
      : ['th', 'en', 'zh'];
@endphp

<div class="col-md-4">
  <div class="card border shadow-none h-100 mb-0">
    <div class="card-body">
      <div class="form-check form-switch mb-3">
        <input type="hidden" name="show_home_button" value="0">

        <input
          type="checkbox"
          name="show_home_button"
          value="1"
          id="show_home_button"
          class="form-check-input"
          {{ old('show_home_button', isset($theme) ? (int) $theme->show_home_button : 1) ? 'checked' : '' }}
        >

        <label class="form-check-label fw-semibold" for="show_home_button">
          แสดงปุ่มกลับหน้าแรก
        </label>
      </div>

      <label class="form-label">ข้อความปุ่มหน้าแรก</label>
      <input
        type="text"
        name="home_button_text"
        id="home_button_text"
        value="{{ old('home_button_text', $theme->home_button_text ?? 'หน้าหลัก') }}"
        class="form-control @error('home_button_text') is-invalid @enderror"
        placeholder="หน้าหลัก"
      >

      @error('home_button_text')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
</div>

<div class="col-md-4">
  <div class="card border shadow-none h-100 mb-0">
    <div class="card-body">
      <div class="form-check form-switch mb-3">
        <input type="hidden" name="show_language_selector" value="0">

        <input
          type="checkbox"
          name="show_language_selector"
          value="1"
          id="show_language_selector"
          class="form-check-input"
          {{ old('show_language_selector', isset($theme) ? (int) $theme->show_language_selector : 1) ? 'checked' : '' }}
        >

        <label class="form-check-label fw-semibold" for="show_language_selector">
          แสดงปุ่มเลือกภาษา
        </label>
      </div>

      <label class="form-label">ภาษาเริ่มต้น</label>
      <select
        name="default_language"
        id="default_language"
        class="form-select @error('default_language') is-invalid @enderror"
      >
        <option value="th" {{ old('default_language', $theme->default_language ?? 'th') === 'th' ? 'selected' : '' }}>
          ไทย
        </option>
        <option value="en" {{ old('default_language', $theme->default_language ?? 'th') === 'en' ? 'selected' : '' }}>
          English
        </option>
        <option value="zh" {{ old('default_language', $theme->default_language ?? 'th') === 'zh' ? 'selected' : '' }}>
          中文
        </option>
      </select>

      @error('default_language')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
</div>

<div class="col-md-4">
  <div class="card border shadow-none h-100 mb-0">
    <div class="card-body">
      <label class="form-label fw-semibold d-block">
        ภาษาที่เปิดให้เลือก
      </label>

      <div class="d-flex flex-column gap-2">
        <div class="form-check">
          <input type="hidden" name="enabled_languages[]" value="">

          <input
            type="checkbox"
            name="enabled_languages[]"
            value="th"
            id="language_th"
            class="form-check-input language-option"
            {{ in_array('th', $enabledLanguages, true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="language_th">
            🇹🇭 ไทย
          </label>
        </div>

        <div class="form-check">
          <input
            type="checkbox"
            name="enabled_languages[]"
            value="en"
            id="language_en"
            class="form-check-input language-option"
            {{ in_array('en', $enabledLanguages, true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="language_en">
            🇬🇧 English
          </label>
        </div>

        <div class="form-check">
          <input
            type="checkbox"
            name="enabled_languages[]"
            value="zh"
            id="language_zh"
            class="form-check-input language-option"
            {{ in_array('zh', $enabledLanguages, true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="language_zh">
            🇨🇳 中文
          </label>
        </div>
      </div>

      @error('enabled_languages')
        <div class="text-danger small mt-2">{{ $message }}</div>
      @enderror
    </div>
  </div>
</div>

<div class="col-12">
  <div class="card border shadow-none mb-0">
    <div class="card-body">
      <label class="form-label d-block mb-3">
        ตัวอย่าง Header หน้าตู้
      </label>

      <div
        id="headerMenuPreview"
        class="rounded overflow-hidden position-relative"
        style="
          min-height: 82px;
          background: {{ old('header_background_color', $theme->header_background_color ?? '#1EB5F0') }};
        "
      >
        <div
          class="d-flex align-items-center justify-content-between gap-3 px-3 py-2"
          style="min-height: 82px;"
        >
          <div class="d-flex align-items-center gap-2">
            <button
              type="button"
              id="previewHomeButton"
              class="btn btn-light btn-sm rounded-pill d-flex align-items-center gap-1"
            >
              <i class="icon-base ti tabler-home"></i>
              <span id="previewHomeText">
                {{ old('home_button_text', $theme->home_button_text ?? 'หน้าหลัก') }}
              </span>
            </button>

            <div id="previewLanguageButtons" class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-light btn-sm rounded-circle preview-language" data-language="th">
                🇹🇭
              </button>
              <button type="button" class="btn btn-light btn-sm rounded-circle preview-language" data-language="en">
                🇬🇧
              </button>
              <button type="button" class="btn btn-light btn-sm rounded-circle preview-language" data-language="zh">
                🇨🇳
              </button>
            </div>
          </div>

          <div class="fw-bold text-white text-center flex-grow-1">
            ผู้เชี่ยวชาญการดูแลผ้าครบวงจร
          </div>

          <div style="width: 120px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>

    <textarea
      name="remark"
      rows="3"
      class="form-control @error('remark') is-invalid @enderror"
    >{{ old('remark', $theme->remark ?? '') }}</textarea>

    @error('remark')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <div class="form-check form-switch">
      <input type="hidden" name="is_active" value="0">

      <input
        type="checkbox"
        name="is_active"
        value="1"
        id="is_active"
        class="form-check-input"
        {{ old('is_active', isset($theme) ? (int) $theme->is_active : 1) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานธีม
      </label>
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-check form-switch">
      <input type="hidden" name="is_default" value="0">

      <input
        type="checkbox"
        name="is_default"
        value="1"
        id="is_default"
        class="form-check-input"
        {{ old('is_default', isset($theme) ? (int) $theme->is_default : 0) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_default">
        ตั้งเป็นธีมเริ่มต้น
      </label>
    </div>
  </div>



  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('frontend.themes.index') }}" class="btn btn-label-secondary">
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      <i class="icon-base ti tabler-device-floppy me-1"></i>
      บันทึก
    </button>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const backgroundType = document.getElementById('backgroundType');

  const backgroundColorFields = document.querySelectorAll('.background-color-field');
  const backgroundImageFields = document.querySelectorAll('.background-image-field');
  const backgroundVideoFields = document.querySelectorAll('.background-video-field');

  function toggleBackgroundFields() {
    const type = backgroundType?.value || 'color';

    backgroundColorFields.forEach(el => {
      el.classList.toggle('d-none', type !== 'color');
    });

    backgroundImageFields.forEach(el => {
      el.classList.toggle('d-none', type !== 'image');
    });

    backgroundVideoFields.forEach(el => {
      el.classList.toggle('d-none', type !== 'video');
    });
  }

  backgroundType?.addEventListener('change', toggleBackgroundFields);
  toggleBackgroundFields();

  const pickers = document.querySelectorAll('.theme-color-picker');

  pickers.forEach(function (picker) {
    picker.addEventListener('input', function () {
      const target = document.getElementById(this.dataset.target);

      if (target) {
        target.value = this.value;
        updatePreview();
      }
    });
  });

  const textInputs = [
    'text_color',
    'background_color',
    'button_color',
    'button_text_color',
    'button_hover_border_color',
  ];

  textInputs.forEach(function (id) {
    const input = document.getElementById(id);

    input?.addEventListener('input', updatePreview);
  });

  function updatePreview() {
    const preview = document.getElementById('themePreview');
    const button = document.getElementById('previewButton');

    const textColor = document.getElementById('text_color')?.value || '#111827';
    const backgroundColor = document.getElementById('background_color')?.value || '#FFFFFF';
    const buttonColor = document.getElementById('button_color')?.value || '#00AEEF';
    const buttonTextColor = document.getElementById('button_text_color')?.value || '#FFFFFF';
    const hoverBorderColor = document.getElementById('button_hover_border_color')?.value || '#00AEEF';

    if (preview) {
      preview.style.color = textColor;
      preview.style.background = backgroundColor;
    }

    if (button) {
      button.style.background = buttonColor;
      button.style.color = buttonTextColor;

      button.onmouseenter = function () {
        button.style.borderColor = hoverBorderColor;
      };

      button.onmouseleave = function () {
        button.style.borderColor = 'transparent';
      };
    }
  }

  updatePreview();
});

const headerType = document.getElementById('headerType');
const headerColorFields = document.querySelectorAll('.header-color-field');
const headerImageFields = document.querySelectorAll('.header-image-field');
const headerVideoFields = document.querySelectorAll('.header-video-field');

function toggleHeaderFields() {
  const type = headerType?.value || 'none';

  headerColorFields.forEach(el => {
    el.classList.toggle('d-none', type !== 'color');
  });

  headerImageFields.forEach(el => {
    el.classList.toggle('d-none', type !== 'image');
  });

  headerVideoFields.forEach(el => {
    el.classList.toggle('d-none', type !== 'video');
  });
}

headerType?.addEventListener('change', toggleHeaderFields);
toggleHeaderFields();

const showHomeButton = document.getElementById('show_home_button');
const homeButtonText = document.getElementById('home_button_text');
const showLanguageSelector = document.getElementById('show_language_selector');
const languageOptions = document.querySelectorAll('.language-option');
const previewHomeButton = document.getElementById('previewHomeButton');
const previewHomeText = document.getElementById('previewHomeText');
const previewLanguageButtons = document.getElementById('previewLanguageButtons');
const headerMenuPreview = document.getElementById('headerMenuPreview');
const headerBackgroundColor = document.getElementById('header_background_color');

function updateHeaderMenuPreview() {
  if (previewHomeButton) {
    previewHomeButton.classList.toggle(
      'd-none',
      !showHomeButton?.checked
    );
  }

  if (previewHomeText) {
    previewHomeText.textContent =
      homeButtonText?.value?.trim() || 'หน้าหลัก';
  }

  if (previewLanguageButtons) {
    previewLanguageButtons.classList.toggle(
      'd-none',
      !showLanguageSelector?.checked
    );
  }

  languageOptions.forEach(option => {
    const previewButton = document.querySelector(
      `.preview-language[data-language="${option.value}"]`
    );

    previewButton?.classList.toggle('d-none', !option.checked);
  });

  if (headerMenuPreview && headerBackgroundColor?.value) {
    headerMenuPreview.style.background = headerBackgroundColor.value;
  }
}

showHomeButton?.addEventListener('change', updateHeaderMenuPreview);
homeButtonText?.addEventListener('input', updateHeaderMenuPreview);
showLanguageSelector?.addEventListener('change', updateHeaderMenuPreview);
headerBackgroundColor?.addEventListener('input', updateHeaderMenuPreview);

languageOptions.forEach(option => {
  option.addEventListener('change', updateHeaderMenuPreview);
});

updateHeaderMenuPreview();
</script>
