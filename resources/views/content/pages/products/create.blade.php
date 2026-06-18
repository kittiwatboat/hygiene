@extends('layouts/layoutMaster')

@section('title', 'เพิ่มสินค้า / น้ำยา')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">เพิ่มสินค้า / น้ำยา</h5>
            <p class="text-muted mb-0">เพิ่มรายการน้ำยาที่ใช้ในตู้</p>
          </div>

          <a href="{{ route('products.index') }}" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-arrow-left me-1"></i>
            กลับ
          </a>
        </div>

        <div class="card-body">
         <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @include('content.pages.products._form')
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
