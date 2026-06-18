@extends('layouts/layoutMaster')

@section('title', 'แก้ไขแบนเนอร์')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">แก้ไขแบนเนอร์</h5>
    <p class="text-muted mb-0">{{ $banner->title }}</p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('banners.update', $banner) }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @method('PUT')
      @include('banners._form')
    </form>
  </div>
</div>
@endsection
