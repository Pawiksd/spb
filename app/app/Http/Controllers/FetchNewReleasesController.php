<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\NewRelease;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class FetchNewReleasesController extends Controller
{
    public function fetchNewReleases()
    {
        try {
            $process = new Process(['/usr/bin/node', base_path('scripts/fetch_new_releases.js')]);
            $process->setTimeout(120); // Set a timeout to avoid hanging
            $process->run();


            if (!$process->isSuccessful()) {
                Log::error('Fetch new releases script failed', ['error' => $process->getErrorOutput()]);
                throw new ProcessFailedException($process);
            }

            var_dump($process->getOutput());

            $output = json_decode($process->getOutput(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode JSON output from fetch_new_releases.js', ['output' => $process->getOutput()]);
                return response()->json(['error' => 'Failed to decode output'], 500);
            }

            foreach ($output as $release) {
                $artist = Artist::firstOrCreate(
                    ['spotify_id' => $release['artist_id']],
                    ['name' => $release['artist_name']]
                );

                NewRelease::updateOrCreate(
                    ['spotify_id' => $release['album_id']],
                    [
                        'title' => $release['album_title'],
                        'artist_id' => $artist->id,
                        'release_date' => $release['release_date'],
                    ]
                );
            }

            return response()->json(['message' => 'New releases fetched and saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error in fetching new releases', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching new releases'], 500);
        }
    }
}
