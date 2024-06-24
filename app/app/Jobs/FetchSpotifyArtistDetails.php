<?php

namespace App\Jobs;

use App\Models\Artist;
use App\Services\SpotifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchSpotifyArtistDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $artist;

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    public function handle()
    {
        $spotifyService = new SpotifyService();
        $artistDetails = $spotifyService->getArtistDetails($this->artist->spotify_id);

        $this->artist->update([
            'email' => $artistDetails['email'] ?? null,
            'instagram' => $artistDetails['instagram'] ?? null,
            'facebook' => $artistDetails['facebook'] ?? null,
            'website' => $artistDetails['website'] ?? null,
            'youtube' => $artistDetails['youtube'] ?? null,
        ]);

        FetchArtistContactInfoFromWebsite::dispatch($this->artist);
    }
}
