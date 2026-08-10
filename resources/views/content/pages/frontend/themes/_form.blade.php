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

    .kiosk-preview-wrap {
        width: 100%;
        display: flex;
        justify-content: center;
        overflow: hidden;
        padding: 4px 0 8px;
    }

    .kiosk-preview {
        position: relative;
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        aspect-ratio: 1920 / 1080;
        min-height: 0;
        border: 8px solid #20242b;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 14px 36px rgba(0, 0, 0, .14)
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
        height: 100%;
        min-height: 0;
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
        gap: 10px;
        padding: 8px 14px
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
        height: 52px;
        object-fit: contain
    }

    .kiosk-body {
        flex: 1;
        min-height: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,.16))
    }

    .kiosk-scene {
        position: relative;
        flex: 1;
        min-height: 0;
        padding: 14px 18px 0;
        display: grid;
        grid-template-rows: 1fr auto;
        gap: 10px
    }

    .kiosk-hero {
        position: relative;
        min-height: 0;
        display: grid;
        grid-template-columns: 1.15fr 1.1fr .8fr;
        gap: 14px;
        align-items: stretch
    }

    .kiosk-copy {
        padding: 12px 6px 0 6px;
        color: inherit
    }

    .kiosk-copy-headline {
        font-size: clamp(20px, 2.2vw, 38px);
        line-height: 1.05;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -.02em
    }

    .kiosk-copy-sub {
        font-size: clamp(14px, 1.35vw, 24px);
        line-height: 1.2;
        font-weight: 700;
        color: #c58a12
    }

    .kiosk-copy-sub .badge15 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin: 0 6px;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, #fff6d0, #f4c542 65%, #cd9516);
        color: #fff;
        font-size: 16px;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(197,138,18,.28)
    }

    .kiosk-stage {
        position: relative;
        min-height: 0;
        display: flex;
        align-items: flex-end;
        justify-content: center
    }

    .kiosk-stage::before {
        content: '';
        position: absolute;
        inset: 6% 12% 14%;
        background: linear-gradient(180deg, rgba(43,168,216,.16), rgba(43,168,216,.02));
        border-radius: 18px
    }

    .kiosk-splash {
        position: absolute;
        left: 5%;
        right: 18%;
        bottom: 10%;
        height: 44%;
        border-radius: 55% 45% 18% 80% / 60% 35% 65% 30%;
        background: linear-gradient(90deg, rgba(43,168,216,.50), rgba(57,196,240,.15));
        filter: blur(1px)
    }

    .kiosk-model {
        position: relative;
        width: 56%;
        height: 82%;
        border-radius: 40% 40% 26% 26% / 18% 18% 16% 16%;
        background: linear-gradient(180deg, #7fd4f7 0%, #43b8e9 48%, #84d7f6 100%);
        clip-path: polygon(46% 0%, 58% 0%, 65% 8%, 69% 18%, 78% 35%, 90% 60%, 77% 70%, 65% 100%, 35% 100%, 26% 74%, 18% 62%, 5% 46%, 22% 26%, 30% 12%, 38% 4%);
        box-shadow: 0 20px 30px rgba(0,0,0,.10)
    }

    .kiosk-products {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 12px;
        padding: 18px 6px 0 0
    }

    .kiosk-pack {
        width: 48%;
        min-width: 96px;
        border-radius: 16px;
        background: linear-gradient(180deg, #3daee5 0%, #1180c4 100%);
        border: 4px solid rgba(255,255,255,.65);
        box-shadow: 0 16px 24px rgba(0,0,0,.12);
        position: relative;
        padding: 14px 10px 10px;
        color: #fff;
        text-align: center
    }

    .kiosk-pack.softener {
        background: linear-gradient(180deg, #55bce8 0%, #146fb2 100%)
    }

    .kiosk-pack.large { height: 72%; }
    .kiosk-pack.small { height: 64%; }

    .kiosk-pack-brand {
        font-size: clamp(18px, 1.5vw, 28px);
        font-weight: 800;
        line-height: 1
    }

    .kiosk-pack-sub {
        font-size: 11px;
        opacity: .92;
        margin-bottom: 10px
    }

    .kiosk-pack-bubble {
        width: 62%;
        aspect-ratio: 1;
        margin: 6px auto 12px;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.95), rgba(255,255,255,.12) 65%);
        border: 3px solid rgba(255,255,255,.60)
    }

    .kiosk-pack-tag {
        position: absolute;
        left: 50%;
        bottom: -12px;
        transform: translateX(-50%);
        padding: 5px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(240,236,221,.95);
        color: #242424;
        box-shadow: 0 5px 10px rgba(0,0,0,.10)
    }

    .kiosk-footer {
        position: relative;
        min-height: 108px;
        padding: 14px 0 18px;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        overflow: hidden
    }

    .kiosk-footer::before,
    .kiosk-footer::after {
        content: '';
        position: absolute;
        left: -5%;
        right: -5%;
        border-radius: 50%;
        background: rgba(87,181,231,.26)
    }

    .kiosk-footer::before {
        top: 18px;
        height: 120px
    }

    .kiosk-footer::after {
        top: 34px;
        height: 120px;
        background: rgba(87,181,231,.38)
    }

    .kiosk-actions {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: center;
        gap: 0;
        flex-shrink: 0
    }

    .kiosk-btn {
        border: 2px solid transparent;
        border-radius: 999px;
        min-width: 280px;
        min-height: 54px;
        padding: 10px 28px;
        font-weight: 800;
        font-size: 18px;
        box-shadow: 0 8px 18px rgba(0,0,0,.14)
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

        .kiosk-preview {
            width: 100%;
            max-width: 820px;
            aspect-ratio: 1920 / 1080
        }

        .kiosk-hero {
            grid-template-columns: 1fr;
        }

        .kiosk-copy {
            text-align: center;
        }

        .kiosk-products {
            padding-top: 0;
        }

        .kiosk-btn {
            min-width: 240px;
            min-height: 52px;
            font-size: 16px;
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
                    <div class="col-md-6">
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

                    <div class="col-md-6">
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
                        <p class="text-muted mb-0">ดูตัวอย่าง Theme แบบภาพรวมตามหน้าจอตู้จริงให้ใกล้เคียงหน้าใช้งาน</p>
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
                                    <div class="d-flex align-items-center gap-1 flex-nowrap">
                                        <button type="button" id="previewHomeButton"
                                            class="btn btn-light btn-sm rounded-pill px-2 {{ old('show_home_button', $theme->show_home_button ?? true) ? '' : 'd-none' }}">
                                            <i class="icon-base ti tabler-home"></i>
                                            <span class="ms-1"
                                                id="previewHomeText">{{ old('home_button_text', $theme->home_button_text ?? 'หน้าหลัก') }}</span>
                                        </button>

                                        <div id="previewLanguageButtons" class="d-flex align-items-center gap-1 {{ old('show_language_selector', $theme->show_language_selector ?? true) ? '' : 'd-none' }}">
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-0"
                                                style="width:32px;height:32px;" title="ไทย">🇹🇭</button>
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-0"
                                                style="width:32px;height:32px;" title="English">🇬🇧</button>
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-0"
                                                style="width:32px;height:32px;" title="中文">🇨🇳</button>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div
                                            id="previewHeaderTitle"
                                            class="fw-bold"
                                            style="
                                                font-size: clamp(18px, 2vw, 30px);
                                                line-height: 1.15;
                                                color: {{ old('text_color', $theme->text_color ?? '#111827') }};
                                                text-shadow: 0 1px 2px rgba(255,255,255,.22);
                                            "
                                        >
                                            ผู้เชี่ยวชาญการดูแลผ้าครบวงจร
                                        </div>
                                    </div>
                                    <div class="kiosk-right"><img id="previewLogoRight1" src="{{ $logoRight1 }}"
                                            class="{{ $logoRight1 ? '' : 'd-none' }}"><img id="previewLogoRight2"
                                            src="{{ $logoRight2 }}" class="{{ $logoRight2 ? '' : 'd-none' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="kiosk-body">
                                <div class="kiosk-scene">
                                    <div class="kiosk-hero">
                                        <div class="kiosk-copy">
                                            <div class="kiosk-copy-headline">ดับเบิ้ลพลังซัก คืนชีวิตผ้า</div>
                                            <div class="kiosk-copy-sub">สะอาด หอมนนาน <span class="badge15">15</span> สัปดาห์</div>
                                        </div>

                                        <div class="kiosk-stage">
                                            <div class="kiosk-splash"></div>
                                            <div class="kiosk-model"></div>
                                        </div>

                                        <div class="kiosk-products">
                                            <div class="kiosk-pack large">
                                                <div class="kiosk-pack-brand">Hygiene</div>
                                                <div class="kiosk-pack-sub">Expert Care</div>
                                                <div class="kiosk-pack-bubble"></div>
                                                <div class="kiosk-pack-tag">ซักผ้า</div>
                                            </div>
                                            <div class="kiosk-pack softener small">
                                                <div class="kiosk-pack-brand">Hygiene</div>
                                                <div class="kiosk-pack-sub">Easy Care</div>
                                                <div class="kiosk-pack-bubble"></div>
                                                <div class="kiosk-pack-tag">ปรับผ้านุ่ม</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="kiosk-footer">
                                        <div class="kiosk-actions">
                                            <button type="button" id="previewPrimaryButton" class="kiosk-btn">
                                                <i class="icon-base ti tabler-bottle me-2"></i>
                                                เลือกเติมน้ำยา
                                                <i class="icon-base ti tabler-chevron-right ms-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
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

            const headerTitle = $('previewHeaderTitle');
            if (headerTitle) {
                headerTitle.style.color = text;
            }

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

        bindPngLogo('headerLogoRight1Input', 'previewLogoRight1');
        bindPngLogo('headerLogoRight2Input', 'previewLogoRight2');
        toggleFields();
        preview();
    });
</script>
