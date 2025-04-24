<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\PurchasesDetailsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::resource('item', ItemController::class);
Route::resource('customers', CustomersController::class);
Route::resource('purchases', PurchasesController::class)->parameters([
    'purchases' => 'purchases'
]);
Route::resource('purchases_details', PurchasesDetailsController::class)->parameters([
    'purchases_details' => 'purchases_details'
]);


require __DIR__.'/auth.php';
