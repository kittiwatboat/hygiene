@extends('layouts/layoutMaster')

@section('title', 'แก้ไขโปรโมชัน')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">แก้ไขโปรโมชัน</h5>
    <p class="text-muted mb-0">{{ $promotion->name }}</p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('promotions.update', $promotion) }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @method('PUT')
      @include('content.pages.promotions._form')
    </form>
  </div>
</div>
@endsection
