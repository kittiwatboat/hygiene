<?php

namespace App\Http\Controllers;

use App\Models\MachineTank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = MachineTank::with(['machine.location', 'product'])
            ->orderBy('machine_id')
            ->orderBy('tank_no');

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
                ->orWhere('tank_name', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('stock_status')) {
            $query->where(function ($q) use ($request) {
                if ($request->stock_status === 'normal') {
                    $q->whereColumn('remaining_liters', '>', 'low_stock_liters');
                }

                if ($request->stock_status === 'low') {
                    $q->whereColumn('remaining_liters', '<=', 'low_stock_liters')
                        ->whereColumn('remaining_liters', '>', 'empty_stock_liters');
                }

                if ($request->stock_status === 'empty') {
                    $q->whereColumn('remaining_liters', '<=', 'empty_stock_liters');
                }
            });
        }

        $tanks = $query->get();

        return view('stock.index', compact('tanks'));
    }

    public function show(MachineTank $tank)
    {
        $tank->load(['machine.location', 'product']);

        return view('stock.show', compact('tank'));
    }

    public function adjust(Request $request, MachineTank $tank)
    {
        $request->validate(
            [
                'remaining_liters' => ['required', 'numeric', 'min:0'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'remaining_liters.required' => 'กรุณากรอกจำนวนน้ำยาคงเหลือ',
                'remaining_liters.numeric' => 'จำนวนน้ำยาคงเหลือต้องเป็นตัวเลข',
                'remaining_liters.min' => 'จำนวนน้ำยาคงเหลือต้องไม่น้อยกว่า 0',
            ]
        );

        DB::transaction(function () use ($request, $tank) {
            $tank->update([
                'remaining_liters' => $request->remaining_liters,
            ]);

            // เดี๋ยวตอนทำ stock_movements ค่อยเพิ่ม log การปรับยอดตรงนี้
        });

        return redirect()
            ->route('stock.show', $tank)
            ->with('success', 'ปรับยอด Stock สำเร็จ');
    }
}
