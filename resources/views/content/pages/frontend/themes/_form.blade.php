@csrf

@php
    $backgroundType = old('background_type', $theme->background_type ?? 'color');
    $headerType = old('header_type', $theme->header_type ?? 'none');
    $bgImage =
        isset($theme) && $theme->background_image
            ? asset('assets/img/frontend/themes/' . $theme->background_image)
            : null;
    $bgVideo =
        isset($theme) && $theme->background_video
            ? asset('assets/videos/frontend/themes/' . $theme->background_video)
            : null;
    $headerImage =
        isset($theme) && $theme->header_background_image
            ? asset('assets/img/frontend/themes/' . $theme->header_background_image)
            : null;
    $headerVideo =
        isset($theme) && $theme->header_background_video
            ? asset('assets/videos/frontend/themes/' . $theme->header_background_video)
            : null;
    $logoMain =
        isset($theme) && $theme->header_logo_main
            ? asset('assets/img/frontend/themes/' . $theme->header_logo_main)
            : null;
    $logoRight1 =
        isset($theme) && $theme->header_logo_right_1
            ? asset('assets/img/frontend/themes/' . $theme->header_logo_right_1)
            : null;
    $logoRight2 =
        isset($theme) && $theme->header_logo_right_2
            ? asset('assets/img/frontend/themes/' . $theme->header_logo_right_2)
            : null;
@endphp

<style>
    .theme-preview-sticky {
        position: sticky;
        top: 90px
    }

    .kiosk-preview {
        position: relative;
        min-height: 700px;
        border: 10px solid #20242b;
        border-radius: 24px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 18px 50px rgba(0, 0, 0, .16)
    }

    .kiosk-bg-img,
    .kiosk-bg-video,
    .kiosk-header-img,
    .kiosk-header-video {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover
    }

    .kiosk-bg-img,
    .kiosk-bg-video {
        z-index: 0
    }

    .kiosk-content {
        position: relative;
        z-index: 2;
        min-height: 700px;
        display: flex;
        flex-direction: column
    }

    .kiosk-header {
        position: relative;
        z-index: 3;
        overflow: hidden
    }

    .kiosk-header-inner {
        position: relative;
        z-index: 2;
        height: 100%;
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 12px;
        padding: 10px 18px
    }

    .kiosk-logo-main {
        max-width: 150px;
        max-height: 52px;
        object-fit: contain
    }

    .kiosk-right {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        min-width: 150px
    }

    .kiosk-right img {
        width: 68px;
        height: 46px;
        object-fit: contain
    }

    .kiosk-body {
        flex: 1;
        padding: 34px;
        display: flex;
        flex-direction: column
    }

    .kiosk-title {
        text-align: center;
        font-size: 30px;
        font-weight: 800
    }

    .kiosk-subtitle {
        text-align: center;
        opacity: .7;
        margin-bottom: 28px
    }

    .kiosk-card {
        width: min(520px, 100%);
        margin: 0 auto;
        background: rgba(255, 255, 255, .9);
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08)
    }

    .kiosk-product {
        display: grid;
        grid-template-columns: 84px 1fr;
        gap: 14px;
        align-items: center;
        background: #f8fafc;
        border-radius: 14px;
        padding: 14px
    }

    .kiosk-product-icon {
        width: 84px;
        height: 104px;
        border-radius: 12px;
        background: white;
        border: 1px solid rgba(0, 0, 0, .07);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px
    }

    .kiosk-actions {
        margin-top: auto;
        padding-top: 28px;
        display: flex;
        justify-content: center;
        gap: 14px
    }

    .kiosk-btn {
        border: 2px solid transparent;
        border-radius: 999px;
        min-width: 150px;
        min-height: 48px;
        padding: 10px 22px;
        font-weight: 700
    }

    .section-help {
        color: #6c757d;
        margin: 0
    }

    .current-thumb {
        max-width: 220px;
        max-height: 130px;
        object-fit: cover
    }

    @media(max-width:1199.98px) {
        .theme-preview-sticky {
            position: static
        }

        .kiosk-preview,
        .kiosk-content {
            min-height: 600px
        }
    }
</style>

