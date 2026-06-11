@csrf

<div class="row g-4">

  <div class="col-md-6">
    <label class="form-label">
      ตู้ <span class="text-danger">*</span>
    </label>

    <select
      name="machine_id"
      class="form-select @error('machine_id') is-invalid @enderror"
      required
    >
      <option value="">-- เลือกตู้ --</option>

      @foreach ($machines as $machine)
        <option
          value="{{ $machine->id }}"
          {{ (string) old('machine_id', $maintenance->machine_id ?? '') === (string) $machine->id ? 'selected' : '' }}
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
    <label class="form-label">
      ประเภทงาน <span class="text-danger">*</span>
    </label>

    @php
      $selectedType = old('type', $maintenance->type ?? 'machine_error');
    @endphp

    <select
      name="type"
      class="form-select @error('type') is-invalid @enderror"
      required
    >
      <option value="machine_error" {{ $selectedType === 'machine_error' ? 'selected' : '' }}>เครื่องขัดข้อง</option>
      <option value="printer_error" {{ $selectedType === 'printer_error' ? 'selected' : '' }}>เครื่องปริ้นมีปัญหา</option>
      <option value="network_error" {{ $selectedType === 'network_error' ? 'selected' : '' }}>Network / Internet</option>
      <option value="cleaning" {{ $selectedType === 'cleaning' ? 'selected' : '' }}>ทำความสะอาด</option>
      <option value="refill_issue" {{ $selectedType === 'refill_issue' ? 'selected' : '' }}>ปัญหาการเติมน้ำยา</option>
      <option value="other" {{ $selectedType === 'other' ? 'selected' : '' }}>อื่น ๆ</option>
    </select>

    @error('type')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      สถานะงาน <span class="text-danger">*</span>
    </label>

    @php
      $selectedStatus = old('status', $maintenance->status ?? 'reported');
    @endphp

    <select
      name="status"
      class="form-select @error('status') is-invalid @enderror"
      required
    >
      <option value="reported" {{ $selectedStatus === 'reported' ? 'selected' : '' }}>แจ้งปัญหา</option>
      <option value="assigned" {{ $selectedStatus === 'assigned' ? 'selected' : '' }}>มอบหมายงานแล้ว</option>
      <option value="repairing" {{ $selectedStatus === 'repairing' ? 'selected' : '' }}>กำลังซ่อม</option>
      <option value="completed" {{ $selectedStatus === 'completed' ? 'selected' : '' }}>ซ่อมเสร็จแล้ว</option>
      <option value="cancelled" {{ $selectedStatus === 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
    </select>

    @error('status')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      ความสำคัญ <span class="text-danger">*</span>
    </label>

    @php
      $selectedPriority = old('priority', $maintenance->priority ?? 'normal');
    @endphp

    <select
      name="priority"
      class="form-select @error('priority') is-invalid @enderror"
      required
    >
      <option value="low" {{ $selectedPriority === 'low' ? 'selected' : '' }}>ต่ำ</option>
      <option value="normal" {{ $selectedPriority === 'normal' ? 'selected' : '' }}>ปกติ</option>
      <option value="high" {{ $selectedPriority === 'high' ? 'selected' : '' }}>ด่วน</option>
      <option value="urgent" {{ $selectedPriority === 'urgent' ? 'selected' : '' }}>ด่วนมาก</option>
    </select>

    @error('priority')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">ผู้รับผิดชอบ</label>

    <select
      name="assigned_to"
      class="form-select @error('assigned_to') is-invalid @enderror"
    >
      <option value="">-- ยังไม่มอบหมาย --</option>

      @foreach ($technicians as $technician)
        <option
          value="{{ $technician->id }}"
          {{ (string) old('assigned_to', $maintenance->assigned_to ?? '') === (string) $technician->id ? 'selected' : '' }}
        >
          {{ $technician->full_name ?? $technician->name }} ({{ $technician->role_text ?? $technician->role }})
        </option>
      @endforeach
    </select>

    @error('assigned_to')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">วันที่แจ้งปัญหา</label>

    <input
      type="datetime-local"
      name="reported_at"
      value="{{ old('reported_at', isset($maintenance) && $maintenance->reported_at ? $maintenance->reported_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
      class="form-control @error('reported_at') is-invalid @enderror"
    >

    @error('reported_at')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">วันที่เริ่มซ่อม</label>

    <input
      type="datetime-local"
      name="started_at"
      value="{{ old('started_at', isset($maintenance) && $maintenance->started_at ? $maintenance->started_at->format('Y-m-d\TH:i') : '') }}"
      class="form-control @error('started_at') is-invalid @enderror"
    >

    @error('started_at')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">วันที่ซ่อมเสร็จ</label>

    <input
      type="datetime-local"
      name="finished_at"
      value="{{ old('finished_at', isset($maintenance) && $maintenance->finished_at ? $maintenance->finished_at->format('Y-m-d\TH:i') : '') }}"
      class="form-control @error('finished_at') is-invalid @enderror"
    >

    @error('finished_at')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">
      รายละเอียดปัญหา <span class="text-danger">*</span>
    </label>

    <textarea
      name="problem"
      rows="4"
      class="form-control @error('problem') is-invalid @enderror"
      placeholder="เช่น เครื่องไม่จ่ายน้ำยา / ปริ้นใบเสร็จไม่ได้ / เครื่อง Offline"
      required
    >{{ old('problem', $maintenance->problem ?? '') }}</textarea>

    @error('problem')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">วิธีแก้ไข / ผลการซ่อม</label>

    <textarea
      name="solution"
      rows="4"
      class="form-control @error('solution') is-invalid @enderror"
      placeholder="เช่น เปลี่ยนสาย LAN / เติมกระดาษ / รีสตาร์ทเครื่อง"
    >{{ old('solution', $maintenance->solution ?? '') }}</textarea>

    @error('solution')
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
    >{{ old('remark', $maintenance->remark ?? '') }}</textarea>

    @error('remark')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('maintenances.index') }}" class="btn btn-label-secondary">
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      <i class="icon-base ti tabler-device-floppy me-1"></i>
      บันทึก
    </button>
  </div>

</div>
