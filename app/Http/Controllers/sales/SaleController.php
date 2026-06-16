<?php

namespace App\Http\Controllers\sales;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MachineTank;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with([
                'machine.location',
                'tank.product',
                'product',
                'createdBy',
            ])
            ->latest('sold_at')
            ->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('transaction_ref', 'like', "%{$keyword}%")
                    ->orWhereHas('machine', function ($machineQuery) use ($keyword) {
                        $machineQuery->where('code', 'like', "%{$keyword}%")
                            ->orWhere('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('product', function ($productQuery) use ($keyword) {
                        $productQuery->where('name', 'like', "%{$keyword}%")
                            ->orWhere('code', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('tank', function ($tankQuery) use ($keyword) {
                        $tankQuery->where('tank_name', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sold_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sold_at', '<=', $request->date_to);
        }

        $sales = $query->get();

        $machines = Machine::orderBy('code')->get();

        return view('content.pages.sales.index', compact('sales', 'machines'));
    }

    public function create(Request $request)
    {
        $machines = Machine::with(['tanks.product'])
            ->where('is_active', 1)
            ->orderBy('code')
            ->get();

        $selectedMachineId = $request->machine_id;

        $tanks = MachineTank::with(['machine', 'product'])
            ->when($selectedMachineId, function ($query) use ($selectedMachineId) {
                $query->where('machine_id', $selectedMachineId);
            })
            ->where('is_active', 1)
            ->orderBy('machine_id')
            ->orderBy('tank_no')
            ->get();

        return view('content.pages.sales.create', compact('machines', 'tanks', 'selectedMachineId'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'machine_tank_id' => ['required', 'exists:machine_tanks,id'],
                'press_count' => ['required', 'integer', 'min:1'],
                'payment_method' => ['required', 'in:cash,qr,true_money,shopee_pay,card,free'],
                'payment_status' => ['required', 'in:paid,pending,failed,refunded'],
                'transaction_ref' => ['nullable', 'string', 'max:255'],
                'sold_at' => ['nullable', 'date'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'machine_tank_id.required' => 'กรุณาเลือกช่องน้ำยาที่ขาย',
                'machine_tank_id.exists' => 'ไม่พบช่องน้ำยาที่เลือก',
                'press_count.required' => 'กรุณากรอกจำนวนครั้งที่กด',
                'press_count.integer' => 'จำนวนครั้งที่กดต้องเป็นตัวเลขจำนวนเต็ม',
                'press_count.min' => 'จำนวนครั้งที่กดต้องมากกว่า 0',
                'payment_method.required' => 'กรุณาเลือกช่องทางชำระเงิน',
                'payment_status.required' => 'กรุณาเลือกสถานะชำระเงิน',
            ]
        );

        $sale = DB::transaction(function () use ($request) {
            $tank = MachineTank::with(['machine', 'product'])
                ->lockForUpdate()
                ->findOrFail($request->machine_tank_id);

            $pressCount = (int) $request->press_count;

            $volumePerPressMl = (float) $tank->volume_per_press_ml;
            $pricePerPress = (float) $tank->price_per_press;

            $volumeLiters = ($pressCount * $volumePerPressMl) / 1000;
            $amount = $pressCount * $pricePerPress;

            $currentStock = (float) $tank->remaining_liters;

            if ($request->payment_status === 'paid' && $volumeLiters > $currentStock) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'machine_tank_id' => 'Stock น้ำยาไม่พอสำหรับจำนวนครั้งที่กด',
                ]);
            }

            $sale = Sale::create([
                'machine_id' => $tank->machine_id,
                'machine_tank_id' => $tank->id,
                'product_id' => $tank->product_id,
                'press_count' => $pressCount,
                'volume_per_press_ml' => $volumePerPressMl,
                'volume_liters' => $volumeLiters,
                'price_per_press' => $pricePerPress,
                'amount' => $amount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'transaction_ref' => $request->transaction_ref,
                'sold_at' => $request->sold_at ?: now(),
                'created_by' => Auth::id(),
                'payload' => [
                    'source' => 'admin',
                    'tank_snapshot' => [
                        'tank_no' => $tank->tank_no,
                        'tank_name' => $tank->tank_name,
                        'product_name' => $tank->product?->name,
                    ],
                ],
                'remark' => $request->remark,
            ]);

            if ($request->payment_status === 'paid') {
                $afterStock = max($currentStock - $volumeLiters, 0);

                $tank->update([
                    'remaining_liters' => $afterStock,
                ]);

                app(\App\Services\SystemAlertService::class)
                    ->syncTankStock($tank->fresh());
            }

            return $sale;
        });

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'บันทึกรายการขายสำเร็จ');
    }

    public function show(Sale $sale)
    {
        $sale->load([
            'machine.location',
            'tank.product',
            'product',
            'createdBy',
        ]);

        return view('content.pages.sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            if ($sale->payment_status === 'paid') {
                $tank = MachineTank::lockForUpdate()->find($sale->machine_tank_id);

                if ($tank) {
                    $capacity = (float) $tank->capacity_liters;
                    $currentStock = (float) $tank->remaining_liters;
                    $restoreStock = $currentStock + (float) $sale->volume_liters;

                    if ($capacity > 0 && $restoreStock > $capacity) {
                        $restoreStock = $capacity;
                    }

                    $tank->update([
                        'remaining_liters' => $restoreStock,
                    ]);
                }
            }

            $sale->delete();
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'ลบรายการขายและคืน Stock สำเร็จ');
    }
}
