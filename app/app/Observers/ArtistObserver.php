<?php
namespace App\Observers;

use App\Models\Artist;
use App\Jobs\FetchSpotifyArtistDetails;

class ArtistObserver
{
    public function created(Artist $artist)
    {
       /* FetchSpotifyArtistDetails::dispatch($artist)
            ->delay(now()->addMinutes(3))
            ->onQueue('default')
            ->retryAfter(60)
            ->tries(3);*/
    }
}
