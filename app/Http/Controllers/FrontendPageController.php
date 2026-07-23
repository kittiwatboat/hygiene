<?php

namespace App\Http\Controllers;

use App\Models\FrontendPage;
use App\Models\FrontendPageMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\FrontendLanguage;
use App\Models\FrontendPaymentMethod;

class FrontendPageController extends Controller
{
    public function index()
    {
        $pages = FrontendPage::withCount('media')
            ->orderBy('sort_order')
            ->get();

        return view('content.pages.frontend.pages.index', compact('pages'));
    }

    public function edit(FrontendPage $page)
    {
        $page->load([
        'media' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        },
    ]);

    $paymentMethods = collect();

    $screenKey = $page->screen_key ?? $page->page_key ?? null;

    if ($screenKey === 'payment_page') {
        $paymentMethods = FrontendPaymentMethod::orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    return view('content.pages.frontend.pages.edit', compact(
        'page',
        'paymentMethods'
    ));
        return view('content.pages.frontend.pages.edit', compact('page'));
    }

    public function update(Request $request, FrontendPage $page)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'title' => ['nullable', 'string', 'max:255'],
        'subtitle' => ['nullable', 'string', 'max:255'],
        // 'is_active' => ['nullable', 'boolean'],
        'remark' => ['nullable', 'string'],

        /*
        |--------------------------------------------------------------------------
        | เฉพาะหน้าเลือกภาษา
        |--------------------------------------------------------------------------
        */
        'language_button_shape' => [
            'nullable',
            'in:circle,rounded-square,square',
        ],
        'language_button_style' => [
            'nullable',
            'in:icon_top_text_bottom,icon_only,text_only',
        ],
        'language_button_size' => [
            'nullable',
            'in:small,medium,large',
        ],
        'show_button_border' => ['nullable', 'boolean'],
        'show_button_shadow' => ['nullable', 'boolean'],

        'show_phone_button' => ['nullable', 'boolean'],

        'phone_button_text' => ['nullable', 'string', 'max:100'],

