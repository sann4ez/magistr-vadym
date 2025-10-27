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

    // SYSTEM
    Route::view('system/logs', 'admin.system.logs')->name('admin.system.logs');
    Route::view('system/tinker', 'admin.system.tinker')/*->middleware('can:dev')*/;
    Route::get('flogs', [\Ka4ivan\LaravelLogger\Http\Controllers\LogViewerController::class, 'index'])->name('flogs');
    Route::view('logs', 'admin.settings.sections.logs')->name('logs.index');
});
