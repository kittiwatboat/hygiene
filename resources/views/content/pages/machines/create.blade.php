@extends('layouts/layoutMaster')

@section('title', 'เพิ่มตู้')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">เพิ่มตู้</h5>
            <p class="mb-0 text-muted">
              กรอกข้อมูลตู้และเลือกสถานที่ติดตั้ง
            </p>
          </div>

          <div>
            <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('machines.store') }}" method="POST">
            @include('machines._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
