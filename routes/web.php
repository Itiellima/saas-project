<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Auth\FirstPasswordController;
use App\Http\Controllers\Auth\RegisterTenantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\WorkshopController;
use App\Livewire\ServiceOrder\Index;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';


// Cadastro de uma nova oficina (tenant) com o seu primeiro usuário
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterTenantController::class, 'create'])->name('register');
    // Até 5 cadastros por minuto por IP, contra criação de oficinas em massa
    Route::post('/register', [RegisterTenantController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});

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

    Route::put('/service-orders/close/{id}', [ServiceOrderController::class, 'closeServiceOrder'])->name('service-orders.close');
});

// Ações da OS que só o dono pode fazer
Route::middleware(['auth', 'owner'])->group(function () {

    Route::delete('/service-orders/{id}', [ServiceOrderController::class, 'destroy'])->name('service-orders.destroy');

    Route::put('/service-orders/reopen/{id}', [ServiceOrderController::class, 'reopen'])->name('service-orders.reopen');

    Route::put('/service-orders/cancel/{id}', [ServiceOrderController::class, 'cancel'])->name('service-orders.cancel');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/items/index', [ItemController::class, 'index'])->name('items.index');
});

// Cadastro de itens, preços e estoque: só o dono (funcionários só consultam)
Route::middleware(['auth', 'owner'])->group(function () {

    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');

    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');

    Route::get('/items/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');

    Route::put('/items/update/{id}', [ItemController::class, 'update'])->name('items.update');

    Route::delete('/items/destroy/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

    Route::put('/items/toggle/{id}', [ItemController::class, 'toggle'])->name('items.toggle');
});

// Primeiro acesso de funcionário: troca da senha provisória
Route::middleware('auth')->group(function () {
    Route::get('/first-password', [FirstPasswordController::class, 'edit'])->name('password.first');
    Route::put('/first-password', [FirstPasswordController::class, 'update'])->name('password.first.update');
});

// Dados da própria oficina e equipe: só o dono
Route::middleware(['auth', 'owner'])->group(function () {
    Route::get('/workshop', [WorkshopController::class, 'edit'])->name('workshop.edit');
    Route::put('/workshop', [WorkshopController::class, 'update'])->name('workshop.update');

    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::post('/team', [TeamController::class, 'store'])->name('team.store');
    Route::put('/team/{id}/password', [TeamController::class, 'resetPassword'])->name('team.reset-password');
});

// Área do administrador geral da plataforma (404 para os demais)
Route::middleware(['auth', 'super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/tenants', [AdminTenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/{tenant}', [AdminTenantController::class, 'show'])->name('tenants.show');
    Route::put('/tenants/{tenant}/suspend', [AdminTenantController::class, 'suspend'])->name('tenants.suspend');
    Route::put('/tenants/{tenant}/reactivate', [AdminTenantController::class, 'reactivate'])->name('tenants.reactivate');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

});
