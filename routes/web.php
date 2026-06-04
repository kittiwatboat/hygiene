<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
