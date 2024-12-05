<?php

use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
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
    Route::get('/home', function () {
        return view('welcome');
    });

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
});

require __DIR__.'/auth.php';
