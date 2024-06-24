<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewRelease;

class DashboardController extends Controller
{
    public function index()
    {
        // Pobieranie ostatnich 50 wydań
        $latestReleases = NewRelease::with('artist')->orderBy('release_date', 'desc')->take(50)->get();

        return view('dashboard', compact('latestReleases'));
    }
}
