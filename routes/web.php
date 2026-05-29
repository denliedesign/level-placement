<?php

use App\Http\Controllers\LevelsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DanceClassesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/scheduler', [DanceClassesController::class, 'scheduler'])->name('dance-classes.scheduler');
Route::match(['get', 'post'], '/scheduler/results', [DanceClassesController::class, 'filter'])->name('dance-classes.filter');
Route::post('/scheduler/favorites/download', [DanceClassesController::class, 'downloadFavorites'])->name('dance-classes.favorites.download');
Route::post('/scheduler/favorites/email', [DanceClassesController::class, 'emailFavorites'])->name('dance-classes.favorites.email');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/levels', [LevelsController::class, 'index'])->name('levels.index');
    Route::get('/levels/import', [LevelsController::class, 'showForm'])->name('levels.import.form');
    Route::post('/levels/import', [LevelsController::class, 'import'])->name('levels.import');
    Route::get('/dance-classes/import', [DanceClassesController::class, 'showImportForm'])->name('dance-classes.import.form');
    Route::post('/dance-classes/import', [DanceClassesController::class, 'import'])->name('dance-classes.import');
});


require __DIR__.'/auth.php';
