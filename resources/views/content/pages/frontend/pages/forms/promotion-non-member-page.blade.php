@php
  $settings = $page->settings_json ?? [];

  $emptyStateImage = $page->media->firstWhere('media_slot', 'empty_state_image');
  $popupPoster = $page->media->firstWhere('media_slot', 'popup_poster');
  $popupQr = $page->media->firstWhere('media_slot', 'popup_qr');

  $iconOptions = [
    'tabler-user-off' => 'User Off',
    'tabler-user-x' => 'User X',
    'tabler-user-question' => 'User Question',
    'tabler-user-plus' => 'User Plus',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-chevron-left' => 'Chevron Left',
  ];
@endphp

<style>
  .non-member-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
    position: relative;
  }

  .non-member-title {
    text-align: center;
    color: #111827;
    font-size: 28px;
    font-weight: 900;
    margin: 10px 0 6px;
  }

  .non-member-subtitle {
    text-align: center;
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 18px;
  }

  .non-member-empty-box {
    min-height: 320px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .non-member-empty-box img {
    max-width: 220px;
    max-height: 220px;
    object-fit: contain;
  }

  .non-member-empty-placeholder {
    width: 220px;
    height: 220px;
    border-radius: 20px;
    background: rgba(255,255,255,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7aa8d8;
    font-size: 70px;
  }

  .non-member-footer {
    display: flex;
    justify-content: center;
    gap: 14px;
    margin-top: 12px;
  }

  .non-member-footer button {
    border: 0;
    border-radius: 10px;
    min-width: 180px;
    min-height: 48px;
    font-weight: 800;
    padding: 10px 18px;
  }

  .btn-skip {
    background: #fff;
    color: #0877c9;
  }

  .btn-register {
    background: #0877c9;
    color: #fff;
  }

  .non-member-popup-overlay {
    position: absolute;
    inset: 0;
    background: rgba(17, 24, 39, .55);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .non-member-popup-overlay.is-open {
    display: flex;
  }

  .non-member-popup {
    background: #fff;
    border-radius: 18px;
    width: 100%;
    max-width: 720px;
    padding: 18px;
    box-shadow: 0 20px 40px rgba(0,0,0,.18);
  }

  .non-member-popup-grid {
    display: grid;
    grid-template-columns: 1.25fr .9fr;
    gap: 14px;
    align-items: stretch;
  }

  .popup-poster-box,
  .popup-qr-box {
    background: #f8fcff;
    border-radius: 14px;
    padding: 10px;
    border: 1px solid #d5ecff;
  }

  .popup-poster-box img,
  .popup-qr-box img {
    width: 100%;
    border-radius: 10px;
    display: block;
    object-fit: cover;
  }

  .popup-placeholder {
    min-height: 260px;
    border-radius: 12px;
    background: linear-gradient(180deg, #e7f6ff 0%, #f7fbff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8bbce1;
    font-weight: 700;
    text-align: center;
    padding: 20px;
  }

  .popup-qr-title {
    color: #0f172a;
    font-size: 18px;
    font-weight: 900;
    line-height: 1.4;
    text-align: center;
    margin-bottom: 10px;
  }

  .popup-actions {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    margin-top: 16px;
  }

  .popup-actions button {
    border: 0;
    border-radius: 10px;
    min-height: 48px;
    min-width: 180px;
    font-weight: 800;
  }

  .popup-skip {
    background: #fff;
    color: #0877c9;
    border: 1px solid #cde7fb;
  }

  .popup-register {
    background: #0877c9;
    color: #fff;
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าไม่พบสมาชิก</h5>
      <p class="text-muted mb-0">
        ใช้แสดงหน้าไม่พบข้อมูลสมาชิก และ Popup สมัครสมาชิก
      </p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">ชื่อหน้า</label>
          <input
            type="text"
            name="name"
            value="{{ old('name', $page->name) }}"
            class="form-control"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label">ข้อความหัวข้อ</label>
          <input
            type="text"
            name="message_title"
            value="{{ old('message_title', $settings['message_title'] ?? 'ไม่พบข้อมูลสมาชิก') }}"
            class="form-control"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">ข้อความรอง</label>
          <input
            type="text"
            name="message_subtitle"
            value="{{ old('message_subtitle', $settings['message_subtitle'] ?? 'กรุณาตรวจสอบเบอร์โทรอีกครั้ง') }}"
            class="form-control"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Icon Step</label>
          <select name="step_icon" class="form-select">
            @foreach ($iconOptions as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ ($settings['step_icon'] ?? 'tabler-user-off') === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">ปุ่มหน้าไม่พบสมาชิก</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_skip_button" value="0">
          <input
            type="checkbox"
            name="show_skip_button"
            value="1"
            class="form-check-input"
            id="show_skip_button"
            {{ old('show_skip_button', $settings['show_skip_button'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_skip_button">
            แสดงปุ่มข้าม
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">ข้อความปุ่มข้าม</label>
          <input
            type="text"
            name="skip_button_text"
            value="{{ old('skip_button_text', $settings['skip_button_text'] ?? 'ข้าม') }}"
            class="form-control"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Action ปุ่มข้าม</label>
          <input
            type="text"
            name="skip_button_action"
            value="{{ old('skip_button_action', $settings['skip_button_action'] ?? 'select_product_page') }}"
            class="form-control"
          >
        </div>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_register_button" value="0">
          <input
            type="checkbox"
            name="show_register_button"
            value="1"
            class="form-check-input"
            id="show_register_button"
            {{ old('show_register_button', $settings['show_register_button'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_register_button">
            แสดงปุ่มสมัครสมาชิก
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">ข้อความปุ่มสมัครสมาชิก</label>
          <input
            type="text"
            name="register_button_text"
            value="{{ old('register_button_text', $settings['register_button_text'] ?? 'สมัครสมาชิก') }}"
            class="form-control"
          >
        </div>

        <hr class="my-4">

        <h6 class="mb-3">ตั้งค่า Popup</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_popup" value="0">
          <input
            type="checkbox"
            name="show_popup"
            value="1"
            class="form-check-input"
            id="show_popup"
            {{ old('show_popup', $settings['show_popup'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_popup">
            แสดง Popup สมัครสมาชิก
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">หัวข้อ Popup</label>
          <input
            type="text"
            name="popup_title"
            value="{{ old('popup_title', $settings['popup_title'] ?? 'สมัครสมาชิกวันนี้ รับส่วนลดทันที 20 บาท') }}"
            class="form-control"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">คำอธิบาย Popup</label>
          <input
            type="text"
            name="popup_subtitle"
            value="{{ old('popup_subtitle', $settings['popup_subtitle'] ?? 'สแกน QR Code เพื่อสมัครสมาชิก') }}"
            class="form-control"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">ข้อความปุ่มข้ามใน Popup</label>
          <input
            type="text"
            name="popup_skip_button_text"
            value="{{ old('popup_skip_button_text', $settings['popup_skip_button_text'] ?? 'ข้าม') }}"
            class="form-control"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">ข้อความปุ่มสมัครสมาชิกใน Popup</label>
          <input
            type="text"
            name="popup_register_button_text"
            value="{{ old('popup_register_button_text', $settings['popup_register_button_text'] ?? 'สมัครสมาชิก') }}"
            class="form-control"
          >
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกข้อมูลหน้า
        </button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">จัดการรูปแสดงผล</h5>
      <p class="text-muted mb-0">
        แยกตามตำแหน่งหน้าจอและ Popup
      </p>
    </div>

    <div class="card-body">
      <form
        action="{{ route('frontend.pages.media.store', $page) }}"
        method="POST"
        enctype="multipart/form-data"
        class="mb-4"
      >
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="empty_state_image">

        <label class="form-label">รูปหน้าไม่พบสมาชิก</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">
          บันทึก / เปลี่ยนรูปหน้าไม่พบสมาชิก
        </button>
      </form>

      <form
        action="{{ route('frontend.pages.media.store', $page) }}"
        method="POST"
        enctype="multipart/form-data"
        class="mb-4"
      >
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="popup_poster">

        <label class="form-label">รูป Poster Popup</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">
          บันทึก / เปลี่ยน Poster Popup
        </button>
      </form>

      <form
        action="{{ route('frontend.pages.media.store', $page) }}"
        method="POST"
        enctype="multipart/form-data"
      >
        @csrf
        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="media_slot" value="popup_qr">

        <label class="form-label">รูป QR Code Popup</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        <button type="submit" class="btn btn-outline-primary w-100 mt-2">
          บันทึก / เปลี่ยน QR Code
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <h5 class="mb-1">Preview หน้าไม่พบสมาชิก + Popup</h5>
        <p class="text-muted mb-0">
          ตัวอย่างการแสดงผลหน้าไม่พบสมาชิก และ Popup สมัครสมาชิก
        </p>
      </div>

      <button
        type="button"
        class="btn btn-primary"
        id="openNonMemberPopupPreview"
      >
        ดูตัวอย่าง Popup
      </button>
    </div>

    <div class="card-body">
      <div class="non-member-preview">
        <div class="non-member-title">
          {{ $settings['message_title'] ?? 'ไม่พบข้อมูลสมาชิก' }}
        </div>

        <div class="non-member-subtitle">
          {{ $settings['message_subtitle'] ?? 'กรุณาตรวจสอบเบอร์โทรอีกครั้ง' }}
        </div>

        <div class="non-member-empty-box">
          @if ($emptyStateImage)
            <img src="{{ $emptyStateImage->file_url }}" alt="ไม่พบสมาชิก">
          @else
            <div class="non-member-empty-placeholder">
              <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-user-off' }}"></i>
            </div>
          @endif
        </div>

        <div class="non-member-footer">
          @if ($settings['show_skip_button'] ?? true)
            <button type="button" class="btn-skip">
              {{ $settings['skip_button_text'] ?? 'ข้าม' }}
            </button>
          @endif

          @if ($settings['show_register_button'] ?? true)
            <button type="button" class="btn-register" id="openNonMemberPopupPreview2">
              {{ $settings['register_button_text'] ?? 'สมัครสมาชิก' }}
            </button>
          @endif
        </div>

        <div class="non-member-popup-overlay" id="nonMemberPopupPreview">
          <div class="non-member-popup">
            <div class="non-member-popup-grid">
              <div class="popup-poster-box">
                @if ($popupPoster)
                  <img src="{{ $popupPoster->file_url }}" alt="Poster Popup">
                @else
                  <div class="popup-placeholder">
                    รูป Poster Popup
                  </div>
                @endif
              </div>

              <div class="popup-qr-box">
                <div class="popup-qr-title">
                  {{ $settings['popup_subtitle'] ?? 'สแกน QR Code เพื่อสมัครสมาชิก' }}
                </div>

                @if ($popupQr)
                  <img src="{{ $popupQr->file_url }}" alt="QR Popup">
                @else
                  <div class="popup-placeholder">
                    รูป QR Code
                  </div>
                @endif
              </div>
            </div>

            <div class="popup-actions">
              <button type="button" class="popup-skip" id="closeNonMemberPopupPreview">
                {{ $settings['popup_skip_button_text'] ?? 'ข้าม' }}
              </button>

              <button type="button" class="popup-register">
                {{ $settings['popup_register_button_text'] ?? 'สมัครสมาชิก' }}
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
  const popup = document.getElementById('nonMemberPopupPreview');
  const open1 = document.getElementById('openNonMemberPopupPreview');
  const open2 = document.getElementById('openNonMemberPopupPreview2');
  const close1 = document.getElementById('closeNonMemberPopupPreview');

  function openPopup() {
    popup?.classList.add('is-open');
  }

  function closePopup() {
    popup?.classList.remove('is-open');
  }

  open1?.addEventListener('click', openPopup);
  open2?.addEventListener('click', openPopup);
  close1?.addEventListener('click', closePopup);

  popup?.addEventListener('click', function (e) {
    if (e.target === popup) {
      closePopup();
    }
  });
});
</script>
