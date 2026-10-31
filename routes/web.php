
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OwnerDashboardController;

// HOME
Route::get('/', function () {
    return view('welcome');
});

// CHOOSE ACCOUNT
Route::get('/choose', function () {
    return view('choose');
})->name('choose');

// VET AUTHENTICATION
Route::get('/login/vet', function () {
    return view('auth.login-vet');
})->name('login.vet');

Route::get('/register/vet', function () {
    return view('auth.register-vet');
})->name('register.vet');

// OWNER AUTHENTICATION
Route::get('/login/owner', function () {
    return view('auth.login-owner');
})->name('login.owner');

Route::get('/register/owner', function () {
    return view('auth.register-owner');
})->name('register.owner');

// PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {

    // GENERAL DASHBOARD
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'vet') {
            return redirect()->route('vet.dashboard');
        }

        return redirect()->route('owner.dashboard');
    })->name('dashboard');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // VET DASHBOARD
    Route::get('/vet/dashboard', function () {
        if (auth()->user()->role !== 'vet') {
            return redirect()->route('owner.dashboard');
        }

        return view('vet.dashboard');
    })->name('vet.dashboard');

    // OWNER DASHBOARD
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'show'])
        ->name('owner.dashboard');
});

// BREEZE AUTHENTICATION ROUTES
require __DIR__.'/auth.php';
