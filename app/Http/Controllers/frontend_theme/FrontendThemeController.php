<?php

namespace App\Http\Controllers\frontend_theme;

use App\Http\Controllers\Controller;
use App\Models\FrontendTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FrontendThemeController extends Controller
{
    public function index()
    {
        $themes = FrontendTheme::query()
            ->orderByDesc('is_default')
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        return view(
            'content.pages.frontend.themes.index',
            compact('themes')
        );
    }

    public function create()
    {
        return view('content.pages.frontend.themes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateTheme($request);

        $logoPath = null;
        $backgroundImagePath = null;
        $backgroundVideoPath = null;
        $headerBackgroundImagePath = null;
        $headerBackgroundVideoPath = null;
        $headerLogoMainPath = null;
        $headerLogoRight1Path = null;
        $headerLogoRight2Path = null;

        if ($request->hasFile('logo')) {
            $logoPath = $this->uploadLogo(
                $request->file('logo')
            );
        }

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

        if ($request->hasFile('header_background_image')) {
            $headerBackgroundImagePath = $this->uploadThemeImage(
                $request->file('header_background_image')
            );
        }

        if ($request->hasFile('header_background_video')) {
            $headerBackgroundVideoPath = $this->uploadThemeVideo(
                $request->file('header_background_video')
            );
        }

        if ($request->hasFile('header_logo_main')) {
            $headerLogoMainPath = $this->uploadThemeImage(
                $request->file('header_logo_main')
            );
        }

        if ($request->hasFile('header_logo_right_1')) {
            $headerLogoRight1Path = $this->uploadThemeImage(
                $request->file('header_logo_right_1')
            );
        }

        if ($request->hasFile('header_logo_right_2')) {
            $headerLogoRight2Path = $this->uploadThemeImage(
                $request->file('header_logo_right_2')
            );
        }

        $isDefault = $request->boolean('is_default');
        $isActive = $request->boolean('is_active');

        /*
        |--------------------------------------------------------------------------
        | Theme ที่เลือกให้ใช้งาน ต้องเปิดใช้งานเสมอ
        |--------------------------------------------------------------------------
        */
        if ($isDefault) {
            $isActive = true;
        }

        $theme = DB::transaction(function () use (
            $validated,
            $logoPath,
            $backgroundImagePath,
            $backgroundVideoPath,
            $headerBackgroundImagePath,
            $headerBackgroundVideoPath,
            $headerLogoMainPath,
            $headerLogoRight1Path,
            $headerLogoRight2Path,
            $isDefault,
            $isActive,
            $request
        ) {
            if ($isDefault) {
                FrontendTheme::query()->update([
                    'is_default' => false,
                ]);
            }

            return FrontendTheme::create([
                'name' => $validated['name'],
                'slug' => $validated['slug']
                    ?: Str::slug($validated['name']),

                'logo' => $logoPath,

                'text_color' => $validated['text_color'],

                'background_type' =>
                    $validated['background_type'],

                'background_color' =>
                    $validated['background_color']
                    ?? '#FFFFFF',

                'background_image' => $backgroundImagePath,
                'background_video' => $backgroundVideoPath,

                'header_type' => $validated['header_type'] ?? 'none',
                'header_height' => $validated['header_height'] ?? 82,
                'header_background_color' =>
                    $validated['header_background_color'] ?? '#1EB5F0',
                'header_background_image' => $headerBackgroundImagePath,
                'header_background_video' => $headerBackgroundVideoPath,
                'header_logo_main' => $headerLogoMainPath,
                'header_logo_right_1' => $headerLogoRight1Path,
                'header_logo_right_2' => $headerLogoRight2Path,

                'show_home_button' => $request->boolean('show_home_button'),
                'home_button_text' =>
                    $validated['home_button_text'] ?? 'หน้าหลัก',

                'button_color' =>
                    $validated['button_color'],

                'button_text_color' =>
                    $validated['button_text_color'],

                'button_hover_border_color' =>
                    $validated['button_hover_border_color'],

                'is_default' => $isDefault,
                'is_active' => $isActive,

                'remark' => $validated['remark'] ?? null,
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | ถ้ายังไม่มี Theme ที่ใช้งาน ให้ Theme แรกเป็น Theme ปัจจุบัน
        |--------------------------------------------------------------------------
        */
        if (
            !FrontendTheme::query()
                ->where('is_default', true)
                ->exists()
        ) {
            $theme->update([
                'is_default' => true,
                'is_active' => true,
            ]);
        }

        return redirect()
            ->route('frontend.themes.index')
            ->with('success', 'เพิ่มธีมหน้าตู้สำเร็จ');
    }

    public function edit(FrontendTheme $theme)
    {
        return view(
            'content.pages.frontend.themes.edit',
            compact('theme')
        );
    }

    public function update(
        Request $request,
        FrontendTheme $theme
    ) {
        $validated = $this->validateTheme(
            $request,
            $theme
        );

        $isDefault = $request->boolean('is_default');
        $isActive = $request->boolean('is_active');

        /*
        |--------------------------------------------------------------------------
        | ป้องกันการปิด Theme ที่หน้าตู้กำลังใช้งาน
        |--------------------------------------------------------------------------
        */
        if ($theme->is_default && !$isActive) {
            return back()
                ->withInput()
                ->withErrors([
                    'is_active' =>
                        'Theme นี้กำลังใช้งานอยู่ กรุณาเลือก Theme อื่นก่อนปิดใช้งาน',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Theme ที่กำหนดให้ใช้งาน ต้องเปิดใช้งานเสมอ
        |--------------------------------------------------------------------------
        */
        if ($isDefault) {
            $isActive = true;
        }

        $logoPath = $theme->logo;

        if (
            $request->boolean('remove_logo')
            && !$request->hasFile('logo')
        ) {
            $this->deleteLogo($theme->logo);
            $logoPath = null;
        }

        if ($request->hasFile('logo')) {
            $newLogo = $this->uploadLogo(
                $request->file('logo')
            );

            $this->deleteLogo($theme->logo);
            $logoPath = $newLogo;
        }

        $backgroundImagePath = $theme->background_image;
        $backgroundVideoPath = $theme->background_video;
        $headerBackgroundImagePath = $theme->header_background_image;
        $headerBackgroundVideoPath = $theme->header_background_video;
        $headerLogoMainPath = $theme->header_logo_main;
        $headerLogoRight1Path = $theme->header_logo_right_1;
        $headerLogoRight2Path = $theme->header_logo_right_2;

        if (
            $request->boolean('remove_background_image')
            && !$request->hasFile('background_image')
        ) {
            $this->deleteThemeImage(
                $theme->background_image
            );

            $backgroundImagePath = null;
        }

        if (
            $request->boolean('remove_background_video')
            && !$request->hasFile('background_video')
        ) {
            $this->deleteThemeVideo(
                $theme->background_video
            );

            $backgroundVideoPath = null;
        }

        if ($request->hasFile('background_image')) {
            $newImage = $this->uploadThemeImage(
                $request->file('background_image')
            );

            $this->deleteThemeImage(
                $theme->background_image
            );

            $backgroundImagePath = $newImage;
        }

        if ($request->hasFile('background_video')) {
            $newVideo = $this->uploadThemeVideo(
                $request->file('background_video')
            );

            $this->deleteThemeVideo(
                $theme->background_video
            );

            $backgroundVideoPath = $newVideo;
        }

        if ($request->boolean('remove_header_background_image') && !$request->hasFile('header_background_image')) {
            $this->deleteThemeImage($theme->header_background_image);
            $headerBackgroundImagePath = null;
        }

        if ($request->boolean('remove_header_background_video') && !$request->hasFile('header_background_video')) {
            $this->deleteThemeVideo($theme->header_background_video);
            $headerBackgroundVideoPath = null;
        }

        if ($request->boolean('remove_header_logo_main') && !$request->hasFile('header_logo_main')) {
            $this->deleteThemeImage($theme->header_logo_main);
            $headerLogoMainPath = null;
        }

        if ($request->boolean('remove_header_logo_right_1') && !$request->hasFile('header_logo_right_1')) {
            $this->deleteThemeImage($theme->header_logo_right_1);
            $headerLogoRight1Path = null;
        }

        if ($request->boolean('remove_header_logo_right_2') && !$request->hasFile('header_logo_right_2')) {
            $this->deleteThemeImage($theme->header_logo_right_2);
            $headerLogoRight2Path = null;
        }

        if ($request->hasFile('header_background_image')) {
            $newHeaderImage = $this->uploadThemeImage($request->file('header_background_image'));
            $this->deleteThemeImage($theme->header_background_image);
            $headerBackgroundImagePath = $newHeaderImage;
        }

        if ($request->hasFile('header_background_video')) {
            $newHeaderVideo = $this->uploadThemeVideo($request->file('header_background_video'));
            $this->deleteThemeVideo($theme->header_background_video);
            $headerBackgroundVideoPath = $newHeaderVideo;
        }

        if ($request->hasFile('header_logo_main')) {
            $newLogo = $this->uploadThemeImage($request->file('header_logo_main'));
            $this->deleteThemeImage($theme->header_logo_main);
            $headerLogoMainPath = $newLogo;
        }

        if ($request->hasFile('header_logo_right_1')) {
            $newLogo = $this->uploadThemeImage($request->file('header_logo_right_1'));
            $this->deleteThemeImage($theme->header_logo_right_1);
            $headerLogoRight1Path = $newLogo;
        }

        if ($request->hasFile('header_logo_right_2')) {
            $newLogo = $this->uploadThemeImage($request->file('header_logo_right_2'));
            $this->deleteThemeImage($theme->header_logo_right_2);
            $headerLogoRight2Path = $newLogo;
        }

        DB::transaction(function () use (
            $validated,
            $theme,
            $logoPath,
            $backgroundImagePath,
            $backgroundVideoPath,
            $headerBackgroundImagePath,
            $headerBackgroundVideoPath,
            $headerLogoMainPath,
            $headerLogoRight1Path,
            $headerLogoRight2Path,
            $isDefault,
            $isActive,
            $request
        ) {
            if ($isDefault) {
                FrontendTheme::query()
                    ->where('id', '!=', $theme->id)
                    ->update([
                        'is_default' => false,
                    ]);
            }

            $theme->update([
                'name' => $validated['name'],

                'slug' => $validated['slug']
                    ?: Str::slug($validated['name']),

                'logo' => $logoPath,

                'text_color' =>
                    $validated['text_color'],

                'background_type' =>
                    $validated['background_type'],

                'background_color' =>
                    $validated['background_color']
                    ?? '#FFFFFF',

                'background_image' =>
                    $backgroundImagePath,

                'background_video' =>
                    $backgroundVideoPath,

                'header_type' =>
                    $validated['header_type'] ?? 'none',

                'header_height' =>
                    $validated['header_height'] ?? 82,

                'header_background_color' =>
                    $validated['header_background_color'] ?? '#1EB5F0',

                'header_background_image' =>
                    $headerBackgroundImagePath,

                'header_background_video' =>
                    $headerBackgroundVideoPath,

                'header_logo_main' =>
                    $headerLogoMainPath,

                'header_logo_right_1' =>
                    $headerLogoRight1Path,

                'header_logo_right_2' =>
                    $headerLogoRight2Path,

                'show_home_button' =>
                    $request->boolean('show_home_button'),

                'home_button_text' =>
                    $validated['home_button_text'] ?? 'หน้าหลัก',

                'button_color' =>
                    $validated['button_color'],

                'button_text_color' =>
                    $validated['button_text_color'],

                'button_hover_border_color' =>
                    $validated[
                        'button_hover_border_color'
                    ],

                'is_default' => $isDefault,
                'is_active' => $isActive,

                'remark' =>
                    $validated['remark']
                    ?? null,
            ]);
        });

        return redirect()
            ->route('frontend.themes.index')
            ->with('success', 'แก้ไขธีมหน้าตู้สำเร็จ');
    }

    /**
     * เลือก Theme ที่หน้าตู้จะใช้งาน
     */
    public function activate(FrontendTheme $theme)
    {
        if (!$theme->is_active) {
            return redirect()
                ->route('frontend.themes.index')
                ->with(
                    'error',
                    'ไม่สามารถเลือก Theme ที่ปิดใช้งานได้'
                );
        }

        DB::transaction(function () use ($theme) {
            FrontendTheme::query()
                ->where('id', '!=', $theme->id)
                ->update([
                    'is_default' => false,
                ]);

            $theme->update([
                'is_active' => true,
                'is_default' => true,
            ]);
        });

        return redirect()
            ->route('frontend.themes.index')
            ->with(
                'success',
                "เลือก Theme {$theme->name} สำหรับหน้าตู้แล้ว"
            );
    }

    public function destroy(FrontendTheme $theme)
    {
        if ($theme->is_default) {
            return back()->with(
                'error',
                'ไม่สามารถลบ Theme ที่กำลังใช้งานอยู่ได้'
            );
        }

        $this->deleteLogo($theme->logo);
        $this->deleteThemeImage($theme->background_image);
        $this->deleteThemeVideo($theme->background_video);
        $this->deleteThemeImage($theme->header_background_image);
        $this->deleteThemeVideo($theme->header_background_video);
        $this->deleteThemeImage($theme->header_logo_main);
        $this->deleteThemeImage($theme->header_logo_right_1);
        $this->deleteThemeImage($theme->header_logo_right_2);

        $theme->delete();

        return redirect()
            ->route('frontend.themes.index')
            ->with('success', 'ลบธีมหน้าตู้สำเร็จ');
    }

    private function validateTheme(
        Request $request,
        ?FrontendTheme $theme = null
    ): array {
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
                    Rule::unique(
                        'frontend_themes',
                        'slug'
                    )->ignore($theme?->id),
                ],

                'logo' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp,svg',
                    'max:4096',
                ],

                'remove_logo' => [
                    'nullable',
                    'boolean',
                ],

                'text_color' => [
                    'required',
                    'string',
                    'max:50',
                ],

                'background_type' => [
                    'required',
                    Rule::in([
                        'color',
                        'image',
                        'video',
                    ]),
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

                'show_home_button' => [
                    'nullable',
                    'boolean',
                ],

                'home_button_text' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

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

                'header_type' => [
                    'nullable',
                    Rule::in([
                        'none',
                        'color',
                        'image',
                        'video',
                    ]),
                ],

                'header_background_color' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'header_background_image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp,svg',
                    'max:5120',
                ],

                'header_background_video' => [
                    'nullable',
                    'file',
                    'mimes:mp4,webm,mov',
                    'max:51200',
                ],

                'header_logo_main' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp,svg',
                    'max:4096',
                ],

                'header_logo_right_1' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp,svg',
                    'max:4096',
                ],

                'header_logo_right_2' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp,svg',
                    'max:4096',
                ],

                'remove_header_background_image' => [
                    'nullable',
                    'boolean',
                ],

                'remove_header_background_video' => [
                    'nullable',
                    'boolean',
                ],

                'remove_header_logo_main' => [
                    'nullable',
                    'boolean',
                ],

                'remove_header_logo_right_1' => [
                    'nullable',
                    'boolean',
                ],

                'remove_header_logo_right_2' => [
                    'nullable',
                    'boolean',
                ],

                'header_height' => [
                    'nullable',
                    'integer',
                    'min:40',
                    'max:300',
                ],
            ],
            [
                'name.required' =>
                    'กรุณากรอกชื่อธีม',

                'slug.unique' =>
                    'Slug นี้ถูกใช้งานแล้ว',

                'text_color.required' =>
                    'กรุณาเลือกสีตัวอักษร',

                'background_type.required' =>
                    'กรุณาเลือกประเภทพื้นหลัง',

                'background_type.in' =>
                    'ประเภทพื้นหลังไม่ถูกต้อง',

                'background_color.required_if' =>
                    'กรุณาเลือกสีพื้นหลัง',

                'background_image.image' =>
                    'ไฟล์พื้นหลังต้องเป็นรูปภาพ',

                'background_image.mimes' =>
                    'รูปพื้นหลังรองรับเฉพาะ JPG, JPEG, PNG, WEBP และ SVG',

                'background_image.max' =>
                    'รูปพื้นหลังต้องมีขนาดไม่เกิน 5 MB',

                'background_video.file' =>
                    'ไฟล์วิดีโอพื้นหลังไม่ถูกต้อง',

                'background_video.mimes' =>
                    'วิดีโอพื้นหลังรองรับเฉพาะ MP4, WEBM และ MOV',

                'background_video.max' =>
                    'วิดีโอพื้นหลังต้องมีขนาดไม่เกิน 50 MB',

                'button_color.required' =>
                    'กรุณาเลือกสีปุ่ม',

                'button_text_color.required' =>
                    'กรุณาเลือกสีตัวอักษรปุ่ม',

                'button_hover_border_color.required' =>
                    'กรุณาเลือกสีเส้นตอน Hover',
            ]
        );
    }

    private function uploadLogo($image): string
    {
        $uploadPath = base_path(
            '../public_html/assets/img/frontend/themes'
        );

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid(
            'theme_logo_',
            true
        ) . '.' . strtolower(
            $image->getClientOriginalExtension()
        );

        $image->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deleteLogo(
        ?string $fileName
    ): void {
        if (!$fileName) {
            return;
        }

        $filePath = base_path(
            '../public_html/assets/img/frontend/themes/'
            . $fileName
        );

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function uploadThemeImage($image): string
    {
        $uploadPath = base_path(
            '../public_html/assets/img/frontend/themes'
        );

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid(
            'theme_bg_',
            true
        ) . '.' . strtolower(
            $image->getClientOriginalExtension()
        );

        $image->move($uploadPath, $fileName);

        return $fileName;
    }

    private function uploadThemeVideo($video): string
    {
        $uploadPath = base_path(
            '../public_html/assets/videos/frontend/themes'
        );

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid(
            'theme_bg_video_',
            true
        ) . '.' . strtolower(
            $video->getClientOriginalExtension()
        );

        $video->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deleteThemeImage(
        ?string $fileName
    ): void {
        if (!$fileName) {
            return;
        }

        $filePath = base_path(
            '../public_html/assets/img/frontend/themes/'
            . $fileName
        );

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function deleteThemeVideo(
        ?string $fileName
    ): void {
        if (!$fileName) {
            return;
        }

        $filePath = base_path(
            '../public_html/assets/videos/frontend/themes/'
            . $fileName
        );

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
