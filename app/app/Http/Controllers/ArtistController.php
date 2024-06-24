<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
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
        $columns = $request->input('columns', [
            'name', 'email', 'instagram', 'facebook', 'website', 'youtube'
        ]);

        $data = Artist::orderBy('created_at', 'desc')->get()->map(function ($artist) use ($columns) {
            $row = [];
            foreach ($columns as $column) {
                $row[] = $artist->$column ?? 'N/A';
            }
            return $row;
        })->toArray();

        return Excel::download(new CustomReportExport($data, $columns), 'artists_report.xlsx');
    }
}
