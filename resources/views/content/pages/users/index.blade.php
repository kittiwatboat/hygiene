@extends('layouts/layoutMaster')

@section('title', 'ผู้ใช้งาน')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])

  <style>
    .user-alert {
      margin: 0 1.5rem 1rem 1.5rem;
      padding: 0.65rem 0.85rem;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .user-alert-content {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      line-height: 1.3;
      font-weight: 500;
    }

    .user-alert-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1rem;
      line-height: 1;
      padding: 0.25rem;
      cursor: pointer;
      opacity: 0.7;
    }

    .user-alert-close:hover {
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
            <h5 class="mb-1">ผู้ใช้งาน</h5>
            <p class="mb-0 text-muted">
              จัดการบัญชีผู้ใช้งาน สิทธิ์การเข้าถึง และสถานะบัญชี
            </p>
          </div>

          <div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
              <i class="icon-base ti tabler-plus me-1"></i>
              เพิ่มผู้ใช้งาน
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success user-alert" role="alert">
            <div class="user-alert-content">
              <i class="icon-base ti tabler-circle-check"></i>
              <span>{{ session('success') }}</span>
            </div>

            <button
              type="button"
              class="user-alert-close"
              onclick="this.closest('.alert').remove()"
              aria-label="Close">
              <i class="icon-base ti tabler-x"></i>
            </button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger user-alert" role="alert">
            <div class="user-alert-content">
              <i class="icon-base ti tabler-alert-circle"></i>
              <span>{{ session('error') }}</span>
            </div>

            <button
              type="button"
              class="user-alert-close"
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
                <th>ผู้ใช้งาน</th>
                <th>ติดต่อ</th>
                <th>สิทธิ์</th>
                <th>สถานะ</th>
                <th>เข้าสู่ระบบล่าสุด</th>
                <th style="width: 110px;">Actions</th>
              </tr>
            </thead>

            <tbody class="table-border-bottom-0">
              @forelse ($users as $index => $user)
                <tr>
                  <td>
                    <span class="fw-medium">{{ $index + 1 }}</span>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                          {{ mb_substr($user->full_name, 0, 1) }}
                        </span>
                      </div>

                      <div>
                        <div class="fw-medium">{{ $user->full_name }}</div>

                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="fw-medium">{{ $user->email }}</div>
                    <div class="text-muted small">
                      {{ $user->phone ?: 'ไม่ระบุเบอร์โทร' }}
                    </div>
                  </td>

                  <td>
                    <span class="badge {{ $user->role_badge_class }}">
                      {{ $user->role_text }}
                    </span>
                  </td>

                  <td>
                    @if ($user->is_active)
                      <span class="badge {{ $user->status_badge_class }}">
                        {{ $user->status_text }}
                      </span>
                    @else
                      <span class="badge bg-label-secondary">
                        ปิดใช้งาน
                      </span>
                    @endif
                  </td>

                  <td>
                    {{ optional($user->last_login_at)->format('d/m/Y H:i') ?: '-' }}
                  </td>

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                      </button>

                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('users.show', $user) }}">
                          <i class="icon-base ti tabler-eye me-1"></i>
                          ดูรายละเอียด
                        </a>

                        <a class="dropdown-item" href="{{ route('users.edit', $user) }}">
                          <i class="icon-base ti tabler-pencil me-1"></i>
                          แก้ไข
                        </a>

                        <form
                          action="{{ route('users.destroy', $user) }}"
                          method="POST"
                          class="user-delete-form">
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
                  <td colspan="7" class="text-center py-5">
                    <div class="mb-2">
                      <i class="icon-base ti tabler-users-off" style="font-size: 42px;"></i>
                    </div>

                    <h6 class="mb-1">ยังไม่มีผู้ใช้งาน</h6>
                    <p class="text-muted mb-3">กดปุ่มเพิ่มผู้ใช้งานเพื่อเริ่มใช้งาน</p>

                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                      <i class="icon-base ti tabler-plus me-1"></i>
                      เพิ่มผู้ใช้งาน
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
      const deleteForms = document.querySelectorAll('.user-delete-form');

      deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
          const confirmed = confirm('ยืนยันการลบผู้ใช้งานรายนี้?');

          if (!confirmed) {
            event.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
