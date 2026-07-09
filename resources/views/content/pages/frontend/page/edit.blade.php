@extends('layouts/layoutMaster')

@section('title', 'แก้ไขหน้าบ้าน')

@section('page-style')
<style>
  .frontend-media-thumb {
    width: 180px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid rgba(67, 89, 113, .15);
    background: #f5f5f7;
  }

  .frontend-media-empty {
    width: 180px;
    height: 100px;
    border-radius: 10px;
    border: 1px dashed rgba(67, 89, 113, .25);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a8aaae;
    background: rgba(75, 70, 92, .05);
  }

  .media-type-badge {
    min-width: 60px;
  }
</style>
@endsection

@section('content')
<div class="row g-4">

  <div class="col-12">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
      <div>
        <h4 class="mb-1">{{ $page->name }}</h4>
        <p class="text-muted mb-0">Page Key: {{ $page->page_key }}</p>
      </div>

      <a href="{{ route('frontend.pages.index') }}" class="btn btn-label-secondary">
        กลับ
      </a>
    </div>
  </div>

  @if (session('success'))
    <div class="col-12">
      <div class="alert alert-success mb-0">{{ session('success') }}</div>
    </div>
  @endif

  @if (session('error'))
    <div class="col-12">
      <div class="alert alert-danger mb-0">{{ session('error') }}</div>
    </div>
  @endif

  @if ($errors->any())
    <div class="col-12">
      <div class="alert alert-danger mb-0">
        <div class="fw-medium mb-1">กรุณาตรวจสอบข้อมูล</div>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  @if ($page->page_key === 'first_page' || (int) $page->id === 1)
    @include('frontend.pages.forms.first-page')
  @elseif ($page->page_key === 'language_page' || (int) $page->id === 2)
    @include('frontend.pages.forms.language-page')
  @else
    @include('frontend.pages.forms.default-page')
  @endif


</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const mediaType = document.getElementById('mediaType');
  const mediaFileInput = document.getElementById('mediaFileInput');

  function updateAcceptType() {
    if (!mediaType || !mediaFileInput) return;

    if (mediaType.value === 'video') {
      mediaFileInput.setAttribute('accept', '.mp4,.webm,.mov');
    } else {
      mediaFileInput.setAttribute('accept', '.jpg,.jpeg,.png,.webp,.svg');
    }
  }

  mediaType?.addEventListener('change', updateAcceptType);
  updateAcceptType();
});
</script>
@endsection
