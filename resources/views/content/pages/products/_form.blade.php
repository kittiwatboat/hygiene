@csrf

<div class="row g-4">

  <div class="col-md-6">
    <label class="form-label">รหัสสินค้า / น้ำยา</label>
    <input
      type="text"
      name="code"
      value="{{ old('code', $product->code ?? '') }}"
      class="form-control @error('code') is-invalid @enderror"
      placeholder="เช่น DETERGENT-001"
    >
    @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="form-text">
      ใช้สำหรับอ้างอิงน้ำยาในระบบ เช่น รหัสสูตร หรือ SKU
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label">
      ชื่อสินค้า / น้ำยา <span class="text-danger">*</span>
    </label>
    <input
      type="text"
      name="name"
      value="{{ old('name', $product->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น น้ำยาซักผ้า"
      required
    >
    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ประเภท</label>
    <select
      name="type"
      class="form-select @error('type') is-invalid @enderror"
    >
      @php
        $selectedType = old('type', $product->type ?? '');
      @endphp

      <option value="">-- เลือกประเภท --</option>
      <option value="detergent" {{ $selectedType === 'detergent' ? 'selected' : '' }}>
        น้ำยาซักผ้า
      </option>
      <option value="softener" {{ $selectedType === 'softener' ? 'selected' : '' }}>
        น้ำยาปรับผ้านุ่ม
      </option>
      <option value="disinfectant" {{ $selectedType === 'disinfectant' ? 'selected' : '' }}>
        น้ำยาฆ่าเชื้อ
      </option>
      <option value="other" {{ $selectedType === 'other' ? 'selected' : '' }}>
        อื่น ๆ
      </option>
    </select>

    @error('type')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      หน่วยนับ <span class="text-danger">*</span>
    </label>
    <select
      name="unit"
      class="form-select @error('unit') is-invalid @enderror"
      required
    >
      @php
        $selectedUnit = old('unit', $product->unit ?? 'liter');
      @endphp

      <option value="liter" {{ $selectedUnit === 'liter' ? 'selected' : '' }}>
        ลิตร
      </option>
      <option value="ml" {{ $selectedUnit === 'ml' ? 'selected' : '' }}>
        มิลลิลิตร
      </option>
      <option value="piece" {{ $selectedUnit === 'piece' ? 'selected' : '' }}>
        ชิ้น
      </option>
    </select>

    @error('unit')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">รายละเอียด</label>
    <textarea
      name="description"
      rows="4"
      class="form-control @error('description') is-invalid @enderror"
      placeholder="รายละเอียดสินค้า/น้ำยา เช่น สูตร กลิ่น หรือหมายเหตุอื่น ๆ"
    >{{ old('description', $product->description ?? '') }}</textarea>

    @error('description')
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
        {{ old('is_active', isset($product) ? (int) $product->is_active : 1) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานสินค้า/น้ำยานี้
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('products.index') }}" class="btn btn-label-secondary">
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      <i class="icon-base ti tabler-device-floppy me-1"></i>
      บันทึก
    </button>
  </div>

</div>
