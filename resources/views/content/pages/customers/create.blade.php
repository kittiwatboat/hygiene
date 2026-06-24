@extends('layouts/layoutMaster')

@section('title', 'เพิ่มสมาชิก')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">เพิ่มสมาชิก</h5>
    <p class="text-muted mb-0">
      เพิ่มข้อมูลสมาชิกสำหรับระบบสะสมแต้ม
    </p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('customers.store') }}"
      method="POST"
    >
      @include('content.pages.customers._form')
    </form>
  </div>
</div>
@endsection
