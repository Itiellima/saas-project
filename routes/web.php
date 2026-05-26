<?php

use App\Http\Controllers\Auth\RegisterTenantController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Pest\Support\View;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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

Route::get('/costumers/index', function() {
    return view('costumers.index');
})->middleware(['auth'])->name('costumers.index');