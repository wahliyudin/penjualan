<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ClassificationController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SupplyController;
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
    return redirect()->route('login');
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

    Route::prefix('barang')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
    });

    Route::prefix('persediaan')->name('supplies.')->group(function () {
        Route::get('/', [SupplyController::class, 'index'])->name('index');
    });

    Route::prefix('pelanggan')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
    });

    Route::prefix('penjualan')->name('sales.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('create', [SaleController::class, 'create'])->name('create');
        Route::get('{id}/edit', [SaleController::class, 'edit'])->name('edit');

        Route::get('{id}/cetak-struk', [SaleController::class, 'cetakStruk'])->name('cetak-struk');
    });
});
Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
