<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/assign-admin-role', function() {
    $role = Role::firstOrCreate(['name' => 'admin']);

    User::all()->each(function($user) use ($role) {
        $user->assignRole($role);
    });

    return 'Admin role assigned to all users';
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::middleware(['can:admin'])->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'show', 'store']);
    });
});

require __DIR__.'/auth.php';
