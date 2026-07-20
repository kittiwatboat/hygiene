@php
  $settings = $page->settings_json ?? [];

  /*
  |--------------------------------------------------------------------------
  | ข้อมูลสมาชิก
  |--------------------------------------------------------------------------
  | หน้าใช้งานจริงสามารถส่งตัวแปร $member เข้ามาได้
  | หากไม่มี จะใช้ผู้ใช้งานที่ Login อยู่ หรือข้อมูลตัวอย่างใน Preview
  */
  $memberData = $member ?? auth()->user();

  $memberName = data_get($memberData, 'name')
    ?? data_get($memberData, 'full_name')
    ?? 'SUCHART';

  $memberCode = data_get($memberData, 'member_code')
    ?? data_get($memberData, 'code')
    ?? 'IP HAPPY FAMILY MEMBER';

  $memberPoints = (int) (
    data_get($memberData, 'points')
    ?? data_get($memberData, 'point_balance')
    ?? data_get($memberData, 'current_points')
    ?? 1240
  );

  /*
  |--------------------------------------------------------------------------
  | โปรโมชั่นที่สมาชิกสามารถแลกได้
  |--------------------------------------------------------------------------
  | Controller หน้าใช้งานจริงควรส่ง $availablePromotions หรือ $promotions
  | แต่ละรายการรองรับ key:
  | id, name, required_points, discount_amount, is_active
  */
  $promotionSource = collect(
    $availablePromotions
      ?? $promotions
      ?? [
        [
          'id' => 1,
          'name' => 'แลกส่วนลด 15 บาท',
          'required_points' => 30,
          'discount_amount' => 15,
          'is_active' => true,
        ],
        [
          'id' => 2,
          'name' => 'แลกส่วนลด 25 บาท',
          'required_points' => 50,
          'discount_amount' => 25,
          'is_active' => true,
        ],
        [
          'id' => 3,
          'name' => 'แลกส่วนลด 50 บาท',
          'required_points' => 100,
          'discount_amount' => 50,
          'is_active' => true,
        ],
      ]
  );

  $redeemablePromotions = $promotionSource
    ->filter(function ($promotion) use ($memberPoints) {
      $active = (bool) data_get($promotion, 'is_active', true);
      $requiredPoints = (int) data_get($promotion, 'required_points', 0);

      return $active && $requiredPoints > 0 && $requiredPoints <= $memberPoints;
    })
    ->sortBy(fn ($promotion) => (int) data_get($promotion, 'required_points', 0))
    ->values();

  $pointHistory = collect(
    $memberPointHistory
      ?? data_get($memberData, 'point_history')
      ?? [
        [
          'date' => '28 พฤษภาคม 2569',
          'description' => 'เติมผงซักฟอก',
          'points' => 500,
          'amount' => 75,
        ],
        [
          'date' => '01 พฤษภาคม 2569',
          'description' => 'เติมน้ำยาปรับผ้านุ่ม',
          'points' => 500,
          'amount' => 75,
        ],
        [
          'date' => '30 เมษายน 2569',
          'description' => 'เติมผงซักฟอก',
          'points' => 500,
          'amount' => 75,
        ],
      ]
  )->take(3);

  $stepIcons = [
    'tabler-user-star' => 'User Star',
    'tabler-user' => 'User',
    'tabler-users' => 'Users',
    'tabler-gift' => 'Gift',
    'tabler-coins' => 'Coins',
    'tabler-discount' => 'Discount',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
  ];

  $buttonIcons = [
    'tabler-arrow-left' => 'Arrow Left',
    'tabler-chevron-left' => 'Chevron Left',
    'tabler-arrow-right' => 'Arrow Right',
    'tabler-chevron-right' => 'Chevron Right',
    'tabler-check' => 'Check',
    'tabler-circle-check' => 'Circle Check',
    'tabler-coins' => 'Coins',
    'tabler-gift' => 'Gift',
    'tabler-discount' => 'Discount',
    'tabler-wallet' => 'Wallet',
    'tabler-user' => 'User',
  ];

  $stepIcon = old(
    'step_icon',
    $settings['step_icon'] ?? 'tabler-user-star'
  );

  $pointSectionIcon = old(
    'point_section_icon',
    $settings['point_section_icon'] ?? 'tabler-wallet'
  );

  $pointOptionIcon = old(
    'point_option_icon',
    $settings['point_option_icon'] ?? 'tabler-gift'
  );

  $selectedOptionIcon = old(
    'selected_option_icon',
    $settings['selected_option_icon'] ?? 'tabler-check'
  );

  $nextOptionIcon = old(
    'next_option_icon',
    $settings['next_option_icon'] ?? 'tabler-chevron-right'
  );

  $backButtonIcon = old(
    'back_button_icon',
    $settings['back_button_icon'] ?? 'tabler-chevron-left'
  );

  $confirmButtonIcon = old(
    'confirm_button_icon',
    $settings['confirm_button_icon'] ?? 'tabler-chevron-right'
  );

  $showBackButton = (bool) old(
    'show_back_button',
    $settings['show_back_button'] ?? true
  );

  $showConfirmButton = (bool) old(
    'show_confirm_button',
    $settings['show_confirm_button'] ?? true
  );
