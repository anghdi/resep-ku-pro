<?php

use App\Livewire\Pages\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\CompleteProfile;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\ManagementCenter;
use App\Livewire\Pages\IngredientsMaster;
use App\Livewire\Pages\ActivityLogs;
use App\Livewire\Pages\AccessControl;
use App\Livewire\Pages\AddNewMenu;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});



Route::prefix('auth/google')->group(function () {
    Route::get('/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', Dashboard::class)->name('dashboard');
//     Route::get('/complete-profile', CompleteProfile::class)->name('complete-profile');
//     Route::get('/management-center', ManagementCenter::class)->name('management-center');
//     Route::get('/ingredients', IngredientsMaster::class)->name('ingredients.index');
//     Route::get('/activity-logs', ActivityLogs::class)->name('activity.logs');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/complete-profile', CompleteProfile::class)->name('complete-profile');
    Route::get('/management-center', ManagementCenter::class)->name('management-center');
    Route::get('/ingredients', IngredientsMaster::class)->name('ingredients.index');
    Route::get('/activity-logs', ActivityLogs::class)->name('activity.logs');
    Route::get('/access-control', AccessControl::class)->name('access-control');
    Route::get('/add-new-menu', AddNewMenu::class)->name('add-new-menu');
    Route::get('/categories', Category::class)->name('categories.index');
});

// 4. Default Route (Kirim ke Login)
Route::get('/', function () {
    return redirect()->route('login');
});
