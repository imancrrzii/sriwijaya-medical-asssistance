<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('patient.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Reset password
    Route::get('/forgot-password', [ResetPasswordController::class, 'forgotView'])->name('forgot.view');
    Route::post('/forgot-password', [ResetPasswordController::class, 'forgotSend'])->name('forgot.send');
    Route::get('/reset-password', [ResetPasswordController::class, 'resetView'])->name('reset.view');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetAction'])->name('reset.action');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Patient
    Route::get('/patient', [PatientController::class, 'index'])->name('patient.index');
    Route::post('/patient', [PatientController::class, 'store'])->name('patient.store');
    Route::get('/patient/{id}', [PatientController::class, 'getPatient'])->name('patient.get');
    Route::post('/patient/{id}/update', [PatientController::class, 'update'])->name('patient.update');
    Route::delete('patient/{id}/delete', [PatientController::class, 'delete'])->name('patient.delete');
    Route::post('/patient/print/{id}', [PatientController::class, 'printPatient'])->name('patient.print');
    Route::get('/patient/showPrint/{id}', [PatientController::class, 'showPrintView'])->name('patient.print.view');

    // Setting
    Route::get('/update-password', [UserController::class, 'showUpdatePasswordView'])->name('update.password.view');
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('update.password');
});