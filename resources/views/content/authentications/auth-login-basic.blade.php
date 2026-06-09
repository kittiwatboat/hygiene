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
          <div id="loginSuccessBox" class="alert alert-success login-error-box mb-4"></div>

          <form id="formAuthentication" class="mb-4" method="POST" autocomplete="off">
            @csrf

            <div class="mb-6 form-control-validation">
              <label for="email" class="form-label">Email</label>
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
              <label class="form-label" for="password">Password</label>
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
              <button id="loginButton" class="btn btn-primary d-grid w-100 hygiene-login-btn" type="submit">
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
    const form = document.getElementById('formAuthentication');
    const loginButton = document.getElementById('loginButton');
    const loginButtonText = document.getElementById('loginButtonText');
    const loginButtonLoading = document.getElementById('loginButtonLoading');

    const loginErrorBox = document.getElementById('loginErrorBox');
    const loginSuccessBox = document.getElementById('loginSuccessBox');

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rememberInput = document.getElementById('remember-me');

    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    const csrfToken = document.querySelector('input[name="_token"]').value;

    function clearMessages() {
      loginErrorBox.classList.remove('show');
      loginErrorBox.innerHTML = '';

      loginSuccessBox.classList.remove('show');
      loginSuccessBox.innerHTML = '';

      emailError.classList.remove('show');
      emailError.innerHTML = '';

      passwordError.classList.remove('show');
      passwordError.innerHTML = '';

      emailInput.classList.remove('is-invalid');
      passwordInput.classList.remove('is-invalid');
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

    function showMainError(message) {
      loginErrorBox.innerHTML = message || 'ไม่สามารถเข้าสู่ระบบได้ กรุณาลองใหม่อีกครั้ง';
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

    form.addEventListener('submit', async function (event) {
      event.preventDefault();

      clearMessages();
      setLoading(true);

      const payload = {
        email: emailInput.value.trim(),
        password: passwordInput.value,
        remember: rememberInput.checked ? 1 : 0
      };

      try {
        const response = await fetch("{{ route('login.post') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (!response.ok) {
          if (response.status === 422) {
            showValidationErrors(result.errors);
            showMainError(result.message || 'กรุณาตรวจสอบข้อมูลให้ถูกต้อง');
            return;
          }

          showMainError(result.message || 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
          return;
        }

        if (result.success !== true) {
          showMainError(result.message || 'เข้าสู่ระบบไม่สำเร็จ');
          return;
        }

        loginSuccessBox.innerHTML = result.message || 'เข้าสู่ระบบสำเร็จ';
        loginSuccessBox.classList.add('show');

        window.location.href = result.redirect_url || "{{ route('dashboard') }}";
      } catch (error) {
        showMainError('เกิดข้อผิดพลาดในการเชื่อมต่อ Server กรุณาลองใหม่อีกครั้ง');
      } finally {
        setLoading(false);
      }
    });
  });
</script>
@endsection
