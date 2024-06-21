<?php

namespace App\Http\Controllers;

use App\Exports\CustomReportExport;
use App\Models\NewRelease;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function download(Request $request)
    {
        $columns = $request->input('columns', [
            'artist', 'title', 'release_date', 'genre', 'label', 'email', 'instagram', 'facebook', 'website', 'youtube'
        ]);

        $data = NewRelease::with('artist')->get()->map(function ($release) use ($columns) {
            $row = [];
            foreach ($columns as $column) {
                if ($column == 'artist') {
                    $row[] = $release->artist->name;
                } else if (in_array($column, ['title', 'release_date', 'genre', 'label'])) {
                    $row[] = $release->$column;
                } else if (in_array($column, ['email', 'instagram', 'facebook', 'website', 'youtube'])) {
                    $row[] = $release->artist->$column;
                }
            }
            return $row;
        })->toArray();

        return Excel::download(new CustomReportExport($data, $columns), 'new_releases_report.xlsx');
    }
}
