@csrf

<div class="row g-4">

  <div class="col-md-6">
    <label class="form-label">
      ชื่อตู้ <span class="text-danger">*</span>
    </label>
    <input
      type="text"
      name="name"
      value="{{ old('name', $machine->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น ตู้หน้าอาคาร A"
      required
    >
    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      รหัสตู้ <span class="text-danger">*</span>
    </label>
    <input
      type="text"
      name="code"
      value="{{ old('code', $machine->code ?? '') }}"
      class="form-control @error('code') is-invalid @enderror"
      placeholder="เช่น VM-001"
      required
    >
    @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">สถานที่ติดตั้ง</label>
    <select
      name="location_id"
      class="form-select @error('location_id') is-invalid @enderror"
    >
      <option value="">-- เลือกสถานที่ --</option>

      @foreach ($locations as $location)
        <option
          value="{{ $location->id }}"
          {{ (string) old('location_id', $machine->location_id ?? '') === (string) $location->id ? 'selected' : '' }}
        >
          {{ $location->name }}
          @if (!empty($location->code))
            ({{ $location->code }})
          @endif
        </option>
      @endforeach
    </select>

    @error('location_id')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      สถานะตู้ <span class="text-danger">*</span>
    </label>
    <select
      name="status"
      class="form-select @error('status') is-invalid @enderror"
      required
    >
      <option value="active" {{ old('status', $machine->status ?? 'active') === 'active' ? 'selected' : '' }}>
        พร้อมใช้งาน
      </option>
      <option value="maintenance" {{ old('status', $machine->status ?? '') === 'maintenance' ? 'selected' : '' }}>
        ซ่อมบำรุง
      </option>
      <option value="inactive" {{ old('status', $machine->status ?? '') === 'inactive' ? 'selected' : '' }}>
        ปิดใช้งาน
      </option>
      <option value="offline" {{ old('status', $machine->status ?? '') === 'offline' ? 'selected' : '' }}>
        ออฟไลน์
      </option>
      <option value="error" {{ old('status', $machine->status ?? '') === 'error' ? 'selected' : '' }}>
        มีปัญหา
      </option>
    </select>

    @error('status')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Serial Number</label>
    <input
      type="text"
      name="serial_number"
      value="{{ old('serial_number', $machine->serial_number ?? '') }}"
      class="form-control @error('serial_number') is-invalid @enderror"
      placeholder="Serial Number"
    >
    @error('serial_number')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">รุ่นตู้</label>
    <input
      type="text"
      name="model"
      value="{{ old('model', $machine->model ?? '') }}"
      class="form-control @error('model') is-invalid @enderror"
      placeholder="เช่น HY-2026"
    >
    @error('model')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <hr class="my-2">
    <h6 class="mb-1">ตั้งค่าน้ำยาในตู้</h6>
    <p class="text-muted mb-0">
      ตู้ 1 เครื่องสามารถตั้งค่าน้ำยาได้สูงสุด 4 ช่อง
    </p>
  </div>

  @php
    $oldTanks = old('tanks', []);
    $machineTanks = isset($machine) ? $machine->tanks->keyBy('tank_no') : collect();
  @endphp

  @for ($i = 1; $i <= 4; $i++)
    @php
      $tank = $machineTanks->get($i);

      $tankProductId = $oldTanks[$i]['product_id'] ?? ($tank->product_id ?? '');
      $tankName = $oldTanks[$i]['tank_name'] ?? ($tank->tank_name ?? 'ช่องน้ำยาที่ ' . $i);
      $tankCapacity = $oldTanks[$i]['capacity_liters'] ?? ($tank->capacity_liters ?? '');
      $tankRemaining = $oldTanks[$i]['remaining_liters'] ?? ($tank->remaining_liters ?? '');
      $tankLowStock = $oldTanks[$i]['low_stock_liters'] ?? ($tank->low_stock_liters ?? '');
      $tankEmptyStock = $oldTanks[$i]['empty_stock_liters'] ?? ($tank->empty_stock_liters ?? '');
      $tankVolume = $oldTanks[$i]['volume_per_press_ml'] ?? ($tank->volume_per_press_ml ?? '');
      $tankPrice = $oldTanks[$i]['price_per_press'] ?? ($tank->price_per_press ?? '');
      $tankActive = $oldTanks[$i]['is_active'] ?? (isset($tank) ? (int) $tank->is_active : 1);
    @endphp

    <div class="col-12">
      <div class="card border shadow-none mb-0">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 py-3">
          <div>
            <h6 class="mb-0">ช่องน้ำยาที่ {{ $i }}</h6>
            <small class="text-muted">เลือกน้ำยา กำหนดความจุ ปริมาณต่อครั้ง และราคา</small>
          </div>

          <div class="form-check form-switch mb-0">
            <input type="hidden" name="tanks[{{ $i }}][is_active]" value="0">

            <input
              type="checkbox"
              class="form-check-input"
              id="tank_active_{{ $i }}"
              name="tanks[{{ $i }}][is_active]"
              value="1"
              {{ (int) $tankActive === 1 ? 'checked' : '' }}
            >

            <label class="form-check-label" for="tank_active_{{ $i }}">
              เปิดใช้งาน
            </label>
          </div>
        </div>

        <div class="card-body">
          <input type="hidden" name="tanks[{{ $i }}][tank_no]" value="{{ $i }}">

          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label">ชื่อช่อง</label>
              <input
                type="text"
                name="tanks[{{ $i }}][tank_name]"
                value="{{ $tankName }}"
                class="form-control @error("tanks.$i.tank_name") is-invalid @enderror"
                placeholder="เช่น น้ำยาซักผ้า"
              >
              @error("tanks.$i.tank_name")
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">
                น้ำยา / สินค้า
              </label>

              <select
                name="tanks[{{ $i }}][product_id]"
                class="form-select @error("tanks.$i.product_id") is-invalid @enderror"
              >
                <option value="">-- เลือกน้ำยา --</option>

                @foreach ($products as $product)
                  <option
                    value="{{ $product->id }}"
                    {{ (string) $tankProductId === (string) $product->id ? 'selected' : '' }}
                  >
                    {{ $product->name }}
                    @if (!empty($product->code))
                      ({{ $product->code }})
                    @endif
                  </option>
                @endforeach
              </select>

              @error("tanks.$i.product_id")
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">ความจุถัง / ลิตร</label>
              <input
                type="number"
                step="0.01"
                min="0"
                name="tanks[{{ $i }}][capacity_liters]"
                value="{{ $tankCapacity }}"
                class="form-control @error("tanks.$i.capacity_liters") is-invalid @enderror"
                placeholder="เช่น 20"
              >
              @error("tanks.$i.capacity_liters")
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">น้ำยาคงเหลือ / ลิตร</label>
              <input
                type="number"
                step="0.01"
                min="0"
                name="tanks[{{ $i }}][remaining_liters]"
                value="{{ $tankRemaining }}"
                class="form-control @error("tanks.$i.remaining_liters") is-invalid @enderror"
                placeholder="เช่น 15"
              >
              @error("tanks.$i.remaining_liters")
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">แจ้งเตือนเมื่อต่ำกว่า / ลิตร</label>
              <input
                type="number"
                step="0.01"
                min="0"
                name="tanks[{{ $i }}][low_stock_liters]"
                value="{{ $tankLowStock }}"
                class="form-control @error("tanks.$i.low_stock_liters") is-invalid @enderror"
                placeholder="เช่น 3"
              >
              @error("tanks.$i.low_stock_liters")
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">ถือว่าหมดเมื่อต่ำกว่า / ลิตร</label>
              <input
                type="number"
                step="0.01"
                min="0"
                name="tanks[{{ $i }}][empty_stock_liters]"
                value="{{ $tankEmptyStock }}"
                class="form-control @error("tanks.$i.empty_stock_liters") is-invalid @enderror"
                placeholder="เช่น 0.5"
              >
              @error("tanks.$i.empty_stock_liters")
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">ปริมาณต่อการกด / ml</label>
              <input
                type="number"
                step="0.01"
                min="0"
                name="tanks[{{ $i }}][volume_per_press_ml]"
                value="{{ $tankVolume }}"
                class="form-control @error("tanks.$i.volume_per_press_ml") is-invalid @enderror"
                placeholder="เช่น 30"
              >
              @error("tanks.$i.volume_per_press_ml")
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">ราคาต่อการกด / บาท</label>
              <input
                type="number"
                step="0.01"
                min="0"
                name="tanks[{{ $i }}][price_per_press]"
                value="{{ $tankPrice }}"
                class="form-control @error("tanks.$i.price_per_press") is-invalid @enderror"
                placeholder="เช่น 10"
              >
              @error("tanks.$i.price_per_press")
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>
    </div>
  @endfor
