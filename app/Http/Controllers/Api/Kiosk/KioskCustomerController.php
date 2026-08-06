<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Throwable;

class KioskCustomerController extends Controller
{
    /**
     * ตรวจสอบสมาชิกจากเบอร์โทรศัพท์
     *
     * POST /api/kiosk/customers/check
     */
    public function check(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate(
                [
                    'phone' => [
                        'required',
                        'string',
                        'max:30',
                    ],
                ],
                [
                    'phone.required' =>
                        'กรุณากรอกหมายเลขโทรศัพท์',

                    'phone.string' =>
                        'หมายเลขโทรศัพท์ไม่ถูกต้อง',

                    'phone.max' =>
                        'หมายเลขโทรศัพท์ยาวเกินไป',
                ]
            );

            $phone = $this->normalizePhone(
                $validated['phone']
            );

            if (!$this->isValidPhone($phone)) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'รูปแบบหมายเลขโทรศัพท์ไม่ถูกต้อง',
                    'errors' => [
                        'phone' => [
                            'กรุณากรอกหมายเลขโทรศัพท์ให้ถูกต้อง',
                        ],
                    ],
                ], 422);
            }

            $customerQuery = Customer::query()
                ->where(function ($query) use ($phone) {
                    $query->where('phone', $phone);

                    /*
                    |--------------------------------------------------------------------------
                    | รองรับข้อมูลเก่าที่อาจเก็บเป็น +66
                    |--------------------------------------------------------------------------
                    */
                    $internationalPhone =
                        $this->toInternationalPhone($phone);

                    if ($internationalPhone) {
                        $query->orWhere(
                            'phone',
                            $internationalPhone
                        );
                    }
                });

            /*
            |--------------------------------------------------------------------------
            | เช็กสถานะเฉพาะเมื่อมีคอลัมน์จริง
            |--------------------------------------------------------------------------
            */
            if (
                Schema::hasColumn(
                    'customers',
                    'is_active'
                )
            ) {
                $customerQuery->where(
                    'is_active',
                    true
                );
            }

            if (
                Schema::hasColumn(
                    'customers',
                    'status'
                )
            ) {
                $customerQuery->where(function ($query) {
                    $query
                        ->whereNull('status')
                        ->orWhereIn('status', [
                            1,
                            '1',
                            'active',
                        ]);
                });
            }

            $customer = $customerQuery->first();

            /*
            |--------------------------------------------------------------------------
            | ไม่พบสมาชิก ให้หน้าตู้ดำเนินการแบบ Guest
            |--------------------------------------------------------------------------
            */
            if (!$customer) {
                return response()->json([
                    'success' => true,
                    'message' => 'ไม่พบข้อมูลสมาชิก',
                    'data' => [
                        'customer_type' => 'guest',
                        'is_member' => false,
                        'customer' => null,

                        'guest' => [
                            'phone' => $phone,
                            'name' => null,
                            'points_balance' => 0,
                            'can_use_points' => false,
                        ],

                        'next_action' =>
                            'continue_as_guest',
                    ],
                ]);
            }

            $pointsBalance = (int) (
                $customer->points_balance
                ?? $customer->points
                ?? 0
            );

            return response()->json([
                'success' => true,
                'message' => 'พบข้อมูลสมาชิก',
                'data' => [
                    'customer_type' => 'member',
                    'is_member' => true,

                    'customer' => [
                        'id' => $customer->id,

                        'member_code' =>
                            $customer->member_code
                            ?? null,

                        'name' =>
                            $customer->name
                            ?? $customer->full_name
                            ?? null,

                        'first_name' =>
                            $customer->first_name
                            ?? null,

                        'last_name' =>
                            $customer->last_name
                            ?? null,

                        'phone' =>
                            $customer->phone
                            ?? $phone,

                        'member_type' =>
                            $customer->member_type
                            ?? 'member',

                        'points_balance' =>
                            $pointsBalance,

                        'can_use_points' =>
                            $pointsBalance > 0,

                        'is_active' =>
                            isset($customer->is_active)
                                ? (bool) $customer->is_active
                                : true,
                    ],

                    'next_action' =>
                        'display_member_information',
                ],
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลที่ส่งมาไม่ถูกต้อง',
                'errors' => $exception->errors(),
            ], 422);
        } catch (Throwable $exception) {
            Log::error('Kiosk customer check error', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'phone' => $request->input('phone'),
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'ไม่สามารถตรวจสอบข้อมูลสมาชิกได้',

                'error' => config('app.debug')
                    ? $exception->getMessage()
                    : null,
            ], 500);
        }
    }

    /**
     * ลบช่องว่าง ขีด และอักขระที่ไม่จำเป็น
     */
    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);

        $phone = preg_replace(
            '/[\s\-\(\)\.]+/',
            '',
            $phone
        );

        /*
        |--------------------------------------------------------------------------
        | แปลง +66 ให้เป็น 0
        |--------------------------------------------------------------------------
        */
        if (str_starts_with($phone, '+66')) {
            $phone = '0' . substr($phone, 3);
        } elseif (str_starts_with($phone, '66')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }

    /**
     * ตรวจรูปแบบเบอร์โทรศัพท์ประเทศไทย
     */
    private function isValidPhone(string $phone): bool
    {
        return preg_match(
            '/^0[0-9]{8,9}$/',
            $phone
        ) === 1;
    }

    /**
     * แปลง 08xxxxxxxx เป็น +668xxxxxxxx
     */
    private function toInternationalPhone(
        string $phone
    ): ?string {
        if (!str_starts_with($phone, '0')) {
            return null;
        }

        return '+66' . substr($phone, 1);
    }
}
