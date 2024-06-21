<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewRelease;

class DashboardController extends Controller
{
    public function index()
    {
        // Pobieranie najnowszego wydania
        $latestRelease = NewRelease::with('artist')->orderBy('release_date', 'desc')->first();

        return view('dashboard', compact('latestRelease'));
    }
}