        'language_button_shape' => ['nullable', 'in:circle,rounded-square,square'],
'language_button_style' => ['nullable', 'in:icon_top_text_bottom,icon_left_text_right,icon_only,text_only'],
'language_button_size' => ['nullable', 'in:small,medium,large'],

'show_button_border' => ['nullable', 'boolean'],
'show_button_shadow' => ['nullable', 'boolean'],
'show_start_button' => ['nullable', 'boolean'],
'start_button_icon' => ['nullable', 'string', 'max:100'],
'start_button_action' => ['nullable', 'string', 'max:100'],
'phone_max_length' => ['nullable', 'integer', 'min:1', 'max:20'],
'show_keypad' => ['nullable', 'boolean'],
'keypad_layout' => ['nullable', 'in:numeric'],
'left_banner_enabled' => ['nullable', 'boolean'],

'show_back_button' => ['nullable', 'boolean'],
'back_button_icon' => ['nullable', 'string', 'max:100'],
'back_button_action' => ['nullable', 'string', 'max:100'],

'show_confirm_button' => ['nullable', 'boolean'],
'confirm_button_icon' => ['nullable', 'string', 'max:100'],
'confirm_button_action' => ['nullable', 'string', 'max:100'],
'back_button_icon_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
'confirm_button_icon_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],

'member_panel_background_type' => ['nullable', 'in:color,image'],
'member_panel_background_color' => ['nullable', 'string', 'max:50'],
'member_panel_background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
'remove_member_panel_background_image' => ['nullable', 'boolean'],

'member_name_card_enabled' => ['nullable', 'boolean'],
'member_name_card_background_color' => ['nullable', 'string', 'max:50'],
'member_name_card_text_color' => ['nullable', 'string', 'max:50'],

'show_member_name' => ['nullable', 'boolean'],
'show_member_points' => ['nullable', 'boolean'],
'show_member_history' => ['nullable', 'boolean'],
'history_limit' => ['nullable', 'integer', 'min:1', 'max:10'],

'step_icon' => ['nullable', 'string', 'max:100'],

'show_home_button' => ['nullable', 'boolean'],
'home_button_icon' => ['nullable', 'string', 'max:100'],
'home_button_action' => ['nullable', 'string', 'max:100'],

'show_select_button' => ['nullable', 'boolean'],
'select_button_icon' => ['nullable', 'string', 'max:100'],
'select_button_action' => ['nullable', 'string', 'max:100'],

'category_primary_icon' => ['nullable', 'string', 'max:100'],
'category_secondary_icon' => ['nullable', 'string', 'max:100'],
'selected_product_icon' => ['nullable', 'string', 'max:100'],
'amount_section_icon' => ['nullable', 'string', 'max:100'],
'total_price_icon' => ['nullable', 'string', 'max:100'],

'show_confirm_button' => ['nullable', 'boolean'],
'confirm_button_icon' => ['nullable', 'string', 'max:100'],
'confirm_button_action' => ['nullable', 'string', 'max:100'],

'point_section_icon' => ['nullable', 'string', 'max:100'],
'point_option_icon' => ['nullable', 'string', 'max:100'],
'selected_option_icon' => ['nullable', 'string', 'max:100'],
'next_option_icon' => ['nullable', 'string', 'max:100'],

'show_skip_button' => ['nullable', 'boolean'],
'skip_button_icon' => ['nullable', 'string', 'max:100'],
'skip_button_action' => ['nullable', 'string', 'max:100'],

'order_summary_icon' => ['nullable', 'string', 'max:100'],
'discount_summary_icon' => ['nullable', 'string', 'max:100'],
'payment_section_icon' => ['nullable', 'string', 'max:100'],
'selected_payment_icon' => ['nullable', 'string', 'max:100'],
'next_payment_icon' => ['nullable', 'string', 'max:100'],

'show_back_button' => ['nullable', 'boolean'],
'back_button_icon' => ['nullable', 'string', 'max:100'],
'back_button_action' => ['nullable', 'string', 'max:100'],

'payment_methods' => ['nullable', 'array'],
'payment_methods.*.code' => ['nullable', 'string', 'max:100'],
'payment_methods.*.name' => ['nullable', 'string', 'max:255'],
'payment_methods.*.subtitle' => ['nullable', 'string', 'max:255'],
'payment_methods.*.icon' => ['nullable', 'string', 'max:100'],
'payment_methods.*.sort_order' => ['nullable', 'integer', 'min:0'],
'payment_methods.*.is_active' => ['nullable', 'boolean'],

'order_summary_icon' => ['nullable', 'string', 'max:100'],
'net_total_icon' => ['nullable', 'string', 'max:100'],
'payment_section_icon' => ['nullable', 'string', 'max:100'],
'back_button_icon' => ['nullable', 'string', 'max:100'],
'back_button_action' => ['nullable', 'string', 'max:100'],

'order_summary_icon' => ['nullable', 'string', 'max:100'],
'net_total_icon' => ['nullable', 'string', 'max:100'],
'back_button_icon' => ['nullable', 'string', 'max:100'],
'back_button_action' => ['nullable', 'string', 'max:100'],

'show_send_otp_button' => ['nullable', 'boolean'],
'send_otp_button_icon' => ['nullable', 'string', 'max:100'],
'send_otp_button_action' => ['nullable', 'string', 'max:100'],

'show_pause_button' => ['nullable', 'boolean'],
'pause_button_icon' => ['nullable', 'string', 'max:100'],
'pause_button_action' => ['nullable', 'string', 'max:100'],

'show_continue_button' => ['nullable', 'boolean'],
'continue_button_icon' => ['nullable', 'string', 'max:100'],
'continue_button_action' => ['nullable', 'string', 'max:100'],

'show_register_button' => ['nullable', 'boolean'],
'register_button_icon' => ['nullable', 'string', 'max:100'],
'register_button_action' => ['nullable', 'string', 'max:100'],

'show_skip_button' => ['nullable', 'boolean'],
'skip_button_icon' => ['nullable', 'string', 'max:100'],
'skip_button_action' => ['nullable', 'string', 'max:100'],

'show_print_receipt' => ['nullable', 'boolean'],
'print_receipt_icon' => ['nullable', 'string', 'max:100'],

'show_no_receipt' => ['nullable', 'boolean'],
'no_receipt_icon' => ['nullable', 'string', 'max:100'],

'show_finish_button' => ['nullable', 'boolean'],
'finish_button_icon' => ['nullable', 'string', 'max:100'],
'finish_button_action' => ['nullable', 'string', 'max:100'],

'show_order_more_button' => ['nullable', 'boolean'],
'order_more_button_icon' => ['nullable', 'string', 'max:100'],
'order_more_button_action' => ['nullable', 'string', 'max:100'],

'show_member_points' => ['nullable', 'boolean'],
'show_total_points' => ['nullable', 'boolean'],

'message_title' => ['nullable', 'string', 'max:255'],
'message_subtitle' => ['nullable', 'string', 'max:255'],

'show_skip_button' => ['nullable', 'boolean'],
'skip_button_text' => ['nullable', 'string', 'max:100'],
'skip_button_icon' => ['nullable', 'string', 'max:100'],
'skip_button_action' => ['nullable', 'string', 'max:100'],

'show_register_button' => ['nullable', 'boolean'],
'register_button_text' => ['nullable', 'string', 'max:100'],
'register_button_icon' => ['nullable', 'string', 'max:100'],
'register_button_action' => ['nullable', 'string', 'max:100'],

'show_popup' => ['nullable', 'boolean'],
'popup_title' => ['nullable', 'string', 'max:255'],
'popup_subtitle' => ['nullable', 'string', 'max:255'],
'popup_skip_button_text' => ['nullable', 'string', 'max:100'],
'popup_register_button_text' => ['nullable', 'string', 'max:100'],
    ]);



    $settings = $page->settings_json ?? [];

    /*
    |--------------------------------------------------------------------------
    | ตั้งค่าเฉพาะหน้าเลือกภาษา
    |--------------------------------------------------------------------------
    */
    $settings = $page->settings_json ?? [];

