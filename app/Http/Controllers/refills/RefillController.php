<?php

namespace App\Http\Controllers\refills;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MachineTank;
use App\Models\Refill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefillController extends Controller
{
    public function index(Request $request)
    {
        $query = Refill::with([
                'machine',
                'tank.product',
                'product',
                'refillBy',
            ])
            ->latest('refill_at')
            ->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->whereHas('machine', function ($machineQuery) use ($keyword) {
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

        $refills = $query->get();

        $machines = Machine::orderBy('code')->get();

        return view('content.pages.refill.index', compact('refills', 'machines'));
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
            ->orderBy('machine_id')
            ->orderBy('tank_no')
            ->get();

        return view('content.pages.refill.create', compact('machines', 'tanks', 'selectedMachineId'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'machine_tank_id' => ['required', 'exists:machine_tanks,id'],
                'refill_liters' => ['required', 'numeric', 'min:0.01'],
                'refill_at' => ['nullable', 'date'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'machine_tank_id.required' => 'กรุณาเลือกช่องน้ำยาที่ต้องการเติม',
                'machine_tank_id.exists' => 'ไม่พบช่องน้ำยาที่เลือก',
                'refill_liters.required' => 'กรุณากรอกจำนวนลิตรที่เติม',
                'refill_liters.numeric' => 'จำนวนลิตรที่เติมต้องเป็นตัวเลข',
                'refill_liters.min' => 'จำนวนลิตรที่เติมต้องมากกว่า 0',
            ]
        );

        $refill = DB::transaction(function () use ($request) {
            $tank = MachineTank::with(['machine', 'product'])
                ->lockForUpdate()
                ->findOrFail($request->machine_tank_id);

            $beforeLiters = (float) $tank->remaining_liters;
            $refillLiters = (float) $request->refill_liters;
            $afterLiters = $beforeLiters + $refillLiters;

            $capacity = (float) $tank->capacity_liters;

            if ($capacity > 0 && $afterLiters > $capacity) {
                $afterLiters = $capacity;
            }

            $refill = Refill::create([
                'machine_id' => $tank->machine_id,
                'machine_tank_id' => $tank->id,
                'product_id' => $tank->product_id,
                'before_liters' => $beforeLiters,
                'refill_liters' => $refillLiters,
                'after_liters' => $afterLiters,
                'refill_by' => Auth::id(),
                'refill_at' => $request->refill_at ?: now(),
                'remark' => $request->remark,
            ]);

            $tank->update([
                'remaining_liters' => $afterLiters,
            ]);

            return $refill;
        });

        return redirect()
            ->route('refills.show', $refill)
            ->with('success', 'บันทึกเติมน้ำยาสำเร็จ');
    }

    public function show(Refill $refill)
    {
        $refill->load([
            'machine.location',
            'tank.product',
            'product',
            'refillBy',
        ]);

        return view('content.pages.refill.show', compact('refill'));
    }

    public function destroy(Refill $refill)
    {
        DB::transaction(function () use ($refill) {
            $tank = MachineTank::lockForUpdate()->find($refill->machine_tank_id);

            if ($tank) {
                $tank->update([
                    'remaining_liters' => $refill->before_liters,
                ]);
            }

            $refill->delete();
        });

        return redirect()
            ->route('refills.index')
            ->with('success', 'ลบบันทึกเติมน้ำยาและคืนค่า Stock เดิมสำเร็จ');
    }
}
