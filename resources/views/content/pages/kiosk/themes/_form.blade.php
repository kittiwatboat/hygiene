@csrf

@php
  $settings = old('settings_json', $theme->settings_json ?? []);
@endphp

<div class="row g-4">

  <div class="col-md-6">
    <label class="form-label">
      ชื่อธีม <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="name"
      value="{{ old('name', $theme->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น Hygiene Default"
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
      placeholder="เช่น hygiene-default"
    >

    @error('slug')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <hr>
    <h6 class="mb-1">สีหลักของธีม</h6>
    <p class="text-muted mb-0">กำหนดชุดสีหลักของหน้าตู้</p>
  </div>

  @php
    $colorFields = [
      'primary_color' => ['สีหลัก', '#00AEEF'],
      'secondary_color' => ['สีรอง', '#FFFFFF'],
      'accent_color' => ['สีเน้น', '#F7941D'],
      'background_color' => ['สีพื้นหลัง', '#FFFFFF'],
      'text_color' => ['สีตัวอักษร', '#111827'],
      'muted_text_color' => ['สีตัวอักษรรอง', '#6B7280'],
      'button_background_color' => ['สีพื้นหลังปุ่ม', '#00AEEF'],
      'button_text_color' => ['สีตัวอักษรปุ่ม', '#FFFFFF'],
      'button_border_color' => ['สีขอบปุ่ม', ''],
      'button_hover_background_color' => ['สีปุ่มตอน Hover', ''],
      'button_hover_text_color' => ['สีตัวอักษรปุ่มตอน Hover', ''],
      'card_background_color' => ['สีพื้นหลังการ์ด', '#FFFFFF'],
      'card_text_color' => ['สีตัวอักษรการ์ด', '#111827'],
      'card_border_color' => ['สีขอบการ์ด', ''],
      'success_color' => ['สี Success', '#22C55E'],
      'warning_color' => ['สี Warning', '#F59E0B'],
      'danger_color' => ['สี Danger', '#EF4444'],
      'info_color' => ['สี Info', '#3B82F6'],
    ];
  @endphp

  @foreach ($colorFields as $field => [$label, $default])
    <div class="col-md-4">
      <label class="form-label">{{ $label }}</label>

      <div class="input-group">
        <input
          type="color"
          value="{{ old($field, $theme->$field ?? $default ?: '#ffffff') }}"
          class="form-control form-control-color theme-color-picker"
          data-target="{{ $field }}"
          style="max-width: 64px;"
        >

        <input
          type="text"
          name="{{ $field }}"
          id="{{ $field }}"
          value="{{ old($field, $theme->$field ?? $default) }}"
          class="form-control @error($field) is-invalid @enderror"
          placeholder="#FFFFFF"
        >
      </div>

      @error($field)
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror
    </div>
  @endforeach

  <div class="col-12">
    <hr>
    <h6 class="mb-1">ฟอนต์และรูปทรง</h6>
  </div>

  <div class="col-md-3">
    <label class="form-label">Font Family</label>

    <input
      type="text"
      name="font_family"
      value="{{ old('font_family', $theme->font_family ?? 'Prompt') }}"
      class="form-control"
      placeholder="Prompt"
    >
  </div>

  <div class="col-md-3">
    <label class="form-label">มุมโค้งปุ่ม</label>

    <input
      type="number"
      name="button_radius"
      value="{{ old('button_radius', $theme->button_radius ?? 24) }}"
      class="form-control"
      min="0"
    >
  </div>

  <div class="col-md-3">
    <label class="form-label">มุมโค้งการ์ด</label>

    <input
      type="number"
      name="card_radius"
      value="{{ old('card_radius', $theme->card_radius ?? 28) }}"
      class="form-control"
      min="0"
    >
  </div>

  <div class="col-md-3">
    <label class="form-label">มุมโค้ง Input</label>

    <input
      type="number"
      name="input_radius"
      value="{{ old('input_radius', $theme->input_radius ?? 16) }}"
      class="form-control"
      min="0"
    >
  </div>

  <div class="col-12">
    <hr>
    <h6 class="mb-1">โลโก้ธีม</h6>
  </div>

  <div class="col-md-6">
    <label class="form-label">อัปโหลดโลโก้</label>

    <input
      type="file"
      name="logo"
      id="themeLogoInput"
      class="form-control @error('logo') is-invalid @enderror"
      accept=".jpg,.jpeg,.png,.webp,.svg"
    >

    @error('logo')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label d-block">ตัวอย่างโลโก้</label>

    <div
      id="themeLogoPreviewWrapper"
      class="{{ isset($theme) && $theme->logo ? '' : 'd-none' }}"
    >
      <img
        id="themeLogoPreview"
        src="{{ isset($theme) && $theme->logo ? asset('assets/img/kiosk/themes/' . $theme->logo) : '' }}"
        class="rounded border p-2"
        style="max-width: 180px; max-height: 90px; object-fit: contain;"
        alt="Theme Logo"
      >
    </div>

    @if (isset($theme) && $theme->logo)
      <div class="form-check mt-2">
        <input
          type="checkbox"
          name="remove_logo"
          value="1"
          class="form-check-input"
          id="remove_logo"
        >

        <label class="form-check-label" for="remove_logo">
          ลบโลโก้เดิม
        </label>
      </div>
    @endif
  </div>

  <div class="col-12">
    <hr>
    <h6 class="mb-1">ค่าเสริม</h6>
  </div>

  <div class="col-md-4">
    <label class="form-label">Overlay Color</label>

    <input
      type="text"
      name="overlay_color"
      value="{{ old('overlay_color', $settings['overlay_color'] ?? '') }}"
      class="form-control"
      placeholder="rgba(0, 0, 0, 0.25)"
    >
  </div>

  <div class="col-md-4">
    <label class="form-label">Shadow</label>

    <input
      type="text"
      name="shadow"
      value="{{ old('shadow', $settings['shadow'] ?? '') }}"
      class="form-control"
      placeholder="0 16px 40px rgba(0,0,0,.12)"
    >
  </div>

  <div class="col-md-4">
    <label class="form-label">Disabled Color</label>

    <input
      type="text"
      name="disabled_color"
      value="{{ old('disabled_color', $settings['disabled_color'] ?? '') }}"
      class="form-control"
      placeholder="#D1D5DB"
    >
  </div>

  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>

    <textarea
      name="remark"
      rows="3"
      class="form-control"
    >{{ old('remark', $theme->remark ?? '') }}</textarea>
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

  <div class="col-12">
    <div class="card border shadow-none">
      <div class="card-header">
        <h6 class="mb-0">ตัวอย่างธีม</h6>
      </div>

      <div class="card-body">
        <div
          id="themePreview"
          class="p-4 rounded"
          style="
            background: {{ old('background_color', $theme->background_color ?? '#FFFFFF') }};
            color: {{ old('text_color', $theme->text_color ?? '#111827') }};
            border: 1px solid {{ old('card_border_color', $theme->card_border_color ?? '#E5E7EB') }};
          "
        >
          <div
            class="p-3 mb-3"
            id="themePreviewCard"
            style="
              background: {{ old('card_background_color', $theme->card_background_color ?? '#FFFFFF') }};
              color: {{ old('card_text_color', $theme->card_text_color ?? '#111827') }};
              border-radius: {{ old('card_radius', $theme->card_radius ?? 28) }}px;
              border: 1px solid {{ old('card_border_color', $theme->card_border_color ?? '#E5E7EB') }};
            "
          >
            <h5 class="mb-1">ตัวอย่างหน้าตู้</h5>
            <p class="mb-0">ข้อความ ตัวการ์ด และปุ่มจะใช้สีจากธีมนี้</p>
          </div>

          <button
            type="button"
            id="themePreviewButton"
            style="
              background: {{ old('button_background_color', $theme->button_background_color ?? '#00AEEF') }};
              color: {{ old('button_text_color', $theme->button_text_color ?? '#FFFFFF') }};
              border-radius: {{ old('button_radius', $theme->button_radius ?? 24) }}px;
              border: 1px solid {{ old('button_border_color', $theme->button_border_color ?? 'transparent') }};
              padding: .75rem 1.5rem;
            "
          >
            ปุ่มตัวอย่าง
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('kiosk.themes.index') }}" class="btn btn-label-secondary">
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
  const pickers = document.querySelectorAll('.theme-color-picker');

  pickers.forEach(function (picker) {
    picker.addEventListener('input', function () {
      const target = document.getElementById(this.dataset.target);

      if (target) {
        target.value = this.value;
      }
    });
  });

  const logoInput = document.getElementById('themeLogoInput');
  const logoWrapper = document.getElementById('themeLogoPreviewWrapper');
  const logoPreview = document.getElementById('themeLogoPreview');

  logoInput?.addEventListener('change', function () {
    const file = this.files?.[0];

    if (!file) {
      return;
    }

    logoPreview.src = URL.createObjectURL(file);
    logoWrapper.classList.remove('d-none');
  });
});
</script>
