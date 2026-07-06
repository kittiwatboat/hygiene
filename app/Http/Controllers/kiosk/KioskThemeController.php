<?php

namespace App\Http\Controllers\kiosk;

use App\Http\Controllers\Controller;
use App\Models\KioskTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KioskThemeController extends Controller
{
    public function index()
    {
        $themes = KioskTheme::query()
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        return view('content.pages.kiosk.themes.index', compact('themes'));
    }

    public function create()
    {
        return view('content.pages.kiosk.themes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateTheme($request);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $this->uploadLogo($request->file('logo'));
        }
        $backgroundImagePath = null;
$backgroundVideoPath = null;

if ($request->hasFile('background_image')) {
    $backgroundImagePath = $this->uploadThemeImage(
        $request->file('background_image')
    );
}

if ($request->hasFile('background_video')) {
    $backgroundVideoPath = $this->uploadThemeVideo(
        $request->file('background_video')
    );
}

      $theme = KioskTheme::create([
    'name' => $validated['name'],
    'slug' => $validated['slug'] ?: Str::slug($validated['name']),

    'text_color' => $validated['text_color'],

    'background_type' => $validated['background_type'],
    'background_color' => $validated['background_color'] ?? '#FFFFFF',
    'background_image' => $backgroundImagePath,
    'background_video' => $backgroundVideoPath,

    'button_color' => $validated['button_color'],
    'button_text_color' => $validated['button_text_color'],
    'button_hover_border_color' => $validated['button_hover_border_color'],

    'is_default' => $request->boolean('is_default'),
    'is_active' => $request->boolean('is_active'),
    'remark' => $validated['remark'] ?? null,
]);

        if ($theme->is_default) {
            $this->clearOtherDefaultThemes($theme);
        }

        return redirect()
            ->route('kiosk.themes.index')
            ->with('success', 'เพิ่มธีมหน้าตู้สำเร็จ');
    }

    public function edit(KioskTheme $theme)
    {
        return view('content.pages.kiosk.themes.edit', compact('theme'));
    }

    public function update(Request $request, KioskTheme $theme)
    {
        $validated = $this->validateTheme($request, $theme);

        $logoPath = $theme->logo;

        if ($request->boolean('remove_logo') && !$request->hasFile('logo')) {
            $this->deleteLogo($theme->logo);
            $logoPath = null;
        }

        if ($request->hasFile('logo')) {
            $newLogo = $this->uploadLogo($request->file('logo'));
            $this->deleteLogo($theme->logo);
            $logoPath = $newLogo;
        }

        $backgroundImagePath = $theme->background_image;
$backgroundVideoPath = $theme->background_video;

if (
    $request->boolean('remove_background_image') &&
    !$request->hasFile('background_image')
) {
    $this->deleteThemeImage($theme->background_image);
    $backgroundImagePath = null;
}

if (
    $request->boolean('remove_background_video') &&
    !$request->hasFile('background_video')
) {
    $this->deleteThemeVideo($theme->background_video);
    $backgroundVideoPath = null;
}

if ($request->hasFile('background_image')) {
    $newImage = $this->uploadThemeImage(
        $request->file('background_image')
    );

    $this->deleteThemeImage($theme->background_image);
    $backgroundImagePath = $newImage;
}

if ($request->hasFile('background_video')) {
    $newVideo = $this->uploadThemeVideo(
        $request->file('background_video')
    );

    $this->deleteThemeVideo($theme->background_video);
    $backgroundVideoPath = $newVideo;
}

$theme->update([
    'name' => $validated['name'],
    'slug' => $validated['slug'] ?: Str::slug($validated['name']),

    'text_color' => $validated['text_color'],

    'background_type' => $validated['background_type'],
    'background_color' => $validated['background_color'] ?? '#FFFFFF',
    'background_image' => $backgroundImagePath,
    'background_video' => $backgroundVideoPath,

    'button_color' => $validated['button_color'],
    'button_text_color' => $validated['button_text_color'],
    'button_hover_border_color' => $validated['button_hover_border_color'],

    'is_default' => $request->boolean('is_default'),
    'is_active' => $request->boolean('is_active'),
    'remark' => $validated['remark'] ?? null,
]);
        if ($theme->is_default) {
            $this->clearOtherDefaultThemes($theme);
        }

        return redirect()
            ->route('kiosk.themes.index')
            ->with('success', 'แก้ไขธีมหน้าตู้สำเร็จ');
    }

    public function destroy(KioskTheme $theme)
    {
        if ($theme->is_default) {
            return back()->with('error', 'ไม่สามารถลบธีมเริ่มต้นได้');
        }

        if ($theme->layouts()->exists() || $theme->screens()->exists()) {
            return back()->with(
                'error',
                'ไม่สามารถลบธีมนี้ได้ เนื่องจากมี Layout หรือหน้าจอใช้งานอยู่'
            );
        }

        $this->deleteLogo($theme->logo);
        $theme->delete();

        return redirect()
            ->route('kiosk.themes.index')
            ->with('success', 'ลบธีมหน้าตู้สำเร็จ');
    }

private function validateTheme(Request $request, ?KioskTheme $theme = null): array
{
    return $request->validate(
        [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('kiosk_themes', 'slug')->ignore($theme?->id),
            ],

            /*
            |--------------------------------------------------------------------------
            | Text
            |--------------------------------------------------------------------------
            */
            'text_color' => [
                'required',
                'string',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------------------
            | Background
            |--------------------------------------------------------------------------
            */
            'background_type' => [
                'required',
                Rule::in(['color', 'image', 'video']),
            ],
            'background_color' => [
                'nullable',
                'string',
                'max:50',
                'required_if:background_type,color',
            ],
            'background_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,svg',
                'max:5120',
            ],
            'background_video' => [
                'nullable',
                'file',
                'mimes:mp4,webm,mov',
                'max:51200',
            ],
            'remove_background_image' => [
                'nullable',
                'boolean',
            ],
            'remove_background_video' => [
                'nullable',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Button
            |--------------------------------------------------------------------------
            */
            'button_color' => [
                'required',
                'string',
                'max:50',
            ],
            'button_text_color' => [
                'required',
                'string',
                'max:50',
            ],
            'button_hover_border_color' => [
                'required',
                'string',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            'is_default' => [
                'nullable',
                'boolean',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'remark' => [
                'nullable',
                'string',
            ],
        ],
        [
            'name.required' => 'กรุณากรอกชื่อธีม',
            'slug.unique' => 'Slug นี้ถูกใช้งานแล้ว',

            'text_color.required' => 'กรุณาเลือกสีตัวอักษร',

            'background_type.required' => 'กรุณาเลือกประเภทพื้นหลัง',
            'background_type.in' => 'ประเภทพื้นหลังไม่ถูกต้อง',
            'background_color.required_if' => 'กรุณาเลือกสีพื้นหลัง',

            'background_image.image' => 'ไฟล์พื้นหลังต้องเป็นรูปภาพ',
            'background_image.mimes' => 'รูปพื้นหลังรองรับเฉพาะ JPG, JPEG, PNG, WEBP และ SVG',
            'background_image.max' => 'รูปพื้นหลังต้องมีขนาดไม่เกิน 5 MB',

            'background_video.file' => 'ไฟล์วิดีโอพื้นหลังไม่ถูกต้อง',
            'background_video.mimes' => 'วิดีโอพื้นหลังรองรับเฉพาะ MP4, WEBM และ MOV',
            'background_video.max' => 'วิดีโอพื้นหลังต้องมีขนาดไม่เกิน 50 MB',

            'button_color.required' => 'กรุณาเลือกสีปุ่ม',
            'button_text_color.required' => 'กรุณาเลือกสีตัวอักษรปุ่ม',
            'button_hover_border_color.required' => 'กรุณาเลือกสีเส้นตอน Hover',
        ]
    );
}

    private function clearOtherDefaultThemes(KioskTheme $theme): void
    {
        KioskTheme::where('id', '!=', $theme->id)
            ->update([
                'is_default' => false,
            ]);
    }

    private function uploadLogo($image): string
    {
        $uploadPath = base_path('../public_html/assets/img/kiosk/themes');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid('theme_logo_', true)
            . '.'
            . strtolower($image->getClientOriginalExtension());

        $image->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deleteLogo(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $filePath = base_path('../public_html/assets/img/kiosk/themes/' . $fileName);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    private function uploadThemeImage($image): string
{
    $uploadPath = base_path('../public_html/assets/img/kiosk/themes');

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $fileName = uniqid('theme_bg_', true)
        . '.'
        . strtolower($image->getClientOriginalExtension());

    $image->move($uploadPath, $fileName);

    return $fileName;
}

private function uploadThemeVideo($video): string
{
    $uploadPath = base_path('../public_html/assets/videos/kiosk/themes');

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $fileName = uniqid('theme_bg_video_', true)
        . '.'
        . strtolower($video->getClientOriginalExtension());

    $video->move($uploadPath, $fileName);

    return $fileName;
}

private function deleteThemeImage(?string $fileName): void
{
    if (!$fileName) {
        return;
    }

    $filePath = base_path('../public_html/assets/img/kiosk/themes/' . $fileName);

    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

private function deleteThemeVideo(?string $fileName): void
{
    if (!$fileName) {
        return;
    }

    $filePath = base_path('../public_html/assets/videos/kiosk/themes/' . $fileName);

    if (file_exists($filePath)) {
        unlink($filePath);
    }
}
}
