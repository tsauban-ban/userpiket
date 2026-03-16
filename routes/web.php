<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\Admin\PicketJournalController;
use App\Http\Controllers\Admin\NotificationJournalController;
use App\Http\Controllers\DivisionController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('manageusers', ManageUserController::class);
    Route::post('/manageusers/import', [ManageUserController::class, 'import'])->name('manageusers.import');
    Route::get('/manageusers/template', [ManageUserController::class, 'downloadTemplate'])->name('manageusers.template');
    Route::prefix('admin')->name('admin.')->group(function () {

        // Picket Journal
        Route::resource('picketjournal', PicketJournalController::class);

        // Division
        Route::resource('division', DivisionController::class);

        // Notification
        Route::resource('notification', NotificationController::class)->only(['index']);
    });

});