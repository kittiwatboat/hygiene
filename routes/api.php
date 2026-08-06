<?php

use App\Http\Controllers\Api\FrontendConfigController;
use Illuminate\Support\Facades\Route;
use App\Models\FrontendTranslation;
use App\Http\Controllers\Api\Kiosk\KioskCustomerController;
use App\Http\Controllers\Api\Kiosk\KioskProductController;
use App\Http\Controllers\Api\Kiosk\KioskQuoteController;


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
});
