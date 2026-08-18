<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Throwable;

class KioskOtpController extends Controller
{
    private const REQUEST_URL = 'https://otp.thaibulksms.com/v2/otp/request';
    private const VERIFY_URL  = 'https://otp.thaibulksms.com/v2/otp/verify';

    /**
     * เก็บ token ของ ThaiBulkSMS ฝั่ง server
     * เพื่อไม่ต้องส่ง token ให้ frontend
     */
    private const TOKEN_CACHE_MINUTES = 10;

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

        $key = (string) config('services.thaibulksms.key');
        $secret = (string) config('services.thaibulksms.secret');

        if ($key === '' || $secret === '') {
            return response()->json([
                'success' => false,
                'message' => 'ยังไม่ได้ตั้งค่า ThaiBulkSMS API',
            ], 500);
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->retry(2, 300)
                ->post(self::REQUEST_URL, [
                    'key' => $key,
                    'secret' => $secret,
                    'msisdn' => $validated['phone'],
                ]);

            $body = $response->json();

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => $this->providerMessage(
                        $body,
                        'ไม่สามารถส่ง OTP ได้ กรุณาลองใหม่'
                    ),
                    'provider_status' => $response->status(),
                ], 422);
            }

            /*
             * ThaiBulkSMS v2 จะคืน token จาก Request OTP
             * รองรับทั้ง token root และ data.token เผื่อ response wrapper
             */
            $token = data_get($body, 'token')
                ?? data_get($body, 'data.token');

            if (!$token) {
                report(new \RuntimeException(
                    'ThaiBulkSMS Request OTP succeeded but token was missing.'
                ));

                return response()->json([
                    'success' => false,
                    'message' => 'ได้รับข้อมูล OTP จากผู้ให้บริการไม่ครบถ้วน',
                ], 502);
            }

            Cache::put(
                $this->cacheKey($validated['phone']),
                [
                    'token' => (string) $token,
                    'phone' => $validated['phone'],
                    'requested_at' => now()->toIso8601String(),
                ],
                now()->addMinutes(self::TOKEN_CACHE_MINUTES)
            );

            return response()->json([
                'success' => true,
                'message' => 'ส่ง OTP สำเร็จ',
                'data' => [
                    'phone' => $validated['phone'],
                    'otp_length' => 6,
                    'next_step' => 'otp',
                ],
            ]);
        } catch (ConnectionException $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถเชื่อมต่อระบบส่ง OTP ได้ กรุณาลองใหม่',
            ], 503);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดระหว่างส่ง OTP',
            ], 500);
        }
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

        $key = (string) config('services.thaibulksms.key');
        $secret = (string) config('services.thaibulksms.secret');

        if ($key === '' || $secret === '') {
            return response()->json([
                'success' => false,
                'message' => 'ยังไม่ได้ตั้งค่า ThaiBulkSMS API',
            ], 500);
        }

        $cached = Cache::get(
            $this->cacheKey($validated['phone'])
        );

        if (!$cached || empty($cached['token'])) {
            throw ValidationException::withMessages([
                'otp' => [
                    'ไม่พบคำขอ OTP หรือ OTP หมดอายุ กรุณาขอ OTP ใหม่',
                ],
            ]);
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->retry(2, 300)
                ->post(self::VERIFY_URL, [
                    'token' => $cached['token'],
                    'key' => $key,
                    'secret' => $secret,
                    'pin' => (string) $validated['otp'],
                ]);

            $body = $response->json();

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => $this->providerMessage(
                        $body,
                        'OTP ไม่ถูกต้อง กรุณาลองใหม่'
                    ),
                    'errors' => [
                        'otp' => [
                            $this->providerMessage(
                                $body,
                                'OTP ไม่ถูกต้อง กรุณาลองใหม่'
                            ),
                        ],
                    ],
                    'provider_status' => $response->status(),
                ], 422);
            }

            /*
             * ยืนยันสำเร็จแล้ว token นี้ไม่ควรนำกลับมาใช้ซ้ำ
             */
            Cache::forget(
                $this->cacheKey($validated['phone'])
            );

            return response()->json([
                'success' => true,
                'message' => 'ยืนยัน OTP สำเร็จ',
                'data' => [
                    'phone' => $validated['phone'],
                    'verified' => true,
                    'next_step' => 'member',
                ],
            ]);
        } catch (ConnectionException $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถเชื่อมต่อระบบตรวจสอบ OTP ได้ กรุณาลองใหม่',
            ], 503);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดระหว่างตรวจสอบ OTP',
            ], 500);
        }
    }

    private function cacheKey(string $phone): string
    {
        return 'kiosk:otp:thaibulksms:' . $phone;
    }

    private function providerMessage(
        mixed $body,
        string $fallback
    ): string {
        if (!is_array($body)) {
            return $fallback;
        }

        return (string) (
            data_get($body, 'message')
            ?? data_get($body, 'error.message')
            ?? data_get($body, 'error')
            ?? $fallback
        );
    }
}
