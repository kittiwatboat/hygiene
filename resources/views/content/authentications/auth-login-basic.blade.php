@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])

<style>
  .hygiene-login-logo {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    background: rgba(0, 127, 196, .12);
    color: #007FC4;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
  }

  .hygiene-login-logo i {
    font-size: 34px;
  }

  .hygiene-login-title {
    color: #007FC4;
    font-weight: 700;
  }

  .hygiene-login-btn {
    background: #007FC4 !important;
    border-color: #007FC4 !important;
  }

  .field-error {
    font-size: 13px;
    color: #ea5455;
    margin-top: 6px;
  }
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
      <div class="card">
        <div class="card-body">

          <div class="app-brand justify-content-center mb-6">
            <a href="{{ url('/') }}" class="app-brand-link flex-column">
              <span class="hygiene-login-logo">
                <i class="icon-base ti tabler-droplet"></i>
              </span>
              <span class="app-brand-text demo text-heading fw-bold hygiene-login-title">
                Hygiene
              </span>
            </a>
          </div>

          <h4 class="mb-1">เข้าสู่ระบบ Hygiene Dashboard</h4>
          <p class="mb-6">ระบบจัดการตู้กดน้ำยาซักผ้าและน้ำยาปรับผ้านุ่ม</p>

          @if (session('success'))
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if (session('error'))
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
              <div class="fw-medium mb-1">เข้าสู่ระบบไม่สำเร็จ</div>
              <ul class="mb-0 ps-4">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form
            id="formAuthentication"
            class="mb-4"
            action="{{ route('login.post') }}"
            method="POST"
            autocomplete="off">

            @csrf

            <div class="mb-6 form-control-validation">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                placeholder="Enter your email"
                value="{{ old('email') }}"
                autocomplete="email"
                autofocus
                required />

              @error('email')
                <div class="field-error">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-6 form-password-toggle form-control-validation">
              <label class="form-label" for="password">Password</label>

              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password"
                  class="form-control @error('password') is-invalid @enderror"
                  name="password"
                  placeholder="············"
                  autocomplete="current-password"
                  required />

                <span class="input-group-text cursor-pointer">
                  <i class="icon-base ti tabler-eye-off"></i>
                </span>
              </div>

              @error('password')
                <div class="field-error">{{ $message }}</div>
              @enderror
            </div>

            <div class="my-8">
              <div class="d-flex justify-content-between">
                <div class="form-check mb-0 ms-2">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="remember-me"
                    name="remember"
                    value="1"
                    {{ old('remember') ? 'checked' : '' }} />

                  <label class="form-check-label" for="remember-me">
                    Remember Me
                  </label>
                </div>

                <a href="javascript:void(0);">
                  <p class="mb-0">Forgot Password?</p>
                </a>
              </div>
            </div>

            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100 hygiene-login-btn" type="submit">
                Login
              </button>
            </div>
          </form>

          <div class="alert alert-info mb-0">
            <small>
              สำหรับผู้ดูแลระบบ พนักงานเติมน้ำยา และช่างเทคนิคที่ได้รับสิทธิ์เท่านั้น
            </small>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
