<?php

namespace App\Http\Controllers\machines;

use App\Http\Controllers\Controller;
use App\Models\FrontendLanguage;
use App\Models\FrontendMachineLanguageSetting;
use App\Models\Location;
use App\Models\Machine;
use App\Models\MachineGroup;
use App\Models\Product;
use App\Models\ProductGroupPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::query()
            ->with(['location', 'tanks.product', 'group.theme'])
            ->latest()
            ->get();

        return view('content.pages.machines.index', compact('machines'));
    }

    public function create()
    {
        $locations = Location::orderBy('name')->get();

        $machineGroups = MachineGroup::query()
    ->with([
        'productPrices' => function ($query) {
            $query
                ->where('is_active', 1)
                ->select([
                    'id',
                    'machine_group_id',
                    'product_id',
                    'amount_ml',
                    'price',
                    'special_price',
                    'is_active',
                    'sort_order',
                ]);
        },
    ])
    ->where('is_active', true)
    ->orderBy('name')
    ->get();

        $products = Product::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        $frontendLanguages = FrontendLanguage::query()
            ->where('is_active', 1)
            ->whereHas('setting', function ($query) {
                $query->where('is_active', 1);
            })
            ->with('setting')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view(
            'content.pages.machines.create',
            compact('locations', 'products', 'frontendLanguages', 'machineGroups')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validateMachine($request);
        $this->validateMachineLanguages($request);
        $this->validateTankProductsForMachineGroup(
            $request,
            $validated['machine_group_id'] ?? null
        );

        DB::transaction(function () use ($request, $validated) {
            $machine = Machine::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'location_id' => $validated['location_id'] ?? null,
                'serial_number' => $validated['serial_number'] ?? null,
                'model' => $validated['model'] ?? null,
                'status' => $validated['status'],
                'remark' => $validated['remark'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'machine_group_id' => $validated['machine_group_id'] ?? null,
            ]);

            $tanksInput = collect($request->input('tanks', []))->keyBy('tank_no');

            for ($i = 1; $i <= 4; $i++) {
                $tank = $tanksInput->get($i, []);

                $machine->tanks()->create([
                    'tank_no' => $i,
                    'product_id' => !empty($tank['product_id']) ? $tank['product_id'] : null,
                    'tank_name' => $tank['tank_name'] ?? ('ช่องน้ำยาที่ ' . $i),
                    'capacity_liters' => $tank['capacity_liters'] ?? 0,
                    'remaining_liters' => $tank['remaining_liters'] ?? 0,
                    'low_stock_liters' => $tank['low_stock_liters'] ?? 0,
                    'empty_stock_liters' => $tank['empty_stock_liters'] ?? 0,
                    'is_active' => isset($tank['is_active']) ? (bool) $tank['is_active'] : false,
                ]);
            }

            $this->syncMachineLanguages($request, $machine);
        });

        return redirect()
            ->route('machines.index')
            ->with('success', 'เพิ่มข้อมูลตู้สำเร็จ');
    }

    public function show(Machine $machine)
{
    $machine->load([
        'location',
        'tanks.product',
        'group.theme',
        'group.productPrices' => function ($query) {
            $query
                ->where('is_active', 1)
                ->orderBy('product_id')
                ->orderBy('sort_order')
                ->orderBy('amount_ml');
        },
    ]);

    return view(
        'content.pages.machines.show',
        compact('machine')
    );
}

    public function edit(Machine $machine)
    {
        $machine->load([
            'tanks.product',
            'frontendMachineLanguageSettings.language',
        ]);

        $machineGroups = MachineGroup::query()
    ->with([
        'productPrices' => function ($query) {
            $query
                ->where('is_active', 1)
                ->select([
                    'id',
                    'machine_group_id',
                    'product_id',
                    'amount_ml',
                    'price',
                    'special_price',
                    'is_active',
                    'sort_order',
                ]);
        },
    ])
    ->where(function ($query) use ($machine) {
        $query
            ->where('is_active', true)
            ->orWhere('id', $machine->machine_group_id);
    })
    ->orderBy('name')
    ->get();

        $locations = Location::orderBy('name')->get();

        $products = Product::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        $frontendLanguages = FrontendLanguage::query()
            ->where('is_active', 1)
            ->whereHas('setting', function ($query) {
                $query->where('is_active', 1);
            })
            ->with('setting')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view(
            'content.pages.machines.edit',
            compact('machine', 'locations', 'products', 'frontendLanguages', 'machineGroups')
        );
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $this->validateMachine($request, $machine);
        $this->validateMachineLanguages($request);
        $this->validateTankProductsForMachineGroup(
            $request,
            $validated['machine_group_id'] ?? null
        );

        DB::transaction(function () use ($request, $machine, $validated) {
            $machine->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'location_id' => $validated['location_id'] ?? null,
                'serial_number' => $validated['serial_number'] ?? null,
                'model' => $validated['model'] ?? null,
                'status' => $validated['status'],
                'remark' => $validated['remark'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'machine_group_id' => $validated['machine_group_id'] ?? null,
            ]);

            $tanksInput = collect($request->input('tanks', []))->keyBy('tank_no');

            for ($i = 1; $i <= 4; $i++) {
                $tank = $tanksInput->get($i, []);

                $machine->tanks()->updateOrCreate(
                    ['tank_no' => $i],
                    [
                        'product_id' => !empty($tank['product_id']) ? $tank['product_id'] : null,
                        'tank_name' => $tank['tank_name'] ?? ('ช่องน้ำยาที่ ' . $i),
                        'capacity_liters' => $tank['capacity_liters'] ?? 0,
                        'remaining_liters' => $tank['remaining_liters'] ?? 0,
                        'low_stock_liters' => $tank['low_stock_liters'] ?? 0,
                        'empty_stock_liters' => $tank['empty_stock_liters'] ?? 0,
                        'is_active' => isset($tank['is_active']) ? (bool) $tank['is_active'] : false,
                    ]
                );
            }

            $this->syncMachineLanguages($request, $machine);
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

    private function validateMachine(Request $request, ?Machine $machine = null): array
    {
        $codeRule = 'unique:machines,code';

        if ($machine) {
            $codeRule .= ',' . $machine->id;
        }

        return $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:100', $codeRule],
                'location_id' => ['nullable', 'exists:locations,id'],
                'machine_group_id' => ['nullable', 'integer', 'exists:machine_groups,id'],
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
                'tanks.*.is_active' => ['nullable', 'boolean'],

                'use_custom_languages' => ['nullable', 'boolean'],
                'machine_language_ids' => ['nullable', 'array', 'max:3'],
                'machine_language_ids.*' => ['exists:frontend_languages,id'],
                'default_machine_language_id' => ['nullable', 'exists:frontend_languages,id'],
            ],
            [
                'name.required' => 'กรุณากรอกชื่อตู้',
                'code.required' => 'กรุณากรอกรหัสตู้',
                'code.unique' => 'รหัสตู้นี้ถูกใช้งานแล้ว',
                'status.required' => 'กรุณาเลือกสถานะตู้',
                'machine_group_id.exists' => 'กลุ่มตู้ที่เลือกไม่ถูกต้อง',
                'tanks.*.product_id.exists' => 'สินค้า/น้ำยาที่เลือกไม่ถูกต้อง',
            ]
        );
    }

    private function validateTankProductsForMachineGroup(
        Request $request,
        ?int $machineGroupId
    ): void {
        $selectedTanks = collect($request->input('tanks', []))
            ->filter(fn ($tank) => !empty($tank['product_id']));

        if ($selectedTanks->isEmpty()) {
            return;
        }

        if (!$machineGroupId) {
            throw ValidationException::withMessages([
                'machine_group_id' => 'กรุณาเลือกกลุ่มตู้ก่อนกำหนดสินค้าใน Tank',
            ]);
        }

        $machineGroup = MachineGroup::find($machineGroupId);

        if (!$machineGroup) {
            throw ValidationException::withMessages([
                'machine_group_id' => 'ไม่พบกลุ่มตู้ที่เลือก',
            ]);
        }

        $productIds = $selectedTanks
            ->pluck('product_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $allowedProductIds = ProductGroupPrice::query()
            ->where('machine_group_id', $machineGroupId)
            ->where('is_active', 1)
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->unique();

        $invalidProductIds = $productIds
            ->diff($allowedProductIds)
            ->values();

        if ($invalidProductIds->isEmpty()) {
            return;
        }

        $invalidProducts = Product::query()
            ->whereIn('id', $invalidProductIds)
            ->get(['id', 'code', 'name'])
            ->keyBy('id');

        $errors = [];

        foreach ($selectedTanks as $index => $tank) {
            $productId = (int) ($tank['product_id'] ?? 0);

            if (!$invalidProductIds->contains($productId)) {
                continue;
            }

            $product = $invalidProducts->get($productId);
            $label = $product
                ? $product->name . (!empty($product->code) ? ' (' . $product->code . ')' : '')
                : 'สินค้าที่เลือก';
            $tankNo = $tank['tank_no'] ?? ($index + 1);

            $errors["tanks.{$index}.product_id"] =
                "Tank {$tankNo}: {$label} ยังไม่ได้กำหนดราคาเปิดใช้งานในกลุ่ม {$machineGroup->name}";
        }

        throw ValidationException::withMessages($errors);
    }

    private function validateMachineLanguages(Request $request): void
    {
        if (!$request->boolean('use_custom_languages')) {
            return;
        }

        $languageIds = array_values(
            array_map('strval', $request->input('machine_language_ids', []))
        );

        $defaultLanguageId = (string) $request->input('default_machine_language_id');

        if (count($languageIds) < 1) {
            throw ValidationException::withMessages([
                'machine_language_ids' => 'กรุณาเลือกภาษาอย่างน้อย 1 ภาษา',
            ]);
        }

        if (count($languageIds) > 3) {
            throw ValidationException::withMessages([
                'machine_language_ids' => 'เลือกภาษาได้สูงสุด 3 ภาษา',
            ]);
        }

        if (!$defaultLanguageId || !in_array($defaultLanguageId, $languageIds, true)) {
            throw ValidationException::withMessages([
                'default_machine_language_id' => 'ภาษาหลักต้องอยู่ในภาษาที่เลือกใช้งาน',
            ]);
        }
    }

    private function syncMachineLanguages(Request $request, Machine $machine): void
    {
        if (!$request->boolean('use_custom_languages')) {
            FrontendMachineLanguageSetting::where('machine_id', $machine->id)->delete();
            return;
        }

        $languageIds = array_values(
            array_map('strval', $request->input('machine_language_ids', []))
        );

        $defaultLanguageId = (string) $request->input('default_machine_language_id');

        FrontendMachineLanguageSetting::where('machine_id', $machine->id)->delete();

        foreach ($languageIds as $index => $languageId) {
            FrontendMachineLanguageSetting::create([
                'machine_id' => $machine->id,
                'language_id' => $languageId,
                'sort_order' => $index + 1,
                'is_default' => (string) $languageId === (string) $defaultLanguageId,
                'is_active' => true,
            ]);
        }
    }
}
