<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $this->ensureDefaultSettings();

        $settings = Setting::orderBy('group')
            ->orderBy('id')
            ->get()
            ->groupBy('group');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => ['required', 'array'],
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->input('settings', []) as $key => $value) {
                Setting::where('key', $key)->update([
                    'value' => is_array($value) ? json_encode($value) : $value,
                ]);
            }

            foreach ($request->input('boolean_settings', []) as $key => $value) {
                Setting::where('key', $key)->update([
                    'value' => $request->boolean("settings.$key") ? 1 : 0,
                ]);
            }
        });

        return redirect()
            ->route('settings.index')
            ->with('success', 'บันทึกการตั้งค่าสำเร็จ');
    }

    private function ensureDefaultSettings(): void
    {
        $defaults = [
            [
                'group' => 'general',
                'key' => 'system_name',
                'name' => 'ชื่อระบบ',
                'value' => 'Hygiene Vending System',
                'type' => 'text',
                'description' => 'ชื่อระบบที่ใช้แสดงในหลังบ้าน',
            ],
            [
                'group' => 'general',
                'key' => 'company_name',
                'name' => 'ชื่อบริษัท',
                'value' => 'Hygiene',
                'type' => 'text',
                'description' => 'ชื่อบริษัทหรือแบรนด์',
            ],
            [
                'group' => 'machine',
                'key' => 'machine_offline_minutes',
                'name' => 'ถือว่าเครื่องออฟไลน์เมื่อไม่ติดต่อเกิน / นาที',
                'value' => '10',
                'type' => 'integer',
                'description' => 'ใช้ตรวจสอบ heartbeat จากตู้',
            ],
            [
                'group' => 'stock',
                'key' => 'default_low_stock_liters',
                'name' => 'ค่าแจ้งเตือนน้ำยาใกล้หมดเริ่มต้น / ลิตร',
                'value' => '3',
                'type' => 'decimal',
                'description' => 'ค่าเริ่มต้นเวลาเพิ่มช่องน้ำยา',
            ],
            [
                'group' => 'stock',
                'key' => 'default_empty_stock_liters',
                'name' => 'ถือว่าน้ำยาหมดเมื่อต่ำกว่า / ลิตร',
                'value' => '0.5',
                'type' => 'decimal',
                'description' => 'ใช้แจ้งเตือนน้ำยาหมด',
            ],
            [
                'group' => 'printer',
                'key' => 'receipt_print_enabled',
                'name' => 'เปิดใช้งานการปริ้นใบเสร็จ',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'ถ้าปิด ระบบจะไม่สั่งปริ้นใบเสร็จ',
            ],
            [
                'group' => 'printer',
                'key' => 'default_paper_size',
                'name' => 'ขนาดกระดาษเริ่มต้น',
                'value' => '58mm',
                'type' => 'select',
                'description' => 'ขนาดกระดาษเริ่มต้นของเครื่องปริ้น',
            ],
            [
                'group' => 'payment',
                'key' => 'payment_cash_enabled',
                'name' => 'เปิดรับเงินสด',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิด/ปิดช่องทางเงินสด',
            ],
            [
                'group' => 'payment',
                'key' => 'payment_qr_enabled',
                'name' => 'เปิดรับ QR Payment',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิด/ปิดช่องทาง QR Payment',
            ],
            [
                'group' => 'notification',
                'key' => 'notify_low_stock_enabled',
                'name' => 'แจ้งเตือนน้ำยาใกล้หมด',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิด/ปิดการแจ้งเตือนน้ำยาใกล้หมด',
            ],
            [
                'group' => 'notification',
                'key' => 'notify_printer_error_enabled',
                'name' => 'แจ้งเตือนเครื่องปริ้นมีปัญหา',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิด/ปิดการแจ้งเตือนเครื่องปริ้น',
            ],
            [
                'group' => 'api',
                'key' => 'api_token',
                'name' => 'API Token สำหรับตู้',
                'value' => '',
                'type' => 'password',
                'description' => 'Token ที่ให้เครื่องตู้ใช้ยิง API เข้าระบบ',
            ],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
