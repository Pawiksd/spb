<?php

namespace App\Jobs;

use App\Models\Artist;
use App\Models\NewRelease;
use App\Services\SpotifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchSpotifyNewReleases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $spotifyService = new SpotifyService();
        $newReleases = $spotifyService->getNewReleases();

        foreach ($newReleases['albums']['items'] as $album) {
            $artistData = $album['artists'][0];
            $artist = Artist::firstOrCreate(
                ['spotify_id' => $artistData['id']],
                ['name' => $artistData['name']]
            );

            NewRelease::create([
                'spotify_id' => $album['id'],
                'title' => $album['name'],
                'artist_id' => $artist->id,
                'release_date' => $album['release_date'],
                'genre' => $album['genres'][0] ?? null,
                'label' => $album['label'] ?? null,
            ]);

            FetchSpotifyArtistDetails::dispatch($artist);
        }
    }
}
