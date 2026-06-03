<?php

use App\Http\Controllers\Auth\RegisterTenantController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';


Route::get('/register', [RegisterTenantController::class, 'create'])->name('register');
Route::post('/register', [RegisterTenantController::class, 'store'])->name('register.store');

Route::get('/vehicles/create', function () {
    return view('vehicles.create');
})->middleware(['auth'])->name('vehicles.create');

Route::get('/customers/index', function() {
    return view('customers.index');
})->middleware(['auth'])->name('customers.index');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/service-orders/create', [ServiceOrderController::class, 'create'])->name('service-orders.create');
    
    Route::get('/service-orders/index', [ServiceOrderController::class, 'index'])->name('service-orders.index');

    Route::post('/service-orders/store', [ServiceOrderController::class, 'store'])->name('service-orders.store');
    
    Route::get('/service-orders/{id}', [ServiceOrderController::class, 'show'])->name('service-orders.show');
});
