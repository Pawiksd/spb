<?php

namespace App\Jobs;

use App\Models\Artist;
use App\Models\NewRelease;
use App\Models\Subscription;
use App\Notifications\NewReleaseNotification;
use App\Services\SpotifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchSpotifyNewReleases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        Log::info('Fetching new releases from Spotify.');

        $spotifyService = new SpotifyService();
        $newReleases = $spotifyService->getNewReleases();

        if (isset($newReleases['albums']['items'])) {
            Log::info('New releases fetched.', ['count' => count($newReleases['albums']['items'])]);

            foreach ($newReleases['albums']['items'] as $album) {
                try {
                    Log::info('Processing album.', ['album' => $album['name']]);

                    $artistData = $album['artists'][0];
                    $artist = Artist::firstOrCreate(
                        ['spotify_id' => $artistData['id']],
                        ['name' => $artistData['name']]
                    );

                    $existingRelease = NewRelease::where('spotify_id', $album['id'])->first();

                    if (!$existingRelease) {
                        $newRelease = NewRelease::create([
                            'spotify_id' => $album['id'],
                            'title' => $album['name'],
                            'artist_id' => $artist->id,
                            'release_date' => $album['release_date'],
                            'genre' => $album['genres'][0] ?? null,
                            'label' => $album['label'] ?? null,
                        ]);

                        Log::info('New release added.', ['title' => $album['name']]);

                        // Trigger notifications
                        $subscriptions = Subscription::all();
                        foreach ($subscriptions as $subscription) {
                            Log::info('Notifying user', ['user_id' => $subscription->user->id]);
                            $subscription->user->notify(new NewReleaseNotification($newRelease));
                        }
                    } else {
                        Log::info('Release already exists.', ['title' => $album['name']]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing album.', [
                        'album' => $album['name'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('Processing of new releases completed.');
        } else {
            Log::warning('No new releases found or failed to fetch new releases.');
        }
    }
}
