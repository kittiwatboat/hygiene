<?php

namespace App\Http\Controllers\banners;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')
            ->latest()
            ->get();

        return view('content.pages.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('content.pages.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'image' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
                'link_url' => ['nullable', 'string', 'max:1000'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'start_at' => ['nullable', 'date'],
                'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
                'is_active' => ['nullable', 'boolean'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'title.required' => 'กรุณากรอกชื่อแบนเนอร์',
                'image.required' => 'กรุณาเลือกรูปแบนเนอร์',
                'image.image' => 'ไฟล์ที่เลือกต้องเป็นรูปภาพ',
                'image.mimes' => 'รองรับเฉพาะ JPG, JPEG, PNG และ WEBP',
                'image.max' => 'รูปภาพต้องมีขนาดไม่เกิน 5 MB',
                'end_at.after_or_equal' => 'วันสิ้นสุดต้องไม่น้อยกว่าวันเริ่มต้น',
            ]
        );

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadBannerImage(
                $request->file('image')
            );
        }

        Banner::create([
            'title' => $validated['title'],
            'image' => $imagePath,
            'link_url' => $validated['link_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'start_at' => $validated['start_at'] ?? null,
            'end_at' => $validated['end_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'เพิ่มแบนเนอร์สำเร็จ');
    }

    public function edit(Banner $banner)
    {
        return view('content.pages.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
                'link_url' => ['nullable', 'string', 'max:1000'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'start_at' => ['nullable', 'date'],
                'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
                'is_active' => ['nullable', 'boolean'],
                'remove_image' => ['nullable', 'boolean'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'title.required' => 'กรุณากรอกชื่อแบนเนอร์',
                'image.image' => 'ไฟล์ที่เลือกต้องเป็นรูปภาพ',
                'image.mimes' => 'รองรับเฉพาะ JPG, JPEG, PNG และ WEBP',
                'image.max' => 'รูปภาพต้องมีขนาดไม่เกิน 5 MB',
                'end_at.after_or_equal' => 'วันสิ้นสุดต้องไม่น้อยกว่าวันเริ่มต้น',
            ]
        );

        $imagePath = $banner->image;

        if (
            $request->boolean('remove_image') &&
            !$request->hasFile('image')
        ) {
            $this->deleteBannerImage($banner->image);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $newImagePath = $this->uploadBannerImage(
                $request->file('image')
            );

            $this->deleteBannerImage($banner->image);
            $imagePath = $newImagePath;
        }

        if (!$imagePath) {
            return back()
                ->withInput()
                ->withErrors([
                    'image' => 'แบนเนอร์ต้องมีรูปภาพ',
                ]);
        }

        $banner->update([
            'title' => $validated['title'],
            'image' => $imagePath,
            'link_url' => $validated['link_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'start_at' => $validated['start_at'] ?? null,
            'end_at' => $validated['end_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'แก้ไขแบนเนอร์สำเร็จ');
    }

    public function destroy(Banner $banner)
    {
        $this->deleteBannerImage($banner->image);

        $banner->delete();

        return redirect()
            ->route('banners.index')
            ->with('success', 'ลบแบนเนอร์สำเร็จ');
    }

    private function uploadBannerImage($image): string
    {
        $uploadPath = base_path(
            '../public_html/assets/img/banners'
        );

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid('banner_', true)
            . '.'
            . strtolower($image->getClientOriginalExtension());

        $image->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deleteBannerImage(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $filePath = base_path(
            '../public_html/assets/img/banners/' . $fileName
        );

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
