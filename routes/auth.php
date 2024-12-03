<?php

use App\Http\Controllers\AuthenticateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthenticateController::class, 'create'])->name('login');
    Route::post('login', [AuthenticateController::class, 'store']);
});
Route::middleware(['auth'])->group(function(){
    Route::post('logout', [AuthenticateController::class, 'destroy'])->name('logout');
});