@extends('layouts/layoutMaster')

@section('title', 'จัดการหน้าตู้')

@section('content')
<div class="row g-4">
    @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success mb-0">{{ session('success') }}</div>
        </div>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1">จัดการหน้าตู้ตามกลุ่มตู้</h5>
                <p class="text-muted mb-0">
                    เลือกกลุ่มตู้ก่อน แล้วกำหนดว่ากลุ่มนี้ใช้หน้าใดบ้าง
                </p>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('frontend.pages.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">กลุ่มตู้</label>
                        <select
                            name="machine_group_id"
                            class="form-select"
                            onchange="this.form.submit()"
                        >
                            @forelse ($machineGroups as $group)
                                <option
                                    value="{{ $group->id }}"
                                    {{ (string) $selectedGroupId === (string) $group->id ? 'selected' : '' }}
                                >
                                    {{ $group->name }}
                                    @if ($group->code)
                                        ({{ $group->code }})
                                    @endif
                                </option>
                            @empty
                                <option value="">-- ยังไม่มีกลุ่มตู้ --</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-4">
                        @if ($selectedGroup)
                            <div class="border rounded p-3 bg-light">
                                <div class="small text-muted">Theme ของกลุ่ม</div>
                                <div class="fw-semibold mt-1">
                                    {{ optional($selectedGroup->theme)->name ?? 'ยังไม่ได้กำหนด Theme' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($selectedGroup)
        <div class="col-12">
            <form
                method="POST"
                action="{{ route('frontend.pages.groups.update', $selectedGroup) }}"
            >
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h5 class="mb-1">
                                หน้าที่ใช้กับกลุ่ม: {{ $selectedGroup->name }}
                            </h5>
                            <p class="text-muted mb-0">
                                เปิดเฉพาะหน้าที่ต้องการให้ตู้ในกลุ่มนี้ใช้งาน
                            </p>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="icon-base ti tabler-device-floppy me-1"></i>
                            บันทึกหน้าของกลุ่ม
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th>หน้า</th>
                                    <th style="width:210px;">ประเภทหน้า</th>
                                    <th style="width:150px;" class="text-center">
                                        ใช้กับกลุ่มนี้
                                    </th>
                                    <th style="width:120px;">สถานะหน้า</th>
                                    <th style="width:120px;" class="text-center">จัดการ</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($pages as $page)
                                    @php
                                        $setting = $groupPageSettings->get($page->id);
                                        $isUsed = $setting && (bool) $setting->is_active;
                                        $screenKey = $page->screen_key ?? $page->page_key ?? '';
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <div class="fw-semibold">{{ $page->name }}</div>

                                            @if ($page->subtitle)
                                                <small class="text-muted d-block">
                                                    {{ $page->subtitle }}
                                                </small>
                                            @endif

                                            @if ($screenKey)
                                                <code>{{ $screenKey }}</code>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-label-primary">
                                                {{ $page->page_type ?? $page->type ?? 'หน้าทั่วไป' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input
                                                    type="checkbox"
                                                    name="page_ids[]"
                                                    value="{{ $page->id }}"
                                                    class="form-check-input"
                                                    {{ $isUsed ? 'checked' : '' }}
                                                >
                                            </div>
                                        </td>

                                        <td>
                                            @if ((bool) ($page->is_active ?? true))
                                                <span class="badge bg-label-success">เปิดใช้งาน</span>
                                            @else
                                                <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a
                                                href="{{ route('frontend.pages.edit', $page) }}"
                                                class="btn btn-sm btn-primary"
                                            >
                                                <i class="icon-base ti tabler-pencil me-1"></i>
                                                แก้ไข
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            ยังไม่มีข้อมูลหน้าตู้
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection