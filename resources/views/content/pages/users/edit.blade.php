@extends('layouts/layoutMaster')

@section('title', 'แก้ไขผู้ใช้งาน')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">แก้ไขผู้ใช้งาน</h5>
            <p class="text-muted mb-0">{{ $user->full_name }} - {{ $user->email }}</p>
          </div>

          <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-arrow-left me-1"></i>
            กลับ
          </a>
        </div>

        <div class="card-body">
          <form action="{{ route('users.update', $user) }}" method="POST">
            @method('PUT')
            @include('content.pages.users._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
