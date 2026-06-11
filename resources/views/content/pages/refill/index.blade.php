@extends('layouts/layoutMaster')

@section('title', 'บันทึกเติมน้ำยา')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])

  <style>
    .refill-alert {
      margin: 0 1.5rem 1rem 1.5rem;
      padding: 0.65rem 0.85rem;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .refill-alert-content {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      line-height: 1.3;
      font-weight: 500;
    }

    .refill-alert-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1rem;
      line-height: 1;
      padding: 0.25rem;
      cursor: pointer;
      opacity: 0.7;
    }

    .refill-alert-close:hover {
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
            <h5 class="mb-1">บันทึกเติมน้ำยา</h5>
            <p class="mb-0 text-muted">
              ประวัติการเติมน้ำยาเข้าตู้ แยกตามช่องน้ำยา
            </p>
          </div>

          <div>
            <a href="{{ route('refills.create') }}" class="btn btn-primary">
              <i class="icon-base ti tabler-plus me-1"></i>
              บันทึกเติมน้ำยา
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success refill-alert" role="alert">
            <div class="refill-alert-content">
              <i class="icon-base ti tabler-circle-check"></i>
              <span>{{ session('success') }}</span>
            </div>

            <button
              type="button"
              class="refill-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif

        <div class="card-body border-top">
          <form method="GET" action="{{ route('refills.index') }}">
            <div class="row g-3 align-items-end">
              <div class="col-md-5">
                <label class="form-label">ค้นหา</label>
                <input
                  type="text"
                  name="keyword"
                  value="{{ request('keyword') }}"
                  class="form-control"
                  placeholder="ค้นหาตู้ น้ำยา หรือชื่อช่อง"
                >
              </div>

              <div class="col-md-5">
                <label class="form-label">ตู้</label>
                <select name="machine_id" class="form-select">
                  <option value="">ทั้งหมด</option>
                  @foreach ($machines as $machine)
                    <option
                      value="{{ $machine->id }}"
                      {{ (string) request('machine_id') === (string) $machine->id ? 'selected' : '' }}
                    >
                      {{ $machine->code }} - {{ $machine->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                  <i class="icon-base ti tabler-search me-1"></i>
                  ค้นหา
                </button>
              </div>
            </div>
          </form>
        </div>

        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th style="width: 70px;">#</th>
                <th>วันที่เติม</th>
                <th>ตู้ / ช่อง</th>
                <th>น้ำยา</th>
                <th>ก่อนเติม</th>
                <th>เติม</th>
                <th>หลังเติม</th>
                <th>ผู้บันทึก</th>
                <th style="width: 110px;">Actions</th>
              </tr>
            </thead>

            <tbody class="table-border-bottom-0">
              @forelse ($refills as $index => $refill)
                <tr>
                  <td>
                    <span class="fw-medium">{{ $index + 1 }}</span>
                  </td>

                  <td>
                    <div class="fw-medium">
                      {{ optional($refill->refill_at)->format('d/m/Y') ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      {{ optional($refill->refill_at)->format('H:i') ?: '' }}
                    </div>
                  </td>

                  <td>
                    <div class="fw-medium">
                      {{ $refill->machine?->code ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      {{ $refill->machine?->name ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      ช่อง {{ $refill->tank?->tank_no ?: '-' }}:
                      {{ $refill->tank?->tank_name ?: '-' }}
                    </div>
                  </td>

                  <td>
                    <div class="fw-medium">
                      {{ $refill->product?->name ?: $refill->tank?->product?->name ?: '-' }}
                    </div>
                    <div class="text-muted small">
                      {{ $refill->product?->code ?: $refill->tank?->product?->code ?: '' }}
                    </div>
                  </td>

                  <td>
                    {{ number_format((float) $refill->before_liters, 2) }} L
                  </td>

                  <td>
                    <span class="badge bg-label-primary">
                      +{{ number_format((float) $refill->refill_liters, 2) }} L
                    </span>
                  </td>

                  <td>
                    {{ number_format((float) $refill->after_liters, 2) }} L
                  </td>

                  <td>
                    {{ $refill->refillBy?->full_name ?: $refill->refillBy?->name ?: '-' }}
                  </td>

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                      </button>

                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('refills.show', $refill) }}">
                          <i class="icon-base ti tabler-eye me-1"></i>
                          ดูรายละเอียด
                        </a>

                        <form
                          action="{{ route('refills.destroy', $refill) }}"
                          method="POST"
                          class="refill-delete-form">
                          @csrf
                          @method('DELETE')

                          <button type="submit" class="dropdown-item text-danger">
                            <i class="icon-base ti tabler-trash me-1"></i>
                            ลบ / คืน Stock
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center py-5">
                    <div class="mb-2">
                      <i class="icon-base ti tabler-droplet-plus" style="font-size: 42px;"></i>
                    </div>

                    <h6 class="mb-1">ยังไม่มีประวัติเติมน้ำยา</h6>
                    <p class="text-muted mb-3">กดปุ่มบันทึกเติมน้ำยาเพื่อเริ่มใช้งาน</p>

                    <a href="{{ route('refills.create') }}" class="btn btn-primary">
                      <i class="icon-base ti tabler-plus me-1"></i>
                      บันทึกเติมน้ำยา
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
      const deleteForms = document.querySelectorAll('.refill-delete-form');

      deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
          const confirmed = confirm('ยืนยันการลบบันทึกนี้? ระบบจะคืนค่า Stock กลับเป็นค่าก่อนเติม');

          if (!confirmed) {
            event.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
