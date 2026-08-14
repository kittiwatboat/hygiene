<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\MachineGroup;
use App\Models\ProductGroupPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('content.pages.products.index', compact('products'));
    }

public function create()
{
    $machineGroups = MachineGroup::query()
        ->where('is_active', 1)
        ->orderBy('name')
        ->get();

    return view(
        'content.pages.products.create',
        compact('machineGroups')
    );
}

    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'code' => ['nullable', 'string', 'max:100', 'unique:products,code'],
                    'name' => ['required', 'string', 'max:255'],
                    'type' => ['nullable', 'string', 'max:100'],
                    'unit' => ['required', 'string', 'max:50'],
                    'description' => ['nullable', 'string'],
                    'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                    'is_active' => ['nullable', 'boolean'],
                    'group_prices' => ['nullable', 'array'],
'group_prices.*.machine_group_id' => [
    'required_with:group_prices',
    'integer',
    'exists:machine_groups,id',
],
'group_prices.*.amount_ml' => [
    'required_with:group_prices',
    'integer',
    'min:1',
],
'group_prices.*.price' => [
    'required_with:group_prices',
    'numeric',
    'min:0',
],
'group_prices.*.special_price' => [
    'nullable',
    'numeric',
    'min:0',
],
'group_prices.*.is_active' => [
    'nullable',
    'boolean',
],
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
                $image = $request->file('image');
                $fileName = uniqid('product_', true) . '.' . strtolower($image->getClientOriginalExtension());
                $uploadPath = base_path('../public_html/assets/img/products');

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $image->move($uploadPath, $fileName);
                $imagePath = $fileName;
            }

            $product = Product::create([
    'code' => $validated['code'] ?? null,
    'name' => $validated['name'],
    'type' => $validated['type'] ?? null,
    'unit' => $validated['unit'],
    'description' => $validated['description'] ?? null,
    'image' => $imagePath,
    'is_active' => $request->boolean('is_active'),
]);

