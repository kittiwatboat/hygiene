<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
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
                'is_active' => ['nullable', 'boolean'],
            ],
            [
                'code.unique' => 'รหัสสินค้า/น้ำยานี้ถูกใช้งานแล้ว',
                'name.required' => 'กรุณากรอกชื่อสินค้า/น้ำยา',
                'unit.required' => 'กรุณากรอกหน่วยนับ',
            ]
        );

        Product::create([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'unit' => $request->unit,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'เพิ่มสินค้า/น้ำยาสำเร็จ');
    }

    public function show(Product $product)
    {
        $product->load('tanks.machine');

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
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
            ],
            [
                'code.unique' => 'รหัสสินค้า/น้ำยานี้ถูกใช้งานแล้ว',
                'name.required' => 'กรุณากรอกชื่อสินค้า/น้ำยา',
                'unit.required' => 'กรุณากรอกหน่วยนับ',
            ]
        );

        $product->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'unit' => $request->unit,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'แก้ไขสินค้า/น้ำยาสำเร็จ');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'ลบสินค้า/น้ำยาสำเร็จ');
    }
}
