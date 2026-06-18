<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('content.pages.products.index', compact('products'));
    }

    public function create()
    {
        return view('content.pages.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
    [
        'code' => ['nullable', 'string', 'max:100', 'unique:products,code'],
        'name' => ['required', 'string', 'max:255'],
        'type' => ['nullable', 'string', 'max:100'],
        'unit' => ['required', 'string', 'max:50'],
        'description' => ['nullable', 'string'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'is_active' => ['nullable', 'boolean'],
    ],
    [
        'name.required' => 'กรุณากรอกชื่อสินค้า/น้ำยา',
        'code.unique' => 'รหัสสินค้า/น้ำยานี้ถูกใช้งานแล้ว',
        'image.image' => 'ไฟล์ที่เลือกต้องเป็นรูปภาพ',
        'image.mimes' => 'รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ WEBP',
        'image.max' => 'ขนาดรูปต้องไม่เกิน 5 MB',
    ]
);
$imagePath = null;

if ($request->hasFile('image')) {
    $imagePath = $request->file('image')->store('products', 'public');
}
        Product::create([
    'code' => $request->code,
    'name' => $request->name,
    'type' => $request->type,
    'unit' => $request->unit,
    'description' => $request->description,
    'image' => $imagePath,
    'is_active' => $request->boolean('is_active'),
]);

        return redirect()
            ->route('products.index')
            ->with('success', 'เพิ่มสินค้า/น้ำยาสำเร็จ');
    }

    public function show(Product $product)
    {
        $product->load('tanks.machine');

        return view('content.pages.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('content.pages.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate(
            [
                'code' => [
                    'nullable',
                    'string',
                    'max:100',
                    Rule::unique('products', 'code')->ignore($product->id),
                ],
                'name' => ['required', 'string', 'max:255'],
                'type' => ['nullable', 'string', 'max:100'],
                'unit' => ['required', 'string', 'max:50'],
                'description' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_image' => ['nullable', 'boolean'],
            ],
            [
                'code.unique' => 'รหัสสินค้า/น้ำยานี้ถูกใช้งานแล้ว',
                'name.required' => 'กรุณากรอกชื่อสินค้า/น้ำยา',
                'unit.required' => 'กรุณากรอกหน่วยนับ',
                'image.image' => 'ไฟล์ที่เลือกต้องเป็นรูปภาพ',
                'image.mimes' => 'รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ WEBP',
                'image.max' => 'ขนาดรูปต้องไม่เกิน 5 MB',
            ]
        );
$imagePath = $product->image;

if ($request->boolean('remove_image')) {
    if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
    }

    $imagePath = null;
}

if ($request->hasFile('image')) {
    if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
    }

    $imagePath = $request->file('image')->store('products', 'public');
}
        $product->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'unit' => $request->unit,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'แก้ไขสินค้า/น้ำยาสำเร็จ');
    }

    public function destroy(Product $product)
    {
      if ($product->image && Storage::disk('public')->exists($product->image)) {
    Storage::disk('public')->delete($product->image);
}

$product->delete();
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'ลบสินค้า/น้ำยาสำเร็จ');
    }
}
