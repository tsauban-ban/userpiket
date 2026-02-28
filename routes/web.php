<?php

use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ManageUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('formlogin');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::resource('manageusers', ManageUserController::class);
 
Route::post('/manageusers/import', [ManageUserController::class, 'import'])->name('manageusers.import');

Route::resource('divisions', DivisionController::class);
 
Route::get('/manageusers/template/download', [ManageUserController::class, 'downloadTemplate'])->name('manageusers.template');