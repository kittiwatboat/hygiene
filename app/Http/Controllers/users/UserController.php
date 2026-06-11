<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return view('content.pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('content.pages.users.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['nullable', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['nullable', 'string', 'max:50'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'role' => ['required', 'in:admin,staff,technician'],
                'status' => ['required', 'in:active,inactive,suspended'],
                'is_active' => ['nullable', 'boolean'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'first_name.required' => 'กรุณากรอกชื่อ',
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
                'password.required' => 'กรุณากรอกรหัสผ่าน',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
                'password.confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
                'role.required' => 'กรุณาเลือกสิทธิ์ผู้ใช้งาน',
                'status.required' => 'กรุณาเลือกสถานะ',
            ]
        );

        $fullName = trim($request->first_name . ' ' . $request->last_name);

        User::create([
            'name' => $fullName,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role,
            'status' => $request->status,
            'is_active' => $request->boolean('is_active'),
            'remark' => $request->remark,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'เพิ่มผู้ใช้งานสำเร็จ');
    }

    public function show(User $user)
    {
        return view('content.pages.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('content.pages.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate(
            [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['nullable', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'phone' => ['nullable', 'string', 'max:50'],
                'password' => ['nullable', 'string', 'min:6', 'confirmed'],
                'role' => ['required', 'in:admin,staff,technician'],
                'status' => ['required', 'in:active,inactive,suspended'],
                'is_active' => ['nullable', 'boolean'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'first_name.required' => 'กรุณากรอกชื่อ',
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
                'password.confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
                'role.required' => 'กรุณาเลือกสิทธิ์ผู้ใช้งาน',
                'status.required' => 'กรุณาเลือกสถานะ',
            ]
        );

        $fullName = trim($request->first_name . ' ' . $request->last_name);

        $data = [
            'name' => $fullName,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
            'is_active' => $request->boolean('is_active'),
            'remark' => $request->remark,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'แก้ไขผู้ใช้งานสำเร็จ');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'ไม่สามารถลบบัญชีที่กำลังใช้งานอยู่ได้');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'ลบผู้ใช้งานสำเร็จ');
    }
}
