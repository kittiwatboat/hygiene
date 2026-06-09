<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\machines\MachineController;
use App\Http\Controllers\locations\LocationController;
use App\Http\Controllers\stocks\StockController;
use App\Http\Controllers\refills\RefillController;
use App\Http\Controllers\sales\SaleController;
use App\Http\Controllers\reports\ReportController;
use App\Http\Controllers\alerts\AlertController;
use App\Http\Controllers\users\UserController;
use App\Http\Controllers\settings\SettingController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\locations\AddressController;

Route::get('/cookie-test', function () {
    session(['test_value' => 'OK']);

    return response('cookie test ok')
        ->cookie('manual_test_cookie', 'OK', 120);
});

Route::get('/session-read', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'test_value' => session('test_value'),
        'csrf_token' => csrf_token(),
    ]);
});
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Cache cleared!';
})->name('cache.clear');

/*
|--------------------------------------------------------------------------
| logins and registrations
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'login'])->name('login.post');
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');

Route::middleware('admin.auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | All other routes that require authentication
    |--------------------------------------------------------------------------
    */

    // The rest of the routes are defined here...
/*
|--------------------------------------------------------------------------
| Main Page Route
|--------------------------------------------------------------------------
*/

Route::get('/', [HomePage::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Machine Management
|--------------------------------------------------------------------------
*/

Route::prefix('machines')->name('machines.')->group(function () {
    Route::get('/', [MachineController::class, 'index'])->name('index');
    Route::get('/create', [MachineController::class, 'create'])->name('create');
    Route::post('/', [MachineController::class, 'store'])->name('store');
    Route::get('/{machine}', [MachineController::class, 'show'])->name('show');
    Route::get('/{machine}/edit', [MachineController::class, 'edit'])->name('edit');
    Route::put('/{machine}', [MachineController::class, 'update'])->name('update');
    Route::delete('/{machine}', [MachineController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Locations
|--------------------------------------------------------------------------
*/

Route::prefix('address')->name('address.')->group(function () {
    Route::get('/districts/{province}', [AddressController::class, 'districts'])->name('districts');
    Route::get('/subdistricts/{district}', [AddressController::class, 'subdistricts'])->name('subdistricts');
});

Route::prefix('locations')->name('locations.')->group(function () {
    Route::get('/', [LocationController::class, 'index'])->name('index');
    Route::get('/create', [LocationController::class, 'create'])->name('create');
    Route::post('/', [LocationController::class, 'store'])->name('store');
    Route::get('/{location}', [LocationController::class, 'show'])->name('show');
    Route::get('/{location}/edit', [LocationController::class, 'edit'])->name('edit');
    Route::put('/{location}', [LocationController::class, 'update'])->name('update');
    Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Stock น้ำยา
|--------------------------------------------------------------------------
*/

Route::prefix('stock')->name('stock.')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index');
    Route::get('/history', [StockController::class, 'history'])->name('history');
    Route::get('/low-stock', [StockController::class, 'lowStock'])->name('low-stock');
    Route::get('/{machine}', [StockController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Refill น้ำยา
|--------------------------------------------------------------------------
*/

Route::prefix('refills')->name('refills.')->group(function () {
    Route::get('/', [RefillController::class, 'index'])->name('index');
    Route::get('/create', [RefillController::class, 'create'])->name('create');
    Route::post('/', [RefillController::class, 'store'])->name('store');
    Route::get('/{refill}', [RefillController::class, 'show'])->name('show');
    Route::get('/{refill}/edit', [RefillController::class, 'edit'])->name('edit');
    Route::put('/{refill}', [RefillController::class, 'update'])->name('update');
    Route::delete('/{refill}', [RefillController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Sales / Transactions
|--------------------------------------------------------------------------
*/

Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [SaleController::class, 'index'])->name('index');
    Route::get('/transactions', [SaleController::class, 'transactions'])->name('transactions');
    Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/daily-sales', [ReportController::class, 'dailySales'])->name('daily-sales');
    Route::get('/monthly-sales', [ReportController::class, 'monthlySales'])->name('monthly-sales');
    Route::get('/machine-sales', [ReportController::class, 'machineSales'])->name('machine-sales');
    Route::get('/location-sales', [ReportController::class, 'locationSales'])->name('location-sales');
    Route::get('/stock-usage', [ReportController::class, 'stockUsage'])->name('stock-usage');
});

/*
|--------------------------------------------------------------------------
| Alerts
|--------------------------------------------------------------------------
*/

Route::prefix('alerts')->name('alerts.')->group(function () {
    Route::get('/', [AlertController::class, 'index'])->name('index');
    Route::get('/offline', [AlertController::class, 'offline'])->name('offline');
    Route::get('/low-stock', [AlertController::class, 'lowStock'])->name('low-stock');
    Route::get('/errors', [AlertController::class, 'errors'])->name('errors');
    Route::post('/{alert}/read', [AlertController::class, 'markAsRead'])->name('read');
});

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
*/

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::put('/', [SettingController::class, 'update'])->name('update');

    Route::get('/pricing', [SettingController::class, 'pricing'])->name('pricing');
    Route::put('/pricing', [SettingController::class, 'updatePricing'])->name('pricing.update');

    Route::get('/machine-config', [SettingController::class, 'machineConfig'])->name('machine-config');
    Route::put('/machine-config', [SettingController::class, 'updateMachineConfig'])->name('machine-config.update');

    Route::get('/notification', [SettingController::class, 'notification'])->name('notification');
    Route::put('/notification', [SettingController::class, 'updateNotification'])->name('notification.update');
});
});

/*
|--------------------------------------------------------------------------
| Locale
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', [LanguageController::class, 'swap'])->name('lang.swap');
