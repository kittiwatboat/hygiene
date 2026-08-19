<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\KioskSelection;
use App\Models\Machine;
use App\Models\MachineTank;
use App\Models\ProductGroupPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class KioskSelectionController extends Controller
{
    public function confirm(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'items' => ['required', 'array', 'min:1', 'max:4'],
                'items.*.tank_id' => ['required', 'integer'],
                'items.*.price_option_id' => ['required', 'integer'],
                'items.*.quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
            ], [
                'items.required' => 'กรุณาเลือกสินค้า/น้ำยา',
                'items.array' => 'รูปแบบรายการสินค้าไม่ถูกต้อง',
                'items.min' => 'กรุณาเลือกสินค้า/น้ำยาอย่างน้อย 1 รายการ',
                'items.*.tank_id.required' => 'ไม่พบช่องน้ำยาที่เลือก',
                'items.*.price_option_id.required' => 'ไม่พบราคาหรือปริมาตรที่เลือก',
            ]);

            /*
            |--------------------------------------------------------------------------
            | หา Machine จาก tank_id ที่เลือก
            |--------------------------------------------------------------------------
            | ไม่ต้องส่ง serial_number / model / code ซ้ำ
            */
            $firstTank = MachineTank::query()
                ->with('machine.group')
                ->where('is_active', 1)
                ->find((int) $validated['items'][0]['tank_id']);

            if (!$firstTank || !$firstTank->machine) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบตู้หรือช่องน้ำยาที่เลือก',
                ], 404);
            }

            $machine = Machine::query()
                ->with([
                    'group',
                    'tanks' => fn ($q) => $q
                        ->where('is_active', 1)
                        ->orderBy('tank_no'),
                    'tanks.product',
                ])
                ->where('id', $firstTank->machine_id)
                ->where('is_active', 1)
                ->first();

            if (!$machine) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลเครื่อง',
                ], 404);
            }

            if (!$machine->machine_group_id || !$machine->group) {
                return response()->json([
                    'success' => false,
                    'message' => 'เครื่องนี้ยังไม่ได้กำหนดกลุ่มตู้',
                ], 422);
            }

            $tanks = $machine->tanks->keyBy('id');

            $selectedItems = collect();
            $subtotal = 0.0;
            $productDiscount = 0.0;
            $netTotal = 0.0;

            foreach ($validated['items'] as $index => $input) {
                $tank = $tanks->get((int) $input['tank_id']);

                if (!$tank) {
                    throw ValidationException::withMessages([
                        "items.{$index}.tank_id" => [
                            'ช่องน้ำยาที่เลือกไม่อยู่ในเครื่องนี้',
                        ],
                    ]);
                }

                $product = $tank->product;

                if (!$product || !(bool) $product->is_active) {
                    throw ValidationException::withMessages([
                        "items.{$index}.tank_id" => [
                            'สินค้าในช่องน้ำยานี้ไม่สามารถใช้งานได้',
                        ],
                    ]);
                }

                $priceOption = ProductGroupPrice::query()
                    ->where('id', (int) $input['price_option_id'])
                    ->where('machine_group_id', $machine->machine_group_id)
                    ->where('product_id', $product->id)
                    ->where('is_active', 1)
                    ->first();

                if (!$priceOption) {
                    throw ValidationException::withMessages([
                        "items.{$index}.price_option_id" => [
                            'ปริมาตรหรือราคาที่เลือกไม่ถูกต้องสำหรับสินค้านี้',
                        ],
                    ]);
                }

                $quantity = (int) ($input['quantity'] ?? 1);

                $normalUnitPrice = (float) $priceOption->price;
                $specialUnitPrice = $priceOption->special_price !== null
                    ? (float) $priceOption->special_price
                    : null;

                $unitPrice = (
                    $specialUnitPrice !== null &&
                    $specialUnitPrice < $normalUnitPrice
                ) ? $specialUnitPrice : $normalUnitPrice;

                $normalLineTotal = $normalUnitPrice * $quantity;
                $lineTotal = $unitPrice * $quantity;
                $lineDiscount = max(0, $normalLineTotal - $lineTotal);

                $subtotal += $normalLineTotal;
                $productDiscount += $lineDiscount;
                $netTotal += $lineTotal;

                $selectedItems->push([
                    'tank_id' => $tank->id,
                    'tank_no' => (int) $tank->tank_no,
                    'tank_name' => $tank->tank_name,
                    'product_id' => $product->id,
                    'product_code' => $product->code,
                    'product_name' => $product->name,
                    'product_type' => $product->type,
                    'product_unit' => $product->unit,
                    'product_image_url' => $product->image
                        ? asset('assets/img/products/' . $product->image)
                        : null,
                    'price_option_id' => $priceOption->id,
                    'amount_ml' => (int) $priceOption->amount_ml,
                    'quantity' => $quantity,
                    'normal_unit_price' => round($normalUnitPrice, 2),
                    'special_unit_price' => $specialUnitPrice !== null
                        ? round($specialUnitPrice, 2)
                        : null,
                    'unit_price' => round($unitPrice, 2),
                    'discount' => round($lineDiscount, 2),
                    'line_total' => round($lineTotal, 2),
                ]);
            }

            $summary = [
                'total_items' => (int) $selectedItems->sum('quantity'),
                'subtotal' => round($subtotal, 2),
                'product_discount' => round($productDiscount, 2),
                'promotion_discount' => 0.00,
                'points_discount' => 0.00,
                'net_total_before_member' => round($netTotal, 2),
            ];

            $selection = DB::transaction(function () use ($machine, $selectedItems, $summary) {
                return KioskSelection::create([
                    'selection_token' => (string) Str::uuid(),
                    'machine_id' => $machine->id,
                    'machine_group_id' => $machine->machine_group_id,
                    'items' => $selectedItems->values()->all(),
                    'summary' => $summary,
                    'status' => 'selected',
                    'expires_at' => now()->addMinutes(30),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'บันทึกรายการสินค้าที่เลือกสำเร็จ',
                'data' => [
                    'selection_token' => $selection->selection_token,
                    'items' => $selection->items,
                    'summary' => $selection->summary,
                    'next_step' => 'phone',
                ],
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลสินค้าที่เลือกไม่ถูกต้อง',
                'errors' => $exception->errors(),
            ], 422);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถบันทึกรายการสินค้าได้',
            ], 500);
        }
    }

    public function attachPhone(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'selection_token' => ['required', 'uuid'],
            'phone' => ['required', 'string', 'regex:/^0[0-9]{9}$/'],
        ]);

        $selection = KioskSelection::query()
            ->where('selection_token', $validated['selection_token'])
            ->first();

        if (!$selection) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการสินค้าที่เลือก',
            ], 404);
        }

        $selection->update([
            'phone' => $validated['phone'],
            'status' => 'phone_attached',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'บันทึกเบอร์โทรกับรายการสินค้าสำเร็จ',
            'data' => [
                'selection_token' => $selection->selection_token,
                'phone' => $selection->phone,
                'items' => $selection->items,
                'summary' => $selection->summary,
                'next_step' => 'otp',
            ],
        ]);
    }
}
