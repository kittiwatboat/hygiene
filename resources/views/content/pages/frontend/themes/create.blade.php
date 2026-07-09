@extends('layouts/layoutMaster')

@section('title', 'เพิ่มธีมหน้าตู้')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">เพิ่มธีมหน้าตู้</h5>
    <p class="text-muted mb-0">
      กำหนดสี ฟอนต์ ปุ่ม และรูปแบบหลักของหน้าตู้
    </p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('frontend.themes.store') }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @include('content.pages.frontend.themes._form')
    </form>
  </div>
</div>
@endsection
