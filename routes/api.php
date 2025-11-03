<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => [
//    \App\Http\Middleware\SetClientDomain::class,
//    \App\Http\Middleware\StateClientResponse::class
]], function () {

    require __DIR__.'/api-auth.php';

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'my'], function () {
        Route::get('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'edit']);
        Route::post('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'update']);
        Route::delete('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'delete']);

        // TRACK
        Route::group(['prefix' => 'tracks'], function () {
            Route::get('/form', [\App\Http\Client\Api\Controllers\My\UsertrackerController::class, 'form']);
            Route::post('/', [\App\Http\Client\Api\Controllers\My\UsertrackerController::class, 'tracks']);
            Route::get('/{created_at}/list', [\App\Http\Client\Api\Controllers\My\UsertrackerController::class, 'list']);
            Route::get('/statistic', [\App\Http\Client\Api\Controllers\My\UsertrackerController::class, 'statistic']);
        });
    });

    // PAGES
    Route::get('pages/{page:slug}', [\App\Http\Client\Api\Controllers\PageController::class, 'show']);

    // ARTICLES
    Route::get('articles', [\App\Http\Client\Api\Controllers\ArticleController::class, 'index']);
    Route::get('articles/categories', [\App\Http\Client\Api\Controllers\ArticleController::class, 'categories']);
    Route::get('articles/{slug}', [\App\Http\Client\Api\Controllers\ArticleController::class, 'show']);
    Route::get('articles/categories/{category:slug}', [\App\Http\Client\Api\Controllers\ArticleController::class, 'category']);

    // MEDITATIONS
    Route::get('meditations', [\App\Http\Client\Api\Controllers\MeditationController::class, 'index']);
    Route::get('meditations/categories', [\App\Http\Client\Api\Controllers\MeditationController::class, 'categories']);
    Route::get('meditations/{slug}', [\App\Http\Client\Api\Controllers\MeditationController::class, 'show']);
    Route::get('meditations/categories/{category:slug}', [\App\Http\Client\Api\Controllers\MeditationController::class, 'category']);
});





