<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\ProductGroupPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class KioskSelectionController extends Controller
{
    public function confirm(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'serial_number' => ['required', 'string', 'max:255'],
                'model' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:100'],
                'items' => ['required', 'array', 'min:1', 'max:4'],
                'items.*.tank_id' => ['required', 'integer'],
                'items.*.price_option_id' => ['required', 'integer'],
                'items.*.quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
            ], [
                'items.required' => 'กรุณาเลือกสินค้า/น้ำยา',
                'items.min' => 'กรุณาเลือกสินค้า/น้ำยาอย่างน้อย 1 รายการ',
            ]);

            $machine = Machine::query()
                ->with([
                    'group',
                    'tanks' => fn ($q) => $q->where('is_active', 1)->orderBy('tank_no'),
                    'tanks.product',
                ])
                ->where('serial_number', $validated['serial_number'])
                ->where('model', $validated['model'])
                ->where('code', $validated['code'])
                ->where('is_active', 1)
                ->first();

            if (!$machine) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลเครื่อง',
                    'data' => null,
                ], 404);
            }

            if (!$machine->machine_group_id || !$machine->group) {
                return response()->json([
                    'success' => false,
                    'message' => 'เครื่องนี้ยังไม่ได้กำหนดกลุ่มตู้',
                    'data' => null,
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
                        "items.{$index}.tank_id" => ['ช่องน้ำยาที่เลือกไม่อยู่ในเครื่องนี้'],
                    ]);
                }

                $product = $tank->product;

                if (!$product || !(bool) $product->is_active) {
                    throw ValidationException::withMessages([
                        "items.{$index}.tank_id" => ['สินค้าในช่องน้ำยานี้ไม่สามารถใช้งานได้'],
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
                        "items.{$index}.price_option_id" => ['ปริมาตรหรือราคาที่เลือกไม่ถูกต้องสำหรับสินค้านี้'],
                    ]);
                }

                $quantity = (int) ($input['quantity'] ?? 1);
                $normalUnitPrice = (float) $priceOption->price;
                $specialUnitPrice = $priceOption->special_price !== null
                    ? (float) $priceOption->special_price
                    : null;

                $unitPrice = ($specialUnitPrice !== null && $specialUnitPrice < $normalUnitPrice)
                    ? $specialUnitPrice
                    : $normalUnitPrice;

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
                    'product_image' => $product->image,
                    'product_image_url' => $product->image
                        ? asset('assets/img/products/' . $product->image)
                        : null,
                    'price_option_id' => $priceOption->id,
                    'amount_ml' => (int) $priceOption->amount_ml,
                    'quantity' => $quantity,
                    'normal_unit_price' => round($normalUnitPrice, 2),
                    'special_unit_price' => $specialUnitPrice !== null ? round($specialUnitPrice, 2) : null,
                    'unit_price' => round($unitPrice, 2),
                    'discount' => round($lineDiscount, 2),
                    'line_total' => round($lineTotal, 2),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'ยืนยันรายการสินค้าที่เลือกสำเร็จ',
                'data' => [
                    'machine' => [
                        'id' => $machine->id,
                        'code' => $machine->code,
                        'name' => $machine->name,
                        'serial_number' => $machine->serial_number,
                        'model' => $machine->model,
                        'machine_group_id' => $machine->machine_group_id,
                    ],
                    'machine_group' => [
                        'id' => $machine->group->id,
                        'code' => $machine->group->code,
                        'name' => $machine->group->name,
                    ],
                    'items' => $selectedItems->values(),
                    'summary' => [
                        'total_items' => (int) $selectedItems->sum('quantity'),
                        'subtotal' => round($subtotal, 2),
                        'product_discount' => round($productDiscount, 2),
                        'promotion_discount' => 0.00,
                        'points_discount' => 0.00,
                        'net_total_before_member' => round($netTotal, 2),
                    ],
                    'next_step' => 'phone',
                    'after_otp_step' => 'member',
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
                'message' => 'ไม่สามารถยืนยันรายการสินค้าได้',
            ], 500);
        }
    }
}
