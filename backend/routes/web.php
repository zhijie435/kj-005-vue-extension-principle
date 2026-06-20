<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\ShipmentController as AdminShipmentController;
use App\Http\Controllers\Supplier\OrderController as SupplierOrderController;
use App\Http\Controllers\Supplier\ProductController as SupplierProductController;
use App\Http\Controllers\Supplier\ReturnController as SupplierReturnController;
use App\Http\Controllers\Supplier\ShipmentController as SupplierShipmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::get('/products/{id}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');

    Route::get('/shipments', [AdminShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [AdminShipmentController::class, 'create'])->name('shipments.create');
    Route::get('/shipments/{id}', [AdminShipmentController::class, 'show'])->name('shipments.show');

    Route::get('/returns', [AdminReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{id}', [AdminReturnController::class, 'show'])->name('returns.show');
    Route::get('/returns/{id}/edit', [AdminReturnController::class, 'edit'])->name('returns.edit');
});

Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/products', [SupplierProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [SupplierProductController::class, 'create'])->name('products.create');
    Route::get('/products/{id}', [SupplierProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit', [SupplierProductController::class, 'edit'])->name('products.edit');

    Route::get('/orders', [SupplierOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [SupplierOrderController::class, 'show'])->name('orders.show');

    Route::get('/shipments', [SupplierShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [SupplierShipmentController::class, 'create'])->name('shipments.create');
    Route::get('/shipments/{id}', [SupplierShipmentController::class, 'show'])->name('shipments.show');

    Route::get('/returns', [SupplierReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{id}', [SupplierReturnController::class, 'show'])->name('returns.show');
    Route::get('/returns/{id}/edit', [SupplierReturnController::class, 'edit'])->name('returns.edit');
});
