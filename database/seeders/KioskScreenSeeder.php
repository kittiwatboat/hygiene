<?php

namespace Database\Seeders;

use App\Models\KioskLayout;
use App\Models\KioskScreen;
use App\Models\KioskTheme;
use Illuminate\Database\Seeder;

class KioskScreenSeeder extends Seeder
{
    public function run(): void
    {
        $theme = KioskTheme::where('is_default', true)
            ->where('is_active', true)
            ->first();

        $layout = KioskLayout::where('is_default', true)
            ->where('is_active', true)
            ->first();

        $screens = [
            [
                'screen_key' => 'idle',
                'name' => 'หน้าเริ่มต้น',
                'title' => 'Hygiene Detergent Refill Station',
                'subtitle' => 'เติมน้ำยาได้ง่าย สะดวก และรวดเร็ว',
                'button_text' => 'เริ่มใช้งาน',
                'button_action' => 'language',
                'sort_order' => 1,
                'is_required' => true,
                'settings_json' => [
                    'timeout_seconds' => 0,
                    'show_start_button' => true,
                    'translations' => [
                        'th' => [
                            'title' => 'Hygiene Detergent Refill Station',
                            'subtitle' => 'เติมน้ำยาได้ง่าย สะดวก และรวดเร็ว',
                            'button_text' => 'เริ่มใช้งาน',
                        ],
                        'en' => [
                            'title' => 'Hygiene Detergent Refill Station',
                            'subtitle' => 'Easy, convenient and fast refill',
                            'button_text' => 'Start',
                        ],
                        'zh' => [
                            'title' => 'Hygiene Detergent Refill Station',
                            'subtitle' => '方便快捷地补充洗涤液',
                            'button_text' => '开始',
                        ],
                    ],
                ],
            ],
            [
                'screen_key' => 'language',
                'name' => 'หน้าเลือกภาษา',
                'title' => 'กรุณาเลือกภาษา',
                'subtitle' => 'Please select your language',
                'button_text' => null,
                'button_action' => 'select_product',
                'sort_order' => 2,
                'is_required' => true,
                'settings_json' => [
                    'max_languages' => 3,
                    'show_flags' => true,
                    'auto_skip_if_one_language' => true,
                    'translations' => [
                        'th' => [
                            'title' => 'กรุณาเลือกภาษา',
                            'subtitle' => 'เลือกภาษาที่ต้องการใช้งาน',
                        ],
                        'en' => [
                            'title' => 'Please select language',
                            'subtitle' => 'Choose your preferred language',
                        ],
                        'zh' => [
                            'title' => '请选择语言',
                            'subtitle' => '请选择您要使用的语言',
                        ],
                    ],
                ],
            ],
            [
                'screen_key' => 'select_product',
                'name' => 'หน้าเลือกน้ำยา',
                'title' => 'เลือกน้ำยาที่ต้องการเติม',
                'subtitle' => null,
                'button_text' => null,
                'button_action' => 'select_amount',
                'sort_order' => 3,
                'is_required' => true,
                'settings_json' => [
                    'columns' => 4,
                    'show_product_image' => true,
                    'show_price' => true,
                    'show_volume' => true,
                    'disabled_when_empty' => true,
                ],
            ],
            [
                'screen_key' => 'select_amount',
                'name' => 'หน้าเลือกปริมาณ',
                'title' => 'เลือกจำนวนครั้งที่ต้องการเติม',
                'subtitle' => null,
                'button_text' => 'ถัดไป',
                'button_action' => 'member',
                'sort_order' => 4,
                'is_required' => true,
                'settings_json' => [
                    'show_price_summary' => true,
                    'allow_multiple_press' => true,
                ],
            ],
            [
                'screen_key' => 'member',
                'name' => 'หน้าสมาชิก',
                'title' => 'สมาชิก / แต้มสะสม',
                'subtitle' => 'กรอกเบอร์โทรศัพท์เพื่อสะสมหรือใช้แต้ม',
                'button_text' => 'ถัดไป',
                'button_action' => 'promotion',
                'sort_order' => 5,
                'is_required' => false,
                'settings_json' => [
                    'allow_skip' => true,
                    'lookup_by_phone' => true,
                    'show_points_balance' => true,
                ],
            ],
            [
                'screen_key' => 'promotion',
                'name' => 'หน้าโปรโมชัน',
                'title' => 'โปรโมชันและส่วนลด',
                'subtitle' => null,
                'button_text' => 'ชำระเงิน',
                'button_action' => 'payment',
                'sort_order' => 6,
                'is_required' => false,
                'settings_json' => [
                    'show_available_promotions' => true,
                    'allow_redeem_points' => true,
                    'show_points_balance' => true,
                ],
            ],
            [
                'screen_key' => 'payment',
                'name' => 'หน้าชำระเงิน',
                'title' => 'ชำระเงิน',
                'subtitle' => 'กรุณาชำระเงินภายในเวลาที่กำหนด',
                'button_text' => null,
                'button_action' => 'dispensing',
                'sort_order' => 7,
                'is_required' => true,
                'settings_json' => [
                    'timeout_seconds' => 120,
                    'payment_methods' => ['promptpay'],
                    'show_qr' => true,
                ],
            ],
            [
                'screen_key' => 'dispensing',
                'name' => 'หน้ากำลังจ่ายน้ำยา',
                'title' => 'กำลังจ่ายน้ำยา',
                'subtitle' => 'กรุณารอสักครู่',
                'button_text' => null,
                'button_action' => 'receipt',
                'sort_order' => 8,
                'is_required' => true,
                'settings_json' => [
                    'show_progress' => true,
                    'show_animation' => true,
                ],
            ],
            [
                'screen_key' => 'receipt',
                'name' => 'หน้าใบเสร็จ',
                'title' => 'สรุปรายการ',
                'subtitle' => null,
                'button_text' => 'เสร็จสิ้น',
                'button_action' => 'thank_you',
                'sort_order' => 9,
                'is_required' => false,
                'settings_json' => [
                    'show_total' => true,
                    'show_points_earned' => true,
                    'show_qr_receipt' => false,
                ],
            ],
            [
                'screen_key' => 'thank_you',
                'name' => 'หน้าขอบคุณ',
                'title' => 'ขอบคุณที่ใช้บริการ',
                'subtitle' => 'Thank you',
                'button_text' => 'กลับหน้าแรก',
                'button_action' => 'idle',
                'sort_order' => 10,
                'is_required' => true,
                'settings_json' => [
                    'auto_redirect_seconds' => 8,
                ],
            ],
            [
                'screen_key' => 'error',
                'name' => 'หน้าข้อผิดพลาด',
                'title' => 'เกิดข้อผิดพลาด',
                'subtitle' => 'กรุณาติดต่อเจ้าหน้าที่',
                'button_text' => 'กลับหน้าแรก',
                'button_action' => 'idle',
                'sort_order' => 99,
                'is_required' => true,
                'settings_json' => [
                    'show_contact_info' => true,
                ],
            ],
        ];

        foreach ($screens as $screen) {
            KioskScreen::updateOrCreate(
                [
                    'theme_id' => $theme?->id,
                    'screen_key' => $screen['screen_key'],
                ],
                array_merge($screen, [
                    'theme_id' => $theme?->id,
                    'layout_id' => $layout?->id,
                    'background_type' => 'color',
                    'background_color' => '#FFFFFF',
                    'is_active' => true,
                ])
            );
        }
    }
}