<div class="col-12">
  <hr class="my-2">

  <h6 class="mb-1">ตั้งค่าภาษาหน้าตู้</h6>
  <p class="text-muted mb-0">
    หากไม่กำหนดภาษาเฉพาะ ระบบจะใช้ค่าภาษากลางจากเมนูตั้งค่าภาษา
  </p>
</div>

@php
  $useCustomLanguages = old(
      'use_custom_languages',
      isset($machine) && $machine->kioskLanguageSettings->count() > 0 ? 1 : 0
  );

  $selectedMachineLanguageIds = old(
      'machine_language_ids',
      isset($machine)
          ? $machine->kioskLanguageSettings->pluck('language_id')->map(fn ($id) => (string) $id)->toArray()
          : []
  );

  $defaultMachineLanguageId = old(
      'default_machine_language_id',
      isset($machine)
          ? optional($machine->kioskLanguageSettings->firstWhere('is_default', true))->language_id
          : null
  );
@endphp

<div class="col-12">
  <div class="form-check form-switch">
    <input type="hidden" name="use_custom_languages" value="0">

    <input
      type="checkbox"
      name="use_custom_languages"
      value="1"
      id="use_custom_languages"
      class="form-check-input"
      {{ (int) $useCustomLanguages === 1 ? 'checked' : '' }}
    >

    <label class="form-check-label" for="use_custom_languages">
      กำหนดภาษาเฉพาะสำหรับตู้นี้
    </label>
  </div>
