<div class="col-lg-5">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าเลือกภาษา</h5>
      <p class="text-muted mb-0">
        กำหนดรูปแบบปุ่มภาษาและปุ่มด้านล่างของหน้าจอตู้
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
            value="language_page.title"
            class="form-control"
            readonly
          >
          <div class="form-text">
            ข้อความหัวข้อจะถูกแปลอัตโนมัติตามภาษาที่ผู้ใช้เลือก
          </div>
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

        <h6 class="mb-3">รูปแบบปุ่มภาษา</h6>

        <div class="mb-3">
          <label class="form-label">รูปทรงปุ่มภาษา</label>
          <select name="language_button_shape" class="form-select">
            <option
              value="circle"
              {{ old('language_button_shape', $settings['language_button_shape'] ?? 'circle') === 'circle' ? 'selected' : '' }}
            >
              วงกลม
            </option>
            <option
              value="rounded-square"
              {{ old('language_button_shape', $settings['language_button_shape'] ?? '') === 'rounded-square' ? 'selected' : '' }}
            >
              สี่เหลี่ยมมุมมน
            </option>
            <option
              value="square"
              {{ old('language_button_shape', $settings['language_button_shape'] ?? '') === 'square' ? 'selected' : '' }}
            >
              สี่เหลี่ยม
            </option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">รูปแบบการแสดงปุ่มภาษา</label>
          <select name="language_button_style" class="form-select">
            <option
              value="icon_top_text_bottom"
              {{ old('language_button_style', $settings['language_button_style'] ?? 'icon_top_text_bottom') === 'icon_top_text_bottom' ? 'selected' : '' }}
            >
              Icon ด้านบน / ข้อความด้านล่าง
            </option>
            <option
              value="icon_left_text_right"
              {{ old('language_button_style', $settings['language_button_style'] ?? '') === 'icon_left_text_right' ? 'selected' : '' }}
            >
              Icon ซ้าย / ข้อความขวา
            </option>
            <option
              value="icon_only"
              {{ old('language_button_style', $settings['language_button_style'] ?? '') === 'icon_only' ? 'selected' : '' }}
            >
              แสดงเฉพาะ Icon
            </option>
            <option
              value="text_only"
              {{ old('language_button_style', $settings['language_button_style'] ?? '') === 'text_only' ? 'selected' : '' }}
            >
              แสดงเฉพาะข้อความ
            </option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">ขนาดปุ่มภาษา</label>
          <select name="language_button_size" class="form-select">
            <option
              value="small"
              {{ old('language_button_size', $settings['language_button_size'] ?? '') === 'small' ? 'selected' : '' }}
            >
              เล็ก
            </option>
            <option
              value="medium"
              {{ old('language_button_size', $settings['language_button_size'] ?? 'medium') === 'medium' ? 'selected' : '' }}
            >
              กลาง
            </option>
            <option
              value="large"
              {{ old('language_button_size', $settings['language_button_size'] ?? '') === 'large' ? 'selected' : '' }}
            >
              ใหญ่
            </option>
          </select>
        </div>

        <div class="form-check form-switch mb-2">
          <input type="hidden" name="show_button_border" value="0">
          <input
            type="checkbox"
            name="show_button_border"
            value="1"
            id="show_button_border"
            class="form-check-input"
            {{ old('show_button_border', $settings['show_button_border'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_button_border">
            แสดงเส้นขอบปุ่มภาษา
          </label>
        </div>

        <div class="form-check form-switch mb-4">
          <input type="hidden" name="show_button_shadow" value="0">
          <input
            type="checkbox"
            name="show_button_shadow"
            value="1"
            id="show_button_shadow"
            class="form-check-input"
            {{ old('show_button_shadow', $settings['show_button_shadow'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_button_shadow">
            แสดงเงาปุ่มภาษา
          </label>
        </div>


        {{-- <div class="form-check form-switch mb-4">
          <input type="hidden" name="is_active" value="0">
          <input
            type="checkbox"
            name="is_active"
            value="1"
            id="is_active"
            class="form-check-input"
            {{ old('is_active', (int) $page->is_active) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="is_active">
            เปิดใช้งานหน้านี้
          </label>
        </div> --}}

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้าเลือกภาษา
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-7">
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-1">Preview โครงหน้าเลือกภาษา</h5>
      <p class="text-muted mb-0">
        ตัวอย่างภาพรวมของหน้าจอ โดยข้อความจริงจะเปลี่ยนตามภาษาที่เลือก
      </p>
    </div>

    <div class="card-body">
      <div class="border rounded p-4" style="background: #dff8ff;">
        <div class="text-center mb-4">
          <div class="fw-bold text-primary">
            {{ __('language_page.title') }}
          </div>
          <small class="text-muted">
            ตัวอย่างจริงจะดึงคำแปลจาก frontend_translations
          </small>
        </div>

        <div class="d-flex justify-content-center gap-4 mb-4">
          <div class="text-center">
            <div class="mx-auto mb-2 rounded-circle border bg-white d-flex align-items-center justify-content-center" style="width: 78px; height: 78px;">
              TH
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary">
              ภาษาไทย
            </button>
          </div>

          <div class="text-center">
            <div class="mx-auto mb-2 rounded-circle border bg-white d-flex align-items-center justify-content-center" style="width: 78px; height: 78px;">
              EN
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary">
              English
            </button>
          </div>

          <div class="text-center">
            <div class="mx-auto mb-2 rounded-circle border bg-white d-flex align-items-center justify-content-center" style="width: 78px; height: 78px;">
              CN
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary">
              中文
            </button>
          </div>
        </div>


      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">จัดการภาษาและคำแปล</h5>
      <p class="text-muted mb-0">
        ภาษา, icon ของภาษา และคำแปลหลายภาษา จะจัดการแยกจากหน้านี้
      </p>
    </div>

    <div class="card-body">
      <div class="d-flex flex-column flex-md-row gap-2">
        <a href="{{ route('frontend.languages.index') }}" class="btn btn-label-primary">
          <i class="icon-base ti tabler-language me-1"></i>
          จัดการภาษา
        </a>
{{--
        @if (Route::has('frontend.translations.index'))
          <a href="{{ route('frontend.translations.index') }}" class="btn btn-label-primary">
            <i class="icon-base ti tabler-translate me-1"></i>
            จัดการคำแปล
          </a>
        @endif --}}
      </div>
    </div>
  </div>
</div>
