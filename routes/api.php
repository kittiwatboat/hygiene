<?php

use App\Http\Controllers\Api\FrontendConfigController;
use Illuminate\Support\Facades\Route;
use App\Models\FrontendTranslation;
use App\Http\Controllers\Api\Kiosk\KioskCustomerController;
use App\Http\Controllers\Api\Kiosk\KioskProductController;
use App\Http\Controllers\Api\Kiosk\KioskQuoteController;
// use App\Http\Controllers\Api\Frontend\MachineThemeController;
use App\Http\Controllers\Api\Frontend\MachineProductController;
use App\Http\Controllers\Api\Kiosk\KioskOtpController;
use App\Http\Controllers\Api\Kiosk\KioskSelectionController;
use App\Http\Controllers\Api\Kiosk\KioskPaymentController;
use App\Http\Controllers\Api\Kiosk\KioskDispenseController;


Route::prefix('frontend')->group(function () {
    Route::get(
        '/bootstrap',
        [FrontendConfigController::class, 'bootstrap']
    );

    Route::get(
        '/theme',
        [FrontendConfigController::class, 'theme']
    );

    Route::get(
        '/pages',
        [FrontendConfigController::class, 'pages']
    );

    Route::get(
        '/pages/{screenKey}',
        [FrontendConfigController::class, 'page']
    );
    // Route::get(
    //     '/machines/{machine}/theme',
    //     [MachineThemeController::class, 'show']
    // );
    Route::get(
    '/machine-products',
    [MachineProductController::class, 'index']
);
Route::get('/test-product-api', function () {
    return response()->json([
        'success' => true,
        'message' => 'API route works',
    ]);
});
});

Route::prefix('kiosk')->group(function () {
    Route::post(
        '/customers/check',
        [KioskCustomerController::class, 'check']
    );

    Route::get(
        '/machines/{machine}/products',
        [KioskProductController::class, 'index']
    );

    Route::post(
        '/quote',
        [KioskQuoteController::class, 'calculate']
    );
    Route::post('/otp/send', [KioskOtpController::class, 'send']);
    Route::post('/otp/verify', [KioskOtpController::class, 'verify']);

    Route::post('/selection/confirm', [KioskSelectionController::class, 'confirm']);
    Route::post('/selection/phone', [KioskSelectionController::class, 'attachPhone']);
    Route::post('/selection/member-result', [KioskSelectionController::class, 'updateMemberResult']);
    Route::get('/selection/{selectionToken}', [KioskSelectionController::class, 'show']);

     Route::post('/payment/create', [KioskPaymentController::class, 'create']);
    Route::get('/payment/status/{paymentToken}', [KioskPaymentController::class, 'status']);

    // หลัง payment = paid ให้ Frontend เรียกเพื่อสร้างงานจ่ายน้ำยา
    Route::post(
        '/dispense/start',
        [KioskDispenseController::class, 'start']
    );

    // หน้า "กำลังเติมน้ำยา" ใช้ poll endpoint นี้
    Route::get(
        '/dispense/{dispenseToken}/status',
        [KioskDispenseController::class, 'status']
    );

    // Machine/Controller อัปเดตการจ่ายน้ำยาแต่ละรายการ
    Route::post(
        '/dispense/{dispenseToken}/items/{itemId}/status',
        [KioskDispenseController::class, 'updateItem']
    );
});
Route::post('/payment/ipone/callback', [KioskPaymentController::class, 'callback']);

