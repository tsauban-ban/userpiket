<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
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



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
});



Route::middleware(['auth', 'role:admin'])->group(function () {
    
    

    Route::get('/manageusers', [ManageUserController::class, 'index'])->name('manageusers.index');
    Route::post('/manageusers', [ManageUserController::class, 'store'])->name('manageusers.store');
    Route::put('/manageusers/{id}', [ManageUserController::class, 'update'])->name('manageusers.update');
    Route::delete('/manageusers/{id}', [ManageUserController::class, 'destroy'])->name('manageusers.destroy');
    Route::post('/manageusers/import', [ManageUserController::class, 'import'])->name('manageusers.import');
    Route::get('/manageusers/export', [ManageUserController::class, 'export'])->name('manageusers.export');
    
    
    
    Route::resource('divisions', DivisionController::class);
    
    
    
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
        
        
        
        Route::prefix('notification')->name('notification.')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'index'])->name('notification.index');
            Route::get('/{id}', [AdminNotificationController::class, 'show'])->name('notification.show');
            Route::post('/broadcast', [AdminNotificationController::class, 'sendBroadcast'])->name('notification.broadcast');
            Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('notification.destroy');
            Route::delete('/clear/read', [AdminNotificationController::class, 'clearRead'])->name('notification.clearRead');
        });
    });
});
