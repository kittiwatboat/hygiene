<?php

use App\Http\Controllers\Api\FrontendConfigController;
use Illuminate\Support\Facades\Route;

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
