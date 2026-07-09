@csrf

<div class="row g-4">

  <div class="col-md-4">
    <label class="form-label">
      รหัสภาษา <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="code"
      value="{{ old('code', $language->code ?? '') }}"
      class="form-control @error('code') is-invalid @enderror"
      placeholder="เช่น th, en, zh"
      maxlength="20"
      required
    >

    @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="form-text">
      ใช้เป็น code ภาษาบนหน้าตู้ เช่น th, en, zh
    </div>
  </div>

  <div class="col-md-4">
    <label class="form-label">
      ชื่อภาษา <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="name"
      value="{{ old('name', $language->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น Thai, English"
      required
    >

    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">
      ชื่อที่แสดงหน้าตู้ <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="native_name"
      value="{{ old('native_name', $language->native_name ?? '') }}"
      class="form-control @error('native_name') is-invalid @enderror"
      placeholder="เช่น ภาษาไทย, English, 中文"
      required
    >

    @error('native_name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Locale</label>

    <input
      type="text"
      name="locale"
      value="{{ old('locale', $language->locale ?? '') }}"
      class="form-control @error('locale') is-invalid @enderror"
      placeholder="เช่น th_TH, en_US, zh_CN"
    >

    @error('locale')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ลำดับ</label>

    <input
      type="number"
      name="sort_order"
      value="{{ old('sort_order', $language->sort_order ?? 0) }}"
      class="form-control @error('sort_order') is-invalid @enderror"
      min="0"
    >

    @error('sort_order')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">รูปธง</label>

    <input
      type="file"
      name="flag_image"
      id="flagImageInput"
      class="form-control @error('flag_image') is-invalid @enderror"
      accept=".jpg,.jpeg,.png,.webp,.svg"
    >

    @error('flag_image')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="form-text">
      รองรับ JPG, PNG, WEBP, SVG ขนาดไม่เกิน 2 MB
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label d-block">ตัวอย่างรูปธง</label>

    <div
      id="flagPreviewWrapper"
      class="{{ isset($language) && $language->flag_image ? '' : 'd-none' }}"
    >
      <img
        id="flagPreview"
        src="{{ isset($language) && $language->flag_image ? asset('assets/img/languages/' . $language->flag_image) : '' }}"
        alt="Flag"
        class="rounded border"
        style="width: 90px; height: 60px; object-fit: cover;"
      >
    </div>

    @if (isset($language) && $language->flag_image)
      <div class="form-check mt-2">
        <input
          type="checkbox"
          name="remove_flag_image"
          value="1"
          class="form-check-input"
          id="remove_flag_image"
        >

        <label class="form-check-label" for="remove_flag_image">
          ลบรูปธงเดิม
        </label>
      </div>
    @endif
  </div>

  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>

    <textarea
      name="remark"
      rows="3"
      class="form-control @error('remark') is-invalid @enderror"
    >{{ old('remark', $language->remark ?? '') }}</textarea>

    @error('remark')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <div class="form-check form-switch">
      <input type="hidden" name="is_active" value="0">

      <input
        type="checkbox"
        name="is_active"
        value="1"
        id="is_active"
        class="form-check-input"
        {{ old('is_active', isset($language) ? (int) $language->is_active : 1) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานภาษา
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a
      href="{{ route('frontend.languages.index') }}"
      class="btn btn-label-secondary"
    >
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
  const input = document.getElementById('flagImageInput');
  const wrapper = document.getElementById('flagPreviewWrapper');
  const preview = document.getElementById('flagPreview');

  if (!input || !wrapper || !preview) {
    return;
  }

  input.addEventListener('change', function () {
    const file = this.files?.[0];

    if (!file) {
      return;
    }

    preview.src = URL.createObjectURL(file);
    wrapper.classList.remove('d-none');
  });
});
</script>
