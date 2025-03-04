<?php

use App\Models\Artist;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ArtistController;
use App\Jobs\FetchArtistContactInfoFromWebsite;
use App\Jobs\FetchSpotifyNewReleases;
use App\Jobs\UpdateMissingContactInfo;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TestNotificationController;
use App\Http\Controllers\FetchNewReleasesController;
use App\Jobs\FetchSpotifyNewReleasesJob;

Route::get('/fetch-spotify-new-releases', function () {
    FetchSpotifyNewReleases::dispatch();
    return 'Job dispatched';
});

Route::get('/fetch-new-releases', [FetchNewReleasesController::class, 'fetchNewReleases']);

Route::get('/fetch-new-releases-js', function () {
    FetchSpotifyNewReleasesJob::dispatch();
    return response()->json(['message' => 'Job dispatched']);
});


Route::get('/update-artists', function () {
    UpdateMissingContactInfo::dispatch();
    return 'Job dispatched';
});

// /fetch-artist-contact-info/0pVHBkObr9UNIWpms1e4I0
Route::get('/fetch-artist-contact-info/{id}', function ($id) {
    $artist = Artist::findOrFail($id);
    FetchArtistContactInfoFromWebsite::dispatch($artist); //->onQueue('fetch-artist-info');
    return 'Job dispatched';
});


Route::get('/test-notification', [TestNotificationController::class, 'sendTestNotification']);


Route::get('/assign-admin-role', function() {
    $role = Role::firstOrCreate(['name' => 'admin']);

    User::all()->each(function($user) use ($role) {
        $user->assignRole($role);
    });

    return 'Admin role assigned to all users';
});


Route::middleware(['auth'])->group(function () {
    /*Route::get('/', function () {
        return view('dashboard');
    });*/
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
    Route::get('/artists/search', [ArtistController::class, 'search'])->name('artists.search');
    Route::get('/artists/download-report', [ArtistController::class, 'downloadReport'])->name('artists.download-report');
    Route::get('/artists/download-report-all', [ArtistController::class, 'downloadAllReport'])->name('artists.download-report-all'); // Dodanie tej trasy

    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::middleware(['can:admin'])->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'show', 'store']);
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::post('/unsubscribe', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');
});

Route::get('/download-report', [ReportController::class, 'download'])->name('download-report');

require __DIR__.'/auth.php';
