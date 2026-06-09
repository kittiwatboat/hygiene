<?php

namespace App\Http\Controllers\Machines;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::query()
            ->orderByDesc('id')
            ->get();

        return view('content.pages.machines.index', compact('machines'));
    }

    public function create()
    {
        return view('content.pages.machines.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'machine_code' => 'required|string|max:100|unique:machines,machine_code',
            'machine_name' => 'required|string|max:255',
            'location_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'tank_capacity_liter' => 'required|numeric|min:0',
            'current_stock_liter' => 'required|numeric|min:0',
            'volume_per_press_ml' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,maintenance,out_of_stock',
            'note' => 'nullable|string',
        ], [
            'machine_code.required' => 'กรุณากรอกรหัสตู้',
            'machine_code.unique' => 'รหัสตู้นี้ถูกใช้งานแล้ว',
            'machine_name.required' => 'กรุณากรอกชื่อตู้',
            'tank_capacity_liter.required' => 'กรุณากรอกความจุถัง',
            'current_stock_liter.required' => 'กรุณากรอกปริมาณคงเหลือ',
            'volume_per_press_ml.required' => 'กรุณากรอกปริมาณต่อการกด',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('machines.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            Machine::create([
                'machine_code' => trim($request->machine_code),
                'machine_name' => trim($request->machine_name),
                'location_name' => $request->location_name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'tank_capacity_liter' => $request->tank_capacity_liter ?? 0,
                'current_stock_liter' => $request->current_stock_liter ?? 0,
                'volume_per_press_ml' => $request->volume_per_press_ml ?? 0,
                'total_press_count' => 0,
                'status' => $request->status,
                'note' => $request->note,
            ]);

            DB::commit();

            return redirect()
                ->route('machines.index')
                ->with('success', 'เพิ่มตู้สำเร็จ');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('machines.create')
                ->withInput()
                ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function show(Machine $machine)
    {
        return view('content.pages.machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        return view('content.pages.machines.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validator = Validator::make($request->all(), [
            'machine_code' => 'required|string|max:100|unique:machines,machine_code,' . $machine->id,
            'machine_name' => 'required|string|max:255',
            'location_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'tank_capacity_liter' => 'required|numeric|min:0',
            'current_stock_liter' => 'required|numeric|min:0',
            'volume_per_press_ml' => 'required|numeric|min:0',
            'total_press_count' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,maintenance,out_of_stock',
            'note' => 'nullable|string',
        ], [
            'machine_code.required' => 'กรุณากรอกรหัสตู้',
            'machine_code.unique' => 'รหัสตู้นี้ถูกใช้งานแล้ว',
            'machine_name.required' => 'กรุณากรอกชื่อตู้',
            'tank_capacity_liter.required' => 'กรุณากรอกความจุถัง',
            'current_stock_liter.required' => 'กรุณากรอกปริมาณคงเหลือ',
            'volume_per_press_ml.required' => 'กรุณากรอกปริมาณต่อการกด',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('machines.edit', $machine)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $machine->update([
                'machine_code' => trim($request->machine_code),
                'machine_name' => trim($request->machine_name),
                'location_name' => $request->location_name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'tank_capacity_liter' => $request->tank_capacity_liter ?? 0,
                'current_stock_liter' => $request->current_stock_liter ?? 0,
                'volume_per_press_ml' => $request->volume_per_press_ml ?? 0,
                'total_press_count' => $request->total_press_count ?? 0,
                'status' => $request->status,
                'note' => $request->note,
            ]);

            DB::commit();

            return redirect()
                ->route('machines.index')
                ->with('success', 'แก้ไขข้อมูลตู้สำเร็จ');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('machines.edit', $machine)
                ->withInput()
                ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function destroy(Machine $machine)
    {
        try {
            $machine->delete();

            return redirect()
                ->route('machines.index')
                ->with('success', 'ลบตู้สำเร็จ');
        } catch (\Throwable $e) {
            return redirect()
                ->route('machines.index')
                ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
