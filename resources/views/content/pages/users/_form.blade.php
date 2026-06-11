@csrf

<div class="row g-4">

  <div class="col-md-6">
    <label class="form-label">
      ชื่อ <span class="text-danger">*</span>
    </label>
    <input
      type="text"
      name="first_name"
      value="{{ old('first_name', $user->first_name ?? '') }}"
      class="form-control @error('first_name') is-invalid @enderror"
      placeholder="เช่น Boat"
      required
    >
    @error('first_name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">นามสกุล</label>
    <input
      type="text"
      name="last_name"
      value="{{ old('last_name', $user->last_name ?? '') }}"
      class="form-control @error('last_name') is-invalid @enderror"
      placeholder="เช่น Tanapasakul"
    >
    @error('last_name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      อีเมล <span class="text-danger">*</span>
    </label>
    <input
      type="email"
      name="email"
      value="{{ old('email', $user->email ?? '') }}"
      class="form-control @error('email') is-invalid @enderror"
      placeholder="เช่น admin@example.com"
      required
    >
    @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">เบอร์โทร</label>
    <input
      type="text"
      name="phone"
      value="{{ old('phone', $user->phone ?? '') }}"
      class="form-control @error('phone') is-invalid @enderror"
      placeholder="เช่น 0812345678"
    >
    @error('phone')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      รหัสผ่าน
      @if (!isset($user))
        <span class="text-danger">*</span>
      @endif
    </label>
    <input
      type="password"
      name="password"
      class="form-control @error('password') is-invalid @enderror"
      placeholder="{{ isset($user) ? 'เว้นว่างไว้ถ้าไม่ต้องการเปลี่ยน' : 'กรอกรหัสผ่าน' }}"
      {{ isset($user) ? '' : 'required' }}
    >
    @error('password')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if (isset($user))
      <div class="form-text">ถ้าไม่ต้องการเปลี่ยนรหัสผ่าน ให้เว้นว่างไว้</div>
    @endif
  </div>

  <div class="col-md-6">
    <label class="form-label">
      ยืนยันรหัสผ่าน
      @if (!isset($user))
        <span class="text-danger">*</span>
      @endif
    </label>
    <input
      type="password"
      name="password_confirmation"
      class="form-control"
      placeholder="ยืนยันรหัสผ่านอีกครั้ง"
      {{ isset($user) ? '' : 'required' }}
    >
  </div>

  <div class="col-md-6">
    <label class="form-label">
      สิทธิ์ผู้ใช้งาน <span class="text-danger">*</span>
    </label>

    @php
      $selectedRole = old('role', $user->role ?? 'staff');
    @endphp

    <select
      name="role"
      class="form-select @error('role') is-invalid @enderror"
      required
    >
      <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>
        ผู้ดูแลระบบ
      </option>
      <option value="staff" {{ $selectedRole === 'staff' ? 'selected' : '' }}>
        เจ้าหน้าที่
      </option>
      <option value="technician" {{ $selectedRole === 'technician' ? 'selected' : '' }}>
        ช่าง / เติมน้ำยา
      </option>
    </select>

    @error('role')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      สถานะบัญชี <span class="text-danger">*</span>
    </label>

    @php
      $selectedStatus = old('status', $user->status ?? 'active');
    @endphp

    <select
      name="status"
      class="form-select @error('status') is-invalid @enderror"
      required
    >
      <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>
        ใช้งานปกติ
      </option>
      <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>
        ปิดใช้งาน
      </option>
      <option value="suspended" {{ $selectedStatus === 'suspended' ? 'selected' : '' }}>
        ระงับการใช้งาน
      </option>
    </select>

    @error('status')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">หมายเหตุ</label>
    <textarea
      name="remark"
      rows="3"
      class="form-control @error('remark') is-invalid @enderror"
      placeholder="รายละเอียดเพิ่มเติม"
    >{{ old('remark', $user->remark ?? '') }}</textarea>

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
        {{ old('is_active', isset($user) ? (int) $user->is_active : 1) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานบัญชีนี้
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      <i class="icon-base ti tabler-device-floppy me-1"></i>
      บันทึก
    </button>
  </div>

</div>
