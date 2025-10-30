<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('pages.dashboard.index', [
            'title' => 'Dashboard',
            'menu' => 'dashboard',
        ]);
    })->name('dashboard');

    // other controller for any licensed function
    Route::get('product/search', [OtherController::class, 'searchProduct'])->name('product.search');
    Route::get('product/show/by-id/{item_id}', [OtherController::class, 'showDetailProductById'])->name('product/show.by-id');
    Route::get('product/get/data/batch', [OtherController::class, 'getDataBatch'])->name('product/get/data.batch');
    Route::get('product/get/data/batch/format2', [OtherController::class, 'getDataBatchV2'])->name('product/get/data/batch.v2');
    Route::get('product/get/item/batch/{batchId}', [OtherController::class, 'getItemBatch'])->name('product/get/item.batch');
    Route::get('product/get/batch/select', [OtherController::class, 'getBatchSelect'])->name('product/get/batch.select');
    // Route::get('product/to/cart/{id}', [OtherController::class, 'addProductToCart'])->name('product/to.cart');
    Route::get('purchase/temp/detail/byId/{id}', [OtherController::class, 'getTempDetailById'])->name('purchase/temp/detail.byId');


    // kategori barang (DONE)
    Route::get('kategori/barang/index', [ItemCategoryController::class, 'index'])->name('kategori/barang.index');
    Route::get('kategori/barang/create', [ItemCategoryController::class, 'create'])->name('kategori/barang.create');
    Route::post('kategori/barang/store', [ItemCategoryController::class, 'store'])->name('kategori/barang.store');
    Route::get('kategori/barang/show/{id}', [ItemCategoryController::class, 'show'])->name('kategori/barang.show');
    Route::get('kategori/barang/edit/{id}', [ItemCategoryController::class, 'edit'])->name('kategori/barang.edit');
    Route::delete('kategori/barang/destroy/{id}', [ItemCategoryController::class, 'destroy'])->name('kategori/barang.destroy');
    Route::post('kategori/barang/restore/{id}', [ItemCategoryController::class, 'restore'])->name('kategori/barang.restore');

    // barang
    Route::get('barang/index', [ItemController::class, 'index'])->name('barang.index');
    Route::get('barang/create', [ItemController::class, 'create'])->name('barang.create');
    Route::post('barang/store', [ItemController::class, 'store'])->name('barang.store');
    Route::get('barang/edit/{id}', [ItemController::class, 'edit'])->name('barang.edit');
    Route::get('barang/show/{id}', [ItemController::class, 'show'])->name('barang.show');
    Route::delete('barang/destroy/{id}', [ItemController::class, 'destroy'])->name('barang.destroy');
    Route::post('barang/restore/{id}', [ItemController::class, 'restore'])->name('barang.restore');
    // store and add to cart
    Route::post('item/store/add/to/cart', [ItemController::class, 'storeAndAddToCart'])->name('item/store/add/to.cart');

    // supplier (DONE)
    Route::get('supplier/index', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('supplier/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::delete('supplier/destroy/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::post('supplier/restore/{id}', [SupplierController::class, 'restore'])->name('supplier.restore');

    // User management
    Route::get('user/index', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('user/restore/{id}', [UserController::class, 'restore'])->name('user.restore');

    // Penjualan
    Route::get('sales/riwayat', [SalesController::class, 'index'])->name('sales/riwayat.index');
    Route::get('sales/riwayat/detail/{id}', [SalesController::class, 'detail'])->name('sales/riwayat.detail');
    Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('sales/store', [SalesController::class, 'store'])->name('sales.store');
    Route::get('sales/invoice', [SalesController::class, 'show'])->name('sales.invoice');
    Route::post('sales/add/to/cart', [SalesController::class, 'addToCart'])->name('sales/add/to.cart');

    // cart
    Route::get('cart/store/{barcode}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('cart/reset', [CartController::class, 'resetCart'])->name('cart.reset');
    Route::put('cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    // Route::post('cart/store', [CartController::class, 'store'])->name('cart.store');

    // Pembelian
    Route::get('pembelian/create', [TransactionController::class, 'create'])->name('pembelian.create');
    Route::post('pembelian/store/item', [TransactionController::class, 'storeItem'])->name('pembelian/store.item');
    Route::put('pembelian/update/item/{id}', [TransactionController::class, 'updateItem'])->name('pembelian/update.item');
    Route::delete('pembelian/destroy/{id}', [TransactionController::class, 'destroyItem'])->name('pembelian.destroy');
    Route::delete('pembelian/reset', [TransactionController::class, 'resetCart'])->name('pembelian.reset');
    Route::post('pembelian/save/all', [TransactionController::class, 'finishPurchase'])->name('pembelian/save.all');

    // setting
    Route::get('pengaturan/sistem/index', [SettingController::class, 'index'])->name('pengaturan/sistem.index');
    Route::post('pengaturan/sistem/store', [SettingController::class, 'store'])->name('pengaturan/sistem.store');

    // Satuan
    Route::get('unit/index', [UnitController::class, 'index'])->name('unit.index');
    Route::get('unit/create', [UnitController::class, 'create'])->name('unit.create');
    Route::post('unit/store', [UnitController::class, 'store'])->name('unit.store');
    Route::get('unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::get('unit/show/{id}', [UnitController::class, 'show'])->name('unit.show');
    Route::put('unit/update/{id}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('unit/destroy/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');

    // Pelanggan (DONE)
    Route::get('customer/index', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::delete('customer/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::post('customer/restore/{id}', [CustomerController::class, 'restore'])->name('customer.restore');

    // stok barang
    Route::get('stok/barang', [ProductStockController::class, 'index'])->name('stok/barang.index');
    Route::get('stok/barang/create', [ProductStockController::class, 'create'])->name('stok/barang.create');
    Route::post('stok/barang/store', [ProductStockController::class, 'store'])->name('stok/barang.store');

    // Extras
    // inventory Movements
    Route::get('inventory/movement/get/table', [InventoryMovementController::class, 'getTable'])->name('inventory/movement.getTable');
    Route::get('inventory/movement', [InventoryMovementController::class, 'index'])->name('inventory/movement.index');
    Route::get('inventory/movement/create', [InventoryMovementController::class, 'create'])->name('inventory/movement.create');
    Route::post('inventory/movement/store', [InventoryMovementController::class, 'store'])->name('inventory/movement.store');
});

require __DIR__.'/auth.php';
