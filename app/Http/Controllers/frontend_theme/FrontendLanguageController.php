<?php

namespace App\Http\Controllers\frontend_theme;

use App\Http\Controllers\Controller;
use App\Models\FrontendLanguage;
use App\Models\FrontendLanguageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\FrontendMachineLanguageSetting;
use App\Models\Machine;

class FrontendLanguageController extends Controller
{
    public function index()
    {
        $languages = FrontendLanguage::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $activeSettings = FrontendLanguageSetting::with('language')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $selectedLanguageIds = $activeSettings
            ->pluck('language_id')
            ->map(fn ($id) => (string) $id)
            ->toArray();

        $defaultLanguageId = optional(
            $activeSettings->firstWhere('is_default', true)
        )->language_id;

        return view(
            'content.pages.frontend.languages.index',
            compact(
                'languages',
                'activeSettings',
                'selectedLanguageIds',
                'defaultLanguageId'
            )
        );
    }

    public function create()
    {
        return view('content.pages.frontend.languages.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateLanguage($request);

        $flagImage = null;

        if ($request->hasFile('flag_image')) {
            $flagImage = $this->uploadFlagImage(
                $request->file('flag_image')
            );
        }

        FrontendLanguage::create([
            'code' => strtolower($validated['code']),
            'name' => $validated['name'],
            'native_name' => $validated['native_name'],
            'flag_image' => $flagImage,
            'locale' => $validated['locale'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('content.pages.frontend.languages.index')
            ->with('success', 'เพิ่มภาษาสำเร็จ');
    }

    public function edit(FrontendLanguage $language)
    {
        return view('content.pages.frontend.languages.edit', compact('language'));
    }

    public function update(Request $request, FrontendLanguage $language)
    {
        $validated = $this->validateLanguage($request, $language);

        $flagImage = $language->flag_image;

        if (
            $request->boolean('remove_flag_image') &&
            !$request->hasFile('flag_image')
        ) {
            $this->deleteFlagImage($language->flag_image);
            $flagImage = null;
        }

        if ($request->hasFile('flag_image')) {
            $newFlagImage = $this->uploadFlagImage(
                $request->file('flag_image')
            );

            $this->deleteFlagImage($language->flag_image);
            $flagImage = $newFlagImage;
        }

        $language->update([
            'code' => strtolower($validated['code']),
            'name' => $validated['name'],
            'native_name' => $validated['native_name'],
            'flag_image' => $flagImage,
            'locale' => $validated['locale'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('kiosk.languages.index')
            ->with('success', 'แก้ไขภาษาสำเร็จ');
    }

    public function destroy(FrontendLanguage $language)
    {
        if ($language->setting()->exists()) {
            return back()->with(
                'error',
                'ไม่สามารถลบภาษานี้ได้ เนื่องจากถูกเลือกใช้งานในหน้าตู้ กรุณานำออกจากภาษาที่เปิดใช้ก่อน'
            );
        }

        $this->deleteFlagImage($language->flag_image);

        $language->delete();

        return redirect()
            ->route('kiosk.languages.index')
            ->with('success', 'ลบภาษาสำเร็จ');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate(
            [
                'language_ids' => [
                    'required',
                    'array',
                    'min:1',
                    'max:3',
                ],
                'language_ids.*' => [
                    'required',
                    'exists:frontend_languages,id',
                ],
                'default_language_id' => [
                    'required',
                    'exists:frontend_languages,id',
                ],
            ],
            [
                'language_ids.required' => 'กรุณาเลือกภาษาที่ต้องการเปิดใช้',
                'language_ids.min' => 'กรุณาเลือกอย่างน้อย 1 ภาษา',
                'language_ids.max' => 'เลือกภาษาได้สูงสุด 3 ภาษา',
                'default_language_id.required' => 'กรุณาเลือกภาษาหลัก',
            ]
        );

        $languageIds = array_values($validated['language_ids']);
        $defaultLanguageId = $validated['default_language_id'];

        if (!in_array($defaultLanguageId, $languageIds)) {
            return back()
                ->withInput()
                ->withErrors([
                    'default_language_id' => 'ภาษาหลักต้องอยู่ในภาษาที่เลือกใช้งาน',
                ]);
        }

        DB::transaction(function () use ($languageIds, $defaultLanguageId) {
            FrontendLanguageSetting::query()->delete();

            foreach ($languageIds as $index => $languageId) {
                FrontendLanguageSetting::create([
                    'language_id' => $languageId,
                    'sort_order' => $index + 1,
                    'is_default' => (string) $languageId === (string) $defaultLanguageId,
                    'is_active' => true,
                ]);
            }
        });

        return redirect()
            ->route('kiosk.languages.index')
            ->with('success', 'บันทึกภาษาที่ใช้หน้าตู้สำเร็จ');
    }

    private function validateLanguage(
        Request $request,
        ?FrontendLanguage $language = null
    ): array {
        return $request->validate(
            [
                'code' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('frontend_languages', 'code')
                        ->ignore($language?->id),
                ],
                'name' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'native_name' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'flag_image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp,svg',
                    'max:2048',
                ],
                'remove_flag_image' => [
                    'nullable',
                    'boolean',
                ],
                'locale' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'sort_order' => [
                    'nullable',
                    'integer',
                    'min:0',
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
                'code.required' => 'กรุณากรอกรหัสภาษา',
                'code.unique' => 'รหัสภาษานี้ถูกใช้งานแล้ว',
                'name.required' => 'กรุณากรอกชื่อภาษา',
                'native_name.required' => 'กรุณากรอกชื่อภาษาที่ใช้แสดง',
                'flag_image.image' => 'ไฟล์ธงต้องเป็นรูปภาพ',
                'flag_image.mimes' => 'รองรับเฉพาะ JPG, JPEG, PNG, WEBP และ SVG',
                'flag_image.max' => 'รูปธงต้องมีขนาดไม่เกิน 2 MB',
            ]
        );
    }

    private function uploadFlagImage($image): string
    {
        $uploadPath = base_path(
            '../public_html/assets/img/languages'
        );

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid('lang_', true)
            . '.'
            . strtolower($image->getClientOriginalExtension());

        $image->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deleteFlagImage(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $filePath = base_path(
            '../public_html/assets/img/languages/' . $fileName
        );

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    private function getLanguagesForMachine($machine)
{
    $machineSettings = FrontendMachineLanguageSetting::with('language')
        ->where('machine_id', $machine->id)
        ->where('is_active', true)
        ->whereHas('language', function ($query) {
            $query->where('is_active', true);
        })
        ->orderBy('sort_order')
        ->limit(3)
        ->get();

    if ($machineSettings->count() > 0) {
        return $machineSettings;
    }

    return FrontendLanguageSetting::with('language')
        ->where('is_active', true)
        ->whereHas('language', function ($query) {
            $query->where('is_active', true);
        })
        ->orderBy('sort_order')
        ->limit(3)
        ->get();
}
}
