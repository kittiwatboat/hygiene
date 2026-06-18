@csrf

<div class="row g-4">

  <div class="col-md-8">
    <label class="form-label">
      ชื่อแบนเนอร์ <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="title"
      value="{{ old('title', $banner->title ?? '') }}"
      class="form-control @error('title') is-invalid @enderror"
      placeholder="เช่น โปรโมชันประจำเดือน"
      required
    >

    @error('title')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">ลำดับการแสดง</label>

    <input
      type="number"
      name="sort_order"
      value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
      class="form-control @error('sort_order') is-invalid @enderror"
      min="0"
    >

    @error('sort_order')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">
      รูปแบนเนอร์
      @if (!isset($banner))
        <span class="text-danger">*</span>
      @endif
    </label>

    <input
      type="file"
      name="image"
      id="bannerImageInput"
      class="form-control @error('image') is-invalid @enderror"
      accept=".jpg,.jpeg,.png,.webp"
      {{ isset($banner) ? '' : 'required' }}
    >

    @error('image')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="form-text">
      แนะนำขนาด 1920 × 600 px รองรับ JPG, PNG และ WEBP
    </div>
  </div>

  <div class="col-12">
    <div
      id="bannerPreviewWrapper"
      class="{{ isset($banner) && $banner->image ? '' : 'd-none' }}"
    >
      <img
        id="bannerPreview"
        src="{{ isset($banner) && $banner->image ? asset('assets/img/banners/' . $banner->image) : '' }}"
        alt="Banner preview"
        class="rounded border"
        style="width:100%;max-height:300px;object-fit:cover;"
      >
    </div>
  </div>

  <div class="col-12">
    <label class="form-label">ลิงก์เมื่อกดแบนเนอร์</label>

    <input
      type="text"
      name="link_url"
      value="{{ old('link_url', $banner->link_url ?? '') }}"
      class="form-control @error('link_url') is-invalid @enderror"
      placeholder="เช่น https://example.com หรือ /promotions"
    >

    @error('link_url')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">วันเริ่มแสดง</label>

    <input
      type="datetime-local"
      name="start_at"
      value="{{ old('start_at', isset($banner) && $banner->start_at ? $banner->start_at->format('Y-m-d\TH:i') : '') }}"
      class="form-control @error('start_at') is-invalid @enderror"
    >

    @error('start_at')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">วันสิ้นสุด</label>

    <input
      type="datetime-local"
      name="end_at"
      value="{{ old('end_at', isset($banner) && $banner->end_at ? $banner->end_at->format('Y-m-d\TH:i') : '') }}"
      class="form-control @error('end_at') is-invalid @enderror"
    >

    @error('end_at')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>

    <textarea
      name="remark"
      rows="3"
      class="form-control"
    >{{ old('remark', $banner->remark ?? '') }}</textarea>
  </div>

  <div class="col-12">
    <div class="form-check form-switch">
      <input type="hidden" name="is_active" value="0">

      <input
        type="checkbox"
        name="is_active"
        value="1"
        class="form-check-input"
        id="is_active"
        {{ old('is_active', isset($banner) ? (int) $banner->is_active : 1) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานแบนเนอร์
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('banners.index') }}" class="btn btn-label-secondary">
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
  const input = document.getElementById('bannerImageInput');
  const wrapper = document.getElementById('bannerPreviewWrapper');
  const preview = document.getElementById('bannerPreview');

  if (!input) return;

  input.addEventListener('change', function () {
    const file = this.files?.[0];

    if (!file) return;

    preview.src = URL.createObjectURL(file);
    wrapper.classList.remove('d-none');
  });
});
</script>
