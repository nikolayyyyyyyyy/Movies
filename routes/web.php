<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMovieReactionController;
use App\Http\Controllers\FavoriteController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('movies', MovieController::class)
        ->scoped(['movie' => 'slug']);
    Route::resource('categories', CategoryController::class)
        ->scoped(['category' => 'slug']);

    Route::resource('users', UserController::class);
    Route::resource('actors', ActorController::class);

    Route::post('/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');

    Route::post('/user-movie-reactions', [UserMovieReactionController::class, 'store'])
        ->name('user-movie-reactions.store');

    Route::post('/favorites', [FavoriteController::class, 'storeOrDestroy'])
        ->name('favorites.storeOrDestroy');

    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites.index');
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/update-profile-picture', [ProfileController::class, 'updateProfilePicture'])
        ->name('profile.update-profile-picture');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    Route::put('/profile/destroy-profile-picture', [ProfileController::class, 'destroyProfilePicture'])
        ->name('users.destroy-profile-picture');
});

require __DIR__ . '/auth.php';
