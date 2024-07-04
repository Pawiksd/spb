<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewRelease;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Pobieranie parametrów sortowania
        $sortField = $request->get('sortField', 'release_date');
        $sortOrder = $request->get('sortOrder', 'desc');

        // Jeśli sortowanie według nazwy artysty, dostosuj zapytanie
        if ($sortField == 'artist_name') {
            $sortField = 'artists.name';
        }

        // Pobieranie ostatnich 50 wydań z sortowaniem
        $latestReleases = NewRelease::with('artist')
            ->join('artists', 'new_releases.artist_id', '=', 'artists.id')
            ->orderBy($sortField, $sortOrder)
            ->select('new_releases.*') // Dodajemy to, aby uniknąć konfliktu kolumn przy JOIN
            ->take(50)
            ->get();

        return view('dashboard', compact('latestReleases', 'sortField', 'sortOrder'));
    }
}
