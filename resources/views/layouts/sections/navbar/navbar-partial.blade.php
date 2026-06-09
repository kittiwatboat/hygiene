@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$user = Auth::user();
$userName = $user->name ?? 'Admin';
$userEmail = $user->email ?? '-';
$userInitial = strtoupper(mb_substr($userName, 0, 1));
@endphp

<!-- Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
<div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4 ms-0">
  <a href="{{ url('/') }}" class="app-brand-link">
    <span class="app-brand-logo demo">@include('_partials.macros')</span>
    <span class="app-brand-text demo menu-text fw-bold">
      {{ config('variables.templateName', 'Hygiene') }}
    </span>
  </a>

  @if (isset($menuHorizontal))
  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
    <i class="icon-base ti tabler-x icon-sm d-flex align-items-center justify-content-center"></i>
  </a>
  @endif
</div>
@endif

@if (!isset($navbarHideToggle))
<div
  class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
  <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
    <i class="icon-base ti tabler-menu-2 icon-md"></i>
  </a>
</div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

  <div class="navbar-nav align-items-center">
    <div class="nav-item d-none d-md-flex align-items-center">
      <span class="text-body-secondary">
        ระบบจัดการตู้กดน้ำยาซักผ้า
      </span>
    </div>
  </div>

  <ul class="navbar-nav flex-row align-items-center ms-auto">

    @if (Auth::check())
    <!-- Notifications -->
    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
      <a
        class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
        href="javascript:void(0);"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false">
        <span class="position-relative">
          <i class="icon-base ti tabler-bell icon-22px"></i>
          <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
        </span>
      </a>

      <ul class="dropdown-menu dropdown-menu-end p-0">
        <li class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h6 class="mb-0 me-auto">แจ้งเตือน</h6>
            <span class="badge bg-label-danger">12 รายการ</span>
          </div>
        </li>

        <li class="dropdown-notifications-list scrollable-container">
          <ul class="list-group list-group-flush">
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <span class="avatar-initial rounded-circle bg-label-danger">
                      <i class="icon-base ti tabler-plug-connected-x"></i>
                    </span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="small mb-1">HY-009 ออฟไลน์</h6>
                  <small class="mb-1 d-block text-body">
                    ขาดการเชื่อมต่อเกิน 20 นาที
                  </small>
                  <small class="text-body-secondary">2 นาทีที่แล้ว</small>
                </div>
              </div>
            </li>

            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <span class="avatar-initial rounded-circle bg-label-warning">
                      <i class="icon-base ti tabler-droplet-exclamation"></i>
                    </span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="small mb-1">น้ำยาใกล้หมด</h6>
                  <small class="mb-1 d-block text-body">
                    HY-002 น้ำยาปรับผ้านุ่มเหลือต่ำกว่าเกณฑ์
                  </small>
                  <small class="text-body-secondary">8 นาทีที่แล้ว</small>
                </div>
              </div>
            </li>
          </ul>
        </li>

        <li class="border-top">
          <a href="{{ url('/alerts') }}" class="dropdown-item d-flex justify-content-center text-primary p-3">
            ดูแจ้งเตือนทั้งหมด
          </a>
        </li>
      </ul>
    </li>
    <!-- /Notifications -->
    @endif

    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      @if (Auth::check())
      <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <span class="avatar-initial rounded-circle bg-label-primary">
            {{ $userInitial }}
          </span>
        </div>
      </a>

      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item mt-0" href="javascript:void(0);">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-2">
                <div class="avatar avatar-online">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    {{ $userInitial }}
                  </span>
                </div>
              </div>

              <div class="flex-grow-1">
                <h6 class="mb-0">{{ $userName }}</h6>
                <small class="text-body-secondary">{{ $userEmail }}</small>
              </div>
            </div>
          </a>
        </li>

        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>

        <li>
          <a class="dropdown-item" href="{{ url('/') }}">
            <i class="icon-base ti tabler-smart-home me-3 icon-md"></i>
            <span class="align-middle">Dashboard</span>
          </a>
        </li>

        <li>
          <a class="dropdown-item" href="{{ url('/alerts') }}">
            <i class="icon-base ti tabler-bell-ringing me-3 icon-md"></i>
            <span class="align-middle">แจ้งเตือน</span>
          </a>
        </li>

        <li>
          <a class="dropdown-item" href="{{ url('/settings') }}">
            <i class="icon-base ti tabler-settings me-3 icon-md"></i>
            <span class="align-middle">ตั้งค่าระบบ</span>
          </a>
        </li>

        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>

        <li>
          <form method="POST" id="logout-form" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="dropdown-item">
              <i class="icon-base ti tabler-logout me-3 icon-md"></i>
              <span class="align-middle">ออกจากระบบ</span>
            </button>
          </form>
        </li>
      </ul>
      @else
      <div class="d-grid px-2 pt-2 pb-1">
        <a class="btn btn-sm btn-primary d-flex align-items-center" href="{{ route('login') }}">
          <small class="align-middle">Login</small>
          <i class="icon-base ti tabler-login ms-2 icon-14px"></i>
        </a>
      </div>
      @endif
    </li>
    <!-- /User -->
  </ul>
</div>
