<?php

use App\Http\Controllers\Api\FrontendConfigController;
use Illuminate\Support\Facades\Route;

Route::prefix('frontend')->group(function () {
    Route::get(
        '/theme',
        [FrontendConfigController::class, 'theme']
    );

    Route::get(
        '/first-page',
        [FrontendConfigController::class, 'firstPage']
    );

    Route::get(
        '/screens/select-product',
        [FrontendConfigController::class, 'selectProduct']
    );
    Route::get(
    '/screens/order-summary',
    [FrontendConfigController::class, 'orderSummary']
);
});
