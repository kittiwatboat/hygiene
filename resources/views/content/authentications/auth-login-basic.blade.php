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

         <form id="form_submit" class="mb-4" method="POST" autocomplete="off">
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
    <button
      id="loginButton"
      class="btn btn-primary d-grid w-100 hygiene-login-btn"
      type="button"
      onclick="check_login()">
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

@section('page-script')
<script>
  function clearLoginErrors() {
    $('#email').removeClass('is-invalid');
    $('#password').removeClass('is-invalid');

    $('#emailError').removeClass('show').html('');
    $('#passwordError').removeClass('show').html('');
  }

  function showFieldErrors(errors) {
    if (!errors) {
      return;
    }

    if (errors.email && errors.email.length > 0) {
      $('#email').addClass('is-invalid');
      $('#emailError').addClass('show').html(errors.email[0]);
    }

    if (errors.password && errors.password.length > 0) {
      $('#password').addClass('is-invalid');
      $('#passwordError').addClass('show').html(errors.password[0]);
    }
  }

  function check_login() {
    clearLoginErrors();

    var email = $('#email').val();
    var password = $('#password').val();

    if (email === '' || password === '') {
      Swal.fire({
        icon: 'warning',
        title: 'เกิดข้อผิดพลาด',
        html: "กรุณากรอกข้อมูลที่มี <span class='text-danger'>*</span> ให้ครบถ้วน !",
        showCancelButton: false,
        confirmButtonText: 'Close',
      });

      return false;
    }

    Swal.fire({
      icon: 'warning',
      html: 'กรุณากด <b>ยืนยัน</b> เพื่อเข้าสู่ระบบ',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน',
      cancelButtonText: 'ยกเลิก',
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData($('#form_submit')[0]);

        $('#loginButton').prop('disabled', true).html(
          '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>กำลังตรวจสอบ...'
        );

        $.ajax({
          type: 'POST',
          url: "{{ route('login.post') }}",
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'json',
          headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            'X-Requested-With': 'XMLHttpRequest'
          },
          success: function (data) {
            Swal.fire({
              icon: 'success',
              title: data.title || 'สำเร็จ',
              text: data.text || 'เข้าสู่ระบบสำเร็จ',
              showConfirmButton: false,
              timer: 900
            }).then(function () {
              window.location.href = data.redirect_url || "{{ route('dashboard') }}";
            });
          },
          error: function (xhr) {
            $('#loginButton').prop('disabled', false).html('Login');

            if (xhr.status === 419) {
              Swal.fire({
                title: 'Session หมดอายุ',
                text: 'กรุณารีเฟรชหน้าแล้วลองเข้าสู่ระบบใหม่อีกครั้ง',
                icon: 'error',
                confirmButtonText: 'รีเฟรชหน้า',
              }).then(function () {
                window.location.reload();
              });

              return;
            }

            var errorMessage = xhr.responseJSON;

            if (errorMessage && errorMessage.errors) {
              showFieldErrors(errorMessage.errors);
            }

            Swal.fire({
              title: errorMessage && errorMessage.title ? errorMessage.title : 'เกิดข้อผิดพลาด',
              text: errorMessage && errorMessage.text ? errorMessage.text : 'ไม่สามารถเข้าสู่ระบบได้ กรุณาลองใหม่อีกครั้ง',
              icon: 'error',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'ปิด',
              customClass: {
                confirmButton: 'btn btn-danger waves-effect waves-light'
              },
            });
          }
        });
      }
    });
  }

  $(document).on('keypress', '#email, #password', function (event) {
    if (event.which === 13) {
      check_login();
    }
  });
</script>
@endsection
