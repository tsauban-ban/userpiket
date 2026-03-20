<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\Admin\PicketJournalController;
use App\Http\Controllers\Admin\NotificationController; // PASTIKAN INI ADA

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes yang memerlukan authentication
Route::middleware('auth')->group(function () {
    
    // Manage Users
    Route::resource('manageusers', ManageUserController::class);
    Route::post('/manageusers/import', [ManageUserController::class, 'import'])->name('manageusers.import');
    Route::get('/manageusers/template/download', [ManageUserController::class, 'downloadTemplate'])->name('manageusers.template');
    
    // Divisions
    Route::resource('divisions', DivisionController::class);
    
    // ============= ROUTES ADMIN =============
    Route::prefix('admin')->name('admin.')->group(function () {
        // Picket Journal
        Route::resource('picketjournal', PicketJournalController::class);
        Route::get('picketjournal/{picketjournal}/show', [PicketJournalController::class, 'show'])->name('picketjournal.show');
        
        // ============= ROUTES NOTIFIKASI UNTUK ADMIN =============
        Route::prefix('notification')->name('notification.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
            Route::post('/broadcast', [NotificationController::class, 'sendBroadcast'])->name('broadcast');
            Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::delete('/clear/read', [NotificationController::class, 'clearRead'])->name('clearRead');
        });
    });
});