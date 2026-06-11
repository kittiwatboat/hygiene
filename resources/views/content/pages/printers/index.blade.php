@extends('layouts/layoutMaster')

@section('title', 'เครื่องปริ้นเตอร์')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])

  <style>
    .printer-alert {
      margin: 0 1.5rem 1rem 1.5rem;
      padding: 0.65rem 0.85rem;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .printer-alert-content {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      line-height: 1.3;
      font-weight: 500;
    }

    .printer-alert-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1rem;
      line-height: 1;
      padding: 0.25rem;
      cursor: pointer;
      opacity: 0.7;
    }

    .printer-alert-close:hover {
      opacity: 1;
    }
  </style>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">เครื่องปริ้นเตอร์</h5>
            <p class="mb-0 text-muted">
              จัดการเครื่องปริ้นที่ใช้กับตู้ เช่น ปริ้นใบเสร็จ สถานะกระดาษ และสถานะการเชื่อมต่อ
            </p>
          </div>

          <div>
            <a href="{{ route('printers.create') }}" class="btn btn-primary">
              <i class="icon-base ti tabler-plus me-1"></i>
              เพิ่มเครื่องปริ้น
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success printer-alert" role="alert">
            <div class="printer-alert-content">
              <i class="icon-base ti tabler-circle-check"></i>
              <span>{{ session('success') }}</span>
            </div>

            <button
              type="button"
              class="printer-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger printer-alert" role="alert">
            <div class="printer-alert-content">
              <i class="icon-base ti tabler-alert-circle"></i>
              <span>{{ session('error') }}</span>
            </div>

            <button
              type="button"
              class="printer-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif

        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th style="width: 70px;">#</th>
                <th>เครื่องปริ้น</th>
                <th>ตู้ที่เชื่อมต่อ</th>
                <th>การเชื่อมต่อ</th>
                <th>กระดาษ</th>
                <th>สถานะ</th>
                <th>เปิดใช้งาน</th>
                <th style="width: 110px;">Actions</th>
              </tr>
            </thead>

            <tbody class="table-border-bottom-0">
              @forelse ($printers as $index => $printer)
                <tr>
                  <td>
                    <span class="fw-medium">{{ $index + 1 }}</span>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="icon-base ti tabler-printer"></i>
                        </span>
                      </div>

                      <div>
                        <div class="fw-medium">{{ $printer->name }}</div>
                        <div class="text-muted small">
                          {{ $printer->code ?: 'ไม่ระบุรหัส' }}
                        </div>

                        @if (!empty($printer->brand) || !empty($printer->model))
                          <div class="text-muted small">
                            {{ $printer->brand }} {{ $printer->model }}
                          </div>
                        @endif
                      </div>
                    </div>
                  </td>

                  <td>
                    @if ($printer->machine)
                      <div class="fw-medium">{{ $printer->machine->code }}</div>
                      <div class="text-muted small">{{ $printer->machine->name }}</div>
                    @else
                      <span class="text-muted">ยังไม่ผูกกับตู้</span>
                    @endif
                  </td>

                  <td>
                    <div class="fw-medium">{{ $printer->connection_type_text }}</div>

                    @if ($printer->ip_address)
                      <div class="text-muted small">
                        {{ $printer->ip_address }}
                        @if ($printer->port)
                          :{{ $printer->port }}
                        @endif
                      </div>
                    @endif

                    @if ($printer->paper_size)
                      <div class="text-muted small">
                        กระดาษ {{ $printer->paper_size }}
                      </div>
                    @endif
                  </td>

                  <td>
                    @if ($printer->paper_available)
                      <span class="badge bg-label-success">มีกระดาษ</span>
                    @else
                      <span class="badge bg-label-warning">กระดาษหมด</span>
                    @endif
                  </td>

                  <td>
                    <span class="badge {{ $printer->status_badge_class }}">
                      {{ $printer->status_text }}
                    </span>
                  </td>

                  <td>
                    @if ($printer->is_active)
                      <span class="badge bg-label-success">เปิดใช้งาน</span>
                    @else
                      <span class="badge bg-label-secondary">ปิดใช้งาน</span>
                    @endif
                  </td>

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                      </button>

                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('printers.show', $printer) }}">
                          <i class="icon-base ti tabler-eye me-1"></i>
                          ดูรายละเอียด
                        </a>

                        <a class="dropdown-item" href="{{ route('printers.edit', $printer) }}">
                          <i class="icon-base ti tabler-pencil me-1"></i>
                          แก้ไข
                        </a>

                        <form
                          action="{{ route('printers.destroy', $printer) }}"
                          method="POST"
                          class="printer-delete-form">
                          @csrf
                          @method('DELETE')

                          <button type="submit" class="dropdown-item text-danger">
                            <i class="icon-base ti tabler-trash me-1"></i>
                            ลบ
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <div class="mb-2">
                      <i class="icon-base ti tabler-printer-off" style="font-size: 42px;"></i>
                    </div>

                    <h6 class="mb-1">ยังไม่มีข้อมูลเครื่องปริ้น</h6>
                    <p class="text-muted mb-3">กดปุ่มเพิ่มเครื่องปริ้นเพื่อเริ่มใช้งาน</p>

                    <a href="{{ route('printers.create') }}" class="btn btn-primary">
                      <i class="icon-base ti tabler-plus me-1"></i>
                      เพิ่มเครื่องปริ้น
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
      const deleteForms = document.querySelectorAll('.printer-delete-form');

      deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
          const confirmed = confirm('ยืนยันการลบเครื่องปริ้นรายการนี้?');

          if (!confirmed) {
            event.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
