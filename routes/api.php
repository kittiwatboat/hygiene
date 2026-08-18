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
Route::post('/otp/send', [KioskOtpController::class, 'send']);
Route::post('/otp/verify', [KioskOtpController::class, 'verify']);
// Route::prefix('kiosk')->group(function () {
//     Route::post(
//         '/customers/check',
//         [KioskCustomerController::class, 'check']
//     );

//     Route::get(
//         '/machines/{machine}/products',
//         [KioskProductController::class, 'index']
//     );

//     Route::post(
//         '/quote',
//         [KioskQuoteController::class, 'calculate']
//     );
// });