@endphp

<style>
  .member-mode-preview {
    background: #dff8ff;
    border-radius: 14px;
    padding: 22px 26px 20px;
    overflow: hidden;
  }

  .member-mode-title {
    text-align: center;
    color: #6f63f6;
    font-weight: 800;
    font-size: 20px;
    margin-bottom: 2px;
  }

  .member-mode-subtitle {
    text-align: center;
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 16px;
  }

  .member-mode-step {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
  }

  .member-mode-step-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: #6f63f6;
    font-size: 18px;
  }

  .member-mode-step-circle.done {
    background: #26c875;
  }

  .member-mode-step-circle.pending {
    background: #e8e8ed;
    color: #9295a0;
  }

  .member-mode-step-line {
    width: 48px;
    height: 2px;
    background: #6fbff0;
  }

  .member-mode-content {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(300px, .85fr);
    gap: 18px;
    align-items: stretch;
  }

  .member-info-panel {
    background: linear-gradient(145deg, #075db8 0%, #078ed6 100%);
    border-radius: 14px;
    padding: 18px;
    color: #fff;
    min-height: 300px;
  }

  .member-point-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 12px;
  }

  .member-point-label {
    font-size: 12px;
    opacity: .95;
  }

  .member-point-value {
    font-size: 34px;
    line-height: 1;
    font-weight: 900;
    color: #ffd52f;
    margin-top: 2px;
  }

  .member-card {
    background: linear-gradient(135deg, #35a8f2, #2f65ef);
    border-radius: 10px;
    padding: 10px 12px;
    min-width: 190px;
    box-shadow: 0 5px 12px rgba(0, 0, 0, .14);
  }

  .member-card-code {
    font-size: 9px;
    opacity: .9;
  }

  .member-card-name {
    font-size: 14px;
    font-weight: 900;
    margin-top: 2px;
  }

  .member-history-card {
    background: #fff;
    color: #253858;
    border-radius: 10px;
    padding: 12px;
    margin-top: 16px;
  }

  .member-history-title {
    color: #075db8;
    font-weight: 800;
    font-size: 13px;
    margin-bottom: 8px;
  }

  .member-history-row {
    display: grid;
    grid-template-columns: 95px 1fr 55px;
    gap: 8px;
    font-size: 10px;
    padding: 7px 0;
    border-bottom: 1px solid #e9eef5;
  }

  .member-history-row:last-child {
    border-bottom: 0;
  }

  .member-history-amount {
    text-align: right;
    font-weight: 800;
  }

  .member-promotion-panel {
    background: rgba(255, 255, 255, .76);
    border-radius: 14px;
    padding: 16px;
  }

  .member-promotion-title {
    color: #0075c9;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
  }

  .member-promotion-description {
    color: #0877c9;
    font-size: 16px;
    font-weight: 800;
    margin-bottom: 12px;
  }

  .member-promotion-option {
    min-height: 74px;
    border-radius: 12px;
    padding: 12px 14px;
    color: #fff;
    margin-bottom: 10px;
    display: grid;
    grid-template-columns: 48px 1fr 30px;
    align-items: center;
    gap: 10px;
    background: linear-gradient(90deg, #7568f9, #3c75ff);
  }

  .member-promotion-option:nth-of-type(even) {
    background: linear-gradient(90deg, #5474ff, #006cff);
  }

  .member-promotion-option.is-selected {
    box-shadow: 0 0 0 3px rgba(38, 200, 117, .26);
  }

  .member-promotion-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: rgba(255, 255, 255, .16);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .member-promotion-icon i {
    font-size: 24px;
  }

  .member-promotion-points {
    font-size: 18px;
    font-weight: 900;
    line-height: 1.1;
  }

  .member-promotion-discount {
    font-size: 11px;
    opacity: .95;
  }

  .member-promotion-action {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .member-no-promotion {
    background: #fff;
    color: #667085;
    border-radius: 10px;
    padding: 22px 14px;
    text-align: center;
  }

  .member-mode-footer {
    display: flex;
    justify-content: center;
    gap: 18px;
    margin-top: 18px;
  }

  .member-back-button,
  .member-confirm-button {
    border: 0;
    border-radius: 10px;
    min-width: 150px;
    min-height: 48px;
    padding: 10px 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
  }

  .member-back-button {
    background: #fff;
    color: #0877c9;
  }

  .member-confirm-button {
    background: #0877c9;
    color: #fff;
    font-size: 17px;
  }

  @media (max-width: 991.98px) {
    .member-mode-content {
      grid-template-columns: 1fr;
    }

    .member-point-header {
      flex-direction: column;
    }

    .member-card {
      width: 100%;
    }

    .member-mode-footer {
      flex-direction: column;
    }

    .member-back-button,
    .member-confirm-button {
      width: 100%;
    }
  }
</style>

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าสมาชิกและแลกแต้ม</h5>
      <p class="text-muted mb-0">
        ข้อมูลสมาชิก แต้ม และโปรโมชั่นที่แลกได้จะดึงจากระบบอัตโนมัติ
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
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Icon หน้าสมาชิก</h6>

        <div class="mb-3">
          <label class="form-label">Icon Step ของหน้านี้</label>
          <select name="step_icon" class="form-select">
            @foreach ($stepIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $stepIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon หัวข้อรายการแลกแต้ม</label>
          <select name="point_section_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $pointSectionIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon รายการโปรโมชั่น</label>
          <select name="point_option_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $pointOptionIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon รายการที่เลือก</label>
          <select name="selected_option_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $selectedOptionIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon รายการที่ยังไม่เลือก</label>
          <select name="next_option_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $nextOptionIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <hr class="my-4">

        <h6 class="mb-3">ปุ่มด้านล่าง</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_back_button" value="0">
          <input
            type="checkbox"
            name="show_back_button"
            value="1"
            id="show_back_button"
            class="form-check-input"
            {{ $showBackButton ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_back_button">
            แสดงปุ่มย้อนกลับ
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มย้อนกลับ</label>
          <select name="back_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $backButtonIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="back_button_action"
          value="{{ old(
            'back_button_action',
            $settings['back_button_action'] ?? 'order_summary_page'
          ) }}"
        >

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_confirm_button" value="0">
          <input
            type="checkbox"
            name="show_confirm_button"
            value="1"
            id="show_confirm_button"
            class="form-check-input"
            {{ $showConfirmButton ? 'checked' : '' }}
          >
          <label class="form-check-label" for="show_confirm_button">
            แสดงปุ่มตกลง
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มตกลง</label>
          <select name="confirm_button_icon" class="form-select">
            @foreach ($buttonIcons as $iconClass => $iconLabel)
              <option
                value="{{ $iconClass }}"
                {{ $confirmButtonIcon === $iconClass ? 'selected' : '' }}
              >
                {{ $iconLabel }}
              </option>
            @endforeach
          </select>
        </div>

        <input
          type="hidden"
          name="confirm_button_action"
          value="{{ old(
            'confirm_button_action',
            $settings['confirm_button_action'] ?? 'payment_page'
          ) }}"
        >

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-device-floppy me-1"></i>
          บันทึกหน้าสมาชิกและแลกแต้ม
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Preview หน้าสมาชิกและแลกแต้ม</h5>
      <p class="text-muted mb-0">
        ตัวอย่าง Layout โดยข้อมูลสมาชิก แต้ม และโปรโมชั่นจะดึงจากระบบจริง
      </p>
    </div>

    <div class="card-body">
      <div class="member-mode-preview">
        <div class="member-mode-title">
          member_page.title
        </div>

        <div class="member-mode-subtitle">
          member_page.subtitle
        </div>

        <div class="member-mode-step">
          <span class="member-mode-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span class="member-mode-step-line"></span>

          <span class="member-mode-step-circle done">
            <i class="icon-base ti tabler-check"></i>
          </span>

          <span class="member-mode-step-line"></span>

          <span class="member-mode-step-circle">
            <i class="icon-base ti {{ $stepIcon }}"></i>
          </span>

          <span class="member-mode-step-line"></span>

          <span class="member-mode-step-circle pending">
            <i class="icon-base ti tabler-minus"></i>
          </span>
        </div>

        <div class="member-mode-content">
          <div class="member-info-panel">
            <div class="member-point-header">
              <div>
                <div class="member-point-label">
                  member_page.current_points
                </div>
                <div class="member-point-value">
                  {{ number_format($memberPoints) }}
                </div>
                <small>คะแนน</small>
              </div>

              <div class="member-card">
                <div class="member-card-code">
                  {{ $memberCode }}
                </div>
                <div class="member-card-name">
                  {{ $memberName }}
                </div>
              </div>
            </div>

            <div class="member-history-card">
              <div class="member-history-title">
                ประวัติการรับบริการล่าสุด
              </div>

              @forelse ($pointHistory as $history)
                <div class="member-history-row">
                  <span>{{ data_get($history, 'date', '-') }}</span>
                  <span>
                    {{ data_get($history, 'description', '-') }}
                    {{ number_format((int) data_get($history, 'points', 0)) }}
                    แต้ม
                  </span>
                  <span class="member-history-amount">
                    {{ number_format((float) data_get($history, 'amount', 0)) }}
                    บาท
                  </span>
                </div>
              @empty
                <div class="text-muted small">
                  ยังไม่มีประวัติการใช้งาน
                </div>
              @endforelse
            </div>
          </div>

          <div class="member-promotion-panel">
            <div class="member-promotion-title">
              <i class="icon-base ti {{ $pointSectionIcon }}"></i>
              <span>เลือกใช้แต้ม แลกส่วนลด</span>
            </div>

            <div class="member-promotion-description">
              เชิญชวนคุณแลกแต้ม รับส่วนลด
            </div>

            @forelse ($redeemablePromotions as $index => $promotion)
              @php
                $requiredPoints = (int) data_get(
                  $promotion,
                  'required_points',
                  0
                );

                $discountAmount = (float) data_get(
                  $promotion,
                  'discount_amount',
                  0
                );
              @endphp

              <div class="member-promotion-option {{ $index === 0 ? 'is-selected' : '' }}">
                <div class="member-promotion-icon">
                  <i class="icon-base ti {{ $pointOptionIcon }}"></i>
                </div>

                <div>
                  <div class="member-promotion-points">
                    {{ number_format($requiredPoints) }} แต้ม
                  </div>
                  <div class="member-promotion-discount">
                    ลด {{ number_format($discountAmount) }} บาท
                  </div>
                </div>

                <div class="member-promotion-action">
                  <i class="icon-base ti {{
                    $index === 0
                      ? $selectedOptionIcon
                      : $nextOptionIcon
                  }}"></i>
                </div>
              </div>
            @empty
              <div class="member-no-promotion">
                <i class="icon-base ti tabler-coins fs-2 mb-2"></i>
                <div class="fw-medium">
                  ยังไม่มีโปรโมชั่นที่สามารถแลกได้
                </div>
                <small>
                  คะแนนปัจจุบัน {{ number_format($memberPoints) }} แต้ม
                </small>
              </div>
            @endforelse
          </div>
        </div>

        <div class="member-mode-footer">
          @if ($showBackButton)
            <button type="button" class="member-back-button">
              <i class="icon-base ti {{ $backButtonIcon }}"></i>
              <span>member_page.back_button</span>
            </button>
          @endif

          @if ($showConfirmButton)
            <button type="button" class="member-confirm-button">
              <span>member_page.confirm_button</span>
              <i class="icon-base ti {{ $confirmButtonIcon }}"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
