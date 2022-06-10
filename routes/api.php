<?php

use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\ClassificationController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\SupplyController;
use App\Http\Controllers\API\TypeProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::name('api.')->group(function () {
    Route::prefix('type-products')->name('type-products.')->group(function () {
        Route::post('/', [TypeProductController::class, 'index'])->name('index');
        Route::post('update-or-create', [TypeProductController::class, 'updateOrCreate'])->name('update-or-create');
        Route::get('{id}/edit', [TypeProductController::class, 'edit'])->name('edit');
        Route::delete('{id}/destroy', [TypeProductController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('classifications')->name('classifications.')->group(function () {
        Route::post('/', [ClassificationController::class, 'index'])->name('index');
        Route::post('update-or-create', [ClassificationController::class, 'updateOrCreate'])->name('update-or-create');
        Route::get('{id}/edit', [ClassificationController::class, 'edit'])->name('edit');
        Route::delete('{id}/destroy', [ClassificationController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::post('/', [AccountController::class, 'index'])->name('index');
        Route::post('update-or-create', [AccountController::class, 'updateOrCreate'])->name('update-or-create');
        Route::get('{id}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::delete('{id}/destroy', [AccountController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('products')->name('products.')->group(function () {
        Route::post('/', [ProductController::class, 'index'])->name('index');
        Route::post('update-or-create', [ProductController::class, 'updateOrCreate'])->name('update-or-create');
        Route::get('{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::delete('{id}/destroy', [ProductController::class, 'destroy'])->name('destroy');

        Route::get('{id}/by-id', [ProductController::class, 'byId'])->name('by-id');
    });
    Route::prefix('supplies')->name('supplies.')->group(function () {
        Route::post('/', [SupplyController::class, 'index'])->name('index');
        Route::post('update-or-create', [SupplyController::class, 'updateOrCreate'])->name('update-or-create');
        Route::get('{id}/edit', [SupplyController::class, 'edit'])->name('edit');
        Route::delete('{id}/destroy', [SupplyController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::post('/', [CustomerController::class, 'index'])->name('index');
        Route::post('update-or-create', [CustomerController::class, 'updateOrCreate'])->name('update-or-create');
        Route::get('{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::delete('{id}/destroy', [CustomerController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::post('/', [SaleController::class, 'index'])->name('index');
        Route::post('store', [SaleController::class, 'store'])->name('store');
        Route::put('{id}/update', [SaleController::class, 'update'])->name('update');
        Route::delete('{id}/destroy', [SaleController::class, 'destroy'])->name('destroy');
        Route::delete('{id}/destroy-sale-detail', [SaleController::class,
        'destroySaleDetail'])->name('destroy-sale-detail');
    });
});
