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
    /**
     * Display a listing of vending machines.
     */
    public function index(Request $request): View
    {
        $keyword = $request->input('keyword');
        $locationId = $request->input('location_id');
        $status = $request->input('status');

        $vendingMachines = VendingMachine::query()
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

        return view('content.pages.machines.index', compact(
            'vendingMachines',
            'locations',
            'keyword',
            'locationId',
            'status'
        ));
    }

    /**
     * Show the form for creating a new vending machine.
     */
    public function create(): View
{
    $machine = new VendingMachine();

    $locations = Location::query()
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('machines.create', compact('machine', 'locations'));
}

    /**
     * Store a newly created vending machine in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateVendingMachine($request);

        $validated['is_active'] = $request->boolean('is_active');

        VendingMachine::create($validated);

        return redirect()
            ->route('vending-machines.index')
            ->with('success', 'เพิ่มตู้เรียบร้อยแล้ว');
    }

    /**
     * Display the specified vending machine.
     */
    public function show(VendingMachine $vendingMachine): View
    {
        $vendingMachine->load('location');

        return view('content.pages.machines.show', compact('vendingMachine'));
    }

    /**
     * Show the form for editing the specified vending machine.
     */
    public function edit(VendingMachine $machine)
{
    $locations = Location::query()
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('content.pages.machines.edit', compact('machine', 'locations'));
}

    /**
     * Update the specified vending machine in storage.
     */
    public function update(Request $request, VendingMachine $vendingMachine): RedirectResponse
    {
        $validated = $this->validateVendingMachine($request, $vendingMachine->id);

        $validated['is_active'] = $request->boolean('is_active');

        $vendingMachine->update($validated);

        return redirect()
            ->route('vending-machines.index')
            ->with('success', 'แก้ไขตู้เรียบร้อยแล้ว');
    }

    /**
     * Remove the specified vending machine from storage.
     */
    public function destroy(VendingMachine $vendingMachine): RedirectResponse
    {
        $vendingMachine->delete();

        return redirect()
            ->route('vending-machines.index')
            ->with('success', 'ลบตู้เรียบร้อยแล้ว');
    }

    /**
     * Validate vending machine request.
     */
    private function validateVendingMachine(Request $request, ?int $vendingMachineId = null): array
    {
        return $request->validate([
            'location_id' => ['nullable', 'exists:locations,id'],

            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100', 'unique:vending_machines,code,' . $vendingMachineId],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],

            'status' => ['required', 'string', 'max:50'],

            'capacity_liters' => ['nullable', 'numeric', 'min:0'],
            'remaining_liters' => ['nullable', 'numeric', 'min:0'],
            'volume_per_press_ml' => ['nullable', 'integer', 'min:0'],
            'price_per_press' => ['nullable', 'numeric', 'min:0'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'remark' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
