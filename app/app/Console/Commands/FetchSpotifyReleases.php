<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SpotifyService;

class FetchSpotifyReleases extends Command
{
    protected $signature = 'spotify:fetch-releases';
    protected $description = 'Fetch new releases from Spotify';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $spotifyService = new SpotifyService();
        $newReleases = $spotifyService->getNewReleases();

        // Tutaj możesz przetworzyć dane, np. zapisać je do bazy danych
        foreach ($newReleases['albums']['items'] as $album) {
            $this->info('Title: ' . $album['name'] . ', Artist: ' . $album['artists'][0]['name'] . ' ID: ' . $album['artists'][0]['id']);
        }

        $this->info('Fetched new releases successfully.');
    }
}