</div>

<div class="col-12" id="machineLanguageWrapper">
  <div class="card border shadow-none mb-0">
    <div class="card-body">
      <div class="row g-3">

        <div class="col-md-8">
          <label class="form-label">
            ภาษาที่เปิดใช้บนตู้นี้
          </label>

          <div class="row g-2">
            @foreach ($kioskLanguages as $language)
              <div class="col-md-4">
                <div class="form-check">
                  <input
                    type="checkbox"
                    name="machine_language_ids[]"
                    value="{{ $language->id }}"
                    id="machine_language_{{ $language->id }}"
                    class="form-check-input machine-language-checkbox"
                    {{ in_array((string) $language->id, $selectedMachineLanguageIds) ? 'checked' : '' }}
                  >

                  <label
                    class="form-check-label"
                    for="machine_language_{{ $language->id }}"
                  >
                    {{ $language->native_name }}
                    <small class="text-muted">({{ $language->code }})</small>
                  </label>
                </div>
              </div>
            @endforeach
          </div>

          <div class="form-text">
            เลือกได้สูงสุด 3 ภาษา
          </div>

          @error('machine_language_ids')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">
            ภาษาหลักของตู้นี้
          </label>

          <select
            name="default_machine_language_id"
            class="form-select @error('default_machine_language_id') is-invalid @enderror"
          >
            <option value="">-- เลือกภาษาหลัก --</option>

            @foreach ($kioskLanguages as $language)
              <option
                value="{{ $language->id }}"
                {{ (string) $defaultMachineLanguageId === (string) $language->id ? 'selected' : '' }}
              >
                {{ $language->native_name }} ({{ $language->code }})
              </option>
            @endforeach
          </select>

          @error('default_machine_language_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div>
    </div>
  </div>
</div>
  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>
    <textarea
      name="remark"
      rows="3"
      class="form-control @error('remark') is-invalid @enderror"
      placeholder="รายละเอียดเพิ่มเติม"
    >{{ old('remark', $machine->remark ?? '') }}</textarea>

    @error('remark')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
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
        {{ old('is_active', isset($machine) ? (int) $machine->is_active : 1) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานตู้นี้
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
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
  const useCustomLanguages = document.getElementById('use_custom_languages');
  const wrapper = document.getElementById('machineLanguageWrapper');
  const checkboxes = document.querySelectorAll('.machine-language-checkbox');

  function toggleMachineLanguages() {
    if (!useCustomLanguages || !wrapper) return;

    wrapper.classList.toggle('d-none', !useCustomLanguages.checked);
  }

  useCustomLanguages?.addEventListener('change', toggleMachineLanguages);
  toggleMachineLanguages();

  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      const checked = document.querySelectorAll('.machine-language-checkbox:checked');

      if (checked.length > 3) {
        this.checked = false;
        alert('เลือกภาษาได้สูงสุด 3 ภาษา');
      }
    });
  });
});
</script>
