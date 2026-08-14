<?php

namespace App\Http\Controllers;

use App\Models\FrontendTheme;
use App\Models\MachineGroup;
use App\Models\Product;
use App\Models\ProductGroupPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MachineGroupController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->get('keyword', ''));
        $status = $request->get('status');

        $groups = MachineGroup::query()
            ->with('theme')
            ->withCount('machines')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('code', 'like', "%{$keyword}%")
                        ->orWhere('remark', 'like', "%{$keyword}%");
                });
            })
            ->when(
                in_array((string) $status, ['0', '1'], true),
                fn ($query) => $query->where('is_active', (int) $status)
            )
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view(
            'content.pages.machine-groups.index',
            compact('groups', 'keyword', 'status')
        );
    }

    public function create()
    {
        $themes = FrontendTheme::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('is_active', 1)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view(
            'content.pages.machine-groups.create',
            compact('themes', 'products')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validateGroup($request);

        DB::transaction(function () use ($request, $validated) {
            $machineGroup = MachineGroup::create([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'frontend_theme_id' => $validated['frontend_theme_id'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'remark' => $validated['remark'] ?? null,
            ]);

            $this->syncProductPrices(
                $machineGroup,
                $validated['product_prices'] ?? []
            );
        });

        return redirect()
            ->route('machine-groups.index')
            ->with('success', 'เพิ่มกลุ่มตู้สำเร็จ');
    }

    public function edit(MachineGroup $machineGroup)
    {
        $themes = FrontendTheme::query()
            ->where(function ($query) use ($machineGroup) {
                $query
                    ->where('is_active', true)
                    ->orWhere('id', $machineGroup->frontend_theme_id);
            })
            ->orderBy('name')
            ->get();

        $machineGroup->load([
            'productPrices' => function ($query) {
                $query
                    ->orderBy('product_id')
                    ->orderBy('sort_order')
                    ->orderBy('amount_ml');
            },
        ]);

        $products = Product::query()
            ->where('is_active', 1)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view(
            'content.pages.machine-groups.edit',
            compact('machineGroup', 'themes', 'products')
        );
    }

    public function update(
        Request $request,
        MachineGroup $machineGroup
    ) {
        $validated = $this->validateGroup(
            $request,
            $machineGroup
        );

        DB::transaction(function () use (
            $request,
            $validated,
            $machineGroup
        ) {
            $machineGroup->update([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'frontend_theme_id' => $validated['frontend_theme_id'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'remark' => $validated['remark'] ?? null,
            ]);

            $this->syncProductPrices(
                $machineGroup,
                $validated['product_prices'] ?? []
            );
        });

        return redirect()
            ->route('machine-groups.index')
            ->with('success', 'แก้ไขกลุ่มตู้สำเร็จ');
    }

    public function destroy(MachineGroup $machineGroup)
    {
        if ($machineGroup->machines()->exists()) {
            return back()->with(
                'error',
                'ไม่สามารถลบกลุ่มตู้ได้ เนื่องจากยังมีตู้ใช้งานกลุ่มนี้อยู่'
            );
        }

        $machineGroup->delete();

        return redirect()
            ->route('machine-groups.index')
            ->with('success', 'ลบกลุ่มตู้สำเร็จ');
    }

    private function validateGroup(
        Request $request,
        ?MachineGroup $machineGroup = null
    ): array {
        return $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'code' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique(
                        'machine_groups',
                        'code'
                    )->ignore($machineGroup?->id),
                ],

                'frontend_theme_id' => [
                    'nullable',
                    'integer',
                    'exists:frontend_themes,id',
                ],

                'is_active' => [
                    'nullable',
                    'boolean',
                ],

                'remark' => [
                    'nullable',
                    'string',
                ],

                /*
                |--------------------------------------------------------------------------
                | สินค้าและราคาของกลุ่ม
                |--------------------------------------------------------------------------
                */
                'product_prices' => [
                    'nullable',
                    'array',
                ],

                'product_prices.*.product_id' => [
                    'nullable',
                    'integer',
                    'exists:products,id',
                ],

                'product_prices.*.amount_ml' => [
                    'nullable',
                    'integer',
                    'min:1',
                ],

                'product_prices.*.price' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'product_prices.*.special_price' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'product_prices.*.is_active' => [
                    'nullable',
                    'boolean',
                ],
            ],
            [
                'name.required' =>
                    'กรุณากรอกชื่อกลุ่มตู้',

                'code.required' =>
                    'กรุณากรอกรหัสกลุ่มตู้',

                'code.unique' =>
                    'รหัสกลุ่มตู้นี้ถูกใช้งานแล้ว',

                'frontend_theme_id.exists' =>
                    'Theme ที่เลือกไม่ถูกต้อง',

                'product_prices.*.product_id.exists' =>
                    'สินค้า/น้ำยาที่เลือกไม่ถูกต้อง',

                'product_prices.*.amount_ml.integer' =>
                    'ปริมาตรต้องเป็นตัวเลขจำนวนเต็ม',

                'product_prices.*.amount_ml.min' =>
                    'ปริมาตรต้องมากกว่า 0 ml',

                'product_prices.*.price.numeric' =>
                    'ราคาต้องเป็นตัวเลข',

                'product_prices.*.special_price.numeric' =>
                    'ราคาพิเศษต้องเป็นตัวเลข',
            ]
        );
    }

    private function syncProductPrices(
        MachineGroup $machineGroup,
        array $rows
    ): void {
        $normalizedRows = collect($rows)
            ->filter(function ($row) {
                /*
                |--------------------------------------------------------------------------
                | ข้ามแถวว่าง
                |--------------------------------------------------------------------------
                */
                $hasProduct = !empty($row['product_id']);
                $hasAmount = isset($row['amount_ml'])
                    && $row['amount_ml'] !== '';
                $hasPrice = array_key_exists('price', $row)
                    && $row['price'] !== '';

                return $hasProduct || $hasAmount || $hasPrice;
            })
            ->map(function ($row, $index) {
                /*
                |--------------------------------------------------------------------------
                | ถ้าเริ่มกรอกแถวแล้ว ต้องกรอก Product + Amount + Price ให้ครบ
                |--------------------------------------------------------------------------
                */
                if (
                    empty($row['product_id']) ||
                    !isset($row['amount_ml']) ||
                    $row['amount_ml'] === '' ||
                    !array_key_exists('price', $row) ||
                    $row['price'] === ''
                ) {
                    throw ValidationException::withMessages([
                        'product_prices' =>
                            'กรุณากรอกสินค้า ปริมาตร และราคาให้ครบทุกแถว',
                    ]);
                }

                return [
                    'product_id' => (int) $row['product_id'],
                    'amount_ml' => (int) $row['amount_ml'],
                    'price' => (float) $row['price'],

                    'special_price' => (
                        isset($row['special_price'])
                        && $row['special_price'] !== ''
                    )
                        ? (float) $row['special_price']
                        : null,

                    'is_active' => !empty($row['is_active']),
                    'sort_order' => $index + 1,
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | ป้องกัน Product + Amount ซ้ำใน Group เดียวกัน
        |--------------------------------------------------------------------------
        */
        $duplicate = $normalizedRows
            ->groupBy(function ($row) {
                return $row['product_id']
                    . ':'
                    . $row['amount_ml'];
            })
            ->first(
                fn ($items) => $items->count() > 1
            );

        if ($duplicate) {
            throw ValidationException::withMessages([
                'product_prices' =>
                    'พบสินค้าและปริมาตรซ้ำกันในกลุ่มเดียวกัน กรุณาตรวจสอบข้อมูล',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ราคาพิเศษไม่ควรมากกว่าราคาปกติ
        |--------------------------------------------------------------------------
        */
        foreach ($normalizedRows as $row) {
            if (
                $row['special_price'] !== null &&
                $row['special_price'] > $row['price']
            ) {
                throw ValidationException::withMessages([
                    'product_prices' =>
                        'ราคาพิเศษต้องไม่มากกว่าราคาปกติ',
                ]);
            }
        }

        $keepIds = [];

        foreach ($normalizedRows as $row) {
            $price = ProductGroupPrice::query()
                ->updateOrCreate(
                    [
                        'machine_group_id' =>
                            $machineGroup->id,

                        'product_id' =>
                            $row['product_id'],

                        'amount_ml' =>
                            $row['amount_ml'],
                    ],
                    [
                        'price' =>
                            $row['price'],

                        'special_price' =>
                            $row['special_price'],

                        'is_active' =>
                            $row['is_active'],

                        'sort_order' =>
                            $row['sort_order'],
                    ]
                );

            $keepIds[] = $price->id;
        }

        /*
        |--------------------------------------------------------------------------
        | ลบรายการที่ผู้ใช้เอาออกจากฟอร์ม
        |--------------------------------------------------------------------------
        */
        $deleteQuery = ProductGroupPrice::query()
            ->where(
                'machine_group_id',
                $machineGroup->id
            );

        if (!empty($keepIds)) {
            $deleteQuery->whereNotIn(
                'id',
                $keepIds
            );
        }

        $deleteQuery->delete();
    }
}
