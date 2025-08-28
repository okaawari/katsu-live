<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    BrowseController,
    WatchController,
    VideoProgressController,
    UserController,
    ProfileController,
    TransactionController,
    UserSessionController,
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
Route::get('/transaction/test', [TransactionController::class, 'test']);

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('home', [HomeController::class, 'index'])->name('home');
    
    // Browse routes
    Route::get('browse', [BrowseController::class, 'index'])->name('browse');
    Route::get('browse/suggestions', [BrowseController::class, 'suggestions'])->name('browse.suggestions');
    
    // Watch routes
    Route::get('watch/{slug}', [WatchController::class, 'index'])->name('watch');
    Route::get('/watching', [WatchController::class, 'refresh'])->name('watching.index');
    Route::get('/anime/{id}/details', [WatchController::class, 'getAnimeDetails'])->name('anime.details');

    // Profile routes
    Route::get('profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::post('settings/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('settings/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('settings/stats', [ProfileController::class, 'getStats'])->name('profile.stats');
    
    // Legacy routes for backward compatibility
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::post('user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('user/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::get('user/stats', [UserController::class, 'getStats'])->name('user.stats');
    
    // Redirect old profile route to new settings
    Route::get('profile', function() {
        return redirect()->route('profile.settings');
    })->name('profile');

    // Video Progress routes
    Route::post('/save-progress', [VideoProgressController::class, 'saveProgress'])->name('save-progress');
    Route::get('/get-progress/{animeId}', [VideoProgressController::class, 'getProgress'])->name('get-progress');
    Route::get('/all-progress', [VideoProgressController::class, 'getAllProgress'])->name('all-progress');
    Route::delete('/watching/{id}', [VideoProgressController::class, 'destroy'])->name('watching.destroy');

    // User Session routes
    Route::post('sessions/record-login', [UserSessionController::class, 'recordLogin'])->name('sessions.record-login');
    Route::delete('sessions/{id}', [UserSessionController::class, 'endSession'])->name('sessions.end');
    Route::delete('sessions/end-others', [UserSessionController::class, 'endOtherSessions'])->name('sessions.end-others');
    Route::get('sessions/active', [UserSessionController::class, 'getActiveSessions'])->name('sessions.active');
    Route::get('sessions/stats', [UserSessionController::class, 'getSessionStats'])->name('sessions.stats');

    // Pricing Plan
    Route::view('pricing', 'pricing')->name('pricing');
});

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    
    // Anime management
    Route::resource('anime', App\Http\Controllers\Admin\AnimeController::class);
    
    // Episode management
    Route::resource('episodes', App\Http\Controllers\Admin\EpisodeController::class);
    Route::post('episodes/upload-progress', [App\Http\Controllers\Admin\EpisodeController::class, 'uploadProgress'])->name('episodes.upload-progress');
});

// Auth routes
require __DIR__.'/auth.php';
