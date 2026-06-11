@extends('layouts/layoutMaster')

@section('title', 'แก้ไขงานซ่อม')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">แก้ไขงานซ่อม</h5>
            <p class="text-muted mb-0">{{ $maintenance->code }} - {{ $maintenance->machine?->code }}</p>
          </div>

          <a href="{{ route('maintenances.index') }}" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-arrow-left me-1"></i>
            กลับ
          </a>
        </div>

        <div class="card-body">
          <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
            @method('PUT')
            @include('content.pages.maintenances._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
