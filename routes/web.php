<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Secretaria\PatientController;
use App\Models\Role;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboards protegidos por rol
// Auth Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Usuarios
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');

});

// Auth Secretaria
Route::middleware(['auth', 'role:Secretaria'])->prefix('secretaria')->name('secretaria.')->group(function () {
    Route::get('/dashboard', function () {
        return view('secretaria.dashboard');
    })->name('dashboard');
    // Pacientes
    Route::resource('patients', PatientController::class)
        ->except(['show']);

    // Citas
    Route::resource('appointments', \App\Http\Controllers\Secretaria\AppointmentController::class)->except(['show', 'destroy']);
    Route::patch('appointments/{appointmets}/cancel', [App\Http\Controllers\Secretaria\AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

// Auth Doctor
Route::middleware(['auth', 'role:Doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('doctor.dashboard');
    })->name('dashboard');

    // Pacientes - solo lectura
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

    // Citas - solo lectura
    Route::get('appointments', [\App\Http\Controllers\Secretaria\AppointmentController::class, 'index'])->name('appointments.index');
});

require __DIR__.'/auth.php';
