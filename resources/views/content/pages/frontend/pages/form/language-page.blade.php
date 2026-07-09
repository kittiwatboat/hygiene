<div class="col-lg-6">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">หน้าเลือกภาษา</h5>
      <p class="text-muted mb-0">ตั้งค่ารูปแบบปุ่มภาษา และข้อความของหน้านี้</p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        @php
          $settings = $page->settings_json ?? [];
        @endphp

        <div class="mb-3">
          <label class="form-label">ชื่อหน้า</label>
          <input type="text" name="name" value="{{ old('name', $page->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">หัวข้อ</label>
          <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">คำอธิบาย</label>
          <input type="text" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}" class="form-control">
        </div>

        <hr class="my-4">

        <h6 class="mb-3">รูปแบบปุ่มภาษา</h6>

        <div class="mb-3">
          <label class="form-label">รูปทรงปุ่มภาษา</label>
          <select name="language_button_shape" class="form-select">
            <option value="circle" {{ old('language_button_shape', $settings['language_button_shape'] ?? 'circle') === 'circle' ? 'selected' : '' }}>
              วงกลม
            </option>
            <option value="rounded-square" {{ old('language_button_shape', $settings['language_button_shape'] ?? '') === 'rounded-square' ? 'selected' : '' }}>
              สี่เหลี่ยมมุมมน
            </option>
            <option value="square" {{ old('language_button_shape', $settings['language_button_shape'] ?? '') === 'square' ? 'selected' : '' }}>
              สี่เหลี่ยม
            </option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">รูปแบบการแสดง</label>
          <select name="language_button_style" class="form-select">
            <option value="icon_top_text_bottom" {{ old('language_button_style', $settings['language_button_style'] ?? 'icon_top_text_bottom') === 'icon_top_text_bottom' ? 'selected' : '' }}>
              Icon ด้านบน / ข้อความด้านล่าง
            </option>
            <option value="icon_only" {{ old('language_button_style', $settings['language_button_style'] ?? '') === 'icon_only' ? 'selected' : '' }}>
              แสดงเฉพาะ Icon
            </option>
            <option value="text_only" {{ old('language_button_style', $settings['language_button_style'] ?? '') === 'text_only' ? 'selected' : '' }}>
              แสดงเฉพาะข้อความ
            </option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">ขนาดปุ่ม</label>
          <select name="language_button_size" class="form-select">
            <option value="small" {{ old('language_button_size', $settings['language_button_size'] ?? '') === 'small' ? 'selected' : '' }}>เล็ก</option>
            <option value="medium" {{ old('language_button_size', $settings['language_button_size'] ?? 'medium') === 'medium' ? 'selected' : '' }}>กลาง</option>
            <option value="large" {{ old('language_button_size', $settings['language_button_size'] ?? '') === 'large' ? 'selected' : '' }}>ใหญ่</option>
          </select>
        </div>

        <div class="form-check form-switch mb-2">
          <input type="hidden" name="show_button_border" value="0">
          <input
            type="checkbox"
            name="show_button_border"
            value="1"
            class="form-check-input"
            {{ old('show_button_border', $settings['show_button_border'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label">แสดงเส้นขอบปุ่ม</label>
        </div>

        <div class="form-check form-switch mb-4">
          <input type="hidden" name="show_button_shadow" value="0">
          <input
            type="checkbox"
            name="show_button_shadow"
            value="1"
            class="form-check-input"
            {{ old('show_button_shadow', $settings['show_button_shadow'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label">แสดงเงาปุ่ม</label>
        </div>

        <div class="form-check form-switch mb-4">
          <input type="hidden" name="is_active" value="0">
          <input
            type="checkbox"
            name="is_active"
            value="1"
            id="is_active"
            class="form-check-input"
            {{ old('is_active', (int) $page->is_active) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="is_active">เปิดใช้งานหน้านี้</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">
          บันทึกหน้าเลือกภาษา
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-6">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Icon ปุ่มภาษา</h5>
      <p class="text-muted mb-0">
        เปลี่ยน icon และข้อความของปุ่มภาษาได้ที่เมนูจัดการภาษา
      </p>
    </div>

    <div class="card-body">
      <a href="{{ route('frontend.languages.index') }}" class="btn btn-primary">
        ไปที่จัดการภาษา
      </a>
    </div>
  </div>
</div>
