@csrf

<div class="row g-4">

  <div class="col-md-6">
    <label class="form-label">
      รหัสสมาชิก <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="member_code"
      value="{{ old('member_code', $customer->member_code ?? '') }}"
      class="form-control @error('member_code') is-invalid @enderror"
      placeholder="เช่น MEM000001"
      required
    >

    @error('member_code')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      ชื่อสมาชิก <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="name"
      value="{{ old('name', $customer->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      required
    >

    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">เบอร์โทรศัพท์</label>

    <input
      type="text"
      name="phone"
      value="{{ old('phone', $customer->phone ?? '') }}"
      class="form-control @error('phone') is-invalid @enderror"
    >

    @error('phone')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">อีเมล</label>

    <input
      type="email"
      name="email"
      value="{{ old('email', $customer->email ?? '') }}"
      class="form-control @error('email') is-invalid @enderror"
    >

    @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      สถานะสมาชิก <span class="text-danger">*</span>
    </label>

    @php
      $selectedStatus = old(
          'status',
          $customer->status ?? 'active'
      );
    @endphp

    <select
      name="status"
      class="form-select @error('status') is-invalid @enderror"
      required
    >
      <option
        value="active"
        {{ $selectedStatus === 'active' ? 'selected' : '' }}
      >
        ใช้งานปกติ
      </option>

      <option
        value="suspended"
        {{ $selectedStatus === 'suspended' ? 'selected' : '' }}
      >
        ระงับการใช้งาน
      </option>

      <option
        value="blocked"
        {{ $selectedStatus === 'blocked' ? 'selected' : '' }}
      >
        บล็อก
      </option>
    </select>

    @error('status')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  @isset($customer)
    <div class="col-md-6">
      <label class="form-label">แต้มคงเหลือ</label>

      <input
        type="text"
        class="form-control"
        value="{{ number_format((int) $customer->points_balance) }} แต้ม"
        disabled
      >

      <div class="form-text">
        การเพิ่มหรือลดแต้มต้องทำจากหน้ารายละเอียดสมาชิก
      </div>
    </div>
  @endisset

  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>

    <textarea
      name="remark"
      rows="3"
      class="form-control @error('remark') is-invalid @enderror"
    >{{ old('remark', $customer->remark ?? '') }}</textarea>

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
        id="is_active"
        class="form-check-input"
        {{ old(
            'is_active',
            isset($customer) ? (int) $customer->is_active : 1
        ) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานสมาชิก
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a
      href="{{ route('customers.index') }}"
      class="btn btn-label-secondary"
    >
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      <i class="icon-base ti tabler-device-floppy me-1"></i>
      บันทึก
    </button>
  </div>

</div>
