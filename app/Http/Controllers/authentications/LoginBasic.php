<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                    'title' => 'เข้าสู่ระบบไม่สำเร็จ',
                    'text' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
                    'errors' => [
                        'email' => ['อีเมลหรือรหัสผ่านไม่ถูกต้อง'],
                    ],
                ], 422);
            }

            $user = Auth::user();

            if (!$user->is_active || $user->status !== 'active') {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
    'success' => false,
    'title' => 'บัญชีถูกปิดใช้งาน',
    'text' => 'บัญชีนี้ถูกปิดใช้งาน กรุณาติดต่อผู้ดูแลระบบ',
    'errors' => [
        'email' => ['บัญชีนี้ถูกปิดใช้งาน กรุณาติดต่อผู้ดูแลระบบ'],
    ],
], 403);
            }

            $request->session()->regenerate();

            $user->update([
                'last_login_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'title' => 'สำเร็จ',
                'text' => 'เข้าสู่ระบบสำเร็จ',
                'redirect_url' => route('dashboard'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'title' => 'กรุณาตรวจสอบข้อมูล',
                'text' => 'กรุณากรอกข้อมูลให้ถูกต้อง',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'title' => 'เกิดข้อผิดพลาด',
                'text' => 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ',
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
