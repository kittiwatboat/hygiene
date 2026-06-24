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
use App\Http\Controllers\products\ProductController;
use App\Http\Controllers\printers\PrinterController;
use App\Http\Controllers\maintenance\MaintenanceController;
use App\Http\Controllers\banners\BannerController;
use App\Http\Controllers\promotions\PromotionController;
use App\Http\Controllers\customers\CustomerController;

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
| product stock management
|--------------------------------------------------------------------------
*/

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
});
/*
|--------------------------------------------------------------------------
| Printer Management
|--------------------------------------------------------------------------
*/

Route::prefix('printers')->name('printers.')->group(function () {
    Route::get('/', [PrinterController::class, 'index'])->name('index');
    Route::get('/create', [PrinterController::class, 'create'])->name('create');
    Route::post('/', [PrinterController::class, 'store'])->name('store');
    Route::get('/{printer}', [PrinterController::class, 'show'])->name('show');
    Route::get('/{printer}/edit', [PrinterController::class, 'edit'])->name('edit');
    Route::put('/{printer}', [PrinterController::class, 'update'])->name('update');
    Route::delete('/{printer}', [PrinterController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| stock management
|--------------------------------------------------------------------------
*/
Route::prefix('stock')->name('stock.')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index');
    Route::get('/{tank}', [StockController::class, 'show'])->name('show');
    Route::put('/{tank}/adjust', [StockController::class, 'adjust'])->name('adjust');
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
    Route::delete('/{refill}', [RefillController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Refill น้ำยา
|--------------------------------------------------------------------------
*/

Route::prefix('maintenances')->name('maintenances.')->group(function () {
    Route::get('/', [MaintenanceController::class, 'index'])->name('index');
    Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
    Route::post('/', [MaintenanceController::class, 'store'])->name('store');
    Route::get('/{maintenance}', [MaintenanceController::class, 'show'])->name('show');
    Route::get('/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('edit');
    Route::put('/{maintenance}', [MaintenanceController::class, 'update'])->name('update');
    Route::delete('/{maintenance}', [MaintenanceController::class, 'destroy'])->name('destroy');
});
/*
|--------------------------------------------------------------------------
| Sales / Transactions
|--------------------------------------------------------------------------
*/


Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [SaleController::class, 'index'])->name('index');
    Route::get('/create', [SaleController::class, 'create'])->name('create');
    Route::post('/', [SaleController::class, 'store'])->name('store');
    Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
    Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('destroy');
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
    Route::get('/{systemAlert}', [AlertController::class, 'show'])->name('show');
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
});
});
/*|--------------------------------------------------------------------------
| Banners
|--------------------------------------------------------------------------*/

Route::prefix('banners')->name('banners.')->group(function () {
    Route::get('/', [BannerController::class, 'index'])
        ->name('index');

    Route::get('/create', [BannerController::class, 'create'])
        ->name('create');

    Route::post('/', [BannerController::class, 'store'])
        ->name('store');

    Route::get('/{banner}/edit', [BannerController::class, 'edit'])
        ->name('edit');

    Route::put('/{banner}', [BannerController::class, 'update'])
        ->name('update');

    Route::delete('/{banner}', [BannerController::class, 'destroy'])
        ->name('destroy');
});
/*
|--------------------------------------------------------------------------
| Locale
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', [LanguageController::class, 'swap'])->name('lang.swap');

/*
|--------------------------------------------------------------------------
| Promotion
|--------------------------------------------------------------------------
*/
Route::prefix('promotions')
    ->name('promotions.')
    ->group(function () {
        Route::get('/', [PromotionController::class, 'index'])
            ->name('index');

        Route::get('/create', [PromotionController::class, 'create'])
            ->name('create');

        Route::post('/', [PromotionController::class, 'store'])
            ->name('store');

        Route::get('/{promotion}/edit', [PromotionController::class, 'edit'])
            ->name('edit');

        Route::put('/{promotion}', [PromotionController::class, 'update'])
            ->name('update');

        Route::delete('/{promotion}', [PromotionController::class, 'destroy'])
            ->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */
    Route::prefix('customers')
    ->name('customers.')
    ->group(function () {
        Route::get('/', [CustomerController::class, 'index'])
            ->name('index');

        Route::get('/create', [CustomerController::class, 'create'])
            ->name('create');

        Route::post('/', [CustomerController::class, 'store'])
            ->name('store');

        Route::get('/{customer}', [CustomerController::class, 'show'])
            ->name('show');

        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])
            ->name('edit');

        Route::put('/{customer}', [CustomerController::class, 'update'])
            ->name('update');

        Route::post('/{customer}/adjust-points', [
            CustomerController::class,
            'adjustPoints',
        ])->name('adjust-points');

        Route::delete('/{customer}', [
            CustomerController::class,
            'destroy',
        ])->name('destroy');
    });