$screenKey = $page->screen_key ?? $page->page_key ?? null;

switch ($screenKey) {
   case 'first_page':
    $settings = array_merge($settings, [
        'show_start_button' => $request->boolean('show_start_button'),
        'start_button_icon' => $request->input('start_button_icon', 'tabler-bottle'),
        'start_button_action' => $request->input('start_button_action', 'language_page'),
    ]);
    break;

   case 'language_page':
    $settings = array_merge($settings, [
        'language_button_shape' => $request->input('language_button_shape', 'circle'),
        'language_button_style' => $request->input('language_button_style', 'icon_top_text_bottom'),
        'language_button_size' => $request->input('language_button_size', 'medium'),
        'show_button_border' => $request->boolean('show_button_border'),
        'show_button_shadow' => $request->boolean('show_button_shadow'),
    ]);
    break;
    case 'phone_verify_page':
    $settings = array_merge($settings, [
        'phone_max_length' => (int) $request->input('phone_max_length', 10),
        'phone_placeholder_key' => 'phone_verify_page.phone_placeholder',

        'show_keypad' => $request->boolean('show_keypad'),
        'keypad_layout' => $request->input('keypad_layout', 'numeric'),

        'left_banner_enabled' => $request->boolean('left_banner_enabled'),

        'show_back_button' => $request->boolean('show_back_button'),
        'back_button_icon' => $request->input('back_button_icon', 'tabler-arrow-left'),
        'back_button_action' => $request->input('back_button_action', 'language_page'),

        'show_confirm_button' => $request->boolean('show_confirm_button'),
        'confirm_button_icon' => $request->input('confirm_button_icon', 'tabler-check'),
        'confirm_button_action' => $request->input('confirm_button_action', 'select_product_page'),
        'show_send_otp_button' => $request->boolean('show_send_otp_button'),

'send_otp_button_icon' => $request->input(
    'send_otp_button_icon',
    'tabler-message-code'
),

'send_otp_button_action' => $request->input(
    'send_otp_button_action',
    'send_otp'
),
    ]);
    break;
    case 'member_page':
    $memberPanelBackgroundImage = $settings['member_panel_background_image'] ?? null;

    if ($request->boolean('remove_member_panel_background_image')) {
        $this->deleteMemberPanelBackground($memberPanelBackgroundImage);
        $memberPanelBackgroundImage = null;
    }

    if ($request->hasFile('member_panel_background_image')) {
        $this->deleteMemberPanelBackground($memberPanelBackgroundImage);

        $memberPanelBackgroundImage = $this->uploadMemberPanelBackground(
            $request->file('member_panel_background_image')
        );
    }

    $settings = array_merge($settings, [
        'show_member_name' => $request->boolean('show_member_name'),
        'show_member_points' => $request->boolean('show_member_points'),
        'show_member_history' => $request->boolean('show_member_history'),
        'history_limit' => (int) $request->input('history_limit', 3),

        'member_panel_background_type' => $request->input('member_panel_background_type', 'color'),
        'member_panel_background_color' => $request->input('member_panel_background_color', '#075db8'),
        'member_panel_background_image' => $memberPanelBackgroundImage,

        'member_name_card_enabled' => $request->boolean('member_name_card_enabled'),
        'member_name_card_background_color' => $request->input('member_name_card_background_color', '#238bff'),
        'member_name_card_text_color' => $request->input('member_name_card_text_color', '#ffffff'),

        'step_icon' => $request->input('step_icon', 'tabler-user'),

        'show_home_button' => $request->boolean('show_home_button'),
        'home_button_icon' => $request->input('home_button_icon', 'tabler-home'),
        'home_button_action' => $request->input('home_button_action', 'first_page'),

        'show_select_button' => $request->boolean('show_select_button'),
        'select_button_icon' => $request->input('select_button_icon', 'tabler-bottle'),
        'select_button_action' => $request->input('select_button_action', 'select_product_page'),

        'right_ad_enabled' => true,
    ]);
    break;
    case 'select_product_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input(
            'step_icon',
            'tabler-bottle'
        ),

        'category_primary_icon' => $request->input(
            'category_primary_icon',
            'tabler-basket'
        ),

        'category_secondary_icon' => $request->input(
            'category_secondary_icon',
            'tabler-droplet'
        ),

        'selected_product_icon' => $request->input(
            'selected_product_icon',
            'tabler-check'
        ),

        'amount_section_icon' => $request->input(
            'amount_section_icon',
            'tabler-basket'
        ),

        'total_price_icon' => $request->input(
            'total_price_icon',
            'tabler-wallet'
        ),

        /*
        |--------------------------------------------------------------------------
        | ปุ่มย้อนกลับ
        |--------------------------------------------------------------------------
        */
        'show_back_button' => $request->boolean(
            'show_back_button'
        ),

        'back_button_icon' => $request->input(
            'back_button_icon',
            'tabler-chevron-left'
        ),

        'back_button_action' => $request->input(
            'back_button_action',
            'member_page'
        ),

        /*
        |--------------------------------------------------------------------------
        | ปุ่มตกลง
        |--------------------------------------------------------------------------
        */
        'show_confirm_button' => $request->boolean(
            'show_confirm_button'
        ),

        'confirm_button_icon' => $request->input(
            'confirm_button_icon',
            'tabler-chevron-right'
        ),

        'confirm_button_action' => $request->input(
            'confirm_button_action',
            'promotion_page'
        ),
    ]);
    break;
    case 'promotion_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input('step_icon', 'tabler-discount'),

        'point_section_icon' => $request->input('point_section_icon', 'tabler-wallet'),
        'point_option_icon' => $request->input('point_option_icon', 'tabler-coins'),
        'selected_option_icon' => $request->input('selected_option_icon', 'tabler-check'),
        'next_option_icon' => $request->input('next_option_icon', 'tabler-chevron-right'),

        'show_home_button' => $request->boolean('show_home_button'),
        'home_button_icon' => $request->input('home_button_icon', 'tabler-home'),
        'home_button_action' => $request->input('home_button_action', 'first_page'),

        'show_skip_button' => $request->boolean('show_skip_button'),
        'skip_button_icon' => $request->input('skip_button_icon', 'tabler-chevrons-right'),
        'skip_button_action' => $request->input('skip_button_action', 'payment_page'),

        'show_confirm_button' => $request->boolean('show_confirm_button'),
        'confirm_button_icon' => $request->input('confirm_button_icon', 'tabler-chevron-right'),
        'confirm_button_action' => $request->input('confirm_button_action', 'payment_page'),
    ]);
    break;
    case 'payment_page':
    $paymentMethods = collect($request->input('payment_methods', []))
        ->map(function ($method) {
            return [
                'code' => $method['code'] ?? null,
                'name' => $method['name'] ?? null,
                'subtitle' => $method['subtitle'] ?? null,
                'icon' => $method['icon'] ?? 'tabler-wallet',
                'sort_order' => (int) ($method['sort_order'] ?? 0),
                'is_active' => !empty($method['is_active']),
            ];
        })
        ->filter(fn ($method) => !empty($method['code']) && !empty($method['name']))
        ->sortBy('sort_order')
        ->values()
        ->toArray();

    $settings = array_merge($settings, [
        'step_icon' => $request->input('step_icon', 'tabler-wallet'),

        'order_summary_icon' => $request->input('order_summary_icon', 'tabler-shopping-bag'),
        'discount_summary_icon' => $request->input('discount_summary_icon', 'tabler-wallet'),

        'payment_section_icon' => $request->input('payment_section_icon', 'tabler-credit-card'),
        'selected_payment_icon' => $request->input('selected_payment_icon', 'tabler-check'),
        'next_payment_icon' => $request->input('next_payment_icon', 'tabler-chevron-right'),

        'show_home_button' => $request->boolean('show_home_button'),
        'home_button_icon' => $request->input('home_button_icon', 'tabler-home'),
        'home_button_action' => $request->input('home_button_action', 'first_page'),

        'show_back_button' => $request->boolean('show_back_button'),
        'back_button_icon' => $request->input('back_button_icon', 'tabler-chevron-left'),
        'back_button_action' => $request->input('back_button_action', 'promotion_page'),

        'show_confirm_button' => $request->boolean('show_confirm_button'),
        'confirm_button_icon' => $request->input('confirm_button_icon', 'tabler-chevron-right'),
        'confirm_button_action' => $request->input('confirm_button_action', 'waiting_payment_page'),

        'payment_methods' => $paymentMethods,
    ]);
    break;

    case 'payment_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input('step_icon', 'tabler-credit-card'),

        'order_summary_icon' => $request->input('order_summary_icon', 'tabler-shopping-cart'),
        'net_total_icon' => $request->input('net_total_icon', 'tabler-wallet'),
        'payment_section_icon' => $request->input('payment_section_icon', 'tabler-credit-card'),

        'show_home_button' => $request->boolean('show_home_button', true),
        'home_button_icon' => $request->input('home_button_icon', 'tabler-home'),
        'home_button_action' => $request->input('home_button_action', 'first_page'),

        'show_back_button' => $request->boolean('show_back_button', true),
        'back_button_icon' => $request->input('back_button_icon', 'tabler-chevron-left'),
        'back_button_action' => $request->input('back_button_action', 'promotion_page'),

        'show_confirm_button' => $request->boolean('show_confirm_button', true),
        'confirm_button_icon' => $request->input('confirm_button_icon', 'tabler-chevron-right'),
        'confirm_button_action' => $request->input('confirm_button_action', 'processing_payment_page'),
    ]);
    break;

    case 'processing_payment_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input('step_icon', 'tabler-qrcode'),

        'order_summary_icon' => $request->input('order_summary_icon', 'tabler-shopping-cart'),
        'net_total_icon' => $request->input('net_total_icon', 'tabler-wallet'),

        'show_home_button' => $request->boolean('show_home_button', true),
        'home_button_icon' => $request->input('home_button_icon', 'tabler-home'),
        'home_button_action' => $request->input('home_button_action', 'first_page'),

        'show_back_button' => $request->boolean('show_back_button', true),
        'back_button_icon' => $request->input('back_button_icon', 'tabler-chevron-left'),
        'back_button_action' => $request->input('back_button_action', 'payment_page'),

        'show_confirm_button' => $request->boolean('show_confirm_button', true),
        'confirm_button_icon' => $request->input('confirm_button_icon', 'tabler-chevron-right'),
        'confirm_button_action' => $request->input('confirm_button_action', 'thank_you_page'),
    ]);
    break;
    case 'order_summary_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input(
            'step_icon',
            'tabler-list-details'
        ),

        'order_summary_icon' => $request->input(
            'order_summary_icon',
            'tabler-shopping-cart'
        ),

        'discount_summary_icon' => $request->input(
            'discount_summary_icon',
            'tabler-discount'
        ),

        'net_total_icon' => $request->input(
            'net_total_icon',
            'tabler-wallet'
        ),

        'show_back_button' => $request->boolean(
            'show_back_button'
        ),

        'back_button_icon' => $request->input(
            'back_button_icon',
            'tabler-chevron-left'
        ),

        'back_button_action' => $request->input(
            'back_button_action',
            'select_product_page'
        ),

        'show_confirm_button' => $request->boolean(
            'show_confirm_button'
        ),

        'confirm_button_icon' => $request->input(
            'confirm_button_icon',
            'tabler-chevron-right'
        ),

        'confirm_button_action' => $request->input(
            'confirm_button_action',
            'promotion_page'
        ),
    ]);
    break;
    case 'refill_status_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input(
            'step_icon',
            'tabler-bottle'
        ),

        'show_pause_button' => $request->boolean(
            'show_pause_button'
        ),

        'pause_button_icon' => $request->input(
            'pause_button_icon',
            'tabler-player-pause'
        ),

        'pause_button_action' => $request->input(
            'pause_button_action',
            'pause_refill'
        ),

        'show_continue_button' => $request->boolean(
            'show_continue_button'
        ),

        'continue_button_icon' => $request->input(
            'continue_button_icon',
            'tabler-chevron-right'
        ),

        'continue_button_action' => $request->input(
            'continue_button_action',
            'complete_refill'
        ),
    ]);
    break;
    case 'non_member_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input(
            'step_icon',
            'tabler-user-off'
        ),

        'show_back_button' => $request->boolean(
            'show_back_button'
        ),

        'back_button_icon' => $request->input(
            'back_button_icon',
            'tabler-chevron-left'
        ),

        'back_button_action' => $request->input(
            'back_button_action',
            'phone_verify_page'
        ),

        'show_skip_button' => $request->boolean(
            'show_skip_button'
        ),

        'skip_button_icon' => $request->input(
            'skip_button_icon',
            'tabler-player-track-next'
        ),

        'skip_button_action' => $request->input(
            'skip_button_action',
            'select_product_page'
        ),

        'show_register_button' => $request->boolean(
            'show_register_button'
        ),

        'register_button_icon' => $request->input(
            'register_button_icon',
            'tabler-user-plus'
        ),

        'register_button_action' => $request->input(
            'register_button_action',
            'register_member'
        ),
    ]);
    break;
    case 'guest_thank_you_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input(
            'step_icon',
            'tabler-circle-check'
        ),

        'show_print_receipt' => $request->boolean(
            'show_print_receipt'
        ),

        'print_receipt_icon' => $request->input(
            'print_receipt_icon',
            'tabler-printer'
        ),

        'show_no_receipt' => $request->boolean(
            'show_no_receipt'
        ),

        'no_receipt_icon' => $request->input(
            'no_receipt_icon',
            'tabler-printer-off'
        ),

        'show_finish_button' => $request->boolean(
            'show_finish_button'
        ),

        'finish_button_icon' => $request->input(
            'finish_button_icon',
            'tabler-check'
        ),

        'finish_button_action' => $request->input(
            'finish_button_action',
            'first_page'
        ),

        'show_order_more_button' => $request->boolean(
            'show_order_more_button'
        ),

        'order_more_button_icon' => $request->input(
            'order_more_button_icon',
            'tabler-shopping-cart-plus'
        ),

        'order_more_button_action' => $request->input(
            'order_more_button_action',
            'select_product_page'
        ),
    ]);
    break;
    case 'thank_you_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input(
            'step_icon',
            'tabler-circle-check'
        ),

        'show_print_receipt' => $request->boolean(
            'show_print_receipt'
        ),

        'print_receipt_icon' => $request->input(
            'print_receipt_icon',
            'tabler-printer'
        ),

        'show_no_receipt' => $request->boolean(
            'show_no_receipt'
        ),

        'no_receipt_icon' => $request->input(
            'no_receipt_icon',
            'tabler-printer-off'
        ),

        'show_finish_button' => $request->boolean(
            'show_finish_button'
        ),

        'finish_button_icon' => $request->input(
            'finish_button_icon',
            'tabler-check'
        ),

        'finish_button_action' => $request->input(
            'finish_button_action',
            'first_page'
        ),

        'show_order_more_button' => $request->boolean(
            'show_order_more_button'
        ),

        'order_more_button_icon' => $request->input(
            'order_more_button_icon',
            'tabler-shopping-cart-plus'
        ),

        'order_more_button_action' => $request->input(
            'order_more_button_action',
            'select_product_page'
        ),

        'show_member_points' => $request->boolean(
            'show_member_points'
        ),

        'show_total_points' => $request->boolean(
            'show_total_points'
        ),
    ]);
    break;
    case 'promotion_non_member_page':
    $settings = array_merge($settings, [
        'step_icon' => $request->input(
            'step_icon',
            'tabler-user-off'
        ),

        'message_title' => $request->input(
            'message_title',
            'ไม่พบข้อมูลสมาชิก'
        ),

        'message_subtitle' => $request->input(
            'message_subtitle',
            'กรุณาตรวจสอบเบอร์โทรอีกครั้ง'
        ),

        'show_skip_button' => $request->boolean(
            'show_skip_button'
        ),

        'skip_button_text' => $request->input(
            'skip_button_text',
            'ข้าม'
        ),

        'skip_button_icon' => $request->input(
            'skip_button_icon',
            'tabler-chevron-right'
        ),

        'skip_button_action' => $request->input(
            'skip_button_action',
            'select_product_page'
        ),

        'show_register_button' => $request->boolean(
            'show_register_button'
        ),

        'register_button_text' => $request->input(
            'register_button_text',
            'สมัครสมาชิก'
        ),

        'register_button_icon' => $request->input(
            'register_button_icon',
            'tabler-user-plus'
        ),

        'register_button_action' => $request->input(
            'register_button_action',
            'open_popup'
        ),

        'show_popup' => $request->boolean(
            'show_popup'
        ),

        'popup_title' => $request->input(
            'popup_title',
            'สมัครสมาชิกวันนี้ รับส่วนลดทันที 20 บาท'
        ),

        'popup_subtitle' => $request->input(
            'popup_subtitle',
            'สแกน QR Code เพื่อสมัครสมาชิก'
        ),

        'popup_skip_button_text' => $request->input(
            'popup_skip_button_text',
            'ข้าม'
        ),

        'popup_register_button_text' => $request->input(
            'popup_register_button_text',
            'สมัครสมาชิก'
        ),
    ]);
    break;
}

    $page->update([
        'name' => $validated['name'],
        'title' => $validated['title'] ?? null,
        'subtitle' => $validated['subtitle'] ?? null,
        'settings_json' => $settings,
        //'is_active' => $request->boolean('is_active'),
        'remark' => $validated['remark'] ?? null,
    ]);

    return redirect()
        ->route('frontend.pages.edit', $page)
        ->with('success', 'บันทึกข้อมูลหน้าสำเร็จ');
}
    public function storeMedia(
    Request $request,
    FrontendPage $page
) {
    $validated = $request->validate(
        [
            'media_type' => [
                'required',
                Rule::in([
                    'image',
                    'video',
                ]),
            ],

            'media_slot' => [
                'nullable',
                'string',
                'max:50',
            ],

            'file' => [
                'required',
                'file',
                'max:51200',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'duration_seconds' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'object_fit' => [
                'nullable',
                Rule::in([
                    'cover',
                    'contain',
                ]),
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'remark' => [
                'nullable',
                'string',
            ],
        ],
        [
            'media_type.required' => 'กรุณาเลือกประเภทไฟล์',
            'file.required' => 'กรุณาเลือกไฟล์',
            'file.max' => 'ไฟล์ต้องมีขนาดไม่เกิน 50 MB',
        ]
    );

    if ($validated['media_type'] === 'image') {
        $request->validate([
            'file' => [
                'image',
                'mimes:jpg,jpeg,png,webp,svg',
            ],
        ]);
    }

    if ($validated['media_type'] === 'video') {
        $request->validate([
            'file' => [
                'mimes:mp4,webm,mov',
            ],
        ]);
    }

    $screenKey = $page->screen_key
        ?? $page->page_key
        ?? null;

    $mediaSlot = $validated['media_slot']
        ?? 'default';

    /*
    |--------------------------------------------------------------------------
    | หน้าที่ใช้ Media แยกตามตำแหน่ง
    |--------------------------------------------------------------------------
    */
    $slotBasedScreens = [
        'promotion_non_member_page',
    ];

    /*
    |--------------------------------------------------------------------------
    | หน้าที่มี Media ได้เพียงรายการเดียว
    |--------------------------------------------------------------------------
    */
    $singleMediaScreens = [
        'phone_verify_page',
        'member_page',
        'non_member_page',
    ];

    DB::transaction(function () use (
        $request,
        $validated,
        $page,
        $screenKey,
        $mediaSlot,
        $slotBasedScreens,
        $singleMediaScreens
    ) {
        if (in_array(
            $screenKey,
            $slotBasedScreens,
            true
        )) {
            /*
            |--------------------------------------------------------------------------
            | ลบเฉพาะ Media slot เดียวกัน
            |--------------------------------------------------------------------------
            | เช่น เปลี่ยน popup_qr จะไม่ลบ popup_poster
            */
            $oldMediaItems = $page->media()
                ->where('media_slot', $mediaSlot)
                ->get();
        } elseif (in_array(
            $screenKey,
            $singleMediaScreens,
            true
        )) {
            /*
            |--------------------------------------------------------------------------
            | หน้าเหล่านี้มี Media ได้รายการเดียว
            |--------------------------------------------------------------------------
            */
            $oldMediaItems = $page->media()->get();
        } else {
            /*
            |--------------------------------------------------------------------------
            | หน้าอื่นเพิ่มได้หลาย Media
            |--------------------------------------------------------------------------
            */
            $oldMediaItems = collect();
        }

        foreach ($oldMediaItems as $oldMedia) {
            $this->deleteMediaFile(
                $oldMedia->file_path,
                $oldMedia->media_type
            );

            $oldMedia->delete();
        }

        $fileName = $this->uploadMediaFile(
            $request->file('file'),
            $validated['media_type']
        );

        FrontendPageMedia::create([
            'frontend_page_id' => $page->id,
            'media_type' => $validated['media_type'],
            'media_slot' => $mediaSlot,
            'file_path' => $fileName,
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'duration_seconds' => $validated[
                'duration_seconds'
            ] ?? 5,
            'object_fit' => $validated[
                'object_fit'
            ] ?? 'cover',
            'sort_order' => $validated[
                'sort_order'
            ] ?? 0,
            'remark' => $validated['remark'] ?? null,
        ]);
    });

    return redirect()
        ->route('frontend.pages.edit', $page)
        ->with(
            'success',
            'บันทึก Media สำเร็จ'
        );
}

    public function updateMedia(
    Request $request,
    FrontendPageMedia $media
) {
    $validated = $request->validate([
        'media_slot' => [
            'nullable',
            'string',
            'max:50',
        ],

        'media_type' => [
            'nullable',
            Rule::in([
                'image',
                'video',
            ]),
        ],

        'file' => [
            'nullable',
            'file',
            'max:51200',
        ],

        'title' => [
            'nullable',
            'string',
            'max:255',
        ],

        'subtitle' => [
            'nullable',
            'string',
            'max:255',
        ],

        'duration_seconds' => [
            'nullable',
            'integer',
            'min:1',
        ],

        'object_fit' => [
            'required',
            Rule::in([
                'cover',
                'contain',
            ]),
        ],

        'sort_order' => [
            'nullable',
            'integer',
            'min:0',
        ],

        'remark' => [
            'nullable',
            'string',
        ],
    ]);

    $mediaType = $validated['media_type']
        ?? $media->media_type;

    if ($request->hasFile('file')) {
        if ($mediaType === 'image') {
            $request->validate([
                'file' => [
                    'image',
                    'mimes:jpg,jpeg,png,webp,svg',
                ],
            ]);
        }

        if ($mediaType === 'video') {
            $request->validate([
                'file' => [
                    'mimes:mp4,webm,mov',
                ],
            ]);
        }
    }

    DB::transaction(function () use (
        $request,
        $validated,
        $media,
        $mediaType
    ) {
        $fileName = $media->file_path;

        if ($request->hasFile('file')) {
            $newFileName = $this->uploadMediaFile(
                $request->file('file'),
                $mediaType
            );

            /*
            |--------------------------------------------------------------------------
            | อัปโหลดไฟล์ใหม่สำเร็จก่อน แล้วค่อยลบไฟล์เก่า
            |--------------------------------------------------------------------------
            */
            $this->deleteMediaFile(
                $media->file_path,
                $media->media_type
            );

            $fileName = $newFileName;
        }

        $media->update([
            'media_type' => $mediaType,

            'media_slot' => $validated['media_slot']
                ?? $media->media_slot
                ?? 'default',

            'file_path' => $fileName,

            'title' => $validated['title']
                ?? null,

            'subtitle' => $validated['subtitle']
                ?? null,

            'duration_seconds' => $validated[
                'duration_seconds'
            ] ?? 5,

            'object_fit' => $validated['object_fit'],

            'sort_order' => $validated[
                'sort_order'
            ] ?? 0,

            'remark' => $validated['remark']
                ?? null,
        ]);
    });

    return redirect()
        ->route(
            'frontend.pages.edit',
            $media->page
        )
        ->with(
            'success',
            'แก้ไข Media สำเร็จ'
        );
}

    public function destroyMedia(FrontendPageMedia $media)
    {
        $page = $media->page;

        $this->deleteMediaFile(
            $media->file_path,
            $media->media_type
        );

        $media->delete();

        return redirect()
            ->route('frontend.pages.edit', $page)
            ->with('success', 'ลบสไลด์สำเร็จ');
    }

    private function uploadMediaFile($file, string $mediaType): string
    {
        if ($mediaType === 'video') {
            $uploadPath = base_path('../public_html/assets/videos/frontend/pages');
            $prefix = 'frontend_video_';
        } else {
            $uploadPath = base_path('../public_html/assets/img/frontend/pages');
            $prefix = 'frontend_image_';
        }

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid($prefix, true)
            . '.'
            . strtolower($file->getClientOriginalExtension());

        $file->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deleteMediaFile(?string $fileName, ?string $mediaType): void
    {
        if (!$fileName || !$mediaType) {
            return;
        }

        $basePath = $mediaType === 'video'
            ? base_path('../public_html/assets/videos/frontend/pages')
            : base_path('../public_html/assets/img/frontend/pages');

        $filePath = $basePath . '/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    private function uploadMemberPanelBackground($file): string
{
    $uploadPath = base_path('../public_html/assets/img/frontend/pages/member-backgrounds');

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $fileName = uniqid('member_bg_', true)
        . '.'
        . strtolower($file->getClientOriginalExtension());

    $file->move($uploadPath, $fileName);

    return $fileName;
}

private function deleteMemberPanelBackground(?string $fileName): void
{
    if (!$fileName) {
        return;
    }

    $filePath = base_path('../public_html/assets/img/frontend/pages/member-backgrounds/' . $fileName);

    if (file_exists($filePath)) {
        unlink($filePath);
    }
}
}
