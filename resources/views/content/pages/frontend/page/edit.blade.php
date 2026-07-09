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

  .frontend-preview-box {
    min-height: 220px;
    border-radius: 14px;
    overflow: hidden;
    background: #f6f7fb;
    border: 1px solid rgba(67, 89, 113, .15);
  }

  .frontend-preview-box img,
  .frontend-preview-box video {
    width: 100%;
    height: 220px;
    object-fit: cover;
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
        <p class="text-muted mb-0">
          Page Key: {{ $page->page_key }}
        </p>
      </div>

      <div>
        <a
          href="{{ route('frontend.pages.index') }}"
          class="btn btn-label-secondary"
        >
          กลับ
        </a>
      </div>
    </div>
  </div>

  @if (session('success'))
    <div class="col-12">
      <div class="alert alert-success mb-0">
        {{ session('success') }}
      </div>
    </div>
  @endif

  @if (session('error'))
    <div class="col-12">
      <div class="alert alert-danger mb-0">
        {{ session('error') }}
      </div>
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

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-1">ข้อมูลหน้า</h5>
        <p class="text-muted mb-0">
          ตั้งค่าข้อมูลพื้นฐานของหน้านี้
        </p>
      </div>

      <div class="card-body">
        <form
          action="{{ route('frontend.pages.update', $page) }}"
          method="POST"
        >
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">
              ชื่อหน้า <span class="text-danger">*</span>
            </label>

            <input
              type="text"
              name="name"
              value="{{ old('name', $page->name) }}"
              class="form-control @error('name') is-invalid @enderror"
              required
            >

            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">หัวข้อ</label>

            <input
              type="text"
              name="title"
              value="{{ old('title', $page->title) }}"
              class="form-control @error('title') is-invalid @enderror"
            >

            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">คำอธิบาย</label>

            <input
              type="text"
              name="subtitle"
              value="{{ old('subtitle', $page->subtitle) }}"
              class="form-control @error('subtitle') is-invalid @enderror"
            >

            @error('subtitle')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">หมายเหตุ</label>

            <textarea
              name="remark"
              rows="3"
              class="form-control @error('remark') is-invalid @enderror"
            >{{ old('remark', $page->remark) }}</textarea>

            @error('remark')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-check form-switch mb-4">
            <input type="hidden" name="is_active" value="0">

            <input
              type="checkbox"
              name="is_active"
              value="1"
              id="is_active"
              class="form-check-input"
              {{ old('is_active', (int) $page->is_active) ? 'checked' : '' }}
            >

            <label class="form-check-label" for="is_active">
              เปิดใช้งานหน้านี้
            </label>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="icon-base ti tabler-device-floppy me-1"></i>
            บันทึกข้อมูลหน้า
          </button>
        </form>
      </div>
    </div>

    <div class="card mt-4">
      <div class="card-header">
        <h5 class="mb-1">เพิ่มสไลด์</h5>
        <p class="text-muted mb-0">
          เพิ่มรูปหรือวิดีโอสำหรับหน้านี้
        </p>
      </div>

      <div class="card-body">
        <form
          action="{{ route('frontend.pages.media.store', $page) }}"
          method="POST"
          enctype="multipart/form-data"
        >
          @csrf

          <div class="mb-3">
            <label class="form-label">
              ประเภทไฟล์ <span class="text-danger">*</span>
            </label>

            <select
              name="media_type"
              id="mediaType"
              class="form-select @error('media_type') is-invalid @enderror"
              required
            >
              <option value="image" {{ old('media_type') === 'image' ? 'selected' : '' }}>
                รูปภาพ
              </option>

              <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>
                วิดีโอ
              </option>
            </select>

            @error('media_type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">
              ไฟล์ <span class="text-danger">*</span>
            </label>

            <input
              type="file"
              name="file"
              id="mediaFileInput"
              class="form-control @error('file') is-invalid @enderror"
              required
            >

            @error('file')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <div class="form-text">
              รูปภาพ: JPG, PNG, WEBP, SVG / วิดีโอ: MP4, WEBM, MOV
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">หัวข้อสไลด์</label>

            <input
              type="text"
              name="title"
              value="{{ old('title') }}"
              class="form-control"
            >
          </div>

          <div class="mb-3">
            <label class="form-label">คำอธิบายสไลด์</label>

            <input
              type="text"
              name="subtitle"
              value="{{ old('subtitle') }}"
              class="form-control"
            >
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">เวลาแสดง / วินาที</label>

              <input
                type="number"
                name="duration_seconds"
                value="{{ old('duration_seconds', 5) }}"
                class="form-control"
                min="1"
              >
            </div>

            <div class="col-md-6">
              <label class="form-label">ลำดับ</label>

              <input
                type="number"
                name="sort_order"
                value="{{ old('sort_order', 0) }}"
                class="form-control"
                min="0"
              >
            </div>
          </div>

          <div class="mt-3">
            <label class="form-label">การแสดงผล</label>

            <select name="object_fit" class="form-select">
              <option value="cover" {{ old('object_fit', 'cover') === 'cover' ? 'selected' : '' }}>
                Cover - เต็มพื้นที่
              </option>

              <option value="contain" {{ old('object_fit') === 'contain' ? 'selected' : '' }}>
                Contain - เห็นครบทั้งภาพ
              </option>
            </select>
          </div>

          <div class="form-check form-switch mt-3 mb-4">
            <input type="hidden" name="is_active" value="0">

            <input
              type="checkbox"
              name="is_active"
              value="1"
              id="media_is_active"
              class="form-check-input"
              checked
            >

            <label class="form-check-label" for="media_is_active">
              เปิดใช้งานสไลด์นี้
            </label>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="icon-base ti tabler-plus me-1"></i>
            เพิ่มสไลด์
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h5 class="mb-1">สไลด์ของหน้านี้</h5>
          <p class="text-muted mb-0">
            ระบบจะเล่นรูปและวิดีโอสลับกันตามลำดับจากน้อยไปมาก
          </p>
        </div>

        <span class="badge bg-label-primary align-self-start">
          {{ number_format($page->media->count()) }} รายการ
        </span>
      </div>

      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th style="width: 70px;">ลำดับ</th>
              <th style="width: 210px;">ตัวอย่าง</th>
              <th>รายละเอียด</th>
              <th style="width: 130px;">เวลาแสดง</th>
              <th style="width: 110px;">สถานะ</th>
              <th style="width: 110px;" class="text-center">จัดการ</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($page->media as $media)
              <tr>
                <td>
                  <span class="fw-medium">
                    {{ number_format((int) $media->sort_order) }}
                  </span>
                </td>

                <td>
                  @if ($media->media_type === 'video')
                    <video
                      src="{{ $media->file_url }}"
                      class="frontend-media-thumb"
                      muted
                      controls
                    ></video>
                  @elseif ($media->media_type === 'image')
                    <img
                      src="{{ $media->file_url }}"
                      alt="{{ $media->title ?: $page->name }}"
                      class="frontend-media-thumb"
                    >
                  @else
                    <div class="frontend-media-empty">
                      ไม่พบไฟล์
                    </div>
                  @endif
                </td>

                <td>
                  <div class="mb-1">
                    <span class="badge {{ $media->media_type === 'video' ? 'bg-label-danger' : 'bg-label-info' }} media-type-badge">
                      {{ $media->media_type === 'video' ? 'Video' : 'Image' }}
                    </span>
                  </div>

                  <div class="fw-medium">
                    {{ $media->title ?: '-' }}
                  </div>

                  @if ($media->subtitle)
                    <small class="text-muted d-block">
                      {{ $media->subtitle }}
                    </small>
                  @endif

                  <small class="text-muted d-block mt-1">
                    object-fit: {{ $media->object_fit }}
                  </small>
                </td>

                <td>
                  {{ number_format((int) $media->duration_seconds) }}
                  <small class="text-muted">วินาที</small>
                </td>

                <td>
                  <span class="badge {{ $media->status_class }}">
                    {{ $media->status_text }}
                  </span>
                </td>

                <td class="text-center">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                      data-bs-toggle="dropdown"
                    >
                      <i class="icon-base ti tabler-dots-vertical"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end">
                      <button
                        type="button"
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#editMediaModal{{ $media->id }}"
                      >
                        <i class="icon-base ti tabler-pencil me-2"></i>
                        แก้ไข
                      </button>

                      <div class="dropdown-divider"></div>

                      <form
                        action="{{ route('frontend.pages.media.destroy', $media) }}"
                        method="POST"
                        onsubmit="return confirm('ยืนยันการลบสไลด์นี้?')"
                      >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item text-danger">
                          <i class="icon-base ti tabler-trash me-2"></i>
                          ลบ
                        </button>
                      </form>
                    </div>
                  </div>
                </td>
              </tr>

              <div class="modal fade" id="editMediaModal{{ $media->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">

                    <form
                      action="{{ route('frontend.pages.media.update', $media) }}"
                      method="POST"
                    >
                      @csrf
                      @method('PUT')

                      <div class="modal-header">
                        <h5 class="modal-title">
                          แก้ไขสไลด์
                        </h5>

                        <button
                          type="button"
                          class="btn-close"
                          data-bs-dismiss="modal"
                          aria-label="Close"
                        ></button>
                      </div>

                      <div class="modal-body">
                        <div class="row g-3">

                          <div class="col-md-6">
                            <label class="form-label">หัวข้อ</label>

                            <input
                              type="text"
                              name="title"
                              value="{{ $media->title }}"
                              class="form-control"
                            >
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">คำอธิบาย</label>

                            <input
                              type="text"
                              name="subtitle"
                              value="{{ $media->subtitle }}"
                              class="form-control"
                            >
                          </div>

                          <div class="col-md-4">
                            <label class="form-label">เวลาแสดง / วินาที</label>

                            <input
                              type="number"
                              name="duration_seconds"
                              value="{{ $media->duration_seconds }}"
                              class="form-control"
                              min="1"
                            >
                          </div>

                          <div class="col-md-4">
                            <label class="form-label">ลำดับ</label>

                            <input
                              type="number"
                              name="sort_order"
                              value="{{ $media->sort_order }}"
                              class="form-control"
                              min="0"
                            >
                          </div>

                          <div class="col-md-4">
                            <label class="form-label">การแสดงผล</label>

                            <select name="object_fit" class="form-select">
                              <option value="cover" {{ $media->object_fit === 'cover' ? 'selected' : '' }}>
                                Cover - เต็มพื้นที่
                              </option>

                              <option value="contain" {{ $media->object_fit === 'contain' ? 'selected' : '' }}>
                                Contain - เห็นครบทั้งภาพ
                              </option>
                            </select>
                          </div>

                          <div class="col-12">
                            <label class="form-label">หมายเหตุ</label>

                            <textarea
                              name="remark"
                              rows="3"
                              class="form-control"
                            >{{ $media->remark }}</textarea>
                          </div>

                          <div class="col-12">
                            <div class="form-check form-switch">
                              <input type="hidden" name="is_active" value="0">

                              <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                id="edit_media_active_{{ $media->id }}"
                                class="form-check-input"
                                {{ $media->is_active ? 'checked' : '' }}
                              >

                              <label
                                class="form-check-label"
                                for="edit_media_active_{{ $media->id }}"
                              >
                                เปิดใช้งานสไลด์นี้
                              </label>
                            </div>
                          </div>

                        </div>
                      </div>

                      <div class="modal-footer">
                        <button
                          type="button"
                          class="btn btn-label-secondary"
                          data-bs-dismiss="modal"
                        >
                          ยกเลิก
                        </button>

                        <button type="submit" class="btn btn-primary">
                          บันทึก
                        </button>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="6" class="text-center py-5">
                  <div class="mb-2">
                    <i
                      class="icon-base ti tabler-photo-off text-muted"
                      style="font-size: 48px;"
                    ></i>
                  </div>

                  <h6 class="mb-1">ยังไม่มีสไลด์</h6>

                  <p class="text-muted mb-0">
                    เพิ่มรูปภาพหรือวิดีโอสำหรับแสดงในหน้านี้
                  </p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @if ($page->media->count())
      <div class="card mt-4">
        <div class="card-header">
          <h5 class="mb-1">Preview สไลด์แรก</h5>
          <p class="text-muted mb-0">
            ตัวอย่างสื่อที่อยู่ลำดับแรก
          </p>
        </div>

        <div class="card-body">
          @php
            $firstMedia = $page->media->first();
          @endphp

          <div class="frontend-preview-box">
            @if ($firstMedia && $firstMedia->media_type === 'video')
              <video
                src="{{ $firstMedia->file_url }}"
                controls
                muted
              ></video>
            @elseif ($firstMedia && $firstMedia->media_type === 'image')
              <img
                src="{{ $firstMedia->file_url }}"
                alt="{{ $firstMedia->title ?: $page->name }}"
              >
            @endif
          </div>
        </div>
      </div>
    @endif
  </div>

</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const mediaType = document.getElementById('mediaType');
  const mediaFileInput = document.getElementById('mediaFileInput');

  function updateAcceptType() {
    if (!mediaType || !mediaFileInput) {
      return;
    }

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
