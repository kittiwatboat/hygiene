<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ตั้งค่าหน้าแรก</h5>
      <p class="text-muted mb-0">
        หน้านี้ใช้สำหรับแสดง Banner / Video และปุ่มเริ่มต้นบนหน้าจอตู้
      </p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        @php
          $settings = $page->settings_json ?? [];
        @endphp

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

        <hr class="my-4">

        <h6 class="mb-3">ปุ่มเริ่มต้น</h6>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="show_start_button" value="0">

          <input
            type="checkbox"
            name="show_start_button"
            value="1"
            id="show_start_button"
            class="form-check-input"
            {{ old('show_start_button', $settings['show_start_button'] ?? true) ? 'checked' : '' }}
          >

          <label class="form-check-label" for="show_start_button">
            แสดงปุ่มเริ่มต้น
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label">Translation Key ข้อความปุ่ม</label>

          <input
            type="text"
            value="first_page.start_button"
            class="form-control"
            readonly
          >

          <div class="form-text">
            ข้อความปุ่มจะถูกแปลอัตโนมัติตามภาษาที่ผู้ใช้เลือก
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Icon ปุ่มเริ่มต้น</label>

          <input
            type="text"
            name="start_button_icon"
            value="{{ old('start_button_icon', $settings['start_button_icon'] ?? 'tabler-bottle') }}"
            class="form-control"
            placeholder="เช่น tabler-bottle"
          >

          <div class="form-text">
            ตัวอย่าง icon: tabler-bottle, tabler-droplet, tabler-shopping-cart
          </div>
        </div>

        <input
          type="hidden"
          name="start_button_action"
          value="{{ old('start_button_action', $settings['start_button_action'] ?? 'language_page') }}"
        >

        <hr class="my-4">

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
          บันทึกหน้าแรก
        </button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">เพิ่ม Banner / Video</h5>
      <p class="text-muted mb-0">
        เพิ่มรูปภาพหรือวิดีโอสำหรับแสดงเป็นพื้นหลังหน้าแรก
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
            <option value="image">รูปภาพ</option>
            <option value="video">วิดีโอ</option>
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
            แนะนำขนาด 1920x1080 / รูปภาพ: JPG, PNG, WEBP / วิดีโอ: MP4, WEBM, MOV
          </div>
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

        {{-- <div class="form-check form-switch mt-3 mb-4">
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
            เปิดใช้งาน Banner / Video นี้
          </label>
        </div> --}}

        <button type="submit" class="btn btn-primary w-100">
          <i class="icon-base ti tabler-plus me-1"></i>
          เพิ่ม Banner / Video
        </button>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header d-flex justify-content-between gap-3">
      <div>
        <h5 class="mb-1">รายการ Banner / Video</h5>
        <p class="text-muted mb-0">
          ระบบจะเล่นตามลำดับจากน้อยไปมาก
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
            <th style="width: 80px;">ลำดับ</th>
            <th style="width: 220px;">ตัวอย่าง</th>
            <th>รายละเอียด</th>
            <th style="width: 100px;">เวลา</th>
            <th style="width: 110px;">สถานะ</th>
            <th style="width: 100px;" class="text-center">จัดการ</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($page->media as $media)
            <tr>
              <td>{{ number_format((int) $media->sort_order) }}</td>

              <td>
                @if ($media->media_type === 'video')
                  <video
                    src="{{ $media->file_url }}"
                    class="frontend-media-thumb"
                    muted
                    controls
                  ></video>
                @else
                  <img
                    src="{{ $media->file_url }}"
                    class="frontend-media-thumb"
                    alt="Banner"
                  >
                @endif
              </td>

              <td>
                <span class="badge {{ $media->media_type === 'video' ? 'bg-label-danger' : 'bg-label-info' }}">
                  {{ $media->media_type === 'video' ? 'Video' : 'Image' }}
                </span>

                <div class="fw-medium mt-1">
                  {{ $media->title ?: '-' }}
                </div>

                @if ($media->subtitle)
                  <small class="text-muted d-block">
                    {{ $media->subtitle }}
                  </small>
                @endif

                <small class="text-muted d-block">
                  object-fit: {{ $media->object_fit }}
                </small>
              </td>

              <td>{{ number_format((int) $media->duration_seconds) }} วิ</td>

              <td>
                <span class="badge {{ $media->status_class }}">
                  {{ $media->status_text }}
                </span>
              </td>

              <td class="text-center">
                <form
                  action="{{ route('frontend.pages.media.destroy', $media) }}"
                  method="POST"
                  onsubmit="return confirm('ยืนยันการลบ Banner / Video นี้?')"
                >
                  @csrf
                  @method('DELETE')

                  <button type="submit" class="btn btn-sm btn-danger">
                    ลบ
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-5">
                ยังไม่มี Banner / Video
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
