@extends('layouts/layoutMaster')

@section('title', 'เพิ่มโปรโมชัน')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">เพิ่มโปรโมชัน</h5>
    <p class="text-muted mb-0">
      กำหนดโปรโมชัน ส่วนลด และแต้มสะสม
    </p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('promotions.store') }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @include('content.pages.promotions._form')
    </form>
  </div>
</div>
@endsection
