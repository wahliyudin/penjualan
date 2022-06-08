<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ClassificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TypeProductController;
use App\Http\Controllers\HomeController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('master-data')->group(function () {
        Route::prefix('tipe-barang')->name('type-products.')->group(function () {
            Route::get('/', [TypeProductController::class, 'index'])->name('index');
        });
        Route::prefix('klasipikasi')->name('classifications.')->group(function () {
            Route::get('/', [ClassificationController::class, 'index'])->name('index');
        });
        Route::prefix('rekening')->name('accounts.')->group(function () {
            Route::get('/', [AccountController::class, 'index'])->name('index');
        });
    });
});
Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
