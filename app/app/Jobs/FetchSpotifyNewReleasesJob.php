<?php

namespace App\Jobs;

use App\Models\Artist;
use App\Models\NewRelease;
use App\Models\Subscription;
use App\Notifications\NewReleaseNotification;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchSpotifyNewReleasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        try {
            $process = new Process(['node', base_path('scripts/fetch_new_releases.js')]);
            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('Fetch new releases script failed', ['error' => $process->getErrorOutput()]);
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();
            Log::info('Script output:', ['output' => $output]);

            $data = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
                Log::error('Failed to decode JSON output or output is empty', ['output' => $output]);
                return;
            }

            foreach ($data as $release) {
                $artist = Artist::firstOrCreate(
                    ['spotify_id' => $release['artist_id']],
                    ['name' => $release['artist_name']]
                );

                $existingRelease = NewRelease::where('spotify_id', $release['id'])->first();

                if (!$existingRelease) {

                    $newRelease = NewRelease::create([
                        'spotify_id' =>  $release['album_id'],
                        'title' => $release['album_title'],
                        'artist_id' => $artist->id,
                        'release_date' => $release['release_date'],
                        'genre' => $release['genres'][0] ?? null,
                        'label' => $release['label'] ?? null,
                    ]);

                  /*  NewRelease::updateOrCreate(
                        ['spotify_id' => $release['album_id']],
                        [
                            'title' => $release['album_title'],
                            'artist_id' => $artist->id,
                            'release_date' => $release['release_date'],
                        ]
                    );*/

                    // Trigger notifications
                    $subscriptions = Subscription::all();
                    foreach ($subscriptions as $subscription) {
                        Log::info('Notifying user', ['user_id' => $subscription->user->id]);
                        $subscription->user->notify(new NewReleaseNotification($newRelease));
                    }
                } else {
                    Log::info('Release already exists.', ['title' => $release['name']]);
                }
            }

            Log::info('New releases fetched and saved successfully');
        } catch (\Exception $e) {
            Log::error('Error in fetching new releases', ['message' => $e->getMessage()]);
        }
    }
}
