<?php

namespace App\Http\Controllers\Promotions;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::with('product')
            ->orderBy('sort_order')
            ->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('promotion_type')) {
            $query->where(
                'promotion_type',
                $request->promotion_type
            );
        }

        if ($request->filled('is_active')) {
            $query->where(
                'is_active',
                (int) $request->is_active
            );
        }

        $promotions = $query->get();

        return view('content.pages.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('content.pages.promotions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePromotion($request);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadPromotionImage(
                $request->file('image')
            );
        }

        Promotion::create([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'image' => $imagePath,
            'promotion_type' => $validated['promotion_type'],
            'discount_type' => $validated['discount_type'] ?? null,
            'discount_value' => $validated['discount_value'] ?? 0,
            'max_discount' => $validated['max_discount'] ?? null,
            'points_required' => $validated['points_required'] ?? 0,
            'points_reward' => $validated['points_reward'] ?? 0,
            'minimum_amount' => $validated['minimum_amount'] ?? 0,
            'scope' => $validated['scope'],
            'product_id' => $validated['scope'] === 'product'
                ? ($validated['product_id'] ?? null)
                : null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'used_count' => 0,
            'start_at' => $validated['start_at'] ?? null,
            'end_at' => $validated['end_at'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'description' => $validated['description'] ?? null,
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('promotions.index')
            ->with('success', 'เพิ่มโปรโมชันสำเร็จ');
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view(
            'content.pages.promotions.edit',
            compact('promotion', 'products')
        );
    }

    public function update(
        Request $request,
        Promotion $promotion
    ) {
        $validated = $this->validatePromotion(
            $request,
            $promotion
        );

        $imagePath = $promotion->image;

        if (
            $request->boolean('remove_image') &&
            !$request->hasFile('image')
        ) {
            $this->deletePromotionImage($promotion->image);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $newImage = $this->uploadPromotionImage(
                $request->file('image')
            );

            $this->deletePromotionImage($promotion->image);
            $imagePath = $newImage;
        }

        $promotion->update([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'image' => $imagePath,
            'promotion_type' => $validated['promotion_type'],
            'discount_type' => $validated['discount_type'] ?? null,
            'discount_value' => $validated['discount_value'] ?? 0,
            'max_discount' => $validated['max_discount'] ?? null,
            'points_required' => $validated['points_required'] ?? 0,
            'points_reward' => $validated['points_reward'] ?? 0,
            'minimum_amount' => $validated['minimum_amount'] ?? 0,
            'scope' => $validated['scope'],
            'product_id' => $validated['scope'] === 'product'
                ? ($validated['product_id'] ?? null)
                : null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'start_at' => $validated['start_at'] ?? null,
            'end_at' => $validated['end_at'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'description' => $validated['description'] ?? null,
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('promotions.index')
            ->with('success', 'แก้ไขโปรโมชันสำเร็จ');
    }

    public function destroy(Promotion $promotion)
    {
        $this->deletePromotionImage($promotion->image);

        $promotion->delete();

        return redirect()
            ->route('promotions.index')
            ->with('success', 'ลบโปรโมชันสำเร็จ');
    }

    private function validatePromotion(
        Request $request,
        ?Promotion $promotion = null
    ): array {
        return $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'code' => [
                    'nullable',
                    'string',
                    'max:100',
                    Rule::unique('promotions', 'code')
                        ->ignore($promotion?->id),
                ],
                'image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
                'promotion_type' => [
                    'required',
                    'in:earn_points,redeem_discount,direct_discount',
                ],
                'discount_type' => [
                    'nullable',
                    'in:fixed,percent',
                ],
                'discount_value' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'max_discount' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'points_required' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],
                'points_reward' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],
                'minimum_amount' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'scope' => [
                    'required',
                    'in:all,product',
                ],
                'product_id' => [
                    'nullable',
                    'required_if:scope,product',
                    'exists:products,id',
                ],
                'usage_limit' => [
                    'nullable',
                    'integer',
                    'min:1',
                ],
                'start_at' => ['nullable', 'date'],
                'end_at' => [
                    'nullable',
                    'date',
                    'after_or_equal:start_at',
                ],
                'sort_order' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],
                'is_active' => ['nullable', 'boolean'],
                'remove_image' => ['nullable', 'boolean'],
                'description' => ['nullable', 'string'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'name.required' => 'กรุณากรอกชื่อโปรโมชัน',
                'code.unique' => 'รหัสโปรโมชันนี้ถูกใช้งานแล้ว',
                'promotion_type.required' => 'กรุณาเลือกประเภทโปรโมชัน',
                'scope.required' => 'กรุณาเลือกขอบเขตสินค้า',
                'product_id.required_if' => 'กรุณาเลือกสินค้าที่ร่วมรายการ',
                'end_at.after_or_equal' => 'วันสิ้นสุดต้องไม่น้อยกว่าวันเริ่มต้น',
            ]
        );
    }

    private function uploadPromotionImage($image): string
    {
        $uploadPath = base_path(
            '../public_html/assets/img/promotions'
        );

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid('promotion_', true)
            . '.'
            . strtolower($image->getClientOriginalExtension());

        $image->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deletePromotionImage(
        ?string $fileName
    ): void {
        if (!$fileName) {
            return;
        }

        $filePath = base_path(
            '../public_html/assets/img/promotions/' . $fileName
        );

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
