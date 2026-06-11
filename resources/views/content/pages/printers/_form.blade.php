@csrf

<div class="row g-4">

  <div class="col-md-6">
    <label class="form-label">ตู้ที่เชื่อมต่อ</label>
    <select
      name="machine_id"
      class="form-select @error('machine_id') is-invalid @enderror"
    >
      <option value="">-- ยังไม่ผูกกับตู้ --</option>

      @foreach ($machines as $machine)
        <option
          value="{{ $machine->id }}"
          {{ (string) old('machine_id', $printer->machine_id ?? '') === (string) $machine->id ? 'selected' : '' }}
        >
          {{ $machine->code }} - {{ $machine->name }}
        </option>
      @endforeach
    </select>

    @error('machine_id')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">รหัสเครื่องปริ้น</label>
    <input
      type="text"
      name="code"
      value="{{ old('code', $printer->code ?? '') }}"
      class="form-control @error('code') is-invalid @enderror"
      placeholder="เช่น PRN-001"
    >
    @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      ชื่อเครื่องปริ้น <span class="text-danger">*</span>
    </label>
    <input
      type="text"
      name="name"
      value="{{ old('name', $printer->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น เครื่องปริ้นตู้ BH-001"
      required
    >
    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Serial Number</label>
    <input
      type="text"
      name="serial_number"
      value="{{ old('serial_number', $printer->serial_number ?? '') }}"
      class="form-control @error('serial_number') is-invalid @enderror"
      placeholder="Serial Number"
    >
    @error('serial_number')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ยี่ห้อ</label>
    <input
      type="text"
      name="brand"
      value="{{ old('brand', $printer->brand ?? '') }}"
      class="form-control @error('brand') is-invalid @enderror"
      placeholder="เช่น Epson, Xprinter"
    >
    @error('brand')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">รุ่น</label>
    <input
      type="text"
      name="model"
      value="{{ old('model', $printer->model ?? '') }}"
      class="form-control @error('model') is-invalid @enderror"
      placeholder="เช่น XP-Q200"
    >
    @error('model')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      ประเภทการเชื่อมต่อ <span class="text-danger">*</span>
    </label>

    @php
      $selectedConnectionType = old('connection_type', $printer->connection_type ?? 'usb');
    @endphp

    <select
      name="connection_type"
      class="form-select @error('connection_type') is-invalid @enderror"
      required
    >
      <option value="usb" {{ $selectedConnectionType === 'usb' ? 'selected' : '' }}>USB</option>
      <option value="lan" {{ $selectedConnectionType === 'lan' ? 'selected' : '' }}>LAN</option>
      <option value="wifi" {{ $selectedConnectionType === 'wifi' ? 'selected' : '' }}>Wi-Fi</option>
      <option value="bluetooth" {{ $selectedConnectionType === 'bluetooth' ? 'selected' : '' }}>Bluetooth</option>
    </select>

    @error('connection_type')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">IP Address</label>
    <input
      type="text"
      name="ip_address"
      value="{{ old('ip_address', $printer->ip_address ?? '') }}"
      class="form-control @error('ip_address') is-invalid @enderror"
      placeholder="เช่น 192.168.1.50"
    >
    @error('ip_address')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Port</label>
    <input
      type="number"
      min="0"
      max="65535"
      name="port"
      value="{{ old('port', $printer->port ?? '') }}"
      class="form-control @error('port') is-invalid @enderror"
      placeholder="เช่น 9100"
    >
    @error('port')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ขนาดกระดาษ</label>

    @php
      $selectedPaperSize = old('paper_size', $printer->paper_size ?? '');
    @endphp

    <select
      name="paper_size"
      class="form-select @error('paper_size') is-invalid @enderror"
    >
      <option value="">-- เลือกขนาดกระดาษ --</option>
      <option value="58mm" {{ $selectedPaperSize === '58mm' ? 'selected' : '' }}>58mm</option>
      <option value="80mm" {{ $selectedPaperSize === '80mm' ? 'selected' : '' }}>80mm</option>
      <option value="A5" {{ $selectedPaperSize === 'A5' ? 'selected' : '' }}>A5</option>
      <option value="A4" {{ $selectedPaperSize === 'A4' ? 'selected' : '' }}>A4</option>
    </select>

    @error('paper_size')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      สถานะเครื่องปริ้น <span class="text-danger">*</span>
    </label>

    @php
      $selectedStatus = old('status', $printer->status ?? 'active');
    @endphp

    <select
      name="status"
      class="form-select @error('status') is-invalid @enderror"
      required
    >
      <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>พร้อมใช้งาน</option>
      <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>ปิดใช้งาน</option>
      <option value="offline" {{ $selectedStatus === 'offline' ? 'selected' : '' }}>ออฟไลน์</option>
      <option value="error" {{ $selectedStatus === 'error' ? 'selected' : '' }}>มีปัญหา</option>
      <option value="paper_out" {{ $selectedStatus === 'paper_out' ? 'selected' : '' }}>กระดาษหมด</option>
    </select>

    @error('status')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6 d-flex align-items-end">
    <div class="d-flex flex-column gap-2">
      <div class="form-check form-switch">
        <input type="hidden" name="paper_available" value="0">

        <input
          type="checkbox"
          name="paper_available"
          value="1"
          class="form-check-input"
          id="paper_available"
          {{ old('paper_available', isset($printer) ? (int) $printer->paper_available : 1) ? 'checked' : '' }}
        >

        <label class="form-check-label" for="paper_available">
          มีกระดาษพร้อมใช้งาน
        </label>
      </div>

      <div class="form-check form-switch">
        <input type="hidden" name="is_active" value="0">

        <input
          type="checkbox"
          name="is_active"
          value="1"
          class="form-check-input"
          id="is_active"
          {{ old('is_active', isset($printer) ? (int) $printer->is_active : 1) ? 'checked' : '' }}
        >

        <label class="form-check-label" for="is_active">
          เปิดใช้งานเครื่องปริ้นนี้
        </label>
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
    >{{ old('remark', $printer->remark ?? '') }}</textarea>

    @error('remark')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('printers.index') }}" class="btn btn-label-secondary">
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      <i class="icon-base ti tabler-device-floppy me-1"></i>
      บันทึก
    </button>
  </div>

</div>
