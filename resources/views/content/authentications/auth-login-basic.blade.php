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

  .hygiene-login-btn:disabled {
    opacity: .75;
    cursor: not-allowed;
  }

  .login-error-box {
    display: none;
  }

  .login-error-box.show {
    display: block;
  }

  .field-error {
    display: none;
    font-size: 13px;
    color: #ea5455;
    margin-top: 6px;
  }

  .field-error.show {
    display: block;
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

          <div id="loginErrorBox" class="alert alert-danger login-error-box mb-4"></div>
          {{-- <div id="loginSuccessBox" class="alert alert-success login-error-box mb-4"></div> --}}

<form
  id="form_submit"
  class="mb-4"
  method="POST"
  action="{{ route('login.post') }}"
  autocomplete="off">

  @csrf

  <div class="mb-6 form-control-validation">
    <label for="email" class="form-label">
      Email <span class="text-danger">*</span>
    </label>
    <input
      type="email"
      class="form-control"
      id="email"
      name="email"
      placeholder="Enter your email"
      value="{{ old('email') }}"
      autofocus />

    <div id="emailError" class="field-error"></div>
  </div>

  <div class="mb-6 form-password-toggle form-control-validation">
    <label class="form-label" for="password">
      Password <span class="text-danger">*</span>
    </label>

    <div class="input-group input-group-merge">
      <input
        type="password"
        id="password"
        class="form-control"
        name="password"
        placeholder="············"
        aria-describedby="password" />

      <span class="input-group-text cursor-pointer">
        <i class="icon-base ti tabler-eye-off"></i>
      </span>
    </div>

    <div id="passwordError" class="field-error"></div>
  </div>

  <div class="my-8">
    <div class="d-flex justify-content-between">
      <div class="form-check mb-0 ms-2">
        <input
          class="form-check-input"
          type="checkbox"
          id="remember-me"
          name="remember"
          value="1" />

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
    <button
      id="loginButton"
      class="btn btn-primary d-grid w-100 hygiene-login-btn"
      type="submit">
      <span id="loginButtonText">Login</span>
      <span id="loginButtonLoading" class="d-none">
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        กำลังตรวจสอบ...
      </span>
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
@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form_submit');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const loginErrorBox = document.getElementById('loginErrorBox');

    const loginButton = document.getElementById('loginButton');
    const loginButtonText = document.getElementById('loginButtonText');
    const loginButtonLoading = document.getElementById('loginButtonLoading');

    if (!form) {
      console.error('ไม่พบ form id="form_submit"');
      return;
    }

    function clearErrors() {
      emailInput.classList.remove('is-invalid');
      passwordInput.classList.remove('is-invalid');

      emailError.classList.remove('show');
      passwordError.classList.remove('show');

      emailError.innerHTML = '';
      passwordError.innerHTML = '';

      loginErrorBox.classList.remove('show');
      loginErrorBox.innerHTML = '';
    }

    function setLoading(isLoading) {
      loginButton.disabled = isLoading;

      if (isLoading) {
        loginButtonText.classList.add('d-none');
        loginButtonLoading.classList.remove('d-none');
      } else {
        loginButtonText.classList.remove('d-none');
        loginButtonLoading.classList.add('d-none');
      }
    }

    function showTopError(message) {
      loginErrorBox.innerHTML = message || 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
      loginErrorBox.classList.add('show');
    }

    function showValidationErrors(errors) {
      if (!errors) {
        return;
      }

      if (errors.email && errors.email.length > 0) {
        emailInput.classList.add('is-invalid');
        emailError.innerHTML = errors.email[0];
        emailError.classList.add('show');
      }

      if (errors.password && errors.password.length > 0) {
        passwordInput.classList.add('is-invalid');
        passwordError.innerHTML = errors.password[0];
        passwordError.classList.add('show');
      }
    }

    async function submitLogin() {
      clearErrors();

      const email = emailInput.value.trim();
      const password = passwordInput.value;

      if (email === '') {
        emailInput.classList.add('is-invalid');
        emailError.innerHTML = 'กรุณากรอกอีเมล';
        emailError.classList.add('show');
        showTopError('กรุณากรอกอีเมล');
        emailInput.focus();
        return;
      }

      if (password === '') {
        passwordInput.classList.add('is-invalid');
        passwordError.innerHTML = 'กรุณากรอกรหัสผ่าน';
        passwordError.classList.add('show');
        showTopError('กรุณากรอกรหัสผ่าน');
        passwordInput.focus();
        return;
      }

      setLoading(true);

      try {
        const formData = new FormData(form);

        const response = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          credentials: 'same-origin'
        });

        let data = null;

        try {
          data = await response.json();
        } catch (jsonError) {
          data = null;
        }

        if (response.status === 419) {
          showTopError('Session หมดอายุ กรุณารีเฟรชหน้าแล้วลองเข้าสู่ระบบใหม่อีกครั้ง');

          setTimeout(function () {
            window.location.reload();
          }, 1000);

          return;
        }

        if (!response.ok) {
          if (data && data.errors) {
            showValidationErrors(data.errors);
          }

          showTopError(
            data && data.text
              ? data.text
              : 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
          );

          return;
        }

        if (!data || data.success !== true) {
          showTopError(
            data && data.text
              ? data.text
              : 'ไม่สามารถเข้าสู่ระบบได้ กรุณาลองใหม่อีกครั้ง'
          );

          return;
        }

        window.location.href = data.redirect_url || "{{ route('dashboard') }}";
      } catch (error) {
        console.error(error);
        showTopError('ไม่สามารถเชื่อมต่อ Server ได้ กรุณาลองใหม่อีกครั้ง');
      } finally {
        setLoading(false);
      }
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      submitLogin();
    });
  });
</script>
@endsection
