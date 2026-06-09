<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public function index()
  {
    if (auth()->check()) {
      return redirect()->route('dashboard');
    }
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    try {
      $credentials = $request->only('email', 'password');

      if (Auth::attempt($credentials)) {
        return redirect()->route('dashboard');
      }

      return back()->with('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
    } catch (\Exception $e) {
      return back()->with('error', 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ: ' . $e->getMessage());
    }
  }
}
