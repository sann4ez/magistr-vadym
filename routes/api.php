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

//    // MY/PROFILE
//    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'my'], function () {
//        Route::get('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'edit']);
//        Route::post('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'update']);
//        Route::delete('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'delete']);
//        Route::post('profile/password', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'updatePassword']);
//
//        Route::get('orders', [\App\Http\Client\Api\Controllers\My\OrderController::class, 'index']);
//        Route::post('orders/{order:number}/payment', [\App\Http\Client\Api\Controllers\My\OrderController::class, 'paymentLink']);
//        Route::post('orders/{order:number}/canceled', [\App\Http\Client\Api\Controllers\My\OrderController::class, 'canceled']);
//
//        Route::resource('profilings', \App\Http\Client\Api\Controllers\My\ProfilingController::class);
//
//        // FAVORITES
//        Route::group(['prefix' => 'favorites'], function () {
//            Route::get('variations', [\App\Http\Client\Api\Controllers\My\FavoriteController::class, 'variations']);
//            Route::post('variations/{variation}', [\App\Http\Client\Api\Controllers\My\FavoriteController::class, 'variation']);
//        });
//    });
//
//    // PAGES
//    Route::get('pages/{page:slug}', [\App\Http\Client\Api\Controllers\PageController::class, 'show']);
//
//    // BLOG
//    Route::get('/blog/posts', [\App\Http\Client\Api\Controllers\BlogController::class, 'index']);
//    Route::get('/blog/posts/{post:slug}', [\App\Http\Client\Api\Controllers\BlogController::class, 'show']);
//    Route::get('blog/categories', [\App\Http\Client\Api\Controllers\BlogController::class, 'categories']);
//    Route::get('blog/categories/tree/view', [\App\Http\Client\Api\Controllers\BlogController::class, 'categoriesTree']);
//    Route::get('blog/categories/{category:slug}', [\App\Http\Client\Api\Controllers\BlogController::class, 'category']);
//
//    // CONTENT
//    Route::get('app/glob', [\App\Http\Client\Api\Controllers\AppController::class, 'glob']);
//    Route::get('app/content', [\App\Http\Client\Api\Controllers\AppController::class, 'glob']); // TODO: Deprecated!
//    Route::get('app/slug/{slug}', [\App\Http\Client\Api\Controllers\AppController::class, 'slug']);
//    Route::get('app/translations', [\App\Http\Client\Api\Controllers\AppController::class, 'translations']);
//    Route::get('app/menu/catalog', [\App\Http\Client\Api\Controllers\AppController::class, 'menuCatalog']);
//
//    // BLOCK
//    Route::get('blocks', [\App\Http\Client\Api\Controllers\BlockController::class, 'index']);
//    Route::get('blocks/{block:slug}', [\App\Http\Client\Api\Controllers\BlockController::class, 'show']);

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





