@extends('layouts/layoutMaster')

@section('title', 'ตั้งค่าภาษา')

@section('page-style')
<style>
  .language-flag {
    width: 52px;
    height: 36px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid rgba(67, 89, 113, .16);
    background: #f5f5f7;
  }

  .language-flag-empty {
    width: 52px;
    height: 36px;
    border-radius: 8px;
    background: rgba(75, 70, 92, .08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a8aaae;
  }

  .selected-language-card {
    border: 1px dashed rgba(67, 89, 113, .24);
    border-radius: .75rem;
    padding: 1rem;
  }
</style>
@endsection

@section('content')
<div class="row g-4">

  <div class="col-12">
    <div class="card">

      <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
          <h5 class="mb-1">ตั้งค่าภาษา</h5>
          <p class="text-muted mb-0">
            จัดการภาษาทั้งหมด และเลือกภาษาที่เปิดใช้บนหน้าตู้ได้สูงสุด 3 ภาษา
          </p>
        </div>

        <a
          href="{{ route('frontend.languages.create') }}"
          class="btn btn-primary"
        >
          <i class="icon-base ti tabler-plus me-1"></i>
          เพิ่มภาษา
        </a>
      </div>

      @if (session('success'))
        <div class="alert alert-success mx-4">
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger mx-4">
          {{ session('error') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger mx-4">
          <div class="fw-medium mb-1">กรุณาตรวจสอบข้อมูล</div>
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

    </div>
  </div>

  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-1">ภาษาที่ใช้บนหน้าตู้</h5>
        <p class="text-muted mb-0">
          เลือกได้สูงสุด 3 ภาษา และต้องมีภาษาหลัก 1 ภาษา
        </p>
      </div>

      <div class="card-body">
        <form
          action="{{ route('frontend.languages.settings.update') }}"
          method="POST"
        >
          @csrf

          <div class="mb-3">
            <label class="form-label">
              เลือกภาษาใช้งาน <span class="text-danger">*</span>
            </label>

            @foreach ($languages->where('is_active', true) as $language)
              <div class="form-check mb-2">
                <input
                  type="checkbox"
                  name="language_ids[]"
                  value="{{ $language->id }}"
                  id="language_{{ $language->id }}"
                  class="form-check-input language-checkbox"
                  {{ in_array((string) $language->id, old('language_ids', $selectedLanguageIds)) ? 'checked' : '' }}
                >

                <label
                  class="form-check-label d-flex align-items-center gap-2"
                  for="language_{{ $language->id }}"
                >
                  @if ($language->flag_image)
                    <img
                      src="{{ asset('assets/img/languages/' . $language->flag_image) }}"
                      class="language-flag"
                      alt="{{ $language->native_name }}"
                    >
                  @else
                    <span class="language-flag-empty">
                      <i class="icon-base ti tabler-flag"></i>
                    </span>
                  @endif

                  <span>
                    {{ $language->native_name }}
                    <small class="text-muted">({{ $language->code }})</small>
                  </span>
                </label>
              </div>
            @endforeach

            <div class="form-text">
              หากเลือกเกิน 3 ภาษา ระบบจะไม่ให้บันทึก
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">
              ภาษาหลัก <span class="text-danger">*</span>
            </label>

            <select
              name="default_language_id"
              class="form-select @error('default_language_id') is-invalid @enderror"
              required
            >
              <option value="">-- เลือกภาษาหลัก --</option>

              @foreach ($languages->where('is_active', true) as $language)
                <option
                  value="{{ $language->id }}"
                  {{ (string) old('default_language_id', $defaultLanguageId) === (string) $language->id ? 'selected' : '' }}
                >
                  {{ $language->native_name }} ({{ $language->code }})
                </option>
              @endforeach
            </select>

            @error('default_language_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          @if ($activeSettings->count())
            <div class="selected-language-card mb-4">
              <div class="fw-medium mb-2">ลำดับที่แสดงปัจจุบัน</div>

              @foreach ($activeSettings as $setting)
                @if ($setting->language)
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                      {{ $setting->sort_order }}.
                      {{ $setting->language->native_name }}
                      <small class="text-muted">
                        ({{ $setting->language->code }})
                      </small>
                    </div>

                    @if ($setting->is_default)
                      <span class="badge bg-label-primary">
                        Default
                      </span>
                    @endif
                  </div>
                @endif
              @endforeach
            </div>
          @endif

          <button type="submit" class="btn btn-primary w-100">
            <i class="icon-base ti tabler-device-floppy me-1"></i>
            บันทึกภาษาหน้าตู้
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-1">รายการภาษาทั้งหมด</h5>
        <p class="text-muted mb-0">
          ภาษาทั้งหมดที่ระบบรองรับ
        </p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th style="width: 70px;">#</th>
              <th style="width: 90px;">ธง</th>
              <th>ภาษา</th>
              <th>Locale</th>
              <th>ลำดับ</th>
              <th>สถานะ</th>
              <th style="width: 100px;" class="text-center">จัดการ</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($languages as $index => $language)
              <tr>
                <td>{{ $index + 1 }}</td>

                <td>
                  @if ($language->flag_image)
                    <img
                      src="{{ asset('assets/img/languages/' . $language->flag_image) }}"
                      class="language-flag"
                      alt="{{ $language->native_name }}"
                    >
                  @else
                    <div class="language-flag-empty">
                      <i class="icon-base ti tabler-flag"></i>
                    </div>
                  @endif
                </td>

                <td>
                  <div class="fw-medium">
                    {{ $language->native_name }}
                  </div>

                  <small class="text-muted">
                    {{ $language->name }} / {{ $language->code }}
                  </small>
                </td>

                <td>
                  {{ $language->locale ?: '-' }}
                </td>

                <td>
                  {{ number_format((int) $language->sort_order) }}
                </td>

                <td>
                  <span class="badge {{ $language->status_class }}">
                    {{ $language->status_text }}
                  </span>
                </td>

                <td class="text-center">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                      data-bs-toggle="dropdown"
                    >
                      <i class="icon-base ti tabler-dots-vertical"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end">
                      <a
                        href="{{ route('frontend.languages.edit', $language) }}"
                        class="dropdown-item"
                      >
                        <i class="icon-base ti tabler-pencil me-2"></i>
                        แก้ไข
                      </a>

                      <div class="dropdown-divider"></div>

                      <form
                        action="{{ route('frontend.languages.destroy', $language) }}"
                        method="POST"
                        onsubmit="return confirm('ยืนยันการลบภาษานี้?')"
                      >
                        @csrf
                        @method('DELETE')

                        <button
                          type="submit"
                          class="dropdown-item text-danger"
                        >
                          <i class="icon-base ti tabler-trash me-2"></i>
                          ลบ
                        </button>
                      </form>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5">
                  <div class="mb-2">
                    <i
                      class="icon-base ti tabler-language-off text-muted"
                      style="font-size: 48px;"
                    ></i>
                  </div>

                  <h6 class="mb-1">ยังไม่มีภาษา</h6>

                  <p class="text-muted mb-3">
                    เพิ่มภาษาเพื่อเริ่มตั้งค่าหน้าตู้
                  </p>

                  <a
                    href="{{ route('frontend.languages.create') }}"
                    class="btn btn-primary"
                  >
                    เพิ่มภาษา
                  </a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const checkboxes = document.querySelectorAll('.language-checkbox');

  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      const checked = document.querySelectorAll('.language-checkbox:checked');

      if (checked.length > 3) {
        this.checked = false;
        alert('เลือกภาษาได้สูงสุด 3 ภาษา');
      }
    });
  });
});
</script>
@endsection
