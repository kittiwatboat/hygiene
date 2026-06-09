<?php

namespace App\Http\Controllers\machines;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\VendingMachine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MachineController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->input('keyword');
        $locationId = $request->input('location_id');
        $status = $request->input('status');

        $machines = VendingMachine::query()
            ->with('location')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . $keyword . '%')
                        ->orWhere('serial_number', 'like', '%' . $keyword . '%')
                        ->orWhere('model', 'like', '%' . $keyword . '%');
                });
            })
            ->when($locationId, function ($query) use ($locationId) {
                $query->where('location_id', $locationId);
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $locations = Location::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('content.pages.machines.index', compact('machines', 'locations', 'keyword', 'locationId', 'status'));
    }

    public function create(): View
    {
        $machine = new VendingMachine();

        $locations = Location::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('content.pages.machines.create', compact('machine', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateMachine($request);

        $validated['is_active'] = $request->boolean('is_active');

        VendingMachine::create($validated);

        return redirect()
            ->route('machines.index')
            ->with('success', 'เพิ่มตู้เรียบร้อยแล้ว');
    }

    public function show(VendingMachine $machine): View
    {
        $machine->load('location');

        return view('content.pages.machines.show', compact('machine'));
    }

    public function edit(VendingMachine $machine): View
    {
        $machine->load('location');

        $locations = Location::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('content.pages.machines.edit', compact('machine', 'locations'));
    }

    public function update(Request $request, VendingMachine $machine): RedirectResponse
    {
        $validated = $this->validateMachine($request, $machine->id);

        $validated['is_active'] = $request->boolean('is_active');

        $machine->update($validated);

        return redirect()
            ->route('machines.index')
            ->with('success', 'แก้ไขตู้เรียบร้อยแล้ว');
    }

    public function destroy(VendingMachine $machine): RedirectResponse
    {
        $machine->delete();

        return redirect()
            ->route('machines.index')
            ->with('success', 'ลบตู้เรียบร้อยแล้ว');
    }

    private function validateMachine(Request $request, ?int $machineId = null): array
    {
        return $request->validate([
            'location_id' => ['nullable', 'exists:locations,id'],

            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100', 'unique:vending_machines,code,' . $machineId],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],

            'capacity_liters' => ['nullable', 'numeric', 'min:0'],
            'remaining_liters' => ['nullable', 'numeric', 'min:0'],
            'volume_per_press_ml' => ['nullable', 'integer', 'min:0'],
            'price_per_press' => ['nullable', 'numeric', 'min:0'],

            'remark' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
