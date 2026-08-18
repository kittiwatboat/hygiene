<?php

namespace App\Http\Controllers\refills;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MachineGroup;
use App\Models\MachineTank;
use App\Models\Refill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        $machineGroups = MachineGroup::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        $machines = Machine::query()
            ->where('is_active', 1)
            ->whereNotNull('machine_group_id')
            ->orderBy('code')
            ->get();

        $tanks = MachineTank::query()
            ->with([
                'machine',
                'product',
            ])
            ->where('is_active', 1)
            ->whereNotNull('product_id')
            ->orderBy('machine_id')
            ->orderBy('tank_no')
            ->get();

        return view('content.pages.refill.create', compact(
            'machineGroups',
            'machines',
            'tanks'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'machine_tank_id' => [
                    'required',
                    'exists:machine_tanks,id',
                ],
                'refill_liters' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],
                'production_lot' => [
                    'nullable',
                    'string',
                    'max:100',
                ],
                'refill_at' => [
                    'nullable',
                    'date',
                ],
                'remark' => [
                    'nullable',
                    'string',
                ],
            ],
            [
                'machine_tank_id.required' =>
                    'กรุณาเลือกช่องน้ำยาที่ต้องการเติม',
                'machine_tank_id.exists' =>
                    'ไม่พบช่องน้ำยาที่เลือก',
                'refill_liters.required' =>
                    'กรุณากรอกจำนวนลิตรที่เติม',
                'refill_liters.numeric' =>
                    'จำนวนลิตรที่เติมต้องเป็นตัวเลข',
                'refill_liters.min' =>
                    'จำนวนลิตรที่เติมต้องมากกว่า 0',
                'production_lot.max' =>
                    'LOT การผลิตต้องไม่เกิน 100 ตัวอักษร',
            ]
        );

        $refill = DB::transaction(function () use ($validated) {
            $tank = MachineTank::with([
                    'machine',
                    'product',
                ])
                ->lockForUpdate()
                ->findOrFail($validated['machine_tank_id']);

            if (!$tank->product) {
                throw ValidationException::withMessages([
                    'machine_tank_id' =>
                        'ช่องน้ำยานี้ยังไม่ได้กำหนดสินค้า/น้ำยา',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | LOT การผลิต
            |--------------------------------------------------------------------------
            | บังคับกรอกเฉพาะน้ำยาซักผ้า (detergent)
            */
            $productionLot = null;

            if ($tank->product->type === 'detergent') {
                $productionLot = trim(
                    (string) ($validated['production_lot'] ?? '')
                );

                if ($productionLot === '') {
                    throw ValidationException::withMessages([
                        'production_lot' =>
                            'กรุณากรอก LOT การผลิตสำหรับน้ำยาซักผ้า',
                    ]);
                }
            }

            $beforeLiters = (float) $tank->remaining_liters;
            $refillLiters = (float) $validated['refill_liters'];
            $capacity = (float) $tank->capacity_liters;

            $afterLiters = $beforeLiters + $refillLiters;

            if ($capacity > 0 && $afterLiters > $capacity) {
                $afterLiters = $capacity;
            }

            /*
            |--------------------------------------------------------------------------
            | ป้องกันการกรอกเกินความจุ
            |--------------------------------------------------------------------------
            | ไม่ให้ข้อมูลบันทึกว่าเติม 10L แต่ Stock เพิ่มจริงแค่ 2L
            */
            $actualRefillLiters = $afterLiters - $beforeLiters;

            if ($actualRefillLiters <= 0) {
                throw ValidationException::withMessages([
                    'refill_liters' =>
                        'ช่องน้ำยานี้เต็มแล้ว ไม่สามารถเติมเพิ่มได้',
                ]);
            }

            if ($refillLiters > $actualRefillLiters) {
                throw ValidationException::withMessages([
                    'refill_liters' =>
                        'จำนวนที่เติมเกินความจุคงเหลือของช่องน้ำยา กรุณากรอกไม่เกิน '
                        . number_format($actualRefillLiters, 2)
                        . ' ลิตร',
                ]);
            }

            $refill = Refill::create([
                'machine_id' => $tank->machine_id,
                'machine_tank_id' => $tank->id,
                'product_id' => $tank->product_id,

                'before_liters' => $beforeLiters,
                'refill_liters' => $refillLiters,
                'after_liters' => $afterLiters,

                'production_lot' => $productionLot,

                'refill_by' => Auth::id(),
                'refill_at' => $validated['refill_at'] ?? now(),
                'remark' => $validated['remark'] ?? null,
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
            $tank = MachineTank::lockForUpdate()
                ->find($refill->machine_tank_id);

            if ($tank) {
                $tank->update([
                    'remaining_liters' => $refill->before_liters,
                ]);
            }

            $refill->delete();
        });

        return redirect()
            ->route('refills.index')
            ->with(
                'success',
                'ลบบันทึกเติมน้ำยาและคืนค่า Stock เดิมสำเร็จ'
            );
    }
}
