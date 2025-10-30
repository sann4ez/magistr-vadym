<?php

use Illuminate\Support\Facades\Route;
use App\Http\Auth\Controllers\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});
require __DIR__ . '/admin.php';

Route::middleware(['guest'/*, \App\Http\Middleware\SetClientDomain::class*/])->group(function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth'/*, \App\Http\Middleware\SetClientDomain::class*/])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
