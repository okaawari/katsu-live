<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    BrowseController,
    WatchController,
    VideoProgressController,
    UserController,
};
use Illuminate\Support\Facades\Storage;
use App\Models\Anime;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::view('/', 'welcome');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('browse', [BrowseController::class, 'index'])->name('browse');
    Route::get('watch/{id}', [WatchController::class, 'index'])->name('watch');

    // Profile
    Route::view('profile', 'profile')->name('profile');
    Route::get('user', [UserController::class, 'index'])->name('user');

    // Video Progress
    Route::post('/save-progress', [VideoProgressController::class, 'saveProgress'])->name('save-progress');
    Route::get('/get-progress/{animeId}', [VideoProgressController::class, 'getProgress'])->name('get-progress');

    // Pricing Plan
    Route::view('pricing', 'pricing')->name('pricing');
});

// Auth routes
require __DIR__.'/auth.php';
