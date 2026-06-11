@extends('layouts/layoutMaster')

@section('title', 'เพิ่มผู้ใช้งาน')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">เพิ่มผู้ใช้งาน</h5>
            <p class="text-muted mb-0">เพิ่มบัญชีผู้ใช้งานสำหรับเข้าระบบหลังบ้าน</p>
          </div>

          <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-arrow-left me-1"></i>
            กลับ
          </a>
        </div>

        <div class="card-body">
          <form action="{{ route('users.store') }}" method="POST">
            @include('content.pages.users._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
