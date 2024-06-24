<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\FetchSpotifyArtistDetails;

class UpdateMissingContactInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $artists = Artist::whereNull('email')
                        ->orWhereNull('instagram')
                        ->orWhereNull('facebook')
                        ->orWhereNull('website')
                        ->orWhereNull('youtube')
                        ->get();

        foreach ($artists as $artist) {
            FetchSpotifyArtistDetails::dispatch($artist)
                ->delay(now()->addSeconds(120))
                ->onQueue('default')
                ->retryAfter(60)
                ->tries(2);
        }
    }
}
