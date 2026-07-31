<?php

use App\Http\Controllers\Api\FrontendConfigController;
use Illuminate\Support\Facades\Route;

Route::prefix('frontend')->group(function () {
    Route::get(
        '/first-page',
        [FrontendConfigController::class, 'firstPage']
    )->name('api.frontend.first-page');
});
