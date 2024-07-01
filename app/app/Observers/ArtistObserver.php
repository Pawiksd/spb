<?php
namespace App\Observers;

use App\Models\Artist;
use App\Jobs\FetchArtistContactInfoFromWebsite;

class ArtistObserver
{
    public function created(Artist $artist)
    {
        FetchArtistContactInfoFromWebsite::dispatch($artist)
            ->delay(now()->addMinutes(1));
        //->onQueue('fetch-artist-info');
    }
}
