<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Panitia\AttendeeController;
use App\Http\Controllers\Panitia\EventManageController;
use App\Http\Controllers\Panitia\ReportController;
use App\Http\Controllers\RegistrationController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

/* ---------- Publik ---------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/acara', [EventController::class, 'index'])->name('events.index');
Route::get('/acara/{event}', [EventController::class, 'show'])->name('events.show');

/* ---------- Tamu (belum login) ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/* ---------- Peserta ---------- */
Route::middleware(['auth', EnsureRole::class . ':peserta'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'peserta'])->name('peserta.dashboard');
    Route::post('/acara/{event}/daftar', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::delete('/pendaftaran/{registration}', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
});

/* ---------- Panitia ---------- */
Route::prefix('panitia')
    ->name('panitia.')
    ->middleware(['auth', EnsureRole::class . ':panitia'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'panitia'])->name('dashboard');

        // Manajemen acara
        Route::get('/acara', [EventManageController::class, 'index'])->name('events.index');
        Route::get('/acara/buat', [EventManageController::class, 'create'])->name('events.create');
        Route::post('/acara', [EventManageController::class, 'store'])->name('events.store');
        Route::get('/acara/{event}/ubah', [EventManageController::class, 'edit'])->name('events.edit');
        Route::put('/acara/{event}', [EventManageController::class, 'update'])->name('events.update');
        Route::delete('/acara/{event}', [EventManageController::class, 'destroy'])->name('events.destroy');

        // Manajemen peserta & presensi
        Route::get('/acara/{event}/peserta', [AttendeeController::class, 'index'])->name('attendees.index');
        Route::get('/acara/{event}/peserta/ekspor', [AttendeeController::class, 'export'])->name('attendees.export');
        Route::post('/acara/{event}/konfirmasi-semua', [AttendeeController::class, 'confirmAll'])->name('attendees.confirmAll');
        Route::post('/acara/{event}/presensi', [AttendeeController::class, 'checkInByCode'])->name('attendees.checkinCode');
        Route::patch('/pendaftaran/{registration}/status', [AttendeeController::class, 'updateStatus'])->name('attendees.status');
        Route::patch('/pendaftaran/{registration}/hadir', [AttendeeController::class, 'checkIn'])->name('attendees.checkin');
        Route::delete('/pendaftaran/{registration}/hadir', [AttendeeController::class, 'undoCheckIn'])->name('attendees.undoCheckin');

        // Laporan
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/ekspor', [ReportController::class, 'export'])->name('reports.export');
    });
