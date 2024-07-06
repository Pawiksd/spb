<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\NewRelease;
use App\Exports\CustomReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::orderBy('created_at', 'desc')->take(20)->get();
        $totalArtists = Artist::count();

        return view('artists.index', compact('artists', 'totalArtists'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $artists = Artist::where('name', 'LIKE', "%{$query}%")->take(20)->get();
        $totalFound = $artists->count();

        return response()->json(['artists' => $artists, 'totalFound' => $totalFound]);
    }

    public function downloadReport(Request $request)
    {
        $query = $request->input('query');
        $columns = $request->input('columns', [
            'name', 'email', 'instagram', 'facebook', 'website', 'youtube', 'twitter'
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $artistsQuery = Artist::where('name', 'LIKE', "%{$query}%");

        if ($startDate && $endDate) {
            $artistsQuery->whereHas('newReleases', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('release_date', [$startDate, $endDate]);
            });
        }

        $artists = $artistsQuery->get();

        $data = $artists->map(function ($artist) use ($columns) {
            $row = [];
            foreach ($columns as $column) {
                $row[] = $artist->$column ?? 'N/A';
            }
            return $row;
        })->toArray();

        return Excel::download(new CustomReportExport($data, $columns), 'artists_report.xlsx');
    }

    public function downloadAllReport(Request $request)
    {
        $columns = $request->input('columns', [
            'name', 'email', 'instagram', 'facebook', 'website', 'youtube', 'twitter'
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $artistsQuery = Artist::query();

        if ($startDate && $endDate) {
            $artistsQuery->whereHas('newReleases', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('release_date', [$startDate, $endDate]);
            });
        }

        $artists = $artistsQuery->get();

        $data = $artists->map(function ($artist) use ($columns) {
            $row = [];
            foreach ($columns as $column) {
                $row[] = $artist->$column ?? 'N/A';
            }
            return $row;
        })->toArray();

        return Excel::download(new CustomReportExport($data, $columns), 'all_artists_report.xlsx');
    }
}
