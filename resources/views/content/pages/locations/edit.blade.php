@extends('layouts/layoutMaster')

@section('title', 'แก้ไขสถานที่')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/select2/select2.scss'
  ])
@endsection

@section('page-style')
  @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/select2/select2.js'
  ])
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h5 class="mb-1">แก้ไขสถานที่</h5>
            <p class="mb-0 text-muted">
              แก้ไขข้อมูลสถานที่: {{ $location->name ?: '-' }}
            </p>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('locations.show', $location) }}" class="btn btn-label-info">
              <i class="icon-base ti tabler-eye me-1"></i>
              ดูรายละเอียด
            </a>

            <a href="{{ route('locations.index') }}" class="btn btn-label-secondary">
              <i class="icon-base ti tabler-arrow-left me-1"></i>
              กลับ
            </a>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('locations.update', $location) }}" method="POST">
            @method('PUT')

            @include('content.pages.locations._form', [
              'location' => $location,
              'provinces' => $provinces,
              'districts' => $districts,
              'subdistricts' => $subdistricts,
            ])
          </form>
        </div>

      </div>
    </div>
  </div>
@endsection

@section('page-script')
  @include('content.pages.locations._address-script')
@endsection
