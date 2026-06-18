@extends('layouts/layoutMaster')

@section('title', 'เพิ่มแบนเนอร์')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">เพิ่มแบนเนอร์</h5>
    <p class="text-muted mb-0">เพิ่มรูปสำหรับแสดงบนหน้าเครื่องหรือหน้าลูกค้า</p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('banners.store') }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @include('content.pages.banners._form')
    </form>
  </div>
</div>
@endsection
