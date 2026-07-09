@extends('layouts/layoutMaster')

@section('title', 'แก้ไขภาษา')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">แก้ไขภาษา</h5>
    <p class="text-muted mb-0">
      {{ $language->code }} - {{ $language->native_name }}
    </p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('kiosk.languages.update', $language) }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @method('PUT')
      @include('content.pages.kiosk.languages._form')
    </form>
  </div>
</div>
@endsection
