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

        $theme = KioskTheme::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),

            'primary_color' => $validated['primary_color'],
            'secondary_color' => $validated['secondary_color'],
            'accent_color' => $validated['accent_color'],

            'background_color' => $validated['background_color'],
            'text_color' => $validated['text_color'],
            'muted_text_color' => $validated['muted_text_color'],

            'button_background_color' => $validated['button_background_color'],
            'button_text_color' => $validated['button_text_color'],
            'button_border_color' => $validated['button_border_color'] ?? null,
            'button_hover_background_color' => $validated['button_hover_background_color'] ?? null,
            'button_hover_text_color' => $validated['button_hover_text_color'] ?? null,

            'card_background_color' => $validated['card_background_color'],
            'card_text_color' => $validated['card_text_color'],
            'card_border_color' => $validated['card_border_color'] ?? null,

            'success_color' => $validated['success_color'],
            'warning_color' => $validated['warning_color'],
            'danger_color' => $validated['danger_color'],
            'info_color' => $validated['info_color'],

            'font_family' => $validated['font_family'] ?? null,

            'button_radius' => $validated['button_radius'] ?? 24,
            'card_radius' => $validated['card_radius'] ?? 28,
            'input_radius' => $validated['input_radius'] ?? 16,

            'logo' => $logoPath,

            'settings_json' => [
                'overlay_color' => $validated['overlay_color'] ?? null,
                'shadow' => $validated['shadow'] ?? null,
                'disabled_color' => $validated['disabled_color'] ?? null,
            ],

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

        $theme->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),

            'primary_color' => $validated['primary_color'],
            'secondary_color' => $validated['secondary_color'],
            'accent_color' => $validated['accent_color'],

            'background_color' => $validated['background_color'],
            'text_color' => $validated['text_color'],
            'muted_text_color' => $validated['muted_text_color'],

            'button_background_color' => $validated['button_background_color'],
            'button_text_color' => $validated['button_text_color'],
            'button_border_color' => $validated['button_border_color'] ?? null,
            'button_hover_background_color' => $validated['button_hover_background_color'] ?? null,
            'button_hover_text_color' => $validated['button_hover_text_color'] ?? null,

            'card_background_color' => $validated['card_background_color'],
            'card_text_color' => $validated['card_text_color'],
            'card_border_color' => $validated['card_border_color'] ?? null,

            'success_color' => $validated['success_color'],
            'warning_color' => $validated['warning_color'],
            'danger_color' => $validated['danger_color'],
            'info_color' => $validated['info_color'],

            'font_family' => $validated['font_family'] ?? null,

            'button_radius' => $validated['button_radius'] ?? 24,
            'card_radius' => $validated['card_radius'] ?? 28,
            'input_radius' => $validated['input_radius'] ?? 16,

            'logo' => $logoPath,

            'settings_json' => [
                'overlay_color' => $validated['overlay_color'] ?? null,
                'shadow' => $validated['shadow'] ?? null,
                'disabled_color' => $validated['disabled_color'] ?? null,
            ],

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
                'name' => ['required', 'string', 'max:255'],
                'slug' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('kiosk_themes', 'slug')->ignore($theme?->id),
                ],

                'primary_color' => ['required', 'string', 'max:50'],
                'secondary_color' => ['required', 'string', 'max:50'],
                'accent_color' => ['required', 'string', 'max:50'],

                'background_color' => ['required', 'string', 'max:50'],
                'text_color' => ['required', 'string', 'max:50'],
                'muted_text_color' => ['required', 'string', 'max:50'],

                'button_background_color' => ['required', 'string', 'max:50'],
                'button_text_color' => ['required', 'string', 'max:50'],
                'button_border_color' => ['nullable', 'string', 'max:50'],
                'button_hover_background_color' => ['nullable', 'string', 'max:50'],
                'button_hover_text_color' => ['nullable', 'string', 'max:50'],

                'card_background_color' => ['required', 'string', 'max:50'],
                'card_text_color' => ['required', 'string', 'max:50'],
                'card_border_color' => ['nullable', 'string', 'max:50'],

                'success_color' => ['required', 'string', 'max:50'],
                'warning_color' => ['required', 'string', 'max:50'],
                'danger_color' => ['required', 'string', 'max:50'],
                'info_color' => ['required', 'string', 'max:50'],

                'font_family' => ['nullable', 'string', 'max:255'],

                'button_radius' => ['nullable', 'integer', 'min:0', 'max:200'],
                'card_radius' => ['nullable', 'integer', 'min:0', 'max:200'],
                'input_radius' => ['nullable', 'integer', 'min:0', 'max:200'],

                'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
                'remove_logo' => ['nullable', 'boolean'],

                'overlay_color' => ['nullable', 'string', 'max:100'],
                'shadow' => ['nullable', 'string', 'max:255'],
                'disabled_color' => ['nullable', 'string', 'max:50'],

                'is_default' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'name.required' => 'กรุณากรอกชื่อธีม',
                'primary_color.required' => 'กรุณากรอกสีหลัก',
                'background_color.required' => 'กรุณากรอกสีพื้นหลัง',
                'text_color.required' => 'กรุณากรอกสีตัวอักษร',
                'logo.image' => 'ไฟล์โลโก้ต้องเป็นรูปภาพ',
                'logo.mimes' => 'รองรับเฉพาะ JPG, JPEG, PNG, WEBP และ SVG',
                'logo.max' => 'โลโก้ต้องมีขนาดไม่เกิน 4 MB',
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
}
