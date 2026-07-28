<?php

use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\CheckinScannerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Redirect unauthenticated users to Filament app login
Route::get('/login', function () {
    return redirect('/app/login');
})->name('login');

// Public Event Landing & Attendee Registration Routes
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('public.event.show');
Route::post('/events/{slug}/register', [PublicEventController::class, 'register'])->name('public.event.register');
Route::get('/events/{slug}/success/{code}', [PublicEventController::class, 'success'])->name('public.event.success');
Route::get('/tickets/{code}/download-pdf', [PublicEventController::class, 'downloadPdf'])->name('public.ticket.pdf');

// QR Check-in process endpoint (used by Filament scanner page AJAX)
Route::middleware(['auth'])->post('/checkin/{eventId}/process', [CheckinScannerController::class, 'process'])->name('staff.checkin.process');
