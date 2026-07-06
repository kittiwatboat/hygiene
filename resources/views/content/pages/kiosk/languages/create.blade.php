@extends('layouts/layoutMaster')

@section('title', 'เพิ่มภาษา')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">เพิ่มภาษา</h5>
    <p class="text-muted mb-0">
      เพิ่มภาษาที่ระบบหน้าตู้สามารถรองรับได้
    </p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('kiosk.languages.store') }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @include('content.pages.kiosk.languages._form')
    </form>
  </div>
</div>
@endsection
