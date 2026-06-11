@extends('layouts/layoutMaster')

@section('title', 'เพิ่มเครื่องปริ้น')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">เพิ่มเครื่องปริ้น</h5>
            <p class="text-muted mb-0">เพิ่มเครื่องปริ้นสำหรับใช้งานร่วมกับตู้</p>
          </div>

          <a href="{{ route('printers.index') }}" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-arrow-left me-1"></i>
            กลับ
          </a>
        </div>

        <div class="card-body">
          <form action="{{ route('printers.store') }}" method="POST">
            @include('content.pages.printers._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
