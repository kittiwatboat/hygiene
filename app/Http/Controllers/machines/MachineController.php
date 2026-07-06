<?php

namespace App\Http\Controllers\machines;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Machine;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::with(['location', 'tanks.product'])
            ->latest()
            ->get();

        return view('content.pages.machines.index', compact('machines'));
    }

    public function create()
    {
        $locations = Location::orderBy('name')->get();

        $products = Product::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('content.pages.machines.create', compact('locations', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:100', 'unique:machines,code'],
                'location_id' => ['nullable', 'exists:locations,id'],
                'serial_number' => ['nullable', 'string', 'max:255'],
                'model' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'in:active,maintenance,inactive,offline,error'],
                'remark' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],

                'tanks' => ['required', 'array'],
                'tanks.*.tank_no' => ['required', 'integer', 'between:1,4'],
                'tanks.*.product_id' => ['nullable', 'exists:products,id'],
                'tanks.*.tank_name' => ['nullable', 'string', 'max:255'],
                'tanks.*.capacity_liters' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.remaining_liters' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.low_stock_liters' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.empty_stock_liters' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.volume_per_press_ml' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.price_per_press' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.is_active' => ['nullable', 'boolean'],
            ],
            [
                'name.required' => 'กรุณากรอกชื่อตู้',
                'code.required' => 'กรุณากรอกรหัสตู้',
                'code.unique' => 'รหัสตู้นี้ถูกใช้งานแล้ว',
                'status.required' => 'กรุณาเลือกสถานะตู้',
            ]
        );

        DB::transaction(function () use ($request) {
            $machine = Machine::create([
                'name' => $request->name,
                'code' => $request->code,
                'location_id' => $request->location_id,
                'serial_number' => $request->serial_number,
                'model' => $request->model,
                'status' => $request->status,
                'remark' => $request->remark,
                'is_active' => $request->boolean('is_active'),
            ]);

           $tanksInput = collect($request->input('tanks', []))
    ->keyBy('tank_no');

for ($i = 1; $i <= 4; $i++) {
    $tank = $tanksInput->get($i, []);

    $hasProduct = !empty($tank['product_id']);

    $machine->tanks()->create([
        'tank_no' => $i,
        'product_id' => $hasProduct ? $tank['product_id'] : null,
        'tank_name' => $tank['tank_name'] ?? ('ช่องน้ำยาที่ ' . $i),
        'capacity_liters' => $tank['capacity_liters'] ?? 0,
        'remaining_liters' => $tank['remaining_liters'] ?? 0,
        'low_stock_liters' => $tank['low_stock_liters'] ?? 0,
        'empty_stock_liters' => $tank['empty_stock_liters'] ?? 0,
        'volume_per_press_ml' => $tank['volume_per_press_ml'] ?? 0,
        'price_per_press' => $tank['price_per_press'] ?? 0,
        'is_active' => isset($tank['is_active']) ? (bool) $tank['is_active'] : false,
    ]);
}
        });

        return redirect()
            ->route('machines.index')
            ->with('success', 'เพิ่มข้อมูลตู้สำเร็จ');
    }

    public function show(Machine $machine)
    {
        $machine->load(['location', 'tanks.product']);

        return view('content.pages.machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        $machine->load(['tanks.product']);

        $locations = Location::orderBy('name')->get();

        $products = Product::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('content.pages.machines.edit', compact('machine', 'locations', 'products'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:100', 'unique:machines,code,' . $machine->id],
                'location_id' => ['nullable', 'exists:locations,id'],
                'serial_number' => ['nullable', 'string', 'max:255'],
                'model' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'in:active,maintenance,inactive,offline,error'],
                'remark' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],

                'tanks' => ['required', 'array'],
                'tanks.*.tank_no' => ['required', 'integer', 'between:1,4'],
                'tanks.*.product_id' => ['nullable', 'exists:products,id'],
                'tanks.*.tank_name' => ['nullable', 'string', 'max:255'],
                'tanks.*.capacity_liters' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.remaining_liters' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.low_stock_liters' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.empty_stock_liters' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.volume_per_press_ml' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.price_per_press' => ['nullable', 'numeric', 'min:0'],
                'tanks.*.is_active' => ['nullable', 'boolean'],
            ],
            [
                'name.required' => 'กรุณากรอกชื่อตู้',
                'code.required' => 'กรุณากรอกรหัสตู้',
                'code.unique' => 'รหัสตู้นี้ถูกใช้งานแล้ว',
                'status.required' => 'กรุณาเลือกสถานะตู้',
            ]
        );

        DB::transaction(function () use ($request, $machine) {
            $machine->update([
                'name' => $request->name,
                'code' => $request->code,
                'location_id' => $request->location_id,
                'serial_number' => $request->serial_number,
                'model' => $request->model,
                'status' => $request->status,
                'remark' => $request->remark,
                'is_active' => $request->boolean('is_active'),
            ]);

            $tanksInput = collect($request->input('tanks', []))
    ->keyBy('tank_no');

for ($i = 1; $i <= 4; $i++) {
    $tank = $tanksInput->get($i, []);

    $machine->tanks()->updateOrCreate(
        [
            'tank_no' => $i,
        ],
        [
            'product_id' => !empty($tank['product_id']) ? $tank['product_id'] : null,
            'tank_name' => $tank['tank_name'] ?? ('ช่องน้ำยาที่ ' . $i),
            'capacity_liters' => $tank['capacity_liters'] ?? 0,
            'remaining_liters' => $tank['remaining_liters'] ?? 0,
            'low_stock_liters' => $tank['low_stock_liters'] ?? 0,
            'empty_stock_liters' => $tank['empty_stock_liters'] ?? 0,
            'volume_per_press_ml' => $tank['volume_per_press_ml'] ?? 0,
            'price_per_press' => $tank['price_per_press'] ?? 0,
            'is_active' => isset($tank['is_active']) ? (bool) $tank['is_active'] : false,
        ]
    );
}
        });

        return redirect()
            ->route('machines.index')
            ->with('success', 'แก้ไขข้อมูลตู้สำเร็จ');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();

        return redirect()
            ->route('machines.index')
            ->with('success', 'ลบข้อมูลตู้สำเร็จ');
    }
}
