<?php

//use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\MicropostsController;
use App\Http\Controllers\UserFollowController;  // フォロー
use App\Http\Controllers\FavoritesController;      // お気に入り

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MicropostsController::class, 'index']);

Route::get('/dashboard', [MicropostsController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // フォロー関連
    Route::prefix('users/{id}')->group(function () {
        Route::post('follow', [UserFollowController::class, 'store'])->name('user.follow');
        Route::delete('unfollow', [UserFollowController::class, 'destroy'])->name('user.unfollow');
        Route::get('followings', [UsersController::class, 'followings'])->name('users.followings');
        Route::get('followers', [UsersController::class, 'followers'])->name('users.followers');
    });

    // お気に入り追加・解除
    Route::prefix('microposts/{id}')->group(function () {
        Route::post('favorites', [FavoritesController::class, 'store'])->name('favorites.favorite');
        Route::delete('unfavorite', [FavoritesController::class, 'destroy'])->name('favorites.unfavorite');
    });

    // Micropost登録・削除
    Route::resource('microposts', MicropostsController::class, ['only' => ['store', 'destroy']]);

    // ユーザー関連
    Route::resource('users', UsersController::class, ['only' => ['index', 'show']]);

    // ユーザーのお気に入り投稿一覧（ユーザー詳細ページ内のタブ用）
    Route::get('users/{id}/favorites', [UsersController::class, 'favorites'])->name('users.favorites');
});

require __DIR__.'/auth.php';