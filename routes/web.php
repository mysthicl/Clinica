<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Secretaria\PatientController;

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
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Usuarios
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');

});

Route::middleware(['auth', 'role:Secretaria'])->prefix('secretaria')->name('secretaria.')->group(function () {
    Route::get('/dashboard', function () {
        return view('secretaria.dashboard');
    })->name('dashboard');

    Route::resource('patients', PatientController::class)
        ->except(['show']);
});

Route::middleware(['auth', 'role:Doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('doctor.dashboard');
    })->name('dashboard');

    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
});

require __DIR__.'/auth.php';
