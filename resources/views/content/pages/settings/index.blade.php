@extends('layouts/layoutMaster')

@section('title', 'ตั้งค่าระบบ')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])

  <style>
    .setting-alert {
      margin-bottom: 1rem;
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .setting-alert-content {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      line-height: 1.3;
      font-weight: 500;
    }

    .setting-alert-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1rem;
      line-height: 1;
      padding: 0.25rem;
      cursor: pointer;
      opacity: 0.7;
    }

    .setting-alert-close:hover {
      opacity: 1;
    }

    .setting-group-title {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .setting-description {
      font-size: 0.8125rem;
      color: #6f6b7d;
    }
  </style>
@endsection

@section('content')
  @php
    $groupLabels = [
      'general' => [
        'title' => 'ข้อมูลทั่วไป',
        'icon' => 'tabler-settings',
      ],
      'machine' => [
        'title' => 'ตั้งค่าเครื่อง / ตู้',
        'icon' => 'tabler-wash-machine',
      ],
      'stock' => [
        'title' => 'ตั้งค่า Stock น้ำยา',
        'icon' => 'tabler-bottle',
      ],
      'printer' => [
        'title' => 'ตั้งค่าเครื่องปริ้น',
        'icon' => 'tabler-printer',
      ],
      'payment' => [
        'title' => 'ตั้งค่าการชำระเงิน',
        'icon' => 'tabler-credit-card',
      ],
      'notification' => [
        'title' => 'ตั้งค่าแจ้งเตือน',
        'icon' => 'tabler-bell',
      ],
      'api' => [
        'title' => 'ตั้งค่า API',
        'icon' => 'tabler-api',
      ],
    ];
  @endphp

  <div class="row">
    <div class="col-12">

      <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
          <div>
            <h5 class="mb-1">ตั้งค่าระบบ</h5>
            <p class="text-muted mb-0">
              จัดการค่าพื้นฐานของระบบ เช่น แจ้งเตือน Stock เครื่องปริ้น การชำระเงิน และ API
            </p>
          </div>
        </div>
      </div>

      @if (session('success'))
        <div class="alert alert-success setting-alert" role="alert">
          <div class="setting-alert-content">
            <i class="icon-base ti tabler-circle-check"></i>
            <span>{{ session('success') }}</span>
          </div>

          <button
            type="button"
            class="setting-alert-close"
            onclick="this.closest('.alert').remove()"
            aria-label="Close">
            <i class="icon-base ti tabler-x"></i>
          </button>
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger setting-alert" role="alert">
          <div class="setting-alert-content">
            <i class="icon-base ti tabler-alert-circle"></i>
            <span>กรุณาตรวจสอบข้อมูลอีกครั้ง</span>
          </div>

          <button
            type="button"
            class="setting-alert-close"
            onclick="this.closest('.alert').remove()"
            aria-label="Close">
            <i class="icon-base ti tabler-x"></i>
          </button>
        </div>
      @endif

      <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
          @foreach ($settings as $group => $items)
            @php
              $groupInfo = $groupLabels[$group] ?? [
                'title' => $group,
                'icon' => 'tabler-adjustments',
              ];
            @endphp

            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="mb-0 setting-group-title">
                    <i class="icon-base ti {{ $groupInfo['icon'] }}"></i>
                    {{ $groupInfo['title'] }}
                  </h5>
                </div>

                <div class="card-body">
                  <div class="row g-4">
                    @foreach ($items as $setting)
                      <div class="col-md-6">
                        <label class="form-label">
                          {{ $setting->name ?: $setting->key }}
                        </label>

                        @if ($setting->type === 'boolean')
                          <input type="hidden" name="boolean_settings[{{ $setting->key }}]" value="1">
                          <input type="hidden" name="settings[{{ $setting->key }}]" value="0">

                          <div class="form-check form-switch mt-2">
                            <input
                              type="checkbox"
                              class="form-check-input"
                              id="setting_{{ $setting->key }}"
                              name="settings[{{ $setting->key }}]"
                              value="1"
                              {{ old("settings.$setting->key", $setting->value) ? 'checked' : '' }}
                            >

                            <label class="form-check-label" for="setting_{{ $setting->key }}">
                              เปิดใช้งาน
                            </label>
                          </div>

                        @elseif ($setting->key === 'default_paper_size')
                          <select
                            name="settings[{{ $setting->key }}]"
                            class="form-select"
                          >
                            @php
                              $paperSize = old("settings.$setting->key", $setting->value);
                            @endphp

                            <option value="58mm" {{ $paperSize === '58mm' ? 'selected' : '' }}>58mm</option>
                            <option value="80mm" {{ $paperSize === '80mm' ? 'selected' : '' }}>80mm</option>
                            <option value="A5" {{ $paperSize === 'A5' ? 'selected' : '' }}>A5</option>
                            <option value="A4" {{ $paperSize === 'A4' ? 'selected' : '' }}>A4</option>
                          </select>

                        @elseif ($setting->type === 'password')
                          <input
                            type="text"
                            name="settings[{{ $setting->key }}]"
                            value="{{ old("settings.$setting->key", $setting->value) }}"
                            class="form-control"
                            placeholder="กรอก {{ $setting->name }}"
                          >

                        @elseif (in_array($setting->type, ['number', 'integer', 'decimal']))
                          <input
                            type="number"
                            step="{{ $setting->type === 'integer' ? '1' : '0.01' }}"
                            min="0"
                            name="settings[{{ $setting->key }}]"
                            value="{{ old("settings.$setting->key", $setting->value) }}"
                            class="form-control"
                            placeholder="กรอก {{ $setting->name }}"
                          >

                        @else
                          <input
                            type="text"
                            name="settings[{{ $setting->key }}]"
                            value="{{ old("settings.$setting->key", $setting->value) }}"
                            class="form-control"
                            placeholder="กรอก {{ $setting->name }}"
                          >
                        @endif

                        @if (!empty($setting->description))
                          <div class="setting-description mt-1">
                            {{ $setting->description }}
                          </div>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          @endforeach

          <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ url('/') }}" class="btn btn-label-secondary">
              ยกเลิก
            </a>

            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              บันทึกการตั้งค่า
            </button>
          </div>
        </div>
      </form>

    </div>
  </div>
@endsection
