<?php

use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\CheckinScannerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/admin');
});

// Public Event Landing & Attendee Registration Routes
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('public.event.show');
Route::post('/events/{slug}/register', [PublicEventController::class, 'register'])->name('public.event.register');
Route::get('/events/{slug}/success/{code}', [PublicEventController::class, 'success'])->name('public.event.success');
Route::get('/tickets/{code}/download-pdf', [PublicEventController::class, 'downloadPdf'])->name('public.ticket.pdf');

// Staff QR Check-in Camera Scanner Routes
Route::get('/checkin/{eventId}', [CheckinScannerController::class, 'show'])->name('staff.checkin.show');
Route::post('/checkin/{eventId}/process', [CheckinScannerController::class, 'process'])->name('staff.checkin.process');
