<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\Admin\PicketJournalController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ROUTES UNTUK SEMUA USER YANG LOGIN (termasuk user biasa) ==========
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/filter-by-day', [UserController::class, 'filterByDay'])->name('user.filter.day');
    Route::put('/schedules/{id}/status', [UserController::class, 'updateStatus'])->name('user.schedule.status');
    
    // ========== NOTIFIKASI USER (UNTUK USER BIASA) ==========
    // Route ini harus DI LUAR dari middleware role:admin
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/notifications/delete-read', [NotificationController::class, 'deleteRead'])->name('notifications.delete-read');
    });
});

// ========== ROUTES UNTUK ADMIN SAJA ==========
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Manage Users
    Route::get('/manageusers', [ManageUserController::class, 'index'])->name('manageusers.index');
    Route::post('/manageusers', [ManageUserController::class, 'store'])->name('manageusers.store');
    Route::put('/manageusers/{id}', [ManageUserController::class, 'update'])->name('manageusers.update');
    Route::delete('/manageusers/{id}', [ManageUserController::class, 'destroy'])->name('manageusers.destroy');
    Route::post('/manageusers/import', [ManageUserController::class, 'import'])->name('manageusers.import');
    Route::get('/manageusers/export', [ManageUserController::class, 'export'])->name('manageusers.export');

    // Divisions
    Route::resource('divisions', DivisionController::class);
    
    // Admin Picket Journal
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/picketjournal', [PicketJournalController::class, 'index'])->name('picketjournal.index');
        Route::get('/picketjournal/create', [PicketJournalController::class, 'create'])->name('picketjournal.create');
        Route::post('/picketjournal', [PicketJournalController::class, 'store'])->name('picketjournal.store');
        Route::get('/picketjournal/{picketjournal}', [PicketJournalController::class, 'show'])->name('picketjournal.show');
        Route::get('/picketjournal/{picketjournal}/edit', [PicketJournalController::class, 'edit'])->name('picketjournal.edit');
        Route::put('/picketjournal/{picketjournal}', [PicketJournalController::class, 'update'])->name('picketjournal.update');
        Route::delete('/picketjournal/{picketjournal}', [PicketJournalController::class, 'destroy'])->name('picketjournal.destroy');
        
        Route::get('/picketjournal/export/excel', [PicketJournalController::class, 'exportExcel'])->name('picketjournal.exportExcel');
        Route::get('/picketjournal/export/pdf', [PicketJournalController::class, 'exportPdf'])->name('picketjournal.exportPdf');
        
        // NOTIFIKASI ADMIN
        Route::prefix('notification')->name('notification.')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
            Route::get('/{id}', [AdminNotificationController::class, 'show'])->name('show');
            Route::post('/broadcast', [AdminNotificationController::class, 'sendBroadcast'])->name('broadcast');
            Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
            Route::delete('/clear/read', [AdminNotificationController::class, 'clearRead'])->name('clearRead');
        });
    });
});