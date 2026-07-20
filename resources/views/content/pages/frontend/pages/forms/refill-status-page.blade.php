@php
  $settings = $page->settings_json ?? [];

  $stepIcons = [
    'tabler-bottle' => 'Bottle',
    'tabler-droplet' => 'Droplet',
    'tabler-loader-2' => 'Loader',
    'tabler-progress' => 'Progress',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
  ];

  $buttonIcons = [
    'tabler-player-pause' => 'Pause',
    'tabler-player-play' => 'Play',
    'tabler-player-stop' => 'Stop',
    'tabler-alert-circle' => 'Alert',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-bottle' => 'Bottle',
    'tabler-droplet' => 'Droplet',
  ];

  $stepIcon = old('step_icon', $settings['step_icon'] ?? 'tabler-bottle');
  $pauseIcon = old('pause_button_icon', $settings['pause_button_icon'] ?? 'tabler-player-pause');
  $continueIcon = old('continue_button_icon', $settings['continue_button_icon'] ?? 'tabler-chevron-right');
  $showPauseButton = (bool) old('show_pause_button', $settings['show_pause_button'] ?? true);
  $showContinueButton = (bool) old('show_continue_button', $settings['show_continue_button'] ?? true);
@endphp

<style>
  .refill-preview { background:#dff8ff; border-radius:14px; padding:22px 26px 20px; overflow:hidden; }
  .refill-preview-title { text-align:center; color:#222; font-weight:800; font-size:20px; margin-bottom:2px; }
  .refill-preview-subtitle { text-align:center; color:#6c757d; font-size:12px; margin-bottom:16px; }
  .refill-step { display:flex; align-items:center; justify-content:center; margin-bottom:18px; }
  .refill-step-circle { width:34px; height:34px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; background:#0d8bd7; font-size:16px; }
  .refill-step-circle.done { background:#32c66b; }
  .refill-step-circle.pending { background:#e7e9ed; color:#9aa0aa; }
  .refill-step-line { width:46px; height:2px; background:#67bcec; }
  .refill-content { display:grid; grid-template-columns:38% 62%; gap:14px; align-items:stretch; }
  .refill-control-panel, .refill-status-panel { background:rgba(255,255,255,.76); border-radius:12px; padding:14px; }
  .refill-section-title { color:#0075c9; font-weight:800; display:flex; align-items:center; gap:7px; margin-bottom:12px; font-size:13px; }
  .refill-control-button { width:100%; min-height:74px; border:0; border-radius:10px; padding:12px 14px; margin-bottom:10px; display:grid; grid-template-columns:44px 1fr 26px; gap:10px; align-items:center; text-align:left; background:#fff; color:#0877c9; box-shadow:0 3px 10px rgba(0,0,0,.06); }
  .refill-control-button.is-primary { background:linear-gradient(90deg,#0877c9,#0063c7); color:#fff; }
  .refill-control-icon { width:40px; height:40px; border-radius:50%; background:rgba(255,255,255,.18); display:inline-flex; align-items:center; justify-content:center; }
  .refill-control-button:not(.is-primary) .refill-control-icon { background:#eef7ff; }
  .refill-control-title { font-size:14px; font-weight:900; }
  .refill-control-subtitle { font-size:10px; opacity:.9; margin-top:2px; }
  .refill-status-card { background:#fff; border-radius:10px; padding:20px; min-height:220px; display:flex; flex-direction:column; align-items:center; justify-content:center; }
  .refill-bottle-icon { width:110px; height:130px; color:#0877c9; display:flex; align-items:center; justify-content:center; font-size:92px; margin-bottom:8px; }
  .refill-status-label { color:#1f2937; font-size:13px; font-weight:800; margin-bottom:14px; }
  .refill-progress-wrap { width:100%; }
  .refill-progress-value { color:#0877c9; font-size:12px; font-weight:900; margin-bottom:4px; }
  .refill-progress-track { width:100%; height:8px; border-radius:999px; background:#e7eef5; overflow:hidden; }
  .refill-progress-bar { width:75%; height:100%; border-radius:inherit; background:linear-gradient(90deg,#0a8ed7,#2b3f95); }
  .refill-footer { display:flex; justify-content:center; gap:14px; margin-top:18px; }
  .refill-pause-button, .refill-continue-button { border:0; border-radius:9px; min-width:150px; min-height:46px; padding:10px 18px; display:inline-flex; align-items:center; justify-content:center; gap:9px; font-weight:800; box-shadow:0 4px 12px rgba(0,0,0,.08); }
  .refill-pause-button { background:#fff; color:#e53935; }
  .refill-continue-button { background:#0877c9; color:#fff; font-size:17px; }
  @media (max-width:991.98px) { .refill-content { grid-template-columns:1fr; } .refill-footer { flex-direction:column; } .refill-pause-button, .refill-continue-button { width:100%; } }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้ารอเติมน้ำยา</h5>
      <p class="text-muted mb-0">ตั้งค่า Icon ปุ่มควบคุม และข้อความของหน้าสถานะการเติมน้ำยา</p>
    </div>
    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">ชื่อหน้า <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name', $page->name) }}" class="form-control @error('name') is-invalid @enderror" required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key หัวข้อหน้า</label>
          <input type="text" value="refill_status_page.title" class="form-control" readonly>
          <div class="form-text">ข้อความจริงจะดึงจากระบบแปลภาษา</div>
        </div>

        <div class="mb-3">
          <label class="form-label">หมายเหตุ</label>
          <textarea name="remark" rows="3" class="form-control @error('remark') is-invalid @enderror">{{ old('remark', $page->remark) }}</textarea>
        </div>

        <hr class="my-4">
        <h6 class="mb-3">Icon หน้าสถานะ</h6>
        <div class="mb-3">
          <label class="form-label">Icon Step ของหน้านี้</label>
          <select name="step_icon" class="form-select">
            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $stepIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">
        <h6 class="mb-3">ปุ่มควบคุม</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_pause_button" value="0">
          <input type="checkbox" name="show_pause_button" value="1" id="show_pause_button" class="form-check-input" {{ $showPauseButton ? 'checked' : '' }}>
          <label class="form-check-label" for="show_pause_button">แสดงปุ่มหยุดเติมชั่วคราว</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มหยุดชั่วคราว</label>
          <select name="pause_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $pauseIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>
        <input type="hidden" name="pause_button_action" value="{{ old('pause_button_action', $settings['pause_button_action'] ?? 'pause_refill') }}">

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_continue_button" value="0">
          <input type="checkbox" name="show_continue_button" value="1" id="show_continue_button" class="form-check-input" {{ $showContinueButton ? 'checked' : '' }}>
          <label class="form-check-label" for="show_continue_button">แสดงปุ่มดำเนินการต่อ</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มดำเนินการต่อ</label>
          <select name="continue_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option value="{{ $iconClass }}" {{ $continueIcon === $iconClass ? 'selected' : '' }}>{{ $iconLabel }}</option>
            @endforeach
          </select>
        </div>
        <input type="hidden" name="continue_button_action" value="{{ old('continue_button_action', $settings['continue_button_action'] ?? 'complete_refill') }}">

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>บันทึกหน้ารอเติมน้ำยา
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้ารอเติมน้ำยา</h5>
      <p class="text-muted mb-0">ตัวอย่าง Layout เท่านั้น เปอร์เซ็นต์และสถานะจริงจะดึงจากเครื่อง</p>
    </div>
    <div class="card-body">
      <div class="refill-preview">
        <div class="refill-preview-title">refill_status_page.title</div>
        <div class="refill-preview-subtitle">refill_status_page.subtitle</div>

        <div class="refill-step">
          <span class="refill-step-circle done"><i class="icon-base ti tabler-check"></i></span>
          <span class="refill-step-line"></span>
          <span class="refill-step-circle done"><i class="icon-base ti tabler-check"></i></span>
          <span class="refill-step-line"></span>
          <span class="refill-step-circle done"><i class="icon-base ti tabler-check"></i></span>
          <span class="refill-step-line"></span>
          <span class="refill-step-circle"><i class="icon-base ti {{ $stepIcon }}"></i></span>
          <span class="refill-step-line"></span>
          <span class="refill-step-circle pending"><i class="icon-base ti tabler-minus"></i></span>
        </div>

        <div class="refill-content">
          <div class="refill-control-panel">
            <div class="refill-section-title"><i class="icon-base ti tabler-settings"></i><span>ขั้นตอนเติมน้ำยา</span></div>

            <button type="button" class="refill-control-button is-primary">
              <span class="refill-control-icon"><i class="icon-base ti tabler-bottle"></i></span>
              <span><span class="refill-control-title d-block">ปุ่มกดเติมน้ำยา</span><span class="refill-control-subtitle d-block">เติมน้ำยาตามจำนวนที่เลือก</span></span>
              <i class="icon-base ti tabler-circle-arrow-right"></i>
            </button>

            <button type="button" class="refill-control-button">
              <span class="refill-control-icon"><i class="icon-base ti {{ $pauseIcon }}"></i></span>
              <span><span class="refill-control-title d-block text-danger">ปุ่มหยุดเติมชั่วคราว</span><span class="refill-control-subtitle d-block text-muted">หยุดการเติมชั่วคราวและดำเนินการต่อภายหลัง</span></span>
              <i class="icon-base ti tabler-chevron-right"></i>
            </button>
          </div>

          <div class="refill-status-panel">
            <div class="refill-section-title"><i class="icon-base ti tabler-progress"></i><span>สถานะการเติมน้ำยา</span></div>
            <div class="refill-status-card">
              <div class="refill-status-label">กำลังเติมน้ำยา</div>
              <div class="refill-bottle-icon"><i class="icon-base ti tabler-bottle"></i></div>
              <div class="refill-progress-wrap">
                <div class="refill-progress-value">75%</div>
                <div class="refill-progress-track"><div class="refill-progress-bar"></div></div>
              </div>
            </div>
          </div>
        </div>

        <div class="refill-footer">
          @if ($showPauseButton)
            <button type="button" class="refill-pause-button"><i class="icon-base ti {{ $pauseIcon }}"></i><span>หยุดชั่วคราว</span></button>
          @endif
          @if ($showContinueButton)
            <button type="button" class="refill-continue-button"><span>ดำเนินการต่อ</span><i class="icon-base ti {{ $continueIcon }}"></i></button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
