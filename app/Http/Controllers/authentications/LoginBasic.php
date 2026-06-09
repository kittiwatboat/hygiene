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
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      return redirect()->route('dashboard');
    }

    return redirect()->route('login')->with('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
  }
}
