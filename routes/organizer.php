<?php

use App\Http\Controllers\Organizer\CheckInController as OrganizerCheckInController;
use App\Http\Controllers\Organizer\ConcertController as OrganizerConcertController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\OrderVerificationController as OrganizerOrderVerificationController;
use App\Http\Controllers\Organizer\TicketTypeController as OrganizerTicketTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:organizer'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {
        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('dashboard');

        // CRUD Konser milik EO
        Route::resource('concerts', OrganizerConcertController::class);

        // CRUD Kategori & Kuota Tiket per Konser
        Route::get('/concerts/{concert}/ticket-types', [OrganizerTicketTypeController::class, 'index'])->name('ticket-types.index');
        Route::post('/concerts/{concert}/ticket-types', [OrganizerTicketTypeController::class, 'store'])->name('ticket-types.store');
        Route::put('/concerts/{concert}/ticket-types/{ticketType}', [OrganizerTicketTypeController::class, 'update'])->name('ticket-types.update');
        Route::delete('/concerts/{concert}/ticket-types/{ticketType}', [OrganizerTicketTypeController::class, 'destroy'])->name('ticket-types.destroy');

        // Verifikasi Bukti Pembayaran
        Route::get('/orders', [OrganizerOrderVerificationController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrganizerOrderVerificationController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/verify', [OrganizerOrderVerificationController::class, 'verify'])->name('orders.verify');

        // Validasi Check-In Gate
        Route::get('/check-in', [OrganizerCheckInController::class, 'index'])->name('checkin.index');
        Route::post('/check-in', [OrganizerCheckInController::class, 'store'])->name('checkin.store');
    });