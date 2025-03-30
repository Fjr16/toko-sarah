<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
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
    });
    // Route::get('/home', function () {
    //     return view('welcome');
    // });

    // kategori barang
    Route::get('kategori/barang/index', [ItemCategoryController::class, 'index'])->name('kategori/barang.index');
    Route::get('kategori/barang/create', [ItemCategoryController::class, 'create'])->name('kategori/barang.create');
    Route::post('kategori/barang/store', [ItemCategoryController::class, 'store'])->name('kategori/barang.store');
    Route::get('kategori/barang/show/{id}', [ItemCategoryController::class, 'show'])->name('kategori/barang.show');
    Route::get('kategori/barang/edit/{id}', [ItemCategoryController::class, 'edit'])->name('kategori/barang.edit');
    Route::put('kategori/barang/update/{id}', [ItemCategoryController::class, 'update'])->name('kategori/barang.update');
    Route::delete('kategori/barang/destroy/{id}', [ItemCategoryController::class, 'destroy'])->name('kategori/barang.destroy');
    
    // barang
    Route::get('barang/index', [ItemController::class, 'index'])->name('barang.index');
    Route::get('barang/create', [ItemController::class, 'create'])->name('barang.create');
    Route::post('barang/store', [ItemController::class, 'store'])->name('barang.store');
    Route::get('barang/edit/{id}', [ItemController::class, 'edit'])->name('barang.edit');
    Route::put('barang/update/{id}', [ItemController::class, 'update'])->name('barang.update');
    Route::delete('barang/destroy/{id}', [ItemController::class, 'destroy'])->name('barang.destroy');
    // store and add to cart
    Route::post('item/store/add/to/cart', [ItemController::class, 'storeAndAddToCart'])->name('item/store/add/to.cart');

    // supplier
    Route::get('supplier/index', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('supplier/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('supplier/update/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('supplier/destroy/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::post('supplier/restore/{id}', [SupplierController::class, 'restore'])->name('supplier.restore');

    // User management
    Route::get('user/index', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    
    // Penjualan
    Route::get('sales/riwayat', [SalesController::class, 'index'])->name('sales/riwayat.index');
    Route::get('sales/riwayat/detail/{id}', [SalesController::class, 'detail'])->name('sales/riwayat.detail');
    Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('sales/store', [SalesController::class, 'store'])->name('sales.store');
    Route::get('sales/invoice', [SalesController::class, 'show'])->name('sales.invoice');
    
    // cart
    Route::get('cart/store/{barcode}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('cart/reset', [CartController::class, 'resetCart'])->name('cart.reset');
    Route::put('cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    // Route::post('cart/store', [CartController::class, 'store'])->name('cart.store');

    // Pembelian
    // Route::get('pembelian/riwayat', [SalesController::class, 'index'])->name('pembelian/riwayat.index');
    // Route::get('pembelian/riwayat/detail/{id}', [SalesController::class, 'detail'])->name('pembelian/riwayat.detail');
    Route::get('pembelian/create', [TransactionController::class, 'create'])->name('pembelian.create');
    Route::get('pembelian/store/{barcode}', [TransactionController::class, 'store'])->name('pembelian.store');
    Route::delete('pembelian/destroy/{id}', [TransactionController::class, 'destroy'])->name('pembelian.destroy');
    Route::delete('pembelian/reset', [TransactionController::class, 'reset'])->name('pembelian.reset');
    Route::put('pembelian/update/{id}', [TransactionController::class, 'update'])->name('pembelian.update');
    Route::get('pembelian/invoice', [TransactionController::class, 'show'])->name('pembelian.invoice');

    // setting
    Route::get('pengaturan/sistem.index', [SettingController::class, 'index'])->name('pengaturan/sistem.index');
    // Mata Uang
    Route::get('currency/index', [CurrencyController::class, 'index'])->name('currency.index');
    Route::get('currency/create', [CurrencyController::class, 'create'])->name('currency.create');
    Route::post('currency/store', [CurrencyController::class, 'store'])->name('currency.store');
    Route::get('currency/edit/{id}', [CurrencyController::class, 'edit'])->name('currency.edit');
    Route::get('currency/show/{id}', [CurrencyController::class, 'show'])->name('currency.show');
    Route::put('currency/update/{id}', [CurrencyController::class, 'update'])->name('currency.update');
    Route::delete('currency/destroy/{id}', [CurrencyController::class, 'destroy'])->name('currency.destroy');

    // Satuan
    Route::get('unit/index', [UnitController::class, 'index'])->name('unit.index');
    Route::get('unit/create', [UnitController::class, 'create'])->name('unit.create');
    Route::post('unit/store', [UnitController::class, 'store'])->name('unit.store');
    Route::get('unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::get('unit/show/{id}', [UnitController::class, 'show'])->name('unit.show');
    Route::put('unit/update/{id}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('unit/destroy/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');

    // Pelanggan
    Route::get('customer/index', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('customer/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::post('customer/restore/{id}', [CustomerController::class, 'restore'])->name('customer.restore');
});

require __DIR__.'/auth.php';
