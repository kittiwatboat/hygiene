@extends('layouts/layoutMaster')

@section('title', 'แก้ไขตู้')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">แก้ไขตู้</h5>
            <p class="text-muted mb-0">แก้ไขข้อมูลตู้และตั้งค่าน้ำยา 3 ช่อง</p>
          </div>

          <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
            กลับ
          </a>
        </div>

        <div class="card-body">
          <form action="{{ route('content.pages.machines.update', $machine) }}" method="POST">
            @method('PUT')
            @include('content.pages.machines._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
