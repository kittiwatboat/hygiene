@extends('layouts/layoutMaster')

@section('title', 'แก้ไขสินค้า / น้ำยา')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">แก้ไขสินค้า / น้ำยา ฟฟa</h5>
            <p class="text-muted mb-0">{{ $product->code }} - {{ $product->name }}</p>
          </div>

          <a href="{{ route('products.index') }}" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-arrow-left me-1"></i>
            กลับ
          </a>
        </div>

        <div class="card-body">
          <form
  action="{{ route('products.update', $product) }}"
  method="POST"
  enctype="multipart/form-data"
>
            @method('PUT')
            @include('content.pages.products._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
