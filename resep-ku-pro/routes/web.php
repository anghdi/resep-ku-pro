<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

Route::prefix('auth/google')->group(function () {
    Route::get('/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    // Route::post('/logout', function () {
    //     auth()->logout();
    //     return redirect('/login');
    // })->name('logout');
});

// 4. Default Route (Kirim ke Login)
Route::get('/', function () {
    return redirect()->route('login');
});
