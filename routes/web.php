<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PicketScheduleController as UserPicketScheduleController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\Admin\PicketJournalController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\PicketScheduleController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ROUTES UNTUK SEMUA USER YANG LOGIN ==========
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/filter-by-day', [UserController::class, 'filterByDay'])->name('user.filter.day');
    Route::put('/schedules/{id}/status', [UserController::class, 'updateStatus'])->name('user.schedule.status');
    
    // User Routes
    Route::prefix('user')->name('user.')->group(function () {
        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/notifications/delete-read', [NotificationController::class, 'deleteRead'])->name('notifications.delete-read');
        
        // Picket Journal
        Route::get('/picket', [UserController::class, 'picketIndex'])->name('picket.index');
        Route::get('/picket/create', [UserController::class, 'picketCreate'])->name('picket.create');
        Route::post('/picket/store', [UserController::class, 'picketStore'])->name('picket.store');
        Route::get('/picket/{id}', [UserController::class, 'picketShow'])->name('picket.show');
        Route::post('/picket/{id}/start', [UserController::class, 'startPicket'])->name('picket.start');
        Route::post('/picket/{id}/end', [UserController::class, 'endPicket'])->name('picket.end');
        // Route::post('/picket/{id}/upload-photo', [UserController::class, 'uploadPhoto'])->name('picket.upload');
        Route::post('/picket/{id}/upload', [UserController::class, 'uploadPhoto'])->name('picket.upload');
        
        // ========== PICKET SCHEDULE UNTUK USER (HANYA LIHAT) ==========
        Route::get('/picket-schedule', [UserPicketScheduleController::class, 'index'])->name('picket-schedule.index');
        Route::get('/picket-schedule/{id}', [UserPicketScheduleController::class, 'show'])->name('picket-schedule.show');
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
    
    // Admin Routes Group
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // ========== PICKET SCHEDULE ROUTES (ADMIN) ==========
        Route::get('/picket-schedule', [PicketScheduleController::class, 'index'])->name('picket-schedule.index');
        Route::get('/picket-schedule/{id}/edit', [PicketScheduleController::class, 'edit'])->name('picket-schedule.edit');
        Route::post('/picket-schedule', [PicketScheduleController::class, 'store'])->name('picket-schedule.store');
        Route::put('/picket-schedule/{id}', [PicketScheduleController::class, 'update'])->name('picket-schedule.update');
        Route::delete('/picket-schedule/{id}', [PicketScheduleController::class, 'destroy'])->name('picket-schedule.destroy');
        
        // ========== PICKET JOURNAL ROUTES ==========
        Route::get('/picketjournal', [PicketJournalController::class, 'index'])->name('picketjournal.index');
        Route::get('/picketjournal/create', [PicketJournalController::class, 'create'])->name('picketjournal.create');
        Route::post('/picketjournal', [PicketJournalController::class, 'store'])->name('picketjournal.store');
        Route::get('/picketjournal/{picketjournal}', [PicketJournalController::class, 'show'])->name('picketjournal.show');
        Route::get('/picketjournal/{picketjournal}/edit', [PicketJournalController::class, 'edit'])->name('picketjournal.edit');
        Route::put('/picketjournal/{picketjournal}', [PicketJournalController::class, 'update'])->name('picketjournal.update');
        Route::delete('/picketjournal/{picketjournal}', [PicketJournalController::class, 'destroy'])->name('picketjournal.destroy');
        Route::get('/picketjournal/export/excel', [PicketJournalController::class, 'exportExcel'])->name('picketjournal.exportExcel');
        Route::get('/picketjournal/export/pdf', [PicketJournalController::class, 'exportPdf'])->name('picketjournal.exportPdf');
        
        // ========== NOTIFICATION ROUTES ==========
        Route::prefix('notification')->name('notification.')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
            Route::get('/{id}', [AdminNotificationController::class, 'show'])->name('show');
            Route::post('/broadcast', [AdminNotificationController::class, 'sendBroadcast'])->name('broadcast');
            Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
            Route::delete('/clear/read', [AdminNotificationController::class, 'clearRead'])->name('clearRead');
        });
    });
});