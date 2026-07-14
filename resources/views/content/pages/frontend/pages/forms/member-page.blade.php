@php
  $settings = $page->settings_json ?? [];
  $firstMedia = $page->media->first();

  $stepIcons = [
    'tabler-user' => 'User',
    'tabler-users' => 'Users',
    'tabler-id' => 'ID',
    'tabler-address-book' => 'Address Book',
    'tabler-star' => 'Star',
    'tabler-award' => 'Award',
    'tabler-gift' => 'Gift',
    'tabler-heart' => 'Heart',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
  ];

  $buttonIcons = [
    'tabler-home' => 'Home',
    'tabler-arrow-left' => 'Arrow Left',
    'tabler-chevron-left' => 'Chevron Left',
    'tabler-bottle' => 'Bottle',
    'tabler-droplet' => 'Droplet',
    'tabler-shopping-bag' => 'Shopping Bag',
    'tabler-circle-arrow-right' => 'Circle Arrow Right',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-check' => 'Check',
  ];

  $memberPanelBgType = $settings['member_panel_background_type'] ?? 'color';
  $memberPanelBgColor = $settings['member_panel_background_color'] ?? '#075db8';
  $memberPanelBgImage = !empty($settings['member_panel_background_image'])
      ? asset('assets/img/frontend/pages/member-backgrounds/' . $settings['member_panel_background_image'])
      : null;

  $memberNameCardBg = $settings['member_name_card_background_color'] ?? '#238bff';
  $memberNameCardText = $settings['member_name_card_text_color'] ?? '#ffffff';
@endphp

