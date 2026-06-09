<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginBasic extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $pageConfigs = ['myLayout' => 'blank'];

        return view('content.authentications.auth-login-basic', [
            'pageConfigs' => $pageConfigs,
        ]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate(
                [
                    'email' => ['required', 'email'],
                    'password' => ['required', 'string'],
                ],
                [
                    'email.required' => 'กรุณากรอกอีเมล',
                    'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                    'password.required' => 'กรุณากรอกรหัสผ่าน',
                ]
            );

            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember');

            if (!Auth::attempt($credentials, $remember)) {
                return response()->json([
                    'success' => false,
                    'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
                    'errors' => [
                        'email' => ['อีเมลหรือรหัสผ่านไม่ถูกต้อง'],
                    ],
                ], 422);
            }

            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'เข้าสู่ระบบสำเร็จ',
                'redirect_url' => route('dashboard'),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'กรุณาตรวจสอบข้อมูลให้ถูกต้อง',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}
