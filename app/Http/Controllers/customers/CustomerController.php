<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class CustomerController extends Controller
{
  public function index(Request $request)
  {
    $query = Customer::query()
      ->latest();

    if ($request->filled('keyword')) {
      $keyword = trim((string) $request->keyword);

      $query->where(function ($q) use ($keyword) {
        $q->where('member_code', 'like', "%{$keyword}%")
          ->orWhere('name', 'like', "%{$keyword}%")
          ->orWhere('phone', 'like', "%{$keyword}%")
          ->orWhere('email', 'like', "%{$keyword}%");
      });
    }

    if ($request->filled('member_type')) {
      $query->where('member_type', $request->member_type);
    }

    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    if ($request->has('is_active') && $request->is_active !== '') {
      $query->where('is_active', (int) $request->is_active);
    }

    $customers = $query->paginate(20)
      ->withQueryString();

    return view(
      'content.pages.customers.index',
      compact('customers')
    );
  }

  public function create()
  {
    return view('content.pages.customers.create');
  }

  public function store(Request $request)
  {
    $validated = $this->validateCustomer($request);

    DB::transaction(function () use ($request, $validated) {
      $customer = Customer::create([
        'member_code' => $validated['member_code'],
        'name' => $validated['name'],
        'phone' => $validated['phone'] ?? null,
        'email' => $validated['email'] ?? null,
        'line_id' => $validated['line_id'] ?? null,
        'member_type' => $validated['member_type'] ?? 'new_member',
        'registered_at' => $validated['registered_at'] ?? now(),
        'branch_id' => $validated['branch_id'] ?? null,

        // แต้มสมาชิกใหม่กำหนดโดยระบบเท่านั้น
        'points_balance' => 20,

        'total_topup' => (float) (
          $validated['total_topup'] ?? 0
        ),
        'status' => $validated['status'],
        'is_active' => $request->boolean('is_active'),
        'is_new_member_discount_used' => false,
        'remark' => $validated['remark'] ?? null,
      ]);

      $this->createWelcomePointTransaction($customer);
    });

    return redirect()
      ->route('customers.index')
      ->with(
        'success',
        'เพิ่มสมาชิกสำเร็จ และได้รับแต้มต้อนรับ 20 แต้ม'
      );
  }

  public function show(Customer $customer)
  {
    $customer->load([
      'pointTransactions' => function ($query) {
        $query->with(['promotion', 'creator'])
          ->latest();
      },
    ]);

    return view(
      'content.pages.customers.show',
      compact('customer')
    );
  }

  public function edit(Customer $customer)
  {
    return view(
      'content.pages.customers.edit',
      compact('customer')
    );
  }

  public function update(
    Request $request,
    Customer $customer
  ) {
    $validated = $this->validateCustomer(
      $request,
      $customer
    );

    /*
        |--------------------------------------------------------------------------
        | ไม่รับ points_balance จากหน้าจัดการสมาชิก
        |--------------------------------------------------------------------------
        | แต้มสามารถเปลี่ยนได้จาก flow การใช้งาน/โปรโมชั่นของระบบเท่านั้น
        */
    $customer->update([
      'member_code' => $validated['member_code'],
      'name' => $validated['name'],
      'phone' => $validated['phone'] ?? null,
      'email' => $validated['email'] ?? null,
      'line_id' => $validated['line_id'] ?? null,
      'member_type' => $validated['member_type'],
      'registered_at' => $validated['registered_at']
        ?? $customer->registered_at
        ?? $customer->created_at,
      'branch_id' => $validated['branch_id'] ?? null,
      'total_topup' => (float) (
        $validated['total_topup']
        ?? $customer->total_topup
        ?? 0
      ),
      'status' => $validated['status'],
      'is_active' => $request->boolean('is_active'),
      'is_new_member_discount_used' => $request->boolean(
        'is_new_member_discount_used'
      ),
      'remark' => $validated['remark'] ?? null,
    ]);

    return redirect()
      ->route('customers.index')
      ->with('success', 'แก้ไขสมาชิกสำเร็จ');
  }

  /*
    |--------------------------------------------------------------------------
    | Download Import Template
    |--------------------------------------------------------------------------
    | ใช้ CSV เพื่อให้เปิด/แก้ไขด้วย Excel ได้ทันที
    | ไม่ส่งออก column points_balance เพื่อป้องกันการแก้แต้มผ่าน Import
    */
  public function downloadImportTemplate()
  {
    $fileName = 'customer_import_template.csv';

    return response()->streamDownload(
      function () {
        $handle = fopen('php://output', 'w');

        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
          'member_code',
          'name',
          'phone',
          'email',
          'line_id',
          'member_type',
          'registered_at',
          'branch_id',
          'total_topup',
          'status',
          'is_active',
          'remark',
        ]);

        fputcsv($handle, [
          'MB000001',
          'สมาชิกตัวอย่าง',
          '0812345678',
          'example@email.com',
          '',
          'new_member',
          now()->format('Y-m-d H:i:s'),
          '',
          '0',
          'active',
          '1',
          '',
        ]);

        fclose($handle);
      },
      $fileName,
      [
        'Content-Type' =>
        'text/csv; charset=UTF-8',
      ]
    );
  }

  /*
    |--------------------------------------------------------------------------
    | Import Customer
    |--------------------------------------------------------------------------
    | รองรับ .xlsx, .xls, .csv ผ่าน PhpSpreadsheet
    | - Import เฉพาะสมาชิกใหม่
    | - ข้อมูลซ้ำจะข้าม
    | - ไม่อ่าน/ไม่แก้ points_balance จากไฟล์
    | - สมาชิกที่สร้างใหม่ได้รับแต้มต้อนรับ 20 แต้มจากระบบ
    */
  public function import(Request $request)
  {
    $request->validate(
      [
        'import_file' => [
          'required',
          'file',
          'mimes:xlsx,xls,csv',
          'max:10240',
        ],
      ],
      [
        'import_file.required' =>
        'กรุณาเลือกไฟล์สำหรับ Import',
        'import_file.mimes' =>
        'รองรับไฟล์ Excel .xlsx, .xls และ .csv เท่านั้น',
        'import_file.max' =>
        'ขนาดไฟล์ต้องไม่เกิน 10 MB',
      ]
    );

    try {
      $spreadsheet = IOFactory::load(
        $request->file('import_file')->getRealPath()
      );

      $rows = $spreadsheet
        ->getActiveSheet()
        ->toArray(null, true, true, false);
    } catch (Throwable $e) {
      return back()->with(
        'error',
        'ไม่สามารถอ่านไฟล์ Import ได้: '
          . $e->getMessage()
      );
    }

    if (count($rows) < 2) {
      return back()->with(
        'error',
        'ไม่พบข้อมูลสมาชิกในไฟล์ Import'
      );
    }

    $headers = array_map(
      fn($header) => $this->normalizeImportHeader($header),
      array_shift($rows)
    );

    $requiredHeaders = [
      'member_code',
      'name',
      'phone',
      'member_type',
      'status',
    ];

    $missingHeaders = array_values(
      array_diff($requiredHeaders, $headers)
    );

    if (!empty($missingHeaders)) {
      return back()->with(
        'error',
        'ไฟล์ Import ขาดคอลัมน์: '
          . implode(', ', $missingHeaders)
      );
    }

    $inserted = 0;
    $skipped = 0;
    $failed = 0;
    $errors = [];

    foreach ($rows as $rowIndex => $row) {
      $excelRowNumber = $rowIndex + 2;

      $row = array_pad(
        $row,
        count($headers),
        null
      );

      $data = [];

      foreach ($headers as $index => $header) {
        if ($header === '') {
          continue;
        }

        $data[$header] = $row[$index] ?? null;
      }

      if ($this->isEmptyImportRow($data)) {
        continue;
      }

      $memberCode = trim(
        (string) ($data['member_code'] ?? '')
      );

      $name = trim(
        (string) ($data['name'] ?? '')
      );

      $phone = trim(
        (string) ($data['phone'] ?? '')
      );

      $email = trim(
        (string) ($data['email'] ?? '')
      );

      if (
        $memberCode === ''
        || $name === ''
        || $phone === ''
      ) {
        $failed++;
        $errors[] =
          "แถว {$excelRowNumber}: "
          . 'กรุณากรอก member_code, name และ phone';

        continue;
      }

      $duplicateQuery = Customer::query()
        ->where(function ($query) use (
          $memberCode,
          $phone,
          $email
        ) {
          $query
            ->where('member_code', $memberCode)
            ->orWhere('phone', $phone);

          if ($email !== '') {
            $query->orWhere('email', $email);
          }
        });

      if ($duplicateQuery->exists()) {
        $skipped++;
        continue;
      }

      $memberType = trim(
        (string) (
          $data['member_type']
          ?? 'new_member'
        )
      );

      $status = trim(
        (string) (
          $data['status']
          ?? 'active'
        )
      );

      if (
        !in_array(
          $memberType,
          [
            'member',
            'non_member',
            'new_member',
          ],
          true
        )
      ) {
        $failed++;
        $errors[] =
          "แถว {$excelRowNumber}: "
          . 'member_type ไม่ถูกต้อง';

        continue;
      }

      if (
        !in_array(
          $status,
          [
            'active',
            'suspended',
            'blocked',
          ],
          true
        )
      ) {
        $failed++;
        $errors[] =
          "แถว {$excelRowNumber}: "
          . 'status ไม่ถูกต้อง';

        continue;
      }

      try {
        DB::transaction(function () use (
          $data,
          $memberCode,
          $name,
          $phone,
          $email,
          $memberType,
          $status
        ) {
          $customer = Customer::create([
            'member_code' => $memberCode,
            'name' => $name,
            'phone' => $phone,
            'email' => $email !== ''
              ? $email
              : null,
            'line_id' => $this->nullableString(
              $data['line_id'] ?? null
            ),
            'member_type' => $memberType,
            'registered_at' =>
            $this->parseImportDate(
              $data['registered_at']
                ?? null
            ) ?? now(),
            'branch_id' =>
            $this->nullableInteger(
              $data['branch_id']
                ?? null
            ),

            // ไม่รับแต้มจากไฟล์ Import
            'points_balance' => 20,

            'total_topup' => (float) (
              $data['total_topup'] ?? 0
            ),
            'status' => $status,
            'is_active' =>
            $this->toBoolean(
              $data['is_active']
                ?? 1
            ),
            'is_new_member_discount_used' =>
            false,
            'remark' => $this->nullableString(
              $data['remark'] ?? null
            ),
          ]);

          $this->createWelcomePointTransaction(
            $customer
          );
        });

        $inserted++;
      } catch (Throwable $e) {
        $failed++;

        $errors[] =
          "แถว {$excelRowNumber}: "
          . $e->getMessage();
      }
    }

    $message =
      "Import สมาชิกสำเร็จ {$inserted} รายการ";

    if ($skipped > 0) {
      $message .=
        ", ข้ามข้อมูลซ้ำ {$skipped} รายการ";
    }

    if ($failed > 0) {
      $message .=
        ", ไม่สำเร็จ {$failed} รายการ";
    }

    return redirect()
      ->route('customers.index')
      ->with('success', $message)
      ->with(
        'import_errors',
        array_slice($errors, 0, 20)
      );
  }

  public function destroy(Customer $customer)
  {
    if ($customer->pointTransactions()->exists()) {
      return back()->with(
        'error',
        'ไม่สามารถลบสมาชิกได้ เนื่องจากมีประวัติแต้มแล้ว กรุณาปิดใช้งานแทน'
      );
    }

    $customer->delete();

    return redirect()
      ->route('customers.index')
      ->with('success', 'ลบสมาชิกสำเร็จ');
  }

  public function export(Request $request)
  {
    $query = Customer::query();

    if ($request->filled('keyword')) {
      $keyword = trim((string) $request->keyword);

      $query->where(function ($q) use ($keyword) {
        $q->where(
          'member_code',
          'like',
          "%{$keyword}%"
        )
          ->orWhere(
            'name',
            'like',
            "%{$keyword}%"
          )
          ->orWhere(
            'phone',
            'like',
            "%{$keyword}%"
          )
          ->orWhere(
            'email',
            'like',
            "%{$keyword}%"
          );
      });
    }

    if ($request->filled('member_type')) {
      $query->where(
        'member_type',
        $request->member_type
      );
    }

    if ($request->filled('is_active')) {
      $query->where(
        'is_active',
        $request->boolean('is_active')
      );
    }

    $customers = $query
      ->with('branch')
      ->orderByDesc('registered_at')
      ->orderByDesc('id')
      ->get();

    $fileName = 'customers_'
      . now()->format('Ymd_His')
      . '.csv';

    return response()->streamDownload(
      function () use ($customers) {
        $handle = fopen(
          'php://output',
          'w'
        );

        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
          'ลำดับ',
          'วันที่สมัคร',
          'เวลา',
          'รหัสสมาชิก',
          'ชื่อสมาชิก',
          'อีเมล',
          'เบอร์โทร',
          'LINE ID',
          'แต้มคงเหลือ',
          'ประเภทสมาชิก',
          'สาขาตู้',
          'ใช้งานล่าสุด',
          'ยอดเติมสะสม',
          'สถานะ',
          'เปิดใช้งาน',
        ]);

        foreach (
          $customers as $index => $customer
        ) {
          $registeredAt =
            $customer->registered_at
            ?? $customer->created_at;

          $memberTypeText = match ($customer->member_type
            ?? $customer->customer_type
            ?? 'member') {
            'new_member' => 'New member',
            'non_member' => 'Non-member',
            default => 'Member',
          };

          fputcsv($handle, [
            $index + 1,

            $registeredAt
              ? $registeredAt->format(
                'd/m/Y'
              )
              : '-',

            $registeredAt
              ? $registeredAt->format(
                'H:i'
              )
              : '-',

            $customer->member_code ?? '-',
            $customer->name ?? '-',
            $customer->email ?? '-',
            $customer->phone ?? '-',
            $customer->line_id ?? '-',

            (int) (
              $customer->points_balance
              ?? 0
            ),

            $memberTypeText,

            optional(
              $customer->branch
            )->name
              ?? $customer->branch_name
              ?? '-',

            $customer->last_used_at
              ? $customer->last_used_at
              ->format(
                'd/m/Y H:i'
              )
              : '-',

            number_format(
              (float) (
                $customer->total_topup
                ?? $customer->total_amount
                ?? $customer->total_spent
                ?? 0
              ),
              2,
              '.',
              ''
            ),

            $customer->status_text
              ?? $customer->status
              ?? '-',

            $customer->is_active
              ? 'เปิด'
              : 'ปิด',
          ]);
        }

        fclose($handle);
      },
      $fileName,
      [
        'Content-Type' =>
        'text/csv; charset=UTF-8',
      ]
    );
  }

  private function validateCustomer(
    Request $request,
    ?Customer $customer = null
  ): array {
    return $request->validate(
      [
        'member_code' => [
          'required',
          'string',
          'max:50',
          Rule::unique(
            'customers',
            'member_code'
          )->ignore($customer?->id),
        ],
        'name' => [
          'required',
          'string',
          'max:255',
        ],
        'phone' => [
          'required',
          'string',
          'max:20',
          Rule::unique(
            'customers',
            'phone'
          )->ignore($customer?->id),
        ],
        'email' => [
          'nullable',
          'email',
          'max:255',
          Rule::unique(
            'customers',
            'email'
          )->ignore($customer?->id),
        ],
        'line_id' => [
          'nullable',
          'string',
          'max:100',
        ],
        'member_type' => [
          'required',
          Rule::in([
            'member',
            'non_member',
            'new_member',
          ]),
        ],
        'registered_at' => [
          'nullable',
          'date',
        ],
        'branch_id' => [
          'nullable',
          'integer',
          'exists:branches,id',
        ],
        'total_topup' => [
          'nullable',
          'numeric',
          'min:0',
        ],
        'status' => [
          'required',
          Rule::in([
            'active',
            'suspended',
            'blocked',
          ]),
        ],
        'is_active' => [
          'nullable',
          'boolean',
        ],
        'is_new_member_discount_used' => [
          'nullable',
          'boolean',
        ],
        'remark' => [
          'nullable',
          'string',
        ],

        /*
                |--------------------------------------------------------------------------
                | ไม่มี points_balance ใน validation
                |--------------------------------------------------------------------------
                | ป้องกันการแก้แต้มผ่าน request ของหน้า create/edit
                */
      ],
      [
        'member_code.required' =>
        'กรุณากรอกรหัสสมาชิก',
        'member_code.unique' =>
        'รหัสสมาชิกนี้ถูกใช้งานแล้ว',
        'name.required' =>
        'กรุณากรอกชื่อสมาชิก',
        'phone.required' =>
        'กรุณากรอกเบอร์โทรศัพท์',
        'phone.unique' =>
        'เบอร์โทรศัพท์นี้ถูกใช้งานแล้ว',
        'email.email' =>
        'รูปแบบอีเมลไม่ถูกต้อง',
        'email.unique' =>
        'อีเมลนี้ถูกใช้งานแล้ว',
        'member_type.required' =>
        'กรุณาเลือกประเภทสมาชิก',
        'branch_id.exists' =>
        'ไม่พบข้อมูลสาขาตู้ที่เลือก',
        'status.required' =>
        'กรุณาเลือกสถานะสมาชิก',
      ]
    );
  }

  private function createWelcomePointTransaction(
    Customer $customer
  ): void {
    PointTransaction::create([
      'customer_id' => $customer->id,
      'type' => 'earn',
      'points' => 20,
      'balance_before' => 0,
      'balance_after' => 20,
      'reference_no' =>
      $this->generatePointReference(),
      'description' =>
      'แต้มต้อนรับสมาชิกใหม่',
      'created_by' => Auth::id(),
    ]);
  }

  private function generatePointReference(): string
  {
    return 'PT-'
      . now()->format('YmdHis')
      . '-'
      . strtoupper(
        substr(
          bin2hex(random_bytes(4)),
          0,
          6
        )
      );
  }

  private function normalizeImportHeader(
    mixed $header
  ): string {
    return Str::of((string) $header)
      ->trim()
      ->lower()
      ->replace(' ', '_')
      ->replace('-', '_')
      ->toString();
  }

  private function isEmptyImportRow(
    array $data
  ): bool {
    foreach ($data as $value) {
      if (
        $value !== null
        && trim((string) $value) !== ''
      ) {
        return false;
      }
    }

    return true;
  }

  private function nullableString(
    mixed $value
  ): ?string {
    $value = trim((string) $value);

    return $value === ''
      ? null
      : $value;
  }

  private function nullableInteger(
    mixed $value
  ): ?int {
    if (
      $value === null
      || trim((string) $value) === ''
    ) {
      return null;
    }

    return (int) $value;
  }

  private function toBoolean(
    mixed $value
  ): bool {
    if (is_bool($value)) {
      return $value;
    }

    $value = strtolower(
      trim((string) $value)
    );

    return in_array(
      $value,
      [
        '1',
        'true',
        'yes',
        'y',
        'on',
        'เปิด',
        'active',
      ],
      true
    );
  }

  private function parseImportDate(
    mixed $value
  ): mixed {
    if (
      $value === null
      || trim((string) $value) === ''
    ) {
      return null;
    }

    if (is_numeric($value)) {
      try {
        return ExcelDate::excelToDateTimeObject(
          (float) $value
        );
      } catch (Throwable) {
        return null;
      }
    }

    try {
      return \Carbon\Carbon::parse(
        (string) $value
      );
    } catch (Throwable) {
      return null;
    }
  }
}
