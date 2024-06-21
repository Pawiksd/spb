<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchSpotifyNewReleases;

class RunSpotifyFetch extends Command
{
    protected $signature = 'spotify:run-fetch';
    protected $description = 'Run the FetchSpotifyNewReleases job';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        FetchSpotifyNewReleases::dispatch();

        $this->info('FetchSpotifyNewReleases job dispatched successfully.');
    }
}
