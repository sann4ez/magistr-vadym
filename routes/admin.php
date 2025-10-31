<?php

use Illuminate\Support\Facades\Route;

Route::get('admin/login', [\App\Http\Auth\Controllers\AuthenticatedSessionController::class, 'adminLogin']);
Route::post('admin/login', [\App\Http\Auth\Controllers\AuthenticatedSessionController::class, 'store']);

Route::group([
    'middleware' => ['auth', \App\Http\Middleware\SetAdminDomain::class],
    'prefix' => 'admin',
    'as' => 'admin.',
], function () {
    // HOME
    Route::redirect('/', '/admin/users');
    Route::view('/hello', 'admin.hello');

    // PAGES
    Route::resource('pages', \App\Http\Admin\Controllers\PageController::class, ['except' => 'show']);
    Route::post('pages/{page}/blocks/attach', [\App\Http\Admin\Controllers\PageController::class, 'blocksAttach'])->name('pages.blocks.attach');
    Route::post('pages/{page}/blocks/detach', [\App\Http\Admin\Controllers\PageController::class, 'blocksDetach'])->name('pages.blocks.detach');
    Route::post('pages/{page}/blocks/order', [\App\Http\Admin\Controllers\PageController::class, 'blocksOrder'])->name('pages.blocks.order');

    // BLOCKS
    Route::resource('blocks', \App\Http\Admin\Controllers\BlockController::class);
    Route::get('blocks/{block}/cloning', [\App\Http\Admin\Controllers\BlockController::class, 'cloning'])->name('blocks.cloning');

    // POSTS
    Route::resource('articles', \App\Http\Admin\Controllers\ArticleController::class, ['except' => 'show'])/*->middleware('can:post.read')*/;
    Route::resource('meditations', \App\Http\Admin\Controllers\MeditationController::class, ['except' => 'show'])/*->middleware('can:post.read')*/;

    // USERS
    Route::resource('users', \App\Http\Admin\Controllers\UserController::class)/*->middleware('can:user.read')*/;

    // PROFILE
    Route::get('profile', [\App\Http\Admin\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [\App\Http\Admin\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // TERMS
    Route::resource('terms', \App\Http\Admin\Controllers\TermController::class, ['except' => 'show']);
    Route::post('terms/order', [\App\Http\Admin\Controllers\TermController::class, 'order'])->name('terms.order');

    // SYSTEM
    Route::view('system/logs', 'admin.system.logs')->name('admin.system.logs');
    Route::view('system/tinker', 'admin.system.tinker')/*->middleware('can:dev')*/;
    Route::get('flogs', [\Ka4ivan\LaravelLogger\Http\Controllers\LogViewerController::class, 'index'])->name('flogs');
    Route::view('logs', 'admin.settings.sections.logs')->name('logs.index');

    // SUGGESTS
    Route::get('suggest/posts', [\App\Http\Admin\Controllers\SuggestController::class, 'posts'])->name('suggest.posts');
    Route::get('suggest/pages', [\App\Http\Admin\Controllers\SuggestController::class, 'pages'])->name('suggest.pages');

    // SETTINGS
    Route::redirect('settings', 'settings/items');
    Route::get('settings/{section}', [\App\Http\Admin\Controllers\SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('settings/save', [\App\Http\Admin\Controllers\SettingsController::class, 'save'])->name('settings.save');

    // ITEMS
    Route::resource('items', \App\Http\Admin\Controllers\ItemController::class, ['except' => ['show']]);
    Route::post('items/{item}/editable', [\App\Http\Admin\Controllers\ItemController::class, 'editable'])->name('items.editable');
    Route::post('items/order', [\App\Http\Admin\Controllers\ItemController::class, 'order'])->name('items.order');
});

Route::group([
    'as' => 'unisharp.lfm.',
    'middleware' => [
        'web', 'auth',
        \UniSharp\LaravelFilemanager\Middlewares\CreateDefaultFolder::class,
        \UniSharp\LaravelFilemanager\Middlewares\MultiUser::class]
], function() {
    Route::get('filemanager/jsonitems', [\App\Http\Admin\Controllers\LfmItemsController::class, 'getItems'])->name('getItems');
});
