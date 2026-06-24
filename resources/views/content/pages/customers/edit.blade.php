@extends('layouts/layoutMaster')

@section('title', 'แก้ไขสมาชิก')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-1">แก้ไขสมาชิก</h5>
    <p class="text-muted mb-0">
      {{ $customer->member_code }} - {{ $customer->name }}
    </p>
  </div>

  <div class="card-body">
    <form
      action="{{ route('customers.update', $customer) }}"
      method="POST"
    >
      @method('PUT')
      @include('content.pages.customers._form')
    </form>
  </div>
</div>
@endsection
