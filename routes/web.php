<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\WatchController;
use App\Http\Controllers\VideoProgressController;
use App\Livewire\Search;

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

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');
    
Route::get('browse', [BrowseController::class, 'index'])
    ->middleware(['auth'])
    ->name('browse');

Route::get('watch/{id}', [WatchController::class, 'index'])
    ->middleware(['auth', 'verified']);

// Route::view('home', 'home')
//     ->middleware(['auth'])
//     ->name('home');

Route::post('/save-progress', [VideoProgressController::class, 'saveProgress']);
Route::get('/get-progress/{animeId}', [VideoProgressController::class, 'getProgress']);

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
