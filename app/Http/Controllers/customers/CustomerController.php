<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

        return view('content.pages.customers.index', compact('customers'));
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
                'points_balance' => 20,
                'total_topup' => (float) ($validated['total_topup'] ?? 0),
                'status' => $validated['status'],
                'is_active' => $request->boolean('is_active'),
                'is_new_member_discount_used' => false,
                'remark' => $validated['remark'] ?? null,
            ]);

            PointTransaction::create([
                'customer_id' => $customer->id,
                'type' => 'earn',
                'points' => 20,
                'balance_before' => 0,
                'balance_after' => 20,
                'reference_no' => $this->generatePointReference(),
                'description' => 'แต้มต้อนรับสมาชิกใหม่',
                'created_by' => Auth::id(),
            ]);
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

        return view('content.pages.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('content.pages.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $this->validateCustomer($request, $customer);

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

    public function adjustPoints(Request $request, Customer $customer)
    {
        $validated = $request->validate(
            [
                'adjustment_type' => [
                    'required',
                    Rule::in(['add', 'deduct']),
                ],
                'points' => [
                    'required',
                    'integer',
                    'min:1',
                ],
                'description' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ],
            [
                'adjustment_type.required' => 'กรุณาเลือกประเภทการปรับแต้ม',
                'points.required' => 'กรุณากรอกจำนวนแต้ม',
                'points.integer' => 'จำนวนแต้มต้องเป็นจำนวนเต็ม',
                'points.min' => 'จำนวนแต้มต้องมากกว่า 0',
                'description.required' => 'กรุณาระบุเหตุผลการปรับแต้ม',
            ]
        );

        DB::transaction(function () use ($validated, $customer) {
            /*
            |--------------------------------------------------------------------------
            | Lock แถวสมาชิก ป้องกันยอดแต้มชนกันเมื่อมีหลายรายการพร้อมกัน
            |--------------------------------------------------------------------------
            */
            $lockedCustomer = Customer::query()
                ->lockForUpdate()
                ->findOrFail($customer->id);

            $balanceBefore = (int) $lockedCustomer->points_balance;
            $adjustmentPoints = (int) $validated['points'];

            if ($validated['adjustment_type'] === 'deduct') {
                $adjustmentPoints *= -1;
            }

            $balanceAfter = $balanceBefore + $adjustmentPoints;

            if ($balanceAfter < 0) {
                abort(
                    422,
                    'ไม่สามารถลดแต้มได้ เนื่องจากแต้มคงเหลือไม่เพียงพอ'
                );
            }

            $lockedCustomer->update([
                'points_balance' => $balanceAfter,
            ]);

            PointTransaction::create([
                'customer_id' => $lockedCustomer->id,
                'type' => 'adjust',
                'points' => $adjustmentPoints,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_no' => $this->generatePointReference(),
                'description' => $validated['description'],
                'created_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'ปรับแต้มสมาชิกสำเร็จ');
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
                    Rule::unique('customers', 'member_code')
                        ->ignore($customer?->id),
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
                    Rule::unique('customers', 'phone')
                        ->ignore($customer?->id),
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('customers', 'email')
                        ->ignore($customer?->id),
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
            ],
            [
                'member_code.required' => 'กรุณากรอกรหัสสมาชิก',
                'member_code.unique' => 'รหัสสมาชิกนี้ถูกใช้งานแล้ว',
                'name.required' => 'กรุณากรอกชื่อสมาชิก',
                'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'phone.unique' => 'เบอร์โทรศัพท์นี้ถูกใช้งานแล้ว',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
                'member_type.required' => 'กรุณาเลือกประเภทสมาชิก',
                'branch_id.exists' => 'ไม่พบข้อมูลสาขาตู้ที่เลือก',
                'status.required' => 'กรุณาเลือกสถานะสมาชิก',
            ]
        );
    }

    private function generatePointReference(): string
    {
        return 'PT-' . now()->format('YmdHis') . '-' . strtoupper(
            substr(bin2hex(random_bytes(4)), 0, 6)
        );
    }
    public function export(Request $request)
{
    $query = Customer::query();

    if ($request->filled('keyword')) {
        $keyword = trim($request->keyword);

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
            $handle = fopen('php://output', 'w');

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

            foreach ($customers as $index => $customer) {
                $registeredAt = $customer->registered_at
                    ?? $customer->created_at;

                $memberTypeText = match (
                    $customer->member_type
                        ?? $customer->customer_type
                        ?? 'member'
                ) {
                    'new_member' => 'New member',
                    'non_member' => 'Non-member',
                    default => 'Member',
                };

                fputcsv($handle, [
                    $index + 1,

                    $registeredAt
                        ? $registeredAt->format('d/m/Y')
                        : '-',

                    $registeredAt
                        ? $registeredAt->format('H:i')
                        : '-',

                    $customer->member_code ?? '-',
                    $customer->name ?? '-',
                    $customer->email ?? '-',
                    $customer->phone ?? '-',
                    $customer->line_id ?? '-',

                    (int) ($customer->points_balance ?? 0),

                    $memberTypeText,

                    optional($customer->branch)->name
                        ?? $customer->branch_name
                        ?? '-',

                    $customer->last_used_at
                        ? $customer->last_used_at->format('d/m/Y H:i')
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
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]
    );
}
}
