<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;

Route::get('/', function () {
    return view('welcome');
});
<<<<<<< HEAD
Route::get('/login', function () {
    return view('backend.page.login');
})->name('login');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
=======
>>>>>>> parent of 96fb664 (add login function)
