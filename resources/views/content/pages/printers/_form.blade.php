@csrf

<div class="row g-4">

  <div class="col-md-8">
    <label class="form-label">
      ชื่อโปรโมชัน <span class="text-danger">*</span>
    </label>

    <input
      type="text"
      name="name"
      value="{{ old('name', $promotion->name ?? '') }}"
      class="form-control @error('name') is-invalid @enderror"
      placeholder="เช่น ใช้ 500 แต้ม ลด 50 บาท"
      required
    >

    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">รหัสโปรโมชัน</label>

    <input
      type="text"
      name="code"
      value="{{ old('code', $promotion->code ?? '') }}"
      class="form-control @error('code') is-invalid @enderror"
      placeholder="เช่น POINT500"
    >

    @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      ประเภทโปรโมชัน <span class="text-danger">*</span>
    </label>

    @php
      $selectedType = old(
          'promotion_type',
          $promotion->promotion_type ?? 'earn_points'
      );
    @endphp

    <select
      name="promotion_type"
      id="promotionType"
      class="form-select @error('promotion_type') is-invalid @enderror"
      required
    >
      <option value="earn_points" {{ $selectedType === 'earn_points' ? 'selected' : '' }}>
        ซื้อสินค้าแล้วได้รับแต้ม
      </option>

      <option value="redeem_discount" {{ $selectedType === 'redeem_discount' ? 'selected' : '' }}>
        ใช้แต้มแลกส่วนลด
      </option>

      <option value="direct_discount" {{ $selectedType === 'direct_discount' ? 'selected' : '' }}>
        ส่วนลดทันที
      </option>
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">ขอบเขตสินค้า</label>

    @php
      $selectedScope = old(
          'scope',
          $promotion->scope ?? 'all'
      );
    @endphp

    <select
      name="scope"
      id="promotionScope"
      class="form-select"
    >
      <option value="all" {{ $selectedScope === 'all' ? 'selected' : '' }}>
        สินค้าทั้งหมด
      </option>

      <option value="product" {{ $selectedScope === 'product' ? 'selected' : '' }}>
        เฉพาะสินค้าที่เลือก
      </option>
    </select>
  </div>

  <div class="col-md-6" id="productSelectWrapper">
    <label class="form-label">สินค้าที่ร่วมรายการ</label>

    <select
      name="product_id"
      class="form-select @error('product_id') is-invalid @enderror"
    >
      <option value="">-- เลือกสินค้า --</option>

      @foreach ($products as $product)
        <option
          value="{{ $product->id }}"
          {{ (string) old('product_id', $promotion->product_id ?? '') === (string) $product->id ? 'selected' : '' }}
        >
          {{ $product->code }} - {{ $product->name }}
        </option>
      @endforeach
    </select>

    @error('product_id')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6 earn-points-field">
    <label class="form-label">แต้มที่ได้รับ</label>

    <input
      type="number"
      name="points_reward"
      min="0"
      value="{{ old('points_reward', $promotion->points_reward ?? 0) }}"
      class="form-control"
    >
  </div>

  <div class="col-md-6 redeem-field">
    <label class="form-label">แต้มที่ต้องใช้</label>

    <input
      type="number"
      name="points_required"
      min="0"
      value="{{ old('points_required', $promotion->points_required ?? 0) }}"
      class="form-control"
    >
  </div>

  <div class="col-md-6 discount-field">
    <label class="form-label">รูปแบบส่วนลด</label>

    @php
      $discountType = old(
          'discount_type',
          $promotion->discount_type ?? 'fixed'
      );
    @endphp

    <select name="discount_type" class="form-select">
      <option value="fixed" {{ $discountType === 'fixed' ? 'selected' : '' }}>
        ลดเป็นจำนวนเงิน
      </option>

      <option value="percent" {{ $discountType === 'percent' ? 'selected' : '' }}>
        ลดเป็นเปอร์เซ็นต์
      </option>
    </select>
  </div>

  <div class="col-md-6 discount-field">
    <label class="form-label">มูลค่าส่วนลด</label>

    <input
      type="number"
      step="0.01"
      min="0"
      name="discount_value"
      value="{{ old('discount_value', $promotion->discount_value ?? 0) }}"
      class="form-control"
    >
  </div>

  <div class="col-md-6 discount-field">
    <label class="form-label">ส่วนลดสูงสุด</label>

    <input
      type="number"
      step="0.01"
      min="0"
      name="max_discount"
      value="{{ old('max_discount', $promotion->max_discount ?? '') }}"
      class="form-control"
      placeholder="เว้นว่างหากไม่จำกัด"
    >
  </div>

  <div class="col-md-6">
    <label class="form-label">ยอดซื้อขั้นต่ำ</label>

    <input
      type="number"
      step="0.01"
      min="0"
      name="minimum_amount"
      value="{{ old('minimum_amount', $promotion->minimum_amount ?? 0) }}"
      class="form-control"
    >
  </div>

  <div class="col-md-6">
    <label class="form-label">จำนวนสิทธิ์ทั้งหมด</label>

    <input
      type="number"
      min="1"
      name="usage_limit"
      value="{{ old('usage_limit', $promotion->usage_limit ?? '') }}"
      class="form-control"
      placeholder="เว้นว่างหากไม่จำกัด"
    >
  </div>

  <div class="col-md-6">
    <label class="form-label">ลำดับแสดงผล</label>

    <input
      type="number"
      min="0"
      name="sort_order"
      value="{{ old('sort_order', $promotion->sort_order ?? 0) }}"
      class="form-control"
    >
  </div>

  <div class="col-md-6">
    <label class="form-label">วันเริ่มต้น</label>

    <input
      type="datetime-local"
      name="start_at"
      value="{{ old('start_at', isset($promotion) && $promotion->start_at ? $promotion->start_at->format('Y-m-d\TH:i') : '') }}"
      class="form-control"
    >
  </div>

  <div class="col-md-6">
    <label class="form-label">วันสิ้นสุด</label>

    <input
      type="datetime-local"
      name="end_at"
      value="{{ old('end_at', isset($promotion) && $promotion->end_at ? $promotion->end_at->format('Y-m-d\TH:i') : '') }}"
      class="form-control @error('end_at') is-invalid @enderror"
    >

    @error('end_at')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <label class="form-label">รูปโปรโมชัน</label>

    <input
      type="file"
      name="image"
      id="promotionImageInput"
      class="form-control @error('image') is-invalid @enderror"
      accept=".jpg,.jpeg,.png,.webp"
    >

    @error('image')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-12">
    <div
      id="promotionImageWrapper"
      class="{{ isset($promotion) && $promotion->image ? '' : 'd-none' }}"
    >
      <img
        id="promotionImagePreview"
        src="{{ isset($promotion) && $promotion->image ? asset('assets/img/promotions/' . $promotion->image) : '' }}"
        class="rounded border"
        style="width:260px;height:160px;object-fit:cover;"
        alt="Promotion"
      >
    </div>
  </div>

  <div class="col-12">
    <label class="form-label">รายละเอียดโปรโมชัน</label>

    <textarea
      name="description"
      rows="4"
      class="form-control"
    >{{ old('description', $promotion->description ?? '') }}</textarea>
  </div>

  <div class="col-12">
    <div class="form-check form-switch">
      <input type="hidden" name="is_active" value="0">

      <input
        type="checkbox"
        name="is_active"
        value="1"
        id="is_active"
        class="form-check-input"
        {{ old('is_active', isset($promotion) ? (int) $promotion->is_active : 1) ? 'checked' : '' }}
      >

      <label class="form-check-label" for="is_active">
        เปิดใช้งานโปรโมชัน
      </label>
    </div>
  </div>

  <div class="col-12 d-flex justify-content-end gap-2">
    <a href="{{ route('promotions.index') }}" class="btn btn-label-secondary">
      ยกเลิก
    </a>

    <button type="submit" class="btn btn-primary">
      บันทึก
    </button>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const type = document.getElementById('promotionType');
  const scope = document.getElementById('promotionScope');
  const productWrapper = document.getElementById('productSelectWrapper');

  const earnFields = document.querySelectorAll('.earn-points-field');
  const redeemFields = document.querySelectorAll('.redeem-field');
  const discountFields = document.querySelectorAll('.discount-field');

  function toggleFields() {
    const value = type.value;

    earnFields.forEach(el => {
      el.classList.toggle('d-none', value !== 'earn_points');
    });

    redeemFields.forEach(el => {
      el.classList.toggle('d-none', value !== 'redeem_discount');
    });

    discountFields.forEach(el => {
      el.classList.toggle(
        'd-none',
        !['redeem_discount', 'direct_discount'].includes(value)
      );
    });
  }

  function toggleProduct() {
    productWrapper.classList.toggle(
      'd-none',
      scope.value !== 'product'
    );
  }

  type.addEventListener('change', toggleFields);
  scope.addEventListener('change', toggleProduct);

  toggleFields();
  toggleProduct();

  const imageInput = document.getElementById('promotionImageInput');
  const imageWrapper = document.getElementById('promotionImageWrapper');
  const imagePreview = document.getElementById('promotionImagePreview');

  imageInput?.addEventListener('change', function () {
    const file = this.files?.[0];

    if (!file) {
      return;
    }

    imagePreview.src = URL.createObjectURL(file);
    imageWrapper.classList.remove('d-none');
  });
});
</script>
