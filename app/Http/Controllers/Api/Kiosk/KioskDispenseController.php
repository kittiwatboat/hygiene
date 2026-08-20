<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\KioskDispense;
use App\Models\KioskDispenseItem;
use App\Models\KioskPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class KioskDispenseController extends Controller
{
    /**
     * สร้างงานจ่ายน้ำยาจาก payment ที่ชำระสำเร็จแล้ว
     *
     * Frontend ส่ง payment_token เท่านั้น
     * รายการสินค้า/ปริมาณ Backend อ่านจาก selection ที่ผูกกับ payment
     */
    public function start(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payment_token' => ['required', 'uuid'],
        ]);

        $payment = KioskPayment::query()
            ->with('selection')
            ->where('payment_token', $validated['payment_token'])
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการชำระเงิน',
            ], 404);
        }

        if ($payment->status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'ยังไม่สามารถจ่ายน้ำยาได้ เนื่องจากการชำระเงินยังไม่สำเร็จ',
                'data' => [
                    'payment_status' => $payment->status,
                    'next_step' => 'payment',
                ],
            ], 422);
        }

        $selection = $payment->selection;

        if (!$selection) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการสินค้าที่ผูกกับการชำระเงิน',
            ], 404);
        }

        $existing = KioskDispense::query()
            ->with('items')
            ->where('kiosk_payment_id', $payment->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'พบรายการจ่ายน้ำยาของการชำระเงินนี้แล้ว',
                'data' => $this->formatDispense($existing),
            ]);
        }

        $selectionItems = collect($selection->items ?? []);

        if ($selectionItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบสินค้าที่ต้องจ่ายน้ำยา',
            ], 422);
        }

        try {
            $dispense = DB::transaction(function () use (
                $payment,
                $selection,
                $selectionItems
            ) {
                $dispense = KioskDispense::create([
                    'dispense_token' => (string) Str::uuid(),
                    'kiosk_payment_id' => $payment->id,
                    'kiosk_selection_id' => $selection->id,
                    'payment_token' => $payment->payment_token,
                    'selection_token' => $selection->selection_token,
                    'machine_id' => $selection->machine_id,
                    'status' => 'pending',
                ]);

                foreach ($selectionItems as $item) {
                    $quantity = max(1, (int) ($item['quantity'] ?? 1));
                    $amountMl = (float) ($item['amount_ml'] ?? 0);
                    $targetMl = $amountMl * $quantity;

                    if (
                        empty($item['tank_id'])
                        || empty($item['product_id'])
                        || $amountMl <= 0
                    ) {
                        throw new \RuntimeException(
                            'ข้อมูลรายการสินค้าสำหรับจ่ายน้ำยาไม่ครบถ้วน'
                        );
                    }

                    KioskDispenseItem::create([
                        'kiosk_dispense_id' => $dispense->id,
                        'tank_id' => (int) $item['tank_id'],
                        'product_id' => (int) $item['product_id'],
                        'price_option_id' =>
                            isset($item['price_option_id'])
                                ? (int) $item['price_option_id']
                                : null,

                        'product_code' => $item['product_code'] ?? null,
                        'product_name' =>
                            $item['product_name']
                            ?? ('Product #' . $item['product_id']),
                        'product_type' => $item['product_type'] ?? null,

                        'quantity' => $quantity,
                        'amount_ml_per_unit' => $amountMl,
                        'target_ml' => $targetMl,
                        'dispensed_ml' => 0,
                        'status' => 'pending',
                    ]);
                }

                return $dispense->load('items');
            });

            return response()->json([
                'success' => true,
                'message' => 'สร้างรายการจ่ายน้ำยาสำเร็จ',
                'data' => $this->formatDispense($dispense),
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถสร้างรายการจ่ายน้ำยาได้',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    /**
     * Frontend ใช้เช็คสถานะการจ่ายน้ำยาทั้งรายการ
     */
    public function status(string $dispenseToken): JsonResponse
    {
        $dispense = KioskDispense::query()
            ->with('items')
            ->where('dispense_token', $dispenseToken)
            ->first();

        if (!$dispense) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการจ่ายน้ำยา',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'ดึงสถานะการจ่ายน้ำยาสำเร็จ',
            'data' => $this->formatDispense($dispense),
        ]);
    }

    /**
     * Machine/Controller ใช้อัปเดตสถานะของแต่ละรายการ
     *
     * เช่น:
     * pending -> dispensing -> completed
     */
    public function updateItem(
        Request $request,
        string $dispenseToken,
        int $itemId
    ): JsonResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                'in:pending,dispensing,completed,partial,failed,cancelled',
            ],
            'dispensed_ml' => ['nullable', 'numeric', 'min:0'],
            'failure_message' => ['nullable', 'string', 'max:500'],
        ]);

        $dispense = KioskDispense::query()
            ->with('items')
            ->where('dispense_token', $dispenseToken)
            ->first();

        if (!$dispense) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการจ่ายน้ำยา',
            ], 404);
        }

        $item = $dispense->items->firstWhere('id', $itemId);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการสินค้าที่ต้องจ่ายน้ำยา',
            ], 404);
        }

        $update = [
            'status' => $validated['status'],
            'failure_message' => $validated['failure_message'] ?? null,
        ];

        if (array_key_exists('dispensed_ml', $validated)) {
            $update['dispensed_ml'] = min(
                (float) $validated['dispensed_ml'],
                (float) $item->target_ml
            );
        }

        if ($validated['status'] === 'dispensing' && !$item->started_at) {
            $update['started_at'] = now();
        }

        if (in_array($validated['status'], ['completed','partial','failed','cancelled'], true)) {
            $update['completed_at'] = now();
        }

        if ($validated['status'] === 'completed') {
            $update['dispensed_ml'] = (float) $item->target_ml;
        }

        $item->update($update);

        $this->refreshParentStatus($dispense->fresh('items'));

        return response()->json([
            'success' => true,
            'message' => 'อัปเดตสถานะการจ่ายน้ำยาสำเร็จ',
            'data' => $this->formatDispense(
                $dispense->fresh('items')
            ),
        ]);
    }

    private function refreshParentStatus(KioskDispense $dispense): void
    {
        $items = $dispense->items;

        if ($items->isEmpty()) {
            return;
        }

        $statuses = $items->pluck('status');

        $status = 'pending';

        if ($statuses->every(fn ($s) => $s === 'completed')) {
            $status = 'completed';
        } elseif ($statuses->contains('failed')) {
            $status =
                $statuses->contains('completed')
                    ? 'partial'
                    : 'failed';
        } elseif ($statuses->contains('partial')) {
            $status = 'partial';
        } elseif ($statuses->contains('dispensing')) {
            $status = 'dispensing';
        } elseif ($statuses->every(fn ($s) => $s === 'cancelled')) {
            $status = 'cancelled';
        }

        $update = [
            'status' => $status,
        ];

        if ($status === 'dispensing' && !$dispense->started_at) {
            $update['started_at'] = now();
        }

        if (in_array($status, ['completed','partial','failed','cancelled'], true)) {
            $update['completed_at'] = now();
        }

        $dispense->update($update);
    }

    private function formatDispense(KioskDispense $dispense): array
    {
        $items = $dispense->items->map(function ($item) {
            $target = (float) $item->target_ml;
            $dispensed = (float) $item->dispensed_ml;

            $progress = $target > 0
                ? round(min(100, ($dispensed / $target) * 100), 2)
                : 0;

            return [
                'id' => $item->id,
                'tank_id' => $item->tank_id,
                'product_id' => $item->product_id,
                'price_option_id' => $item->price_option_id,
                'product_code' => $item->product_code,
                'product_name' => $item->product_name,
                'product_type' => $item->product_type,
                'quantity' => (int) $item->quantity,
                'amount_ml_per_unit' => (float) $item->amount_ml_per_unit,
                'target_ml' => $target,
                'dispensed_ml' => $dispensed,
                'progress_percent' => $progress,
                'status' => $item->status,
                'failure_message' => $item->failure_message,
                'started_at' =>
                    optional($item->started_at)->toIso8601String(),
                'completed_at' =>
                    optional($item->completed_at)->toIso8601String(),
            ];
        })->values();

        $nextStep = match ($dispense->status) {
            'completed' => 'complete',
            'failed', 'partial' => 'dispense_problem',
            'cancelled' => 'cancelled',
            default => 'dispense',
        };

        return [
            'dispense_token' => $dispense->dispense_token,
            'payment_token' => $dispense->payment_token,
            'selection_token' => $dispense->selection_token,
            'machine_id' => $dispense->machine_id,
            'status' => $dispense->status,
            'items' => $items,
            'total_items' => $items->count(),
            'completed_items' =>
                $items->where('status', 'completed')->count(),
            'started_at' =>
                optional($dispense->started_at)->toIso8601String(),
            'completed_at' =>
                optional($dispense->completed_at)->toIso8601String(),
            'next_step' => $nextStep,
        ];
    }
}
