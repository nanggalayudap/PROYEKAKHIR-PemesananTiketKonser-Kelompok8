<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\TicketController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/concerts', [CatalogController::class, 'index'])->name('concerts.index');
Route::get('/concerts/{concert:slug}', [CatalogController::class, 'show'])->name('concerts.show');
Route::view('/tentang-kami', 'customer.about')->name('about');

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');
    Route::post('/concerts/{concert:slug}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    Route::post('/orders/{order}/proof', [OrderController::class, 'uploadProof'])->name('orders.proof');
    Route::get('/orders/{order}/eticket', [OrderController::class, 'eticket'])->name('orders.eticket');
});

