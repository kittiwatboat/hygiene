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
use Illuminate\Support\Facades\Http;

class KioskCustomerController extends Controller
{
    /**
     * ตรวจสอบสมาชิกจากเบอร์โทรศัพท์
     *
     * POST /api/kiosk/customers/check
     */
public function check(Request $request): JsonResponse
{
    $validated = $request->validate([
        'phone' => [
            'required',
            'string',
            'max:30',
        ],
    ]);

    $phone = $this->normalizePhone(
        $validated['phone']
    );

    try {
        $response = Http::timeout(15)
            ->acceptJson()
            ->withToken(
                config('services.member_api.token')
            )
            ->post(
                config('services.member_api.url')
                . '/members/check',
                [
                    'phone' => $phone,
                ]
            );

        if (!$response->successful()) {
            Log::error('Member API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'phone' => $phone,
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'ไม่สามารถเชื่อมต่อระบบสมาชิกได้',
            ], 502);
        }

        $memberData = $response->json();

        /*
        |--------------------------------------------------------------------------
        | ปรับตรงนี้ให้ตรงกับ Response จริงของ API สมาชิก
        |--------------------------------------------------------------------------
        */
        $isMember = (bool) (
            data_get($memberData, 'data.is_member')
            ?? false
        );

        if (!$isMember) {
            return response()->json([
                'success' => true,
                'message' => 'ไม่พบข้อมูลสมาชิก',
                'data' => [
                    'customer_type' => 'guest',
                    'is_member' => false,
                    'customer' => null,
                    'guest' => [
                        'phone' => $phone,
                        'points_balance' => 0,
                        'can_use_points' => false,
                    ],
                    'next_action' =>
                        'continue_as_guest',
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'พบข้อมูลสมาชิก',
            'data' => [
                'customer_type' => 'member',
                'is_member' => true,

                'customer' => [
                    'external_id' =>
                        data_get(
                            $memberData,
                            'data.member.id'
                        ),

                    'member_code' =>
                        data_get(
                            $memberData,
                            'data.member.member_code'
                        ),

                    'name' =>
                        data_get(
                            $memberData,
                            'data.member.name'
                        ),

                    'phone' =>
                        data_get(
                            $memberData,
                            'data.member.phone',
                            $phone
                        ),

                    'points_balance' => (int) (
                        data_get(
                            $memberData,
                            'data.member.points'
                        )
                        ?? 0
                    ),

                    'can_use_points' => (int) (
                        data_get(
                            $memberData,
                            'data.member.points'
                        )
                        ?? 0
                    ) > 0,
                ],

                'next_action' =>
                    'display_member_information',
            ],
        ]);
    } catch (Throwable $exception) {
        Log::error('Member API exception', [
            'message' => $exception->getMessage(),
            'phone' => $phone,
        ]);

        return response()->json([
            'success' => false,
            'message' =>
                'ระบบสมาชิกไม่พร้อมใช้งาน กรุณาลองใหม่อีกครั้ง',
        ], 503);
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
