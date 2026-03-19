<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use \App\Http\Controllers\DoctorController;

Route::get('/', [DoctorController::class, 'index']);
Route::post('/store-doctor', [DoctorController::class, 'store'])->name('store.doctor');
Route::get('/get-employee/{code}', [DoctorController::class, 'getEmployee'])->name('get.employee');



ROute::get('import-employees',[AdminController::class, 'importEmployeesForm']);
Route::post('/import-employees', [AdminController::class, 'importEmployees']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/doctors', [AdminController::class, 'index'])->name('doctors.index');
    Route::delete('/admin/doctors/{id}', [AdminController::class, 'destroy'])
        ->name('doctors.destroy');
    Route::get('admin/doctors/export', [AdminController::class, 'export'])->name('doctors.export');


});
