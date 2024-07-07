<?php

namespace App\Http\Controllers;

use App\Models\NewRelease;
use App\Notifications\NewReleaseNotification;
use Illuminate\Support\Facades\Auth;

class TestNotificationController extends Controller
{
    public function sendTestNotification()
    {
        // Fetch a sample release (you can adjust this to fetch a specific release)
        $release = NewRelease::first();

        if (!$release) {
            return "No releases found.";
        }

        // Send the notification to the authenticated user
        Auth::user()->notify(new NewReleaseNotification($release));

        return "Notification sent!";
    }
}
