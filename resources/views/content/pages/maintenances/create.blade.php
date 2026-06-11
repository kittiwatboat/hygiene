@extends('layouts/layoutMaster')

@section('title', 'เปิดงานซ่อม')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">เปิดงานซ่อม</h5>
            <p class="text-muted mb-0">บันทึกปัญหาและมอบหมายงานซ่อมบำรุง</p>
          </div>

          <a href="{{ route('maintenances.index') }}" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-arrow-left me-1"></i>
            กลับ
          </a>
        </div>

        <div class="card-body">
          <form action="{{ route('maintenances.store') }}" method="POST">
            @include('content.pages.maintenances._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
