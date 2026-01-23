<?php

use App\Http\Controllers\EprocController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');


Route::controller(EprocController::class)->prefix('eproc')->middleware(['auth'])->group(function () {
    Route::get('/', 'index')->name('eproc/index');
    Route::get('/create', 'create')->middleware(['auth'])->name('eproc/create');
    Route::post('/', 'store')->middleware(['auth'])->name('eproc/store');
    Route::get('/{id}/edit', 'edit')->middleware(['auth'])->name('eproc/edit');
    Route::get('/{id}', 'show')->middleware(['auth'])->name('eproc/show');
    Route::put('/{id}', 'update')->middleware(['auth'])->name('eproc/update');
    Route::delete('/{id}', 'destroy')->middleware(['auth'])->name('eproc/destroy');
});

Route::controller(UserController::class)->prefix('user')->middleware(['auth'])->group(function () {
    Route::get('/', 'index')->name('user/index');
    Route::get('/create', 'create')->middleware(['auth'])->name('user/create');
    Route::post('/', 'store')->middleware(['auth'])->name('user/store');
    Route::get('/{id}/edit', 'edit')->middleware(['auth'])->name('user/edit');
    Route::get('/{id}', 'show')->middleware(['auth'])->name('user/show');
    Route::put('/{id}', 'update')->middleware(['auth'])->name('user/update');
    Route::delete('/{id}', 'destroy')->middleware(['auth'])->name('user/destroy');
});

// Route::controller()

require __DIR__ . '/settings.php';