<style>
  .member-kiosk-preview {
    width: 100%;
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .member-kiosk-content {
    display: grid;
    grid-template-columns: 48% 52%;
    gap: 10px;
    align-items: stretch;
    min-height: 310px;
  }

  .member-panel {
    border-radius: 10px;
    padding: 22px 18px 18px;
    color: #fff;
    min-height: 310px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
    background-repeat: no-repeat;
  }

  .member-top-row {
    display: grid;
    grid-template-columns: 38% 62%;
    gap: 16px;
    align-items: start;
  }

  .member-points-label {
    font-weight: 700;
    font-size: 18px;
    line-height: 1.2;
  }

.member-points-value {
  font-size: 42px;
  font-weight: 800;
  line-height: 1;
  color: #ffd64d;
  margin-top: 6px;
  white-space: nowrap;
  letter-spacing: -1px;
}

  .member-points-unit {
    font-weight: 700;
    font-size: 18px;
    margin-top: 8px;
  }

  .member-name-card {
    border-radius: 14px;
    padding: 14px 18px;
    min-height: 82px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .member-name-subtitle {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .5px;
    opacity: .95;
  }

  .member-name-title {
    font-size: 28px;
    line-height: 1.1;
    font-weight: 800;
    letter-spacing: 1px;
  }

  .member-history-card {
    background: #fff;
    color: #1f2d3d;
    border-radius: 14px;
    padding: 14px 18px;
    margin-top: 18px;
  }

  .member-history-title {
    font-size: 20px;
    line-height: 1.2;
    font-weight: 800;
    color: #075db8;
    margin-bottom: 10px;
  }

  .member-history-row {
    display: grid;
    grid-template-columns: 34% 46% 20%;
    gap: 8px;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px solid #d9dee3;
    font-size: 12px;
  }

  .member-history-row:last-child {
    border-bottom: 0;
  }

  .member-history-row strong {
    text-align: right;
    font-weight: 800;
  }

  .member-ad-panel {
    min-height: 310px;
    overflow: hidden;
    background: #fff;
  }

  .member-ad-media {
    width: 100%;
    height: 100%;
    min-height: 310px;
    object-fit: cover;
    display: block;
  }

  .member-ad-empty {
    height: 100%;
    min-height: 310px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8c97a4;
    background: #fff;
  }

  .member-kiosk-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 18px;
  }

  .member-home-button {
    border: 0;
    background: #fff;
    color: #0877c9;
    border-radius: 8px;
    padding: 10px 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(0, 0, 0, .08);
  }

  .member-select-button {
    border: 0;
    background: #0877c9;
    color: #fff;
    border-radius: 999px;
    padding: 14px 36px;
    min-width: 260px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    font-size: 20px;
    font-weight: 800;
    box-shadow: 0 6px 14px rgba(0, 90, 160, .28);
  }

  .member-current-ad-preview {
    width: 260px;
    height: 150px;
    object-fit: cover;
  }

  @media (max-width: 991.98px) {
    .member-kiosk-content {
      grid-template-columns: 1fr;
    }

    .member-top-row {
      grid-template-columns: 1fr;
    }

    .member-history-row {
      grid-template-columns: 1fr;
      gap: 2px;
    }

    .member-history-row strong {
      text-align: left;
    }

    .member-kiosk-footer {
      flex-direction: column;
      align-items: stretch;
    }

    .member-home-button,
    .member-select-button {
      width: 100%;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าสมาชิก</h5>
      <p class="text-muted mb-0">
        ตั้งค่ากล่องสมาชิก คะแนน ประวัติ Step และปุ่มของหน้าสมาชิก
      </p>
    </div>

    <div class="card-body">
      <form
        action="{{ route('frontend.pages.update', $page) }}"
        method="POST"
        enctype="multipart/form-data"
      >
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
            value="member_page.title"
            class="form-control"
            readonly
          >

          <div class="form-text">
            ข้อความจริงจะดึงจากระบบแปลภาษา
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

        <h6 class="mb-3">การแสดงข้อมูลสมาชิก</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_member_name" value="0">
          <input
            type="checkbox"
            name="show_member_name"
            value="1"
            id="show_member_name"
            class="form-check-input"
            {{ old('show_member_name', $settings['show_member_name'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_member_name">
            แสดงชื่อสมาชิก
          </label>
        </div>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_member_points" value="0">
          <input
            type="checkbox"
            name="show_member_points"
            value="1"
            id="show_member_points"
            class="form-check-input"
            {{ old('show_member_points', $settings['show_member_points'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_member_points">
            แสดงคะแนนสะสม
          </label>
        </div>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_member_history" value="0">
          <input
            type="checkbox"
            name="show_member_history"
            value="1"
            id="show_member_history"
            class="form-check-input"
            {{ old('show_member_history', $settings['show_member_history'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_member_history">
            แสดงประวัติการรับบริการ
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">จำนวนประวัติที่แสดง</label>

          <input
            type="number"
            name="history_limit"
            value="{{ old('history_limit', $settings['history_limit'] ?? 3) }}"
            class="form-control"
            min="1"
            max="10"
          >
        </div>

        <hr class="my-4">

        <h6 class="mb-3">พื้นหลังกล่องสมาชิก</h6>

        <div class="mb-3">
          <label class="form-label">รูปแบบพื้นหลัง</label>

          <select name="member_panel_background_type" class="form-select">
            <option
              value="color"
              {{ old('member_panel_background_type', $memberPanelBgType) === 'color' ? 'selected' : '' }}
            >
              สีพื้น
            </option>

            <option
              value="image"
              {{ old('member_panel_background_type', $memberPanelBgType) === 'image' ? 'selected' : '' }}
            >
              รูปภาพ
            </option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">สีพื้นหลังกล่องสมาชิก</label>

          <input
            type="text"
            name="member_panel_background_color"
            value="{{ old('member_panel_background_color', $memberPanelBgColor) }}"
            class="form-control"
            placeholder="#075db8"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">อัปโหลดรูปพื้นหลังกล่องสมาชิก</label>

          @if ($memberPanelBgImage)
            <div class="mb-2">
              <img
                src="{{ $memberPanelBgImage }}"
                alt="Member Background"
                class="border rounded"
                style="width: 180px; height: 90px; object-fit: cover;"
              >
            </div>
          @endif

          <input
            type="file"
            name="member_panel_background_image"
            class="form-control @error('member_panel_background_image') is-invalid @enderror"
            accept=".jpg,.jpeg,.png,.webp"
          >

          @error('member_panel_background_image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          <div class="form-text">
            ใช้เมื่อเลือกพื้นหลังเป็นรูปภาพ รองรับ JPG, PNG, WEBP
          </div>
        </div>

        @if ($memberPanelBgImage)
          <div class="form-check form-switch mb-3">
            <input type="hidden" name="remove_member_panel_background_image" value="0">
            <input
              type="checkbox"
              name="remove_member_panel_background_image"
              value="1"
              id="remove_member_panel_background_image"
              class="form-check-input"
            >
            <label class="form-check-label" for="remove_member_panel_background_image">
              ลบรูปพื้นหลังเดิม
            </label>
          </div>
        @endif

        <hr class="my-4">

        <h6 class="mb-3">กล่องชื่อสมาชิก</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="member_name_card_enabled" value="0">
          <input
            type="checkbox"
            name="member_name_card_enabled"
            value="1"
            id="member_name_card_enabled"
            class="form-check-input"
            {{ old('member_name_card_enabled', $settings['member_name_card_enabled'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="member_name_card_enabled">
            แสดงกล่องชื่อสมาชิก
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">สีพื้นกล่องชื่อสมาชิก</label>

          <input
            type="text"
            name="member_name_card_background_color"
            value="{{ old('member_name_card_background_color', $memberNameCardBg) }}"
            class="form-control"
            placeholder="#238bff"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">สีตัวอักษรกล่องชื่อสมาชิก</label>

          <input
            type="text"
            name="member_name_card_text_color"
            value="{{ old('member_name_card_text_color', $memberNameCardText) }}"
            class="form-control"
            placeholder="#ffffff"
          >
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon Step ของหน้านี้</h6>

        <div class="mb-3">
          <label class="form-label">Icon Step หน้าสมาชิก</label>

          <select name="step_icon" class="form-select">
            @php
              $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-user');
            @endphp

            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $stepIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">ปุ่มด้านล่าง</h6>

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
          <input type="hidden" name="show_select_button" value="0">
          <input
            type="checkbox"
            name="show_select_button"
            value="1"
            id="show_select_button"
            class="form-check-input"
            {{ old('show_select_button', $settings['show_select_button'] ?? true) ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_select_button">
            แสดงปุ่มเลือกเติมน้ำยา
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มเลือกเติมน้ำยา</label>

          <select name="select_button_icon" class="form-select">
            @php
              $selectButtonIcon = old('select_button_icon', $settings['select_button_icon'] ?? 'tabler-bottle');
            @endphp

            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $selectButtonIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="select_button_action"
          value="{{ old('select_button_action', $settings['select_button_action'] ?? 'select_product_page') }}"
        >

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้าสมาชิก
        </button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">AD ด้านขวา</h5>
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
            ไฟล์ AD <span class="text-danger">*</span>
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
          บันทึก / เปลี่ยน AD
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้า Member Mode</h5>
      <p class="text-muted mb-0">
        ตัวอย่างโครงหน้าจอสมาชิก โดยข้อมูลจริงจะมาจากข้อมูลสมาชิกในระบบ
      </p>
    </div>

    <div class="card-body">
      <div class="border rounded p-4" style="background: #dff8ff;">
        <div class="text-center mb-3">
          <div class="fw-bold text-primary fs-5">
            member_page.title
          </div>
          <small class="text-muted">
            member_page.subtitle
          </small>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
          <span class="badge rounded-pill bg-success p-2">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span style="width: 60px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-primary p-2">
            <i class="icon-base ti {{ $settings['step_icon'] ?? 'tabler-user' }}"></i>
          </span>

          <span style="width: 60px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-label-secondary p-2">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="member-kiosk-preview">
          <div class="member-kiosk-content">
            <div
              class="member-panel"
              style="
                background-color: {{ $memberPanelBgColor }};
                @if ($memberPanelBgType === 'image' && $memberPanelBgImage)
                  background-image: url('{{ $memberPanelBgImage }}');
                  background-size: cover;
                  background-position: center;
                @endif
              "
            >
              <div class="member-top-row">
                <div class="member-points-box">
                  @if ($settings['show_member_points'] ?? true)
                    <div class="member-points-label">แต้มสะสม</div>
                    <div class="member-points-value">1,240</div>
                    <div class="member-points-unit">คะแนน</div>
                  @endif
                </div>

                <div class="member-name-area">
                  @if (($settings['show_member_name'] ?? true) && ($settings['member_name_card_enabled'] ?? true))
                    <div
                      class="member-name-card"
                      style="
                        background: {{ $memberNameCardBg }};
                        color: {{ $memberNameCardText }};
                      "
                    >
                      <div class="member-name-subtitle">IP HAPPY FAMILY MEMBER</div>
                      <div class="member-name-title">SUCHART</div>
                    </div>
                  @endif
                </div>
              </div>

              @if ($settings['show_member_history'] ?? true)
                <div class="member-history-card">
                  <div class="member-history-title">
                    ประวัติการรับบริการล่าสุด
                  </div>

                  @for ($i = 1; $i <= (int) ($settings['history_limit'] ?? 3); $i++)
                    <div class="member-history-row">
                      <span>
                        {{ $i === 1 ? '28 พฤษภาคม 2569' : ($i === 2 ? '01 พฤษภาคม 2569' : '30 เมษายน 2569') }}
                      </span>

                      <span>
                        เติมน้ำยาปรับผ้านุ่ม&nbsp;&nbsp;500 มล.
                      </span>

                      <strong>115 บาท</strong>
                    </div>
                  @endfor
                </div>
              @endif
            </div>

            <div class="member-ad-panel">
              @if ($firstMedia && $firstMedia->media_type === 'video')
                <video
                  src="{{ $firstMedia->file_url }}"
                  class="member-ad-media"
                  muted
                  controls
                ></video>
              @elseif ($firstMedia && $firstMedia->media_type === 'image')
                <img
                  src="{{ $firstMedia->file_url }}"
                  alt="AD"
                  class="member-ad-media"
                >
              @else
                <div class="member-ad-empty">
                  AD รูปภาพ / วิดีโอ
                </div>
              @endif
            </div>
          </div>

          <div class="member-kiosk-footer">
            @if ($settings['show_home_button'] ?? true)
              <button type="button" class="member-home-button">
                <i class="icon-base ti {{ $settings['home_button_icon'] ?? 'tabler-home' }}"></i>
                <span>member_page.home_button</span>
              </button>
            @endif

            @if ($settings['show_select_button'] ?? true)
              <button type="button" class="member-select-button">
                <i class="icon-base ti {{ $settings['select_button_icon'] ?? 'tabler-bottle' }}"></i>
                <span>member_page.select_button</span>
                <i class="icon-base ti tabler-chevron-right"></i>
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  @if ($firstMedia)
    <div class="card">
      <div class="card-header">
        <h5 class="mb-1">AD ปัจจุบัน</h5>
        <p class="text-muted mb-0">
          ไฟล์ที่ใช้งานอยู่ในหน้าสมาชิก
        </p>
      </div>

      <div class="card-body">
        <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
          <div class="border rounded overflow-hidden" style="width: 260px; height: 150px;">
            @if ($firstMedia->media_type === 'video')
              <video
                src="{{ $firstMedia->file_url }}"
                class="member-current-ad-preview"
                muted
                controls
              ></video>
            @else
              <img
                src="{{ $firstMedia->file_url }}"
                alt="AD"
                class="member-current-ad-preview"
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
            onsubmit="return confirm('ยืนยันการลบ AD นี้?')"
          >
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">
              ลบ AD
            </button>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