$this->syncGroupPrices(
    $product,
    $validated['group_prices'] ?? []
);

            return redirect()
                ->route('products.index')
                ->with('success', 'เพิ่มสินค้า/น้ำยาสำเร็จ');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function show(Product $product)
    {
        $product->load('tanks.machine');

        return view('content.pages.products.show', compact('product'));
    }

    public function edit(Product $product)
{
    $product->load([
        'groupPrices' => function ($query) {
            $query
                ->orderBy('machine_group_id')
                ->orderBy('sort_order')
                ->orderBy('amount_ml');
        },
    ]);

    $machineGroups = MachineGroup::query()
        ->where('is_active', 1)
        ->orderBy('name')
        ->get();

    return view(
        'content.pages.products.edit',
        compact('product', 'machineGroups')
    );
}

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
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
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'group_prices' => ['nullable', 'array'],
'group_prices.*.machine_group_id' => [
    'required_with:group_prices',
    'integer',
    'exists:machine_groups,id',
],
'group_prices.*.amount_ml' => [
    'required_with:group_prices',
    'integer',
    'min:1',
],
'group_prices.*.price' => [
    'required_with:group_prices',
    'numeric',
    'min:0',
],
'group_prices.*.special_price' => [
    'nullable',
    'numeric',
    'min:0',
],
'group_prices.*.is_active' => [
    'nullable',
    'boolean',
],
        ]);

        $uploadPath = base_path('../public_html/assets/img/products');
        $imagePath = $product->image;

        if ($request->boolean('remove_image') && !$request->hasFile('image')) {
            if (!empty($product->image)) {
                $oldImagePath = $uploadPath . '/' . $product->image;

                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = uniqid('product_', true) . '.' . strtolower($image->getClientOriginalExtension());

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $fileName);

            if (!empty($product->image)) {
                $oldImagePath = $uploadPath . '/' . $product->image;

                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imagePath = $fileName;
        }

        $product->update([
            'code' => $validated['code'] ?? null,
            'name' => $validated['name'],
            'type' => $validated['type'] ?? null,
            'unit' => $validated['unit'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);
  $this->syncGroupPrices(
    $product,
    $validated['group_prices'] ?? []
);

        return redirect()
            ->route('products.index')
            ->with('success', 'แก้ไขสินค้า/น้ำยาสำเร็จ');
    }

    /**
     * ดาวน์โหลด Excel Template สำหรับ Import สินค้า/น้ำยา
     */
    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('products_import');

        $headers = [
            'code',
            'name',
            'type',
            'unit',
            'description',
            'is_active',
        ];

        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray([
            ['BH-001', 'น้ำยาปรับผ้านุ่ม', 'น้ำยาปรับผ้านุ่ม', 'ลิตร', 'น้ำยาปรับผ้านุ่ม', 1],
            ['DT-001', 'น้ำยาซักผ้า', 'น้ำยาซักผ้า', 'ลิตร', 'น้ำยาซักผ้า', 1],
        ], null, 'A2');

        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'hygiene_products_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Import Excel สินค้า/น้ำยา
     *
     * Header ที่รองรับ:
     * code, name, type, unit, description, is_active
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ], [
            'file.required' => 'กรุณาเลือกไฟล์ Excel',
            'file.mimes' => 'รองรับเฉพาะไฟล์ XLSX, XLS และ CSV',
            'file.max' => 'ไฟล์ต้องมีขนาดไม่เกิน 10 MB',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);

            if (count($rows) < 2) {
                return back()->withErrors([
                    'file' => 'ไม่พบข้อมูลสำหรับ Import ในไฟล์',
                ]);
            }

            $header = array_map(
                fn ($value) => strtolower(trim((string) $value)),
                $rows[0]
            );

            $requiredHeaders = [
                'code',
                'name',
                'type',
                'unit',
                'description',
                'is_active',
            ];

            $missingHeaders = array_values(array_diff($requiredHeaders, $header));

            if (!empty($missingHeaders)) {
                return back()->withErrors([
                    'file' => 'Header ในไฟล์ไม่ครบ: ' . implode(', ', $missingHeaders),
                ]);
            }

            $columnMap = array_flip($header);
            $preparedRows = [];
            $rowErrors = [];

            foreach (array_slice($rows, 1) as $index => $row) {
                $excelRow = $index + 2;

                $data = [
                    'code' => $this->excelValue($row[$columnMap['code']] ?? null),
                    'name' => $this->excelValue($row[$columnMap['name']] ?? null),
                    'type' => $this->excelValue($row[$columnMap['type']] ?? null),
                    'unit' => $this->excelValue($row[$columnMap['unit']] ?? null),
                    'description' => $this->excelValue($row[$columnMap['description']] ?? null),
                    'is_active' => $this->normalizeExcelBoolean(
                        $row[$columnMap['is_active']] ?? null
                    ),
                ];

                // ข้ามแถวว่างทั้งหมด
                if (
                    $data['code'] === null &&
                    $data['name'] === null &&
                    $data['type'] === null &&
                    $data['unit'] === null &&
                    $data['description'] === null
                ) {
                    continue;
                }

                $validator = Validator::make($data, [
                    'code' => ['nullable', 'string', 'max:100'],
                    'name' => ['required', 'string', 'max:255'],
                    'type' => ['nullable', 'string', 'max:100'],
                    'unit' => ['required', 'string', 'max:50'],
                    'description' => ['nullable', 'string'],
                    'is_active' => ['required', 'boolean'],
                ], [
                    'name.required' => 'กรุณาระบุชื่อสินค้า/น้ำยา',
                    'unit.required' => 'กรุณาระบุหน่วย',
                    'is_active.required' => 'กรุณาระบุสถานะ is_active เป็น 1 หรือ 0',
                    'is_active.boolean' => 'is_active ต้องเป็น 1 หรือ 0',
                ]);

                if ($validator->fails()) {
                    $rowErrors[] = 'แถว ' . $excelRow . ': ' . implode(', ', $validator->errors()->all());
                    continue;
                }

                $preparedRows[] = [
                    'excel_row' => $excelRow,
                    'data' => $validator->validated(),
                ];
            }

            if (!empty($rowErrors)) {
                return back()
                    ->withErrors([
                        'file' => "Import ไม่สำเร็จ กรุณาแก้ไขข้อมูลใน Excel ก่อน\n" . implode("\n", array_slice($rowErrors, 0, 20)),
                    ]);
            }

            if (empty($preparedRows)) {
                return back()->withErrors([
                    'file' => 'ไม่พบข้อมูลสินค้า/น้ำยาสำหรับ Import',
                ]);
            }

            $created = 0;
            $updated = 0;

            DB::transaction(function () use ($preparedRows, &$created, &$updated) {
                foreach ($preparedRows as $item) {
                    $data = $item['data'];
                    $code = $data['code'] ?? null;

                    if (!empty($code)) {
                        $product = Product::query()
                            ->where('code', $code)
                            ->first();

                        if ($product) {
                            // ไม่แก้ image จาก Excel เพื่อรักษารูปเดิมไว้
                            $product->update([
                                'name' => $data['name'],
                                'type' => $data['type'] ?? null,
                                'unit' => $data['unit'],
                                'description' => $data['description'] ?? null,
                                'is_active' => (bool) $data['is_active'],
                            ]);

                            $updated++;
                            continue;
                        }
                    }

                    Product::create([
                        'code' => $code ?: null,
                        'name' => $data['name'],
                        'type' => $data['type'] ?? null,
                        'unit' => $data['unit'],
                        'description' => $data['description'] ?? null,
                        'image' => null,
                        'is_active' => (bool) $data['is_active'],
                    ]);

                    $created++;
                }
            });

            return redirect()
                ->route('products.index')
                ->with(
                    'success',
                    "Import สำเร็จ: เพิ่มใหม่ {$created} รายการ, อัปเดต {$updated} รายการ"
                );
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'file' => 'Import ไม่สำเร็จ: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Product $product)
    {
        if (!empty($product->image)) {
            $imagePath = base_path('../public_html/assets/img/products/' . $product->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'ลบสินค้า/น้ำยาสำเร็จ');
    }

    private function excelValue($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function normalizeExcelBoolean($value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        if (in_array($normalized, ['1', 'true', 'yes', 'y', 'active', 'เปิดใช้งาน'], true)) {
            return true;
        }

        if (in_array($normalized, ['0', 'false', 'no', 'n', 'inactive', 'ปิดใช้งาน'], true)) {
            return false;
        }

        return null;
    }
    private function syncGroupPrices(
    Product $product,
    array $rows
): void {
    DB::transaction(function () use ($product, $rows) {
        $normalized = collect($rows)
            ->filter(function ($row) {
                return !empty($row['machine_group_id'])
                    && !empty($row['amount_ml'])
                    && isset($row['price'])
                    && $row['price'] !== '';
            })
            ->map(function ($row, $index) {
                return [
                    'machine_group_id' => (int) $row['machine_group_id'],
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
        | ป้องกัน Group + ปริมาตร ซ้ำใน request เดียวกัน
        |--------------------------------------------------------------------------
        */
        $duplicate = $normalized
            ->groupBy(function ($row) {
                return $row['machine_group_id']
                    . ':'
                    . $row['amount_ml'];
            })
            ->first(
                fn ($items) => $items->count() > 1
            );

        if ($duplicate) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'group_prices' =>
                    'พบกลุ่มตู้และปริมาตรซ้ำกัน กรุณาตรวจสอบข้อมูลราคา',
            ]);
        }

        $keepIds = [];

        foreach ($normalized as $row) {
            $price = ProductGroupPrice::query()
                ->updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'machine_group_id' => $row['machine_group_id'],
                        'amount_ml' => $row['amount_ml'],
                    ],
                    [
                        'price' => $row['price'],
                        'special_price' => $row['special_price'],
                        'is_active' => $row['is_active'],
                        'sort_order' => $row['sort_order'],
                    ]
                );

            $keepIds[] = $price->id;
        }

        $deleteQuery = ProductGroupPrice::query()
            ->where('product_id', $product->id);

        if (!empty($keepIds)) {
            $deleteQuery->whereNotIn('id', $keepIds);
        }

        $deleteQuery->delete();
    });
}
}
