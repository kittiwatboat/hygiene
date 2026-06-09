@extends('layouts/layoutMaster')

@section('title', 'แก้ไขตู้')

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">แก้ไขตู้</h5>
            <p class="mb-0 text-muted">
              แก้ไขข้อมูลตู้: {{ $machine->name }}
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('machines.show', $machine) }}" class="btn btn-label-info">
              <i class="icon-base ti tabler-eye me-1"></i>
              ดูรายละเอียด
            </a>

            <a href="{{ route('machines.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('machines.update', $machine) }}" method="POST">
            @method('PUT')
            @include('content.pages.machines._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
