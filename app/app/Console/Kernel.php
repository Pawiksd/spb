<?php

namespace App\Console;

use App\Jobs\FetchSpotifyNewReleases;
use App\Jobs\UpdateMissingContactInfo;
use App\Jobs\FetchSpotifyNewReleasesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new FetchSpotifyNewReleases)->daily();
        $schedule->job(new UpdateMissingContactInfo)->daily()->at('00:00');
        $schedule->job(new FetchSpotifyNewReleasesJob)->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
