<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\KioskPayment;
use App\Models\KioskSelection;
use App\Models\Machine;
use App\Models\MachineGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class KioskPaymentController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'selection_token' => ['required', 'uuid'],
            'payment_method' => ['required', 'string', 'in:1,2,3'],
        ], [
            'selection_token.required' => 'ไม่พบ Selection Token',
            'selection_token.uuid' => 'รูปแบบ Selection Token ไม่ถูกต้อง',
            'payment_method.required' => 'กรุณาเลือกช่องทางการชำระเงิน',
            'payment_method.in' => 'ช่องทางการชำระเงินไม่ถูกต้อง',
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

        if ($selection->expires_at && $selection->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'รายการสินค้าที่เลือกหมดอายุ กรุณาเลือกสินค้าใหม่',
            ], 410);
        }

        /*
        |--------------------------------------------------------------------------
        | Machine / Machine Group
        |--------------------------------------------------------------------------
        | KioskSelection ไม่มี relation machine()
        | จึงใช้ machine_id และ machine_group_id ที่บันทึกอยู่ใน selection โดยตรง
        */
        $machine = Machine::query()
            ->where('id', $selection->machine_id)
            ->first();

        if (!$machine) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลเครื่องของรายการนี้',
            ], 422);
        }

        $machineGroup = MachineGroup::query()
            ->where('id', $selection->machine_group_id)
            ->first();

        if (!$machineGroup) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลกลุ่มเครื่องของรายการนี้',
            ], 422);
        }

        if (blank($machine->code)) {
            return response()->json([
                'success' => false,
                'message' => 'เครื่องนี้ยังไม่ได้กำหนด Code สำหรับ Terminal ID',
            ], 422);
        }

        if (blank($machineGroup->code)) {
            return response()->json([
                'success' => false,
                'message' => 'กลุ่มเครื่องนี้ยังไม่ได้กำหนด Code สำหรับ Salesman Code',
            ], 422);
        }

        $summary = $selection->summary ?? [];

        $amount = (float) (
            $summary['net_total']
            ?? $summary['net_total_after_member']
            ?? $summary['net_total_before_member']
            ?? 0
        );

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'ยอดชำระเงินไม่ถูกต้อง',
            ], 422);
        }

        $paymentMethod = (string) $validated['payment_method'];

        $existing = KioskPayment::query()
            ->where('kiosk_selection_id', $selection->id)
            ->where('payment_method', $paymentMethod)
            ->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'พบรายการชำระเงินที่ยังใช้งานได้',
                'data' => $this->formatPayment($existing),
            ]);
        }

        $paymentToken = (string) Str::uuid();
        $orderId = 'HYG-' . now()->format('YmdHis') . '-' . strtoupper(substr(str_replace('-', '', $paymentToken), 0, 8));
        $reference1 = 'KS' . str_pad((string) $selection->id, 8, '0', STR_PAD_LEFT);
        $reference2 = $selection->phone ?: $selection->selection_token;

        $payload = [
            'amount' => number_format($amount, 2, '.', ''),
            'channel' => $paymentMethod,

            'metadata' => json_encode([
                'selection_token' => $selection->selection_token,
                'payment_token' => $paymentToken,
                'machine_id' => $machine->id,
                'machine_group_id' => $machineGroup->id,
            ], JSON_UNESCAPED_UNICODE),

            'orderid' => $orderId,
            'reference1' => $reference1,
            'reference2' => $reference2,
            'remark' => 'Hygiene Kiosk',

            // code ของกลุ่มเครื่อง
            'salemancode' => (string) $machineGroup->code,

            // code ของเครื่อง
            'terminalId' => (string) $machine->code,
        ];

        try {
            $payment = KioskPayment::create([
                'payment_token' => $paymentToken,
                'kiosk_selection_id' => $selection->id,
                'selection_token' => $selection->selection_token,
                'phone' => $selection->phone,
                'provider' => 'ipone',
                'payment_method' => $paymentMethod,
                'order_id' => $orderId,
                'reference1' => $reference1,
                'reference2' => $reference2,
                'amount' => $amount,
                'status' => 'pending',
                'expires_at' => now()->addMinutes(15),
                'request_payload' => $payload,
            ]);

            $response = Http::withBasicAuth(
                    (string) config('services.ipone.username'),
                    (string) config('services.ipone.password')
                )
                ->acceptJson()
                ->asJson()
                ->timeout(20)
                ->post(
                    rtrim((string) config('services.ipone.base_url'), '/')
                    . '/api/PaymentGateway/BAYQRGeneration',
                    $payload
                );

            $body = $response->json();

            if (!$response->successful()) {
                $payment->update([
                    'status' => 'failed',
                    'provider_status' => (string) $response->status(),
                    'provider_response' => is_array($body)
                        ? $body
                        : ['raw' => $response->body()],
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถสร้าง QR ชำระเงินได้',
                    'provider_status' => $response->status(),
                    'provider_response' => $body,
                ], 422);
            }

            $isSuccess =
                (int) data_get($body, 'statuscode') === 200
                && (string) data_get($body, 'businesscode') === '10000'
                && (string) data_get($body, 'data.returnCode') === '10000'
                && strtolower((string) data_get($body, 'data.success')) === 'true';

            if (!$isSuccess) {
                $payment->update([
                    'status' => 'failed',
                    'provider_status' => (string) (
                        data_get($body, 'businesscode')
                        ?? data_get($body, 'data.returnCode')
                    ),
                    'provider_response' => $body,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => (string) (
                        data_get($body, 'data.message')
                        ?? data_get($body, 'message')
                        ?? 'ไม่สามารถสร้าง QR ชำระเงินได้'
                    ),
                    'provider_response' => $body,
                ], 422);
            }

            $payment->update([
                'provider_transaction_id' =>
                    (string) data_get($body, 'data.trxId'),

                'provider_status' =>
                    (string) data_get($body, 'businesscode'),

                'qr_data' =>
                    (string) data_get($body, 'data.qrcodeContent'),

                'provider_response' =>
                    $body,

                'status' =>
                    'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'สร้าง QR ชำระเงินสำเร็จ',
                'data' => [
                    'payment_token' => $payment->payment_token,
                    'selection_token' => $payment->selection_token,
                    'trx_id' => $payment->provider_transaction_id,
                    'amount' => (float) $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'payment_method_label' =>
                        $this->paymentMethodLabel((string) $payment->payment_method),
                    'status' => $payment->status,
                    'qr_content' => $payment->qr_data,
                    'expires_at' => optional($payment->expires_at)->toIso8601String(),
                ],
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดระหว่างสร้างรายการชำระเงิน',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function status(string $paymentToken): JsonResponse
    {
        $payment = KioskPayment::query()
            ->where('payment_token', $paymentToken)
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการชำระเงิน',
            ], 404);
        }

        if (!$payment->provider_transaction_id) {
            return response()->json([
                'success' => false,
                'message' => 'รายการชำระเงินนี้ยังไม่มี Transaction ID จาก Payment Gateway',
            ], 422);
        }

        try {
            $response = Http::withBasicAuth(
                    (string) config('services.ipone.username'),
                    (string) config('services.ipone.password')
                )
                ->acceptJson()
                ->asJson()
                ->timeout(20)
                ->post(
                    rtrim((string) config('services.ipone.base_url'), '/')
                    . '/api/PaymentGateway/CheckPaymentStatus',
                    [
                        'trxId' => $payment->provider_transaction_id,
                    ]
                );

            $body = $response->json();

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถตรวจสอบสถานะการชำระเงินได้',
                    'provider_status' => $response->status(),
                    'provider_response' => $body,
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | ตัวอย่าง Provider Response
            |--------------------------------------------------------------------------
            | Waiting:
            | {
            |   "success": "false",
            |   "transaction_id": "2608201038353344",
            |   "message": "waiting payment"
            | }
            |
            | เราใช้ message + success ในการ map สถานะ
            |--------------------------------------------------------------------------
            */
            $providerSuccess = strtolower(
                (string) data_get($body, 'success')
            );

            $providerMessage = strtolower(
                trim((string) data_get($body, 'message'))
            );

            $providerTransactionId = (string) (
                data_get($body, 'transaction_id')
                ?? $payment->provider_transaction_id
            );

            $status = 'pending';
            $paidAt = null;

            if (
                $providerSuccess === 'true'
                && $providerMessage === 'payment completed'
            ) {
                $status = 'paid';
                $paidAt = now();
            } elseif (
                str_contains($providerMessage, 'waiting')
                || str_contains($providerMessage, 'pending')
            ) {
                $status = 'pending';
            } elseif (
                str_contains($providerMessage, 'expire')
                || str_contains($providerMessage, 'expired')
            ) {
                $status = 'expired';
            } elseif (
                str_contains($providerMessage, 'cancel')
                || str_contains($providerMessage, 'cancelled')
            ) {
                $status = 'cancelled';
            } else {
                $status = 'failed';
            }

            /*
            |--------------------------------------------------------------------------
            | ถ้าระบบเรากำหนด QR หมดอายุแล้ว แต่ Provider ยัง waiting
            |--------------------------------------------------------------------------
            */
            if (
                $status === 'pending'
                && $payment->expires_at
                && $payment->expires_at->isPast()
            ) {
                $status = 'expired';
            }

            $payment->update([
                'provider_transaction_id' =>
                    $providerTransactionId,

                'provider_status' =>
                    (string) data_get($body, 'message'),

                'status' =>
                    $status,

                'paid_at' =>
                    $paidAt ?: $payment->paid_at,

                'provider_response' =>
                    $body,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ตรวจสอบสถานะการชำระเงินสำเร็จ',
                'data' => [
                    'payment_token' =>
                        $payment->payment_token,

                    'selection_token' =>
                        $payment->selection_token,

                    'trx_id' =>
                        $payment->provider_transaction_id,

                    'amount' =>
                        (float) $payment->amount,

                    'status' =>
                        $payment->status,

                    'provider_success' =>
                        data_get($body, 'success'),

                    'provider_message' =>
                        data_get($body, 'message'),

                    'paid_at' =>
                        optional($payment->paid_at)->toIso8601String(),

                    'expires_at' =>
                        optional($payment->expires_at)->toIso8601String(),

                    'next_step' =>
                        $payment->status === 'paid'
                            ? 'dispense'
                            : 'payment',
                ],
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดระหว่างตรวจสอบสถานะการชำระเงิน',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function callback(Request $request): JsonResponse
    {
        $payload = $request->all();

        $orderId = $request->input('orderId') ?? $request->input('order_id');
        $reference1 = $request->input('reference1');

        $payment = KioskPayment::query()
            ->when($orderId, fn ($q) => $q->where('order_id', $orderId))
            ->when(!$orderId && $reference1, fn ($q) => $q->where('reference1', $reference1))
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการชำระเงิน',
            ], 404);
        }

        $payment->update([
            'provider_transaction_id' =>
                $request->input('transactionId')
                ?? $request->input('transaction_id')
                ?? $payment->provider_transaction_id,
            'provider_status' => $request->input('status'),
            'callback_payload' => $payload,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'รับข้อมูล Payment Callback สำเร็จ',
        ]);
    }

    private function paymentMethodLabel(string $method): string
    {
        return match ($method) {
            '1' => 'Alipay',
            '2' => 'PromptPay',
            '3' => 'WeChat Pay',
            default => 'Unknown',
        };
    }

    private function formatPayment(KioskPayment $payment): array
    {
        return [
            'payment_token' => $payment->payment_token,
            'selection_token' => $payment->selection_token,
            'payment_method' => $payment->payment_method,
            'payment_method_label' =>
                $this->paymentMethodLabel((string) $payment->payment_method),
            'order_id' => $payment->order_id,
            'reference1' => $payment->reference1,
            'reference2' => $payment->reference2,
            'amount' => (float) $payment->amount,
            'status' => $payment->status,
            'provider_status' => $payment->provider_status,
            'provider_transaction_id' => $payment->provider_transaction_id,
            'qr_data' => $payment->qr_data,
            'qr_image_url' => $payment->qr_image_url,
            'expires_at' => optional($payment->expires_at)->toIso8601String(),
        ];
    }
}
