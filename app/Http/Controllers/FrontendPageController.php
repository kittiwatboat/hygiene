<?php

namespace App\Http\Controllers;

use App\Models\FrontendPage;
use App\Models\FrontendPageMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FrontendPageController extends Controller
{
    public function index()
    {
        $pages = FrontendPage::withCount('media')
            ->orderBy('id')
            ->get();

        return view('content.pages.frontend.page.index', compact('pages'));
    }

    public function edit(FrontendPage $page)
    {
        $page->load([
        'media' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        },
    ]);
        return view('content.pages.frontend.page.edit', compact('page'));
    }

    public function update(Request $request, FrontendPage $page)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'title' => ['nullable', 'string', 'max:255'],
        'subtitle' => ['nullable', 'string', 'max:255'],
        'is_active' => ['nullable', 'boolean'],
        'remark' => ['nullable', 'string'],

        /*
        |--------------------------------------------------------------------------
        | เฉพาะหน้าเลือกภาษา
        |--------------------------------------------------------------------------
        */
        'language_button_shape' => [
            'nullable',
            'in:circle,rounded-square,square',
        ],
        'language_button_style' => [
            'nullable',
            'in:icon_top_text_bottom,icon_only,text_only',
        ],
        'language_button_size' => [
            'nullable',
            'in:small,medium,large',
        ],
        'show_button_border' => ['nullable', 'boolean'],
        'show_button_shadow' => ['nullable', 'boolean'],

        'show_home_button' => ['nullable', 'boolean'],
        'show_phone_button' => ['nullable', 'boolean'],
        'show_skip_button' => ['nullable', 'boolean'],

        'home_button_text' => ['nullable', 'string', 'max:100'],
        'phone_button_text' => ['nullable', 'string', 'max:100'],
        'skip_button_text' => ['nullable', 'string', 'max:100'],

        'home_button_action' => ['nullable', 'string', 'max:100'],
        'phone_button_action' => ['nullable', 'string', 'max:100'],
        'skip_button_action' => ['nullable', 'string', 'max:100'],
    ]);

    $settings = $page->settings_json ?? [];

    /*
    |--------------------------------------------------------------------------
    | ตั้งค่าเฉพาะหน้าเลือกภาษา
    |--------------------------------------------------------------------------
    */
    $settings = $page->settings_json ?? [];

switch ($page->page_key) {
    case 'first_page':
        $settings = array_merge($settings, [
            'show_start_button' => $request->boolean('show_start_button'),
            'start_button_text' => $request->input('start_button_text', 'เลือกเติมน้ำยา'),
            'start_button_action' => $request->input('start_button_action', 'language_page'),
        ]);
        break;

    case 'language_page':
        $settings = array_merge($settings, [
            'language_button_shape' => $request->input('language_button_shape', 'circle'),
            'language_button_style' => $request->input('language_button_style', 'icon_top_text_bottom'),
            'language_button_size' => $request->input('language_button_size', 'medium'),
            'show_button_border' => $request->boolean('show_button_border'),
            'show_button_shadow' => $request->boolean('show_button_shadow'),
        ]);
        break;
}

    $page->update([
        'name' => $validated['name'],
        'title' => $validated['title'] ?? null,
        'subtitle' => $validated['subtitle'] ?? null,
        'settings_json' => $settings,
        'is_active' => $request->boolean('is_active'),
        'remark' => $validated['remark'] ?? null,
    ]);

    return redirect()
        ->route('frontend.pages.edit', $page)
        ->with('success', 'บันทึกข้อมูลหน้าสำเร็จ');
}
    public function storeMedia(Request $request, FrontendPage $page)
    {
        $validated = $request->validate(
            [
                'media_type' => [
                    'required',
                    Rule::in(['image', 'video']),
                ],
                'file' => [
                    'required',
                    'file',
                    'max:51200',
                ],
                'title' => ['nullable', 'string', 'max:255'],
                'subtitle' => ['nullable', 'string', 'max:255'],
                'duration_seconds' => ['nullable', 'integer', 'min:1'],
                'object_fit' => [
                    'required',
                    Rule::in(['cover', 'contain']),
                ],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['nullable', 'boolean'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'media_type.required' => 'กรุณาเลือกประเภทไฟล์',
                'file.required' => 'กรุณาเลือกไฟล์',
                'file.max' => 'ไฟล์ต้องมีขนาดไม่เกิน 50 MB',
            ]
        );

        if ($validated['media_type'] === 'image') {
            $request->validate([
                'file' => ['image', 'mimes:jpg,jpeg,png,webp,svg'],
            ]);
        }

        if ($validated['media_type'] === 'video') {
            $request->validate([
                'file' => ['mimes:mp4,webm,mov'],
            ]);
        }

        $fileName = $this->uploadMediaFile(
            $request->file('file'),
            $validated['media_type']
        );

        FrontendPageMedia::create([
            'frontend_page_id' => $page->id,
            'media_type' => $validated['media_type'],
            'file_path' => $fileName,
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'duration_seconds' => $validated['duration_seconds'] ?? 5,
            'object_fit' => $validated['object_fit'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('frontend.pages.edit', $page)
            ->with('success', 'เพิ่มสไลด์สำเร็จ');
    }

    public function updateMedia(Request $request, FrontendPageMedia $media)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'duration_seconds' => ['nullable', 'integer', 'min:1'],
            'object_fit' => [
                'required',
                Rule::in(['cover', 'contain']),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'remark' => ['nullable', 'string'],
        ]);

        $media->update([
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'duration_seconds' => $validated['duration_seconds'] ?? 5,
            'object_fit' => $validated['object_fit'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('frontend.pages.edit', $media->page)
            ->with('success', 'แก้ไขสไลด์สำเร็จ');
    }

    public function destroyMedia(FrontendPageMedia $media)
    {
        $page = $media->page;

        $this->deleteMediaFile(
            $media->file_path,
            $media->media_type
        );

        $media->delete();

        return redirect()
            ->route('frontend.pages.edit', $page)
            ->with('success', 'ลบสไลด์สำเร็จ');
    }

    private function uploadMediaFile($file, string $mediaType): string
    {
        if ($mediaType === 'video') {
            $uploadPath = base_path('../public_html/assets/videos/frontend/pages');
            $prefix = 'frontend_video_';
        } else {
            $uploadPath = base_path('../public_html/assets/img/frontend/pages');
            $prefix = 'frontend_image_';
        }

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid($prefix, true)
            . '.'
            . strtolower($file->getClientOriginalExtension());

        $file->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deleteMediaFile(?string $fileName, ?string $mediaType): void
    {
        if (!$fileName || !$mediaType) {
            return;
        }

        $basePath = $mediaType === 'video'
            ? base_path('../public_html/assets/videos/frontend/pages')
            : base_path('../public_html/assets/img/frontend/pages');

        $filePath = $basePath . '/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
