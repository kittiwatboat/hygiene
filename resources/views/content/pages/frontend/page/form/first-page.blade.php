<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ข้อมูลหน้าแรก</h5>
      <p class="text-muted mb-0">ตั้งค่าข้อความและปุ่มของหน้าแรก</p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        @php
          $settings = $page->settings_json ?? [];
        @endphp

        <div class="mb-3">
          <label class="form-label">ชื่อหน้า</label>
          <input type="text" name="name" value="{{ old('name', $page->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">หัวข้อ</label>
          <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">คำอธิบาย</label>
          <input type="text" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">ข้อความปุ่มเริ่มต้น</label>
          <input
            type="text"
            name="start_button_text"
            value="{{ old('start_button_text', $settings['start_button_text'] ?? 'เลือกเติมน้ำยา') }}"
            class="form-control"
          >
        </div>

        <input type="hidden" name="start_button_action" value="language_page">

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
          <label class="form-check-label" for="show_start_button">แสดงปุ่มเริ่มต้น</label>
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
          <label class="form-check-label" for="is_active">เปิดใช้งานหน้านี้</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">บันทึกข้อมูลหน้าแรก</button>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-1">เพิ่ม Banner / Video</h5>
      <p class="text-muted mb-0">เพิ่มรูปหรือวิดีโอ และจัดลำดับการเล่น</p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.media.store', $page) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label">ประเภทไฟล์</label>
          <select name="media_type" id="mediaType" class="form-select" required>
            <option value="image">รูปภาพ</option>
            <option value="video">วิดีโอ</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">ไฟล์</label>
          <input type="file" name="file" id="mediaFileInput" class="form-control" required>
          <div class="form-text">รูปภาพ: JPG, PNG, WEBP, SVG / วิดีโอ: MP4, WEBM, MOV</div>
        </div>

        <div class="mb-3">
          <label class="form-label">หัวข้อสไลด์</label>
          <input type="text" name="title" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">คำอธิบายสไลด์</label>
          <input type="text" name="subtitle" class="form-control">
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">เวลาแสดง / วินาที</label>
            <input type="number" name="duration_seconds" value="5" class="form-control" min="1">
          </div>

          <div class="col-md-6">
            <label class="form-label">ลำดับ</label>
            <input type="number" name="sort_order" value="0" class="form-control" min="0">
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">การแสดงผล</label>
          <select name="object_fit" class="form-select">
            <option value="cover">Cover - เต็มพื้นที่</option>
            <option value="contain">Contain - เห็นครบทั้งภาพ</option>
          </select>
        </div>

        <div class="form-check form-switch mt-3 mb-4">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" value="1" id="media_is_active" class="form-check-input" checked>
          <label class="form-check-label" for="media_is_active">เปิดใช้งานสไลด์นี้</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">
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
        <p class="text-muted mb-0">ระบบจะเล่นตามลำดับจากน้อยไปมาก</p>
      </div>

      <span class="badge bg-label-primary align-self-start">
        {{ number_format($page->media->count()) }} รายการ
      </span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>ลำดับ</th>
            <th>ตัวอย่าง</th>
            <th>รายละเอียด</th>
            <th>เวลา</th>
            <th>สถานะ</th>
            <th class="text-center">จัดการ</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($page->media as $media)
            <tr>
              <td>{{ $media->sort_order }}</td>

              <td>
                @if ($media->media_type === 'video')
                  <video src="{{ $media->file_url }}" class="frontend-media-thumb" muted controls></video>
                @else
                  <img src="{{ $media->file_url }}" class="frontend-media-thumb" alt="">
                @endif
              </td>

              <td>
                <span class="badge {{ $media->media_type === 'video' ? 'bg-label-danger' : 'bg-label-info' }}">
                  {{ $media->media_type === 'video' ? 'Video' : 'Image' }}
                </span>
                <div class="fw-medium mt-1">{{ $media->title ?: '-' }}</div>
                <small class="text-muted">{{ $media->subtitle }}</small>
              </td>

              <td>{{ $media->duration_seconds }} วิ</td>

              <td>
                <span class="badge {{ $media->status_class }}">
                  {{ $media->status_text }}
                </span>
              </td>

              <td class="text-center">
                <form
                  action="{{ route('frontend.pages.media.destroy', $media) }}"
                  method="POST"
                  onsubmit="return confirm('ยืนยันการลบรายการนี้?')"
                >
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">ลบ</button>
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