<div class="row g-4">
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1">ตั้งค่า Theme หน้าตู้</h5>
                <p class="text-muted mb-0">ปรับค่าด้านซ้ายและดูภาพรวมทั้งหมดจาก Live Preview ด้านขวา</p>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">ชื่อธีม <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $theme->name ?? '') }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $theme->slug ?? '') }}"
                            class="form-control @error('slug') is-invalid @enderror">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr class="my-1">
                        <h6 class="mb-1">พื้นหลังและข้อความ</h6>
                        <p class="section-help">กำหนดพื้นหลังหลักและสีตัวอักษรของหน้าตู้</p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">สีตัวอักษร</label>
                        <div class="input-group"><input type="color"
                                value="{{ old('text_color', $theme->text_color ?? '#111827') }}"
                                class="form-control form-control-color theme-color-picker" data-target="text_color"
                                style="max-width:64px"><input type="text" name="text_color" id="text_color"
                                value="{{ old('text_color', $theme->text_color ?? '#111827') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ประเภทพื้นหลัง</label>
                        <select name="background_type" id="backgroundType" class="form-select">
                            <option value="color" {{ $backgroundType === 'color' ? 'selected' : '' }}>สีพื้นหลัง</option>
                            <option value="image" {{ $backgroundType === 'image' ? 'selected' : '' }}>รูปภาพพื้นหลัง</option>
                            <option value="video" {{ $backgroundType === 'video' ? 'selected' : '' }}>วิดีโอพื้นหลัง</option>
                        </select>
                    </div>
                    <div class="col-12 background-color-field">
                        <label class="form-label">สีพื้นหลัง</label>
                        <div class="input-group"><input type="color"
                                value="{{ old('background_color', $theme->background_color ?? '#FFFFFF') }}"
                                class="form-control form-control-color theme-color-picker"
                                data-target="background_color" style="max-width:64px"><input type="text"
                                name="background_color" id="background_color"
                                value="{{ old('background_color', $theme->background_color ?? '#FFFFFF') }}"
                                class="form-control"></div>
                    </div>
                    <div class="col-12 background-image-field">
                        <label class="form-label">รูปภาพพื้นหลัง</label>
                        <input type="file" name="background_image" id="backgroundImageInput" class="form-control"
                            accept=".jpg,.jpeg,.png,.webp,.svg">
                        @if ($bgImage)
                            <div class="mt-2"><img src="{{ $bgImage }}" class="rounded border current-thumb">
                                <div class="form-check mt-2"><input type="checkbox" name="remove_background_image"
                                        value="1" id="remove_background_image" class="form-check-input"><label
                                        for="remove_background_image" class="form-check-label">ลบรูปพื้นหลังเดิม</label>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 background-video-field">
                        <label class="form-label">วิดีโอพื้นหลัง</label>
                        <input type="file" name="background_video" id="backgroundVideoInput" class="form-control"
                            accept=".mp4,.webm,.mov">
                        @if ($bgVideo)
                            <div class="mt-2"><video src="{{ $bgVideo }}" controls muted
                                    style="max-width:100%;max-height:150px" class="rounded border"></video>
                                <div class="form-check mt-2"><input type="checkbox" name="remove_background_video"
                                        value="1" id="remove_background_video" class="form-check-input"><label
                                        for="remove_background_video"
                                        class="form-check-label">ลบวิดีโอพื้นหลังเดิม</label></div>
                            </div>
                        @endif
                    </div>

                    <div class="col-12">
                        <hr class="my-1">
                        <h6 class="mb-1">ปุ่ม</h6>
                        <p class="section-help">กำหนดสีปุ่มหลักและสถานะ hover</p>
                    </div>
                    <div class="col-md-6"><label class="form-label">สีปุ่ม</label>
                        <div class="input-group"><input type="color"
                                value="{{ old('button_color', $theme->button_color ?? '#00AEEF') }}"
                                class="form-control form-control-color theme-color-picker" data-target="button_color"
                                style="max-width:64px"><input type="text" name="button_color" id="button_color"
                                value="{{ old('button_color', $theme->button_color ?? '#00AEEF') }}"
                                class="form-control"></div>
                    </div>
                    <div class="col-md-6"><label class="form-label">สีตัวอักษรปุ่ม</label>
                        <div class="input-group"><input type="color"
                                value="{{ old('button_text_color', $theme->button_text_color ?? '#FFFFFF') }}"
                                class="form-control form-control-color theme-color-picker"
                                data-target="button_text_color" style="max-width:64px"><input type="text"
                                name="button_text_color" id="button_text_color"
                                value="{{ old('button_text_color', $theme->button_text_color ?? '#FFFFFF') }}"
                                class="form-control"></div>
                    </div>
                    <div class="col-12"><label class="form-label">สีเส้นตอน Hover</label>
                        <div class="input-group"><input type="color"
                                value="{{ old('button_hover_border_color', $theme->button_hover_border_color ?? '#00AEEF') }}"
                                class="form-control form-control-color theme-color-picker"
                                data-target="button_hover_border_color" style="max-width:64px"><input type="text"
                                name="button_hover_border_color" id="button_hover_border_color"
                                value="{{ old('button_hover_border_color', $theme->button_hover_border_color ?? '#00AEEF') }}"
                                class="form-control"></div>
                    </div>

                    <div class="col-12">
                        <hr class="my-1">
                        <h6 class="mb-1">Header</h6>
                        <p class="section-help">กำหนดพื้นหลัง Header และโลโก้ในภาพรวมเดียว</p>
                    </div>
                    <div class="col-md-6"><label class="form-label">ประเภท Header</label><select name="header_type"
                            id="headerType" class="form-select">
                            <option value="none" {{ $headerType === 'none' ? 'selected' : '' }}>ไม่ใช้พื้นหลัง Header
                            </option>
                            <option value="color" {{ $headerType === 'color' ? 'selected' : '' }}>สีพื้นหลัง</option>
                            <option value="image" {{ $headerType === 'image' ? 'selected' : '' }}>รูปภาพ</option>
                            <option value="video" {{ $headerType === 'video' ? 'selected' : '' }}>วิดีโอ</option>
                        </select></div>
                    <div class="col-md-6"><label class="form-label">ความสูง Header (px)</label><input type="number"
                            name="header_height" id="header_height"
                            value="{{ old('header_height', $theme->header_height ?? 82) }}" class="form-control"
                            min="40" max="300"></div>
                    <div class="col-12 header-color-field"><label class="form-label">สีพื้นหลัง Header</label>
                        <div class="input-group"><input type="color"
                                value="{{ old('header_background_color', $theme->header_background_color ?? '#1EB5F0') }}"
                                class="form-control form-control-color theme-color-picker"
                                data-target="header_background_color" style="max-width:64px"><input type="text"
                                name="header_background_color" id="header_background_color"
                                value="{{ old('header_background_color', $theme->header_background_color ?? '#1EB5F0') }}"
                                class="form-control"></div>
                    </div>
                    <div class="col-12 header-image-field"><label class="form-label">รูปภาพ Header</label><input
                            type="file" name="header_background_image" id="headerBackgroundImageInput"
                            class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg"></div>
                    <div class="col-12 header-video-field"><label class="form-label">วิดีโอ Header</label><input
                            type="file" name="header_background_video" id="headerBackgroundVideoInput"
                            class="form-control" accept=".mp4,.webm,.mov"></div>
                    <div class="col-md-4">
                        <label class="form-label">โลโก้หลัก</label>
                        <input
                            type="file"
                            name="header_logo_main"
                            id="headerLogoMainInput"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp,.svg"
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            โลโก้ด้านขวา 1
                        </label>

                        <input
                            type="file"
                            name="header_logo_right_1"
                            id="headerLogoRight1Input"
                            class="form-control @error('header_logo_right_1') is-invalid @enderror"
                            accept="image/png,.png"
                        >

                        @error('header_logo_right_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            รองรับเฉพาะไฟล์ PNG
                        </div>

                        @if ($logoRight1)
                            <div class="mt-2">
                                <img
                                    src="{{ $logoRight1 }}"
                                    alt="โลโก้ด้านขวา 1"
                                    class="rounded border p-2"
                                    style="
                                        width: 120px;
                                        height: 70px;
                                        object-fit: contain;
                                        background: #f8f9fa;
                                    "
                                >
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            โลโก้ด้านขวา 2
                        </label>

                        <input
                            type="file"
                            name="header_logo_right_2"
                            id="headerLogoRight2Input"
                            class="form-control @error('header_logo_right_2') is-invalid @enderror"
                            accept="image/png,.png"
                        >

                        @error('header_logo_right_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            รองรับเฉพาะไฟล์ PNG
                        </div>

                        @if ($logoRight2)
                            <div class="mt-2">
                                <img
                                    src="{{ $logoRight2 }}"
                                    alt="โลโก้ด้านขวา 2"
                                    class="rounded border p-2"
                                    style="
                                        width: 120px;
                                        height: 70px;
                                        object-fit: contain;
                                        background: #f8f9fa;
                                    "
                                >
                            </div>
                        @endif
                    </div>

                    <div class="col-12">
                        <hr class="my-1">
                        <h6 class="mb-1">เมนูหน้าตู้</h6>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch mb-3"><input type="hidden" name="show_home_button"
                                value="0"><input type="checkbox" name="show_home_button" value="1"
                                id="show_home_button" class="form-check-input"
                                {{ old('show_home_button', isset($theme) ? (int) $theme->show_home_button : 1) ? 'checked' : '' }}><label
                                class="form-check-label" for="show_home_button">แสดงปุ่มกลับหน้าแรก</label></div>
                        {{-- <label class="form-label">ข้อความปุ่มหน้าแรก</label>
                        <input type="text"
                            name="home_button_text" id="home_button_text"
                            value="{{ old('home_button_text', $theme->home_button_text ?? 'หน้าหลัก') }}"
                            class="form-control"> --}}
                    </div>

                    <div class="col-12">
                        <hr class="my-1">
                        <h6 class="mb-1">สถานะ</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch"><input type="hidden" name="is_active"
                                value="0"><input type="checkbox" name="is_active" value="1"
                                id="is_active" class="form-check-input"
                                {{ old('is_active', isset($theme) ? (int) $theme->is_active : 1) ? 'checked' : '' }}><label
                                class="form-check-label" for="is_active">เปิดใช้งานธีม</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch"><input type="hidden" name="is_default"
                                value="0"><input type="checkbox" name="is_default" value="1"
                                id="is_default" class="form-check-input"
                                {{ old('is_default', isset($theme) ? (int) $theme->is_default : 0) ? 'checked' : '' }}><label
                                class="form-check-label" for="is_default">ใช้เป็น Theme สำรอง</label></div>
                    </div>
                    <div class="col-12"><label class="form-label">หมายเหตุ</label>
                        <textarea name="remark" rows="3" class="form-control">{{ old('remark', $theme->remark ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="theme-preview-sticky">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <h5 class="mb-1">Preview ภาพรวมหน้าตู้</h5>
                        <p class="text-muted mb-0">ดู Header, พื้นหลัง, สีข้อความ และปุ่มพร้อมกันในภาพเดียว</p>
                    </div><span class="badge bg-label-primary">Live Preview</span>
                </div>
                <div class="card-body">
                    <div id="kioskPreview" class="kiosk-preview">
                        <img id="previewBgImage" class="kiosk-bg-img d-none" alt=""><video
                            id="previewBgVideo" class="kiosk-bg-video d-none" autoplay muted loop playsinline></video>
                        <div id="kioskContent" class="kiosk-content">
                            <div id="kioskHeader" class="kiosk-header"
                                style="height:{{ old('header_height', $theme->header_height ?? 82) }}px;background:{{ old('header_background_color', $theme->header_background_color ?? '#1EB5F0') }}">
                                <img id="previewHeaderImage" class="kiosk-header-img d-none" alt=""><video
                                    id="previewHeaderVideo" class="kiosk-header-video d-none" autoplay muted loop
                                    playsinline></video>
                                <div class="kiosk-header-inner">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <button type="button" id="previewHomeButton"
                                            class="btn btn-light btn-sm rounded-pill">
                                            <i class="icon-base ti tabler-home me-1"></i>
                                            <span
                                                id="previewHomeText">{{ old('home_button_text', $theme->home_button_text ?? 'หน้าหลัก') }}</span>
                                        </button>

                                        <div id="previewLanguageButtons" class="d-flex align-items-center gap-1">
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-0"
                                                style="width:34px;height:34px;" title="ไทย">🇹🇭</button>
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-0"
                                                style="width:34px;height:34px;" title="English">🇬🇧</button>
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-0"
                                                style="width:34px;height:34px;" title="中文">🇨🇳</button>
                                        </div>
                                    </div>
                                    <div class="text-center"><img id="previewLogoMain" src="{{ $logoMain }}"
                                            class="kiosk-logo-main {{ $logoMain ? '' : 'd-none' }}">
                                        <div id="previewLogoText"
                                            class="fw-bold text-white {{ $logoMain ? 'd-none' : '' }}">HYGIENE</div>
                                    </div>
                                    <div class="kiosk-right"><img id="previewLogoRight1" src="{{ $logoRight1 }}"
                                            class="{{ $logoRight1 ? '' : 'd-none' }}"><img id="previewLogoRight2"
                                            src="{{ $logoRight2 }}" class="{{ $logoRight2 ? '' : 'd-none' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="kiosk-body">
                                <div class="kiosk-title">ตัวอย่างหน้าตู้</div>
                                <div class="kiosk-subtitle">Preview สำหรับตรวจภาพรวม Theme ก่อนนำไปใช้กับกลุ่มตู้</div>
                                <div class="kiosk-card">
                                    <div class="fw-bold mb-3">ตัวอย่างข้อมูลบนหน้าตู้</div>
                                    <div class="kiosk-product">
                                        <div class="kiosk-product-icon"><i class="icon-base ti tabler-bottle"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-1">Hygiene Expert Care</div>
                                            <div class="small opacity-75 mb-3">ตัวอย่างสินค้าและข้อความสำหรับดู Theme
                                                โดยรวม</div>
                                            <div class="d-flex justify-content-between"><span>จำนวน 1</span><strong>115
                                                    บาท</strong></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kiosk-actions"><button type="button" id="previewSecondaryButton"
                                        class="kiosk-btn">ย้อนกลับ</button><button type="button"
                                        id="previewPrimaryButton" class="kiosk-btn">ยืนยัน</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-text mt-3">Preview นี้ใช้ดูภาพรวม Theme เท่านั้น ข้อมูลสินค้าเป็นข้อมูลตัวอย่าง
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 d-flex justify-content-end gap-2"><a href="{{ route('frontend.themes.index') }}"
            class="btn btn-label-secondary">ยกเลิก</a><button type="submit" class="btn btn-primary"><i
                class="icon-base ti tabler-device-floppy me-1"></i>บันทึก</button></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const current = {
            bgImage: @json($bgImage),
            bgVideo: @json($bgVideo),
            headerImage: @json($headerImage),
            headerVideo: @json($headerVideo)
        };
        const $ = id => document.getElementById(id);
        const bgType = $('backgroundType'),
            headerType = $('headerType');
        const toggle = (sel, show) => document.querySelectorAll(sel).forEach(el => el.classList.toggle('d-none',
            !show));

        function toggleFields() {
            const b = bgType?.value || 'color';
            toggle('.background-color-field', b === 'color');
            toggle('.background-image-field', b === 'image');
            toggle('.background-video-field', b === 'video');
            const h = headerType?.value || 'none';
            toggle('.header-color-field', h === 'color');
            toggle('.header-image-field', h === 'image');
            toggle('.header-video-field', h === 'video')
        }

        function fileUrl(id, fallback) {
            const f = $(id)?.files?.[0];
            return f ? URL.createObjectURL(f) : fallback
        }

        function preview() {
            const frame = $('kioskPreview'),
                content = $('kioskContent'),
                pbtn = $('previewPrimaryButton'),
                sbtn = $('previewSecondaryButton'),
                head = $('kioskHeader');
            const text = $('text_color')?.value || '#111827',
                bg = $('background_color')?.value || '#fff',
                btn = $('button_color')?.value || '#00AEEF',
                btnText = $('button_text_color')?.value || '#fff',
                hover = $('button_hover_border_color')?.value || '#00AEEF';
            content.style.color = text;
            frame.style.background = bg;
            pbtn.style.background = btn;
            pbtn.style.color = btnText;
            pbtn.onmouseenter = () => pbtn.style.borderColor = hover;
            pbtn.onmouseleave = () => pbtn.style.borderColor = 'transparent';
            sbtn.style.background = 'rgba(255,255,255,.92)';
            sbtn.style.color = btn;
            sbtn.style.borderColor = btn;
            const bi = $('previewBgImage'),
                bv = $('previewBgVideo');
            bi.classList.add('d-none');
            bv.classList.add('d-none');
            if (bgType.value === 'image') {
                const u = fileUrl('backgroundImageInput', current.bgImage);
                if (u) {
                    bi.src = u;
                    bi.classList.remove('d-none')
                }
            }
            if (bgType.value === 'video') {
                const u = fileUrl('backgroundVideoInput', current.bgVideo);
                if (u) {
                    bv.src = u;
                    bv.classList.remove('d-none');
                    bv.play().catch(() => {})
                }
            }
            const hi = $('previewHeaderImage'),
                hv = $('previewHeaderVideo');
            hi.classList.add('d-none');
            hv.classList.add('d-none');
            const hc = $('header_background_color')?.value || '#1EB5F0';
            head.style.height = ($('header_height')?.value || 82) + 'px';
            head.style.background = headerType.value === 'none' ? 'transparent' : hc;
            if (headerType.value === 'image') {
                const u = fileUrl('headerBackgroundImageInput', current.headerImage);
                if (u) {
                    hi.src = u;
                    hi.classList.remove('d-none')
                }
            }
            if (headerType.value === 'video') {
                const u = fileUrl('headerBackgroundVideoInput', current.headerVideo);
                if (u) {
                    hv.src = u;
                    hv.classList.remove('d-none');
                    hv.play().catch(() => {})
                }
            }
            $('previewHomeButton').classList.toggle('d-none', !$('show_home_button')?.checked);
            $('previewHomeText').textContent = $('home_button_text')?.value?.trim() || 'หน้าหลัก'
        }

        function bindLogo(input, img, text) {
            $(input)?.addEventListener('change', () => {
                const f = $(input).files?.[0];
                if (!f) return;
                $(img).src = URL.createObjectURL(f);
                $(img).classList.remove('d-none');
                if (text) $(text).classList.add('d-none')
            })
        }
        document.querySelectorAll('.theme-color-picker').forEach(p => p.addEventListener('input', () => {
            const t = $(p.dataset.target);
            if (t) t.value = p.value;
            preview()
        }));
        ['text_color', 'background_color', 'button_color', 'button_text_color', 'button_hover_border_color',
            'header_background_color', 'header_height', 'home_button_text'
        ].forEach(id => $(id)?.addEventListener('input', preview));
        ['backgroundImageInput', 'backgroundVideoInput', 'headerBackgroundImageInput',
            'headerBackgroundVideoInput'
        ].forEach(id => $(id)?.addEventListener('change', preview));
        bgType?.addEventListener('change', () => {
            toggleFields();
            preview()
        });
        headerType?.addEventListener('change', () => {
            toggleFields();
            preview()
        });
        $('show_home_button')?.addEventListener('change', preview);
        function bindPngLogo(inputId, imageId) {
            const input = $(inputId);

            input?.addEventListener('change', () => {
                const file = input.files?.[0];

                if (!file) return;

                const isPng =
                    file.type === 'image/png'
                    || file.name.toLowerCase().endsWith('.png');

                if (!isPng) {
                    input.value = '';
                    alert('โลโก้ด้านขวารองรับเฉพาะไฟล์ PNG เท่านั้น');
                    return;
                }

                const image = $(imageId);

                if (image) {
                    image.src = URL.createObjectURL(file);
                    image.classList.remove('d-none');
                }
            });
        }

        bindLogo('headerLogoMainInput', 'previewLogoMain', 'previewLogoText');
        bindPngLogo('headerLogoRight1Input', 'previewLogoRight1');
        bindPngLogo('headerLogoRight2Input', 'previewLogoRight2');
        toggleFields();
        preview();
    });
</script>
