<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
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

    // supplier
    Route::get('supplier/index', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('supplier/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::get('supplier/show/{id}', [SupplierController::class, 'show'])->name('supplier.show');
    Route::put('supplier/update/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('supplier/destroy/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // User management
    Route::get('user/index', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    
    // Penjualan
    Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('sales/store', [SalesController::class, 'store'])->name('sales.store');
    
    // cart
    Route::get('cart/store/{barcode}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('cart/reset', [CartController::class, 'resetCart'])->name('cart.reset');
    Route::put('cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    // Route::post('cart/store', [CartController::class, 'store'])->name('cart.store');
});

require __DIR__.'/auth.php';
