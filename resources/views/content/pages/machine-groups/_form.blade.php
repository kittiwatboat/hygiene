@php($isEdit = isset($machineGroup))
  <div class="row g-4">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-1">
            {{ $isEdit ? 'แก้ไขกลุ่มตู้' : 'เพิ่มกลุ่มตู้' }}
          </h5>
          <p class="text-muted mb-0">กำหนดข้อมูลกลุ่มและ Theme ที่ตู้ในกลุ่มนี้จะใช้</p>
        </div>
        <div class="card-body">
          <div class="mb-3"><label class="form-label">ชื่อกลุ่มตู้ <span class="text-danger">*</span></label><input
              type="text" name="name"
              value="{{ old('name', $isEdit ? $machineGroup->name : '') }}"
              class="form-control @error('name') is-invalid @enderror" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3"><label class="form-label">รหัสกลุ่ม <span class="text-danger">*</span></label><input
              type="text" name="code"
              value="{{ old('code', $isEdit ? $machineGroup->code : '') }}"
              class="form-control text-uppercase @error('code') is-invalid @enderror" required>
            @error('code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3"><label class="form-label">Theme หน้าตู้</label><select name="frontend_theme_id"
              class="form-select">
              <option value="">-- ยังไม่กำหนด Theme --</option>
              @foreach($themes as $theme)
                <option value="{{ $theme->id }}"
                  {{ (string) old('frontend_theme_id', $isEdit ? $machineGroup->frontend_theme_id : '') === (string) $theme->id ? 'selected' : '' }}>
                  {{ $theme->name }}</option>
              @endforeach
            </select></div>
          <div class="mb-3"><label class="form-label">หมายเหตุ</label>
            <textarea name="remark" rows="4"
              class="form-control">{{ old('remark', $isEdit ? $machineGroup->remark : '') }}</textarea>
          </div>
          <div class="form-check form-switch"><input type="hidden" name="is_active" value="0"><input type="checkbox"
              name="is_active" value="1" id="is_active" class="form-check-input"
              {{ old('is_active', $isEdit ? $machineGroup->is_active : true) ? 'checked' : '' }}><label
              class="form-check-label" for="is_active">เปิดใช้งานกลุ่มตู้</label>
          </div>
          @include('content.pages.machine-groups.partials.product-prices', [
    'products' => $products,
    'machineGroup' => $machineGroup ?? null,
])
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h6 class="mb-0">การทำงาน</h6>
        </div>
        <div class="card-body">
          <div class="alert alert-info">ตู้ทุกเครื่องในกลุ่มจะใช้ Theme ที่กำหนดในกลุ่มนี้</div>
          <div class="d-grid gap-2"><button type="submit" class="btn btn-primary">บันทึก</button><a
              href="{{ route('machine-groups.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
          </div>
        </div>
      </div>
    </div>
  </div>
