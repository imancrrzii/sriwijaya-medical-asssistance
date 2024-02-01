<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
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
    return redirect()->route('dashboard.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/patient-table/{tableNumber}', [DashboardController::class, 'showTableData'])
        ->middleware(['auth', 'can:admin-monitoring-all'])
        ->name('table.data');


    // Patient
    Route::get('/patient', [PatientController::class, 'index'])->name('patient.index');
    Route::post('/patient', [PatientController::class, 'store'])->name('patient.store');
    Route::get('/patient/{id}', [PatientController::class, 'getPatient'])->name('patient.get');
    Route::post('/patient/{id}/edit', [PatientController::class, 'update'])->name('patient.update');
    Route::delete('/patient/{id}/delete', [PatientController::class, 'delete'])->name('patient.delete');
});