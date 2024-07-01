<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateMissingContactInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        Log::info('Starting UpdateMissingContactInfo job.');

        $artists = Artist::whereNull('email')
                        ->orWhereNull('instagram')
                        ->orWhereNull('facebook')
                        ->orWhereNull('website')
                        ->orWhereNull('youtube')
                        ->orWhereNull('twitter')
                        ->get();

        foreach ($artists as $artist) {
            FetchArtistContactInfoFromWebsite::dispatch($artist);
                //->onQueue('fetch-artist-info');
        }

        Log::info('UpdateMissingContactInfo job completed.');
    }
}
