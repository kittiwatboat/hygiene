<?php

namespace App\Http\Controllers\printers;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrinterController extends Controller
{
    public function index()
    {
        $printers = Printer::with('machine')
            ->latest()
            ->get();

        return view('content.pages.printers.index', compact('printers'));
    }

    public function create()
    {
        $machines = Machine::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('content.pages.printers.create', compact('machines'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'machine_id' => ['nullable', 'exists:machines,id'],
                'code' => ['nullable', 'string', 'max:100', 'unique:printers,code'],
                'name' => ['required', 'string', 'max:255'],
                'brand' => ['nullable', 'string', 'max:255'],
                'model' => ['nullable', 'string', 'max:255'],
                'serial_number' => ['nullable', 'string', 'max:255'],
                'connection_type' => ['required', 'in:usb,lan,wifi,bluetooth'],
                'ip_address' => ['nullable', 'string', 'max:100'],
                'port' => ['nullable', 'integer', 'min:0', 'max:65535'],
                'paper_size' => ['nullable', 'string', 'max:100'],
                'status' => ['required', 'in:active,inactive,offline,error,paper_out'],
                'paper_available' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'name.required' => 'กรุณากรอกชื่อเครื่องปริ้น',
                'code.unique' => 'รหัสเครื่องปริ้นนี้ถูกใช้งานแล้ว',
                'machine_id.exists' => 'ไม่พบข้อมูลตู้ที่เลือก',
            ]
        );

        Printer::create([
            'machine_id' => $request->machine_id,
            'code' => $request->code,
            'name' => $request->name,
            'brand' => $request->brand,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'connection_type' => $request->connection_type,
            'ip_address' => $request->ip_address,
            'port' => $request->port,
            'paper_size' => $request->paper_size,
            'status' => $request->status,
            'paper_available' => $request->boolean('paper_available'),
            'is_active' => $request->boolean('is_active'),
            'remark' => $request->remark,
        ]);

        return redirect()
            ->route('printers.index')
            ->with('success', 'เพิ่มเครื่องปริ้นสำเร็จ');
    }

    public function show(Printer $printer)
    {
        $printer->load('machine');

        return view('content.pages.printers.show', compact('printer'));
    }

    public function edit(Printer $printer)
    {
        $machines = Machine::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('content.pages.printers.edit', compact('printer', 'machines'));
    }

    public function update(Request $request, Printer $printer)
    {
        $request->validate(
            [
                'machine_id' => ['nullable', 'exists:machines,id'],
                'code' => [
                    'nullable',
                    'string',
                    'max:100',
                    Rule::unique('printers', 'code')->ignore($printer->id),
                ],
                'name' => ['required', 'string', 'max:255'],
                'brand' => ['nullable', 'string', 'max:255'],
                'model' => ['nullable', 'string', 'max:255'],
                'serial_number' => ['nullable', 'string', 'max:255'],
                'connection_type' => ['required', 'in:usb,lan,wifi,bluetooth'],
                'ip_address' => ['nullable', 'string', 'max:100'],
                'port' => ['nullable', 'integer', 'min:0', 'max:65535'],
                'paper_size' => ['nullable', 'string', 'max:100'],
                'status' => ['required', 'in:active,inactive,offline,error,paper_out'],
                'paper_available' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'name.required' => 'กรุณากรอกชื่อเครื่องปริ้น',
                'code.unique' => 'รหัสเครื่องปริ้นนี้ถูกใช้งานแล้ว',
                'machine_id.exists' => 'ไม่พบข้อมูลตู้ที่เลือก',
            ]
        );

        $printer->update([
            'machine_id' => $request->machine_id,
            'code' => $request->code,
            'name' => $request->name,
            'brand' => $request->brand,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'connection_type' => $request->connection_type,
            'ip_address' => $request->ip_address,
            'port' => $request->port,
            'paper_size' => $request->paper_size,
            'status' => $request->status,
            'paper_available' => $request->boolean('paper_available'),
            'is_active' => $request->boolean('is_active'),
            'remark' => $request->remark,
        ]);

        return redirect()
            ->route('printers.index')
            ->with('success', 'แก้ไขเครื่องปริ้นสำเร็จ');
    }

    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect()
            ->route('printers.index')
            ->with('success', 'ลบเครื่องปริ้นสำเร็จ');
    }
}
