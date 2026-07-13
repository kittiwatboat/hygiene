<div class="col-lg-6">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">ข้อมูลหน้า</h5>
      <p class="text-muted mb-0">ตั้งค่าข้อมูลพื้นฐานของหน้านี้</p>
    </div>

    <div class="card-body">
      <form action="{{ route('frontend.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

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

        <button type="submit" class="btn btn-primary w-100">
          บันทึกข้อมูลหน้า
        </button>
      </form>
    </div>
  </div>
</div>
