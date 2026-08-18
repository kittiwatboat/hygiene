<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KioskOtpController extends Controller
{
    /**
     * Temporary Mock OTP
     *
     * DEV/UAT only:
     * OTP = 123456
     */
    private const MOCK_OTP = '123456';

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^0[0-9]{9}$/',
            ],
        ], [
            'phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
            'phone.regex' => 'หมายเลขโทรศัพท์ต้องเป็นตัวเลข 10 หลักและขึ้นต้นด้วย 0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ส่ง OTP สำเร็จ',
            'data' => [
                'phone' => $validated['phone'],
                'otp_length' => 6,
                'expires_in_seconds' => 300,

                /*
                 * Temporary DEV/UAT only.
                 * ตอนเชื่อม SMS จริง ให้เอา field นี้ออก
                 */
                'mock_otp' => self::MOCK_OTP,
            ],
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^0[0-9]{9}$/',
            ],
            'otp' => [
                'required',
                'digits:6',
            ],
        ], [
            'phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
            'phone.regex' => 'หมายเลขโทรศัพท์ไม่ถูกต้อง',
            'otp.required' => 'กรุณากรอก OTP',
            'otp.digits' => 'OTP ต้องมี 6 หลัก',
        ]);

        if ((string) $validated['otp'] !== self::MOCK_OTP) {
            throw ValidationException::withMessages([
                'otp' => [
                    'OTP ไม่ถูกต้อง กรุณาลองใหม่',
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'ยืนยัน OTP สำเร็จ',
            'data' => [
                'phone' => $validated['phone'],
                'verified' => true,

                /*
                 * frontend ใช้ค่านี้เพื่อเปลี่ยนไป STEP MEMBER
                 */
                'next_step' => 'member',
            ],
        ]);
    }
}
