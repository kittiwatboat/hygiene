<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\ProductGroupPrice;
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
                    'tanks' => fn ($query) =>
                        $query->where('is_active', 1)->orderBy('tank_no'),
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
                    'message' =>
                        'ไม่พบข้อมูลเครื่องที่ตรงกับ Serial Number, รุ่นตู้ และรหัสตู้',
                    'data' => null,
                ], 404);
            }

            if (! $machine->machine_group_id || ! $machine->group) {
                return response()->json([
                    'success' => false,
                    'message' => 'เครื่องนี้ยังไม่ได้กำหนดกลุ่มตู้',
                    'data' => null,
                ], 422);
            }

            $groupPrices = ProductGroupPrice::query()
                ->where(
                    'machine_group_id',
                    $machine->machine_group_id
                )
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->orderBy('amount_ml')
                ->get()
                ->groupBy('product_id');

            $detergent = [];
            $softener = [];
            $other = [];

            foreach ($machine->tanks as $tank) {
                $product = $tank->product;

                if (! $product || ! (bool) $product->is_active) {
                    continue;
                }

                $prices = $groupPrices->get(
                    $product->id,
                    collect()
                );

                // กลุ่มไม่ได้กำหนดราคา = กลุ่มนี้ไม่ขายสินค้านี้
                if ($prices->isEmpty()) {
                    continue;
                }

                $priceOptions = $prices
                    ->map(function ($price) {
                        $normal = (float) $price->price;
                        $special = $price->special_price !== null
                            ? (float) $price->special_price
                            : null;

                        return [
                            'id' => $price->id,
                            'amount_ml' => (int) $price->amount_ml,
                            'price' => $normal,
                            'special_price' => $special,
                            'selling_price' => (
                                $special !== null
                                && $special < $normal
                            )
                                ? $special
                                : $normal,
                        ];
                    })
                    ->values();

                $item = [
                    'tank' => [
                        'id' => $tank->id,
                        'tank_no' => (int) $tank->tank_no,
                        'tank_name' => $tank->tank_name,
                        'capacity_liters' =>
                            $tank->capacity_liters !== null
                                ? (float) $tank->capacity_liters
                                : null,
                        'remaining_liters' =>
                            $tank->remaining_liters !== null
                                ? (float) $tank->remaining_liters
                                : null,
                        'low_stock_liters' =>
                            $tank->low_stock_liters !== null
                                ? (float) $tank->low_stock_liters
                                : null,
                        'empty_stock_liters' =>
                            $tank->empty_stock_liters !== null
                                ? (float) $tank->empty_stock_liters
                                : null,
                    ],
                    'product' => [
                        'id' => $product->id,
                        'code' => $product->code,
                        'name' => $product->name,
                        'type' => $product->type,
                        'unit' => $product->unit,
                        'description' => $product->description,
                        'image' => $product->image,
                        'image_url' => $product->image
                            ? asset(
                                'assets/img/products/' . $product->image
                            )
                            : null,
                        'price_options' => $priceOptions,
                    ],
                ];

                $type = mb_strtolower(
                    trim((string) $product->type)
                );

                if (in_array(
                    $type,
                    ['น้ำยาซักผ้า', 'detergent', 'laundry detergent'],
                    true
                )) {
                    $detergent[] = $item;
                } elseif (in_array(
                    $type,
                    ['น้ำยาปรับผ้านุ่ม', 'softener', 'fabric softener'],
                    true
                )) {
                    $softener[] = $item;
                } else {
                    $other[] = $item;
                }
            }

            return response()->json([
                'success' => true,
                'message' =>
                    'ดึงข้อมูลสินค้าพร้อมราคาตามกลุ่มตู้สำเร็จ',
                'data' => [
                    'machine' => [
                        'id' => $machine->id,
                        'name' => $machine->name,
                        'code' => $machine->code,
                        'serial_number' => $machine->serial_number,
                        'model' => $machine->model,
                        'machine_group_id' =>
                            $machine->machine_group_id,
                    ],
                    'machine_group' => [
                        'id' => $machine->group->id,
                        'name' => $machine->group->name,
                        'code' => $machine->group->code,
                    ],
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
