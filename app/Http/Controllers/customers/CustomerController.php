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

        Customer::create([
            'member_code' => $validated['member_code'],
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'points_balance' => 0,
            'status' => $validated['status'],
            'is_active' => $request->boolean('is_active'),
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'เพิ่มสมาชิกสำเร็จ');
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
            'status' => $validated['status'],
            'is_active' => $request->boolean('is_active'),
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
                    'max:100',
                    Rule::unique('customers', 'member_code')
                        ->ignore($customer?->id),
                ],
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'phone' => [
                    'nullable',
                    'string',
                    'max:30',
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
                'status' => [
                    'required',
                    Rule::in(['active', 'suspended', 'blocked']),
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
                'member_code.required' => 'กรุณากรอกรหัสสมาชิก',
                'member_code.unique' => 'รหัสสมาชิกนี้ถูกใช้งานแล้ว',
                'name.required' => 'กรุณากรอกชื่อสมาชิก',
                'phone.unique' => 'เบอร์โทรศัพท์นี้ถูกใช้งานแล้ว',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
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
}
