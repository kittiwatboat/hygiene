@php
  $settings = $page->settings_json ?? [];
  $firstMedia = $page->media->first();

  $stepIcons = [
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-user' => 'User',
    'tabler-users' => 'Users',
    'tabler-bottle' => 'Bottle',
    'tabler-droplet' => 'Droplet',
    'tabler-credit-card' => 'Credit Card',
    'tabler-receipt' => 'Receipt',
    'tabler-home' => 'Home',
    'tabler-minus' => 'Minus',
    'tabler-point' => 'Point',
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
@endphp

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าสมาชิก</h5>
      <p class="text-muted mb-0">
        ตั้งค่าการแสดงคะแนน ประวัติสมาชิก Step และปุ่มของหน้าสมาชิก
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

        <h6 class="mb-3">ข้อมูลสมาชิกฝั่งซ้าย</h6>

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

        <h6 class="mb-3">Icon Step ด้านบน</h6>

        <div class="mb-3">
          <label class="form-label">Icon Step ที่เสร็จแล้ว</label>

          <select name="completed_step_icon" class="form-select">
            @php
              $completedStepIcon = old('completed_step_icon', $settings['completed_step_icon'] ?? 'tabler-check');
            @endphp

            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $completedStepIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon Step ปัจจุบัน</label>

          <select name="current_step_icon" class="form-select">
            @php
              $currentStepIcon = old('current_step_icon', $settings['current_step_icon'] ?? 'tabler-user');
            @endphp

            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $currentStepIcon === $iconClass ? 'selected' : '' }}>
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon Step ที่ยังไม่ถึง</label>

          <select name="pending_step_icon" class="form-select">
            @php
              $pendingStepIcon = old('pending_step_icon', $settings['pending_step_icon'] ?? 'tabler-minus');
            @endphp

            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $pendingStepIcon === $iconClass ? 'selected' : '' }}>
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
            <i class="icon-base ti {{ $settings['completed_step_icon'] ?? 'tabler-check' }}"></i>
          </span>

          <span style="width: 60px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-primary p-2">
            <i class="icon-base ti {{ $settings['current_step_icon'] ?? 'tabler-user' }}"></i>
          </span>

          <span style="width: 60px; height: 2px; background: #7dbce8;"></span>

          <span class="badge rounded-pill bg-label-secondary p-2">
            <i class="icon-base ti {{ $settings['pending_step_icon'] ?? 'tabler-minus' }}"></i>
          </span>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <div class="bg-primary text-white rounded p-4 h-100">
              @if ($settings['show_member_points'] ?? true)
                <div class="mb-3">
                  <div class="small">แต้มสะสม</div>
                  <div class="display-6 fw-bold">1,240</div>
                  <div class="small">คะแนน</div>
                </div>
              @endif

              @if ($settings['show_member_name'] ?? true)
                <div class="bg-info rounded p-3 mb-3">
                  <div class="small">IP HAPPY FAMILY MEMBER</div>
                  <div class="fw-bold">SUCHART</div>
                </div>
              @endif

              @if ($settings['show_member_history'] ?? true)
                <div class="bg-white text-dark rounded p-3">
                  <div class="fw-bold mb-2">ประวัติการรับบริการล่าสุด</div>

                  @for ($i = 1; $i <= (int) ($settings['history_limit'] ?? 3); $i++)
                    <div class="d-flex justify-content-between small border-bottom py-1">
                      <span>เติมน้ำยาปรับผ้านุ่ม 500 มล.</span>
                      <span>115 บาท</span>
                    </div>
                  @endfor
                </div>
              @endif
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded bg-white overflow-hidden h-100" style="min-height: 260px;">
              @if ($firstMedia && $firstMedia->media_type === 'video')
                <video
                  src="{{ $firstMedia->file_url }}"
                  style="width: 100%; height: 100%; min-height: 260px; object-fit: {{ $firstMedia->object_fit ?? 'cover' }};"
                  muted
                  controls
                ></video>
              @elseif ($firstMedia && $firstMedia->media_type === 'image')
                <img
                  src="{{ $firstMedia->file_url }}"
                  alt="AD"
                  style="width: 100%; height: 100%; min-height: 260px; object-fit: {{ $firstMedia->object_fit ?? 'cover' }};"
                >
              @else
                <div class="h-100 d-flex align-items-center justify-content-center text-muted" style="min-height: 260px;">
                  AD รูปภาพ / วิดีโอ
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          @if ($settings['show_home_button'] ?? true)
            <button type="button" class="btn btn-light">
              <i class="icon-base ti {{ $settings['home_button_icon'] ?? 'tabler-home' }} me-1"></i>
              member_page.home_button
            </button>
          @endif

          @if ($settings['show_select_button'] ?? true)
            <button type="button" class="btn btn-primary px-5">
              <i class="icon-base ti {{ $settings['select_button_icon'] ?? 'tabler-bottle' }} me-1"></i>
              member_page.select_button
              <i class="icon-base ti tabler-chevron-right ms-1"></i>
            </button>
          @endif
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
                style="width: 100%; height: 100%; object-fit: cover;"
                muted
                controls
              ></video>
            @else
              <img
                src="{{ $firstMedia->file_url }}"
                alt="AD"
                style="width: 100%; height: 100%; object-fit: cover;"
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
