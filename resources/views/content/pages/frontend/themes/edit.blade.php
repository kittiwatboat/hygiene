@extends('layouts/layoutMaster')

@section('title', 'แก้ไขธีมหน้าตู้')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">แก้ไขธีมหน้าตู้</h5>
    <p class="text-muted mb-0">
      {{ $theme->name }}
    </p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('kiosk.themes.update', $theme) }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @method('PUT')
      @include('content.pages.kiosk.themes._form')
    </form>
  </div>
</div>
@endsection
