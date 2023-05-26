<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WorkLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group((function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:developer')->group(function () {
        Route::get('/developers/{developer}/worklogs', [DeveloperController::class, 'worklogs'])->name('developers.worklogs');
    });

    Route::middleware('role:developer|admin')->group(function () {
        Route::resource('worklogs', WorkLogController::class)->only('create', 'store');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('clients', ClientController::class)->except('show');
        Route::resource('projects', ProjectController::class)->except('show');
        Route::resource('developers', DeveloperController::class)->except('show');

        Route::resource('worklogs', WorkLogController::class)->except('show','create', 'store');
    });
}));


require __DIR__ . '/auth.php';
