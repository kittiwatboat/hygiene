<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class MachineProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'serial_number' => ['required', 'string', 'max:255'],
                'model' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:100'],
            ]);

            $machine = Machine::query()
                ->with([
                    'group',
                    'tanks' => function ($query) {
                        $query->where('is_active', 1)->orderBy('tank_no');
                    },
                    'tanks.product',
                ])
                ->where('serial_number', $validated['serial_number'])
                ->where('model', $validated['model'])
                ->where('code', $validated['code'])
                ->where('is_active', 1)
                ->first();

            if (! $machine) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลเครื่องที่ตรงกับ Serial Number, รุ่นตู้ และรหัสตู้',
                    'data' => null,
                ], 404);
            }

            $detergent = [];
            $softener = [];
            $other = [];

            foreach ($machine->tanks as $tank) {
                $product = $tank->product;

                if (! $product || ! (bool) $product->is_active) {
                    continue;
                }

                $item = [
                    'tank' => [
                        'id' => $tank->id,
                        'tank_no' => (int) $tank->tank_no,
                        'tank_name' => $tank->tank_name,
                        'capacity_liters' => isset($tank->capacity_liters) ? (float) $tank->capacity_liters : null,
                        'remaining_liters' => isset($tank->remaining_liters) ? (float) $tank->remaining_liters : null,
                        'low_stock_liters' => isset($tank->low_stock_liters) ? (float) $tank->low_stock_liters : null,
                        'empty_stock_liters' => isset($tank->empty_stock_liters) ? (float) $tank->empty_stock_liters : null,
                        'volume_per_press_ml' => isset($tank->volume_per_press_ml) ? (float) $tank->volume_per_press_ml : null,
                        'price_per_press' => isset($tank->price_per_press) ? (float) $tank->price_per_press : null,
                    ],
                    'product' => [
                        'id' => $product->id,
                        'code' => $product->code,
                        'name' => $product->name,
                        'type' => $product->type,
                        'unit' => $product->unit,
                        'description' => $product->description,
                        'image' => $product->image,
                        'image_url' => $product->image ? asset('assets/img/products/' . $product->image) : null,
                        'is_active' => (bool) $product->is_active,
                    ],
                ];

                $type = mb_strtolower(trim((string) $product->type));

                if (in_array($type, ['น้ำยาซักผ้า', 'detergent', 'laundry detergent'], true)) {
                    $detergent[] = $item;
                    continue;
                }

                if (in_array($type, ['น้ำยาปรับผ้านุ่ม', 'softener', 'fabric softener'], true)) {
                    $softener[] = $item;
                    continue;
                }

                $other[] = $item;
            }

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลสินค้าของตู้สำเร็จ',
                'data' => [
                    'machine' => [
                        'id' => $machine->id,
                        'name' => $machine->name,
                        'code' => $machine->code,
                        'serial_number' => $machine->serial_number,
                        'model' => $machine->model,
                        'machine_group_id' => $machine->machine_group_id,
                    ],
                    'machine_group' => $machine->group ? [
                        'id' => $machine->group->id,
                        'name' => $machine->group->name,
                        'code' => $machine->group->code,
                    ] : null,
                    'products' => [
                        'detergent' => [
                            'label' => 'น้ำยาซักผ้า',
                            'count' => count($detergent),
                            'items' => $detergent,
                        ],
                        'softener' => [
                            'label' => 'น้ำยาปรับผ้านุ่ม',
                            'count' => count($softener),
                            'items' => $softener,
                        ],
                        'other' => [
                            'label' => 'อื่น ๆ',
                            'count' => count($other),
                            'items' => $other,
                        ],
                    ],
                ],
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลระบุเครื่องไม่ครบถ้วน',
                'errors' => $exception->errors(),
            ], 422);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถดึงข้อมูลสินค้าของตู้ได้',
            ], 500);
        }
    }
}
