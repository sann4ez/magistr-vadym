<?php

use Illuminate\Support\Facades\Route;

//Route::get('admin/login', [\App\Http\Auth\Controllers\AuthenticatedSessionController::class, 'adminLogin']);
//Route::post('admin/login', [\App\Http\Auth\Controllers\AuthenticatedSessionController::class, 'store']);
//
Route::group([
//    'middleware' => ['auth', 'can:dashboard.auth', \App\Http\Middleware\SetAdminDomain::class],
    'prefix' => 'admin',
    'as' => 'admin.',
], function () {
    // HOME
    Route::redirect('/', '/admin/users');
    Route::view('/hello', 'admin.hello');

    // POSTS
    Route::resource('articles', \App\Http\Admin\Controllers\ArticleController::class, ['except' => 'show'])/*->middleware('can:post.read')*/;
    Route::resource('meditations', \App\Http\Admin\Controllers\MeditationController::class, ['except' => 'show'])/*->middleware('can:post.read')*/;

    // USERS
    Route::resource('users', \App\Http\Admin\Controllers\UserController::class)/*->middleware('can:user.read')*/;

    // TERMS
    Route::resource('terms', \App\Http\Admin\Controllers\TermController::class, ['except' => 'show']);
    Route::post('terms/order', [\App\Http\Admin\Controllers\TermController::class, 'order'])->name('terms.order');
});
