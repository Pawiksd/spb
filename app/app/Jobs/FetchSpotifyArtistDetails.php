<?php

namespace App\Jobs;

use App\Models\Artist;
use App\Services\SpotifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchSpotifyArtistDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $retryAfter = 60;
    public $tries = 3;

    protected $artist;

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    public function handle()
    {
        $spotifyService = new SpotifyService();
        $artistDetails = $spotifyService->getArtistDetails($this->artist->spotify_id);

        $updateData = [];

        if (!empty($artistDetails['email'])) {
            $updateData['email'] = $artistDetails['email'];
        }

        if (!empty($artistDetails['instagram'])) {
            $updateData['instagram'] = $artistDetails['instagram'];
        }

        if (!empty($artistDetails['facebook'])) {
            $updateData['facebook'] = $artistDetails['facebook'];
        }

        if (!empty($artistDetails['website'])) {
            $updateData['website'] = $artistDetails['website'];
        }

        if (!empty($artistDetails['youtube'])) {
            $updateData['youtube'] = $artistDetails['youtube'];
        }

        if (!empty($artistDetails['twitter'])) {
            $updateData['twitter'] = $artistDetails['twitter'];
        }

        if (!empty($updateData)) {
            $this->artist->update($updateData);
        }

        FetchArtistContactInfoFromWebsite::dispatch($this->artist); //->onQueue('fetch-artist-info');
    }
}
