<?php

use App\Http\Controllers\Auth\RegisterTenantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Livewire\ServiceOrder\Index;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';


Route::get('/register', [RegisterTenantController::class, 'create'])->name('register');
Route::post('/register', [RegisterTenantController::class, 'store'])->name('register.store');

Route::get('/vehicles/create', function () {
    return view('vehicles.create');
})->middleware(['auth'])->name('vehicles.create');

Route::get('/customers/index', function () {
    return view('customers.index');
})->middleware(['auth'])->name('customers.index');


Route::middleware(['auth'])->group(function () {

    Route::get('/service-orders/create', [ServiceOrderController::class, 'create'])->name('service-orders.create');

    Route::get('/service-orders/index', [ServiceOrderController::class, 'index'])->name('service-orders.index');

    Route::post('/service-orders/store', [ServiceOrderController::class, 'store'])->name('service-orders.store');

    Route::get('/service-orders/edit/{id}', [ServiceOrderController::class, 'edit'])->name('service-orders.edit');

    Route::put('/service-orders/update/{id}', [ServiceOrderController::class, 'update'])->name('service-orders.update');

    Route::get('/service-orders/{id}/print', [ServiceOrderController::class, 'print'])->name('service-orders.print');

    Route::get('/service-orders/{id}', [ServiceOrderController::class, 'show'])->name('service-orders.show');

    Route::delete('/service-orders/{id}', [ServiceOrderController::class, 'destroy'])->name('service-orders.destroy');

    Route::put('/service-orders/close/{id}', [ServiceOrderController::class, 'closeServiceOrder'])->name('service-orders.close');

    Route::put('/service-orders/reopen/{id}', [ServiceOrderController::class, 'reopen'])->name('service-orders.reopen');

    Route::put('/service-orders/cancel/{id}', [ServiceOrderController::class, 'cancel'])->name('service-orders.cancel');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/items/index', [ItemController::class, 'index'])->name('items.index');

    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');

    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');

    Route::get('/items/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');

    Route::put('/items/update/{id}', [ItemController::class, 'update'])->name('items.update');

    Route::delete('/items/destroy/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

});
