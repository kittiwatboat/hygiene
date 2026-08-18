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


        return redirect()
            ->route('products.index')
            ->with('success', 'แก้ไขสินค้า/น้ำยาสำเร็จ');
    }

    /**
     * ดาวน์โหลด Excel Template สำหรับ Import สินค้า/น้ำยา
     *
     * รูปแบบใหม่อ้างอิงไฟล์ "สินค้า.xlsx"
     * Sheet: สินค้า
     * Columns A:J:
     * ลำดับ, รหัสสินค้า, ชื่อสินค้า, ประเภทสินค้า, ราคา,
     * หน่วย, จำนวนคงเหลือ, LOT การผลิต, สถานะ, หมายเหตุ
     */
    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('สินค้า');

        $headers = [
            'ลำดับ',
            'รหัสสินค้า',
            'ชื่อสินค้า',
            'ประเภทสินค้า',
            'ราคา',
            'หน่วย',
            'จำนวนคงเหลือ',
            'LOT การผลิต',
            'สถานะ',
            'หมายเหตุ',
        ];

        $sheet->fromArray($headers, null, 'A1');

        // เตรียมแถวตัวอย่าง 1-50 เหมือนไฟล์ที่ใช้งานจริง
        for ($row = 2; $row <= 51; $row++) {
            $sheet->setCellValue('A' . $row, $row - 1);
            $sheet->setCellValue('I' . $row, 'ใช้งาน');
        }

        // หมายเหตุด้านขวาของ Template
        $sheet->setCellValue('L1', 'หมายเหตุ');
        $sheet->setCellValue('L2', 'LOT การผลิต');
        $sheet->setCellValue('M2', 'กรอกสำหรับน้ำยาซักผ้า');
        $sheet->setCellValue('L3', 'น้ำยาปรับผ้านุ่ม');
        $sheet->setCellValue('M3', 'LOT การผลิตสามารถเว้นว่างได้ ระบบจะรับไฟล์ได้ตามปกติ');

        // Sheet รายการตัวเลือก สำหรับ Dropdown
        $optionSheet = $spreadsheet->createSheet();
        $optionSheet->setTitle('รายการตัวเลือก');

        $optionSheet->fromArray([
            ['ประเภทสินค้า', 'สถานะ', 'หน่วย'],
            ['น้ำยาซักผ้า', 'ใช้งาน', 'ขวด'],
            ['น้ำยาปรับผ้านุ่ม', 'ไม่ใช้งาน', 'ถุง'],
            [null, 'สินค้าหมด', 'แกลลอน'],
            [null, null, 'ชิ้น'],
        ], null, 'A1');

        // Dropdown: ประเภทสินค้า
        for ($row = 2; $row <= 51; $row++) {
            $typeValidation = $sheet->getCell('D' . $row)->getDataValidation();
            $typeValidation->setType(
                \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST
            );
            $typeValidation->setErrorStyle(
                \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
            );
            $typeValidation->setAllowBlank(true);
            $typeValidation->setShowDropDown(true);
            $typeValidation->setFormula1("'รายการตัวเลือก'!\$A\$2:\$A\$3");

            // Dropdown: หน่วย
            $unitValidation = $sheet->getCell('F' . $row)->getDataValidation();
            $unitValidation->setType(
                \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST
            );
            $unitValidation->setErrorStyle(
                \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
            );
            $unitValidation->setAllowBlank(true);
            $unitValidation->setShowDropDown(true);
            $unitValidation->setFormula1("'รายการตัวเลือก'!\$C\$2:\$C\$5");

            // Dropdown: สถานะ
            $statusValidation = $sheet->getCell('I' . $row)->getDataValidation();
            $statusValidation->setType(
                \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST
            );
            $statusValidation->setErrorStyle(
                \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
            );
            $statusValidation->setAllowBlank(false);
            $statusValidation->setShowDropDown(true);
            $statusValidation->setFormula1("'รายการตัวเลือก'!\$B\$2:\$B\$4");
        }

        // รูปแบบ Header
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );
        $sheet->getStyle('A1:J1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9EAF7');

        $optionSheet->getStyle('A1:C1')->getFont()->setBold(true);
        $optionSheet->getStyle('A1:C1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9EAF7');

        // กำหนดความกว้างคอลัมน์ให้อ่านง่าย
        $widths = [
            'A' => 10,
            'B' => 18,
            'C' => 30,
            'D' => 22,
            'E' => 14,
            'F' => 14,
            'G' => 16,
            'H' => 20,
            'I' => 16,
            'J' => 30,
            'L' => 22,
            'M' => 36,
        ];

        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        foreach (['A', 'B', 'C'] as $column) {
            $optionSheet->getColumnDimension($column)->setWidth(22);
        }

        $sheet->freezePane('A2');

        // กลับมาเปิด Sheet สินค้าเป็นหน้าแรก
        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'hygiene_products_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Import Excel สินค้า/น้ำยา รูปแบบใหม่
     *
     * Header ที่รับจาก Sheet "สินค้า":
     * ลำดับ, รหัสสินค้า, ชื่อสินค้า, ประเภทสินค้า, ราคา,
     * หน่วย, จำนวนคงเหลือ, LOT การผลิต, สถานะ, หมายเหตุ
     *
     * หมายเหตุ:
     * ตาราง products ปัจจุบันมี field:
     * code, name, type, unit, description, image, is_active
     * ดังนั้น ราคา / จำนวนคงเหลือ / LOT การผลิต จะอ่านไฟล์ได้ โดย LOT สามารถเว้นว่างได้
     * แต่ยังไม่บันทึกลง products จนกว่าจะมีโครงสร้าง DB ที่รองรับ
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
            $spreadsheet = IOFactory::load(
                $request->file('file')->getRealPath()
            );

            // ใช้ Sheet ชื่อ "สินค้า" ถ้ามี ไม่เช่นนั้นใช้ Sheet แรก
            $sheet = $spreadsheet->getSheetByName('สินค้า')
                ?? $spreadsheet->getActiveSheet();

            $highestRow = max(1, (int) $sheet->getHighestDataRow());

            // อ่านเฉพาะ A:J เท่านั้น เพราะด้านขวา L:M เป็นหมายเหตุ Template
            $rows = $sheet->rangeToArray(
                'A1:J' . $highestRow,
                null,
                true,
                true,
                false
            );

            if (count($rows) < 2) {
                return back()->withErrors([
                    'file' => 'ไม่พบข้อมูลสำหรับ Import ในไฟล์',
                ]);
            }

            $header = array_map(
                fn ($value) => $this->normalizeExcelHeader($value),
                $rows[0]
            );

            $requiredHeaders = [
                'ลำดับ',
                'รหัสสินค้า',
                'ชื่อสินค้า',
                'ประเภทสินค้า',
                'ราคา',
                'หน่วย',
                'จำนวนคงเหลือ',
                'LOT การผลิต',
                'สถานะ',
                'หมายเหตุ',
            ];

            if ($header !== $requiredHeaders) {
                return back()->withErrors([
                    'file' => 'รูปแบบ Header ไม่ตรงกับ Template กรุณาใช้ไฟล์ Template ล่าสุดของระบบ',
                ]);
            }

            $preparedRows = [];
            $rowErrors = [];

            foreach (array_slice($rows, 1) as $index => $row) {
                $excelRow = $index + 2;

                $code = $this->excelValue($row[1] ?? null);
                $name = $this->excelValue($row[2] ?? null);
                $typeText = $this->excelValue($row[3] ?? null);
                $price = $this->excelValue($row[4] ?? null);
                $unit = $this->excelValue($row[5] ?? null);
                $stock = $this->excelValue($row[6] ?? null);
                $lot = $this->excelValue($row[7] ?? null);
                $statusText = $this->excelValue($row[8] ?? null);
                $remark = $this->excelValue($row[9] ?? null);

                // Template มีเลขลำดับ + สถานะเตรียมไว้ทุกแถว
                // ถ้าไม่มีข้อมูลสินค้าเลย ให้ข้ามแถวนั้น
                if (
                    $code === null &&
                    $name === null &&
                    $typeText === null &&
                    $price === null &&
                    $unit === null &&
                    $stock === null &&
                    $lot === null &&
                    $remark === null
                ) {
                    continue;
                }

                $type = $this->normalizeProductType($typeText);
                $isActive = $this->normalizeProductStatus($statusText);

                $data = [
                    'code' => $code,
                    'name' => $name,
                    'type' => $type,
                    'unit' => $unit,
                    'description' => $remark,
                    'is_active' => $isActive,
                ];

                $validator = Validator::make($data, [
                    'code' => ['nullable', 'string', 'max:100'],
                    'name' => ['required', 'string', 'max:255'],
                    'type' => ['nullable', Rule::in([
                        'detergent',
                        'softener',
                        'disinfectant',
                        'other',
                    ])],
                    'unit' => ['required', 'string', 'max:50'],
                    'description' => ['nullable', 'string'],
                    'is_active' => ['required', 'boolean'],
                ], [
                    'name.required' => 'กรุณาระบุชื่อสินค้า',
                    'type.in' => 'ประเภทสินค้าต้องเป็นค่าที่มีในรายการตัวเลือก',
                    'unit.required' => 'กรุณาระบุหน่วย',
                    'is_active.required' => 'สถานะต้องเป็น ใช้งาน / ไม่ใช้งาน / สินค้าหมด',
                ]);

                if ($validator->fails()) {
                    $rowErrors[] = 'แถว ' . $excelRow . ': '
                        . implode(', ', $validator->errors()->all());
                    continue;
                }

                // ตรวจราคา ถ้ามีการกรอก
                if ($price !== null && !is_numeric(str_replace(',', '', $price))) {
                    $rowErrors[] = 'แถว ' . $excelRow . ': ราคาต้องเป็นตัวเลข';
                    continue;
                }

                // ตรวจจำนวนคงเหลือ ถ้ามีการกรอก
                if ($stock !== null && !is_numeric(str_replace(',', '', $stock))) {
                    $rowErrors[] = 'แถว ' . $excelRow . ': จำนวนคงเหลือต้องเป็นตัวเลข';
                    continue;
                }

                $preparedRows[] = [
                    'excel_row' => $excelRow,
                    'data' => $validator->validated(),
                ];
            }

            if (!empty($rowErrors)) {
                return back()->withErrors([
                    'file' => "Import ไม่สำเร็จ กรุณาแก้ไขข้อมูลใน Excel ก่อน\n"
                        . implode("\n", array_slice($rowErrors, 0, 20)),
                ]);
            }

            if (empty($preparedRows)) {
                return back()->withErrors([
                    'file' => 'ไม่พบข้อมูลสินค้า/น้ำยาสำหรับ Import',
                ]);
            }

            $created = 0;
            $updated = 0;

            DB::transaction(function () use (
                $preparedRows,
                &$created,
                &$updated
            ) {
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

    private function normalizeExcelHeader($value): string
    {
        $value = trim((string) $value);

        // ตัด BOM กรณีมาจาก CSV/Excel บางโปรแกรม
        return preg_replace('/^\\xEF\\xBB\\xBF/', '', $value) ?? $value;
    }

    private function normalizeProductType(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        return match ($value) {
            'น้ำยาซักผ้า', 'detergent' => 'detergent',
            'น้ำยาปรับผ้านุ่ม', 'softener' => 'softener',
            'น้ำยาฆ่าเชื้อ', 'disinfectant' => 'disinfectant',
            'อื่น ๆ', 'อื่นๆ', 'other' => 'other',
            default => '__invalid__',
        };
    }

    private function normalizeProductStatus(?string $value): ?bool
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = strtolower(trim($value));

        if (in_array($value, ['ใช้งาน', '1', 'true', 'active'], true)) {
            return true;
        }

        if (in_array(
            $value,
            ['ไม่ใช้งาน', 'สินค้าหมด', '0', 'false', 'inactive'],
            true
        )) {
            return false;
        }

        return null;
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

}
