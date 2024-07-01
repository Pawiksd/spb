<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FetchArtistContactInfoFromWebsite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $artist;
    public $timeout = 600; // 600 seconds = 10 minutes

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    public function handle()
    {
        Log::info('Starting FetchArtistContactInfoFromWebsite job for artist:', ['artist_id' => $this->artist->id]);

        if (Cache::has('fetch-artist-contact-info-lock')) {
            Log::warning('Job is already running for another artist, releasing lock');
            $this->release(10);
            return;
        }

        Cache::put('fetch-artist-contact-info-lock', true, 600);

        try {
            $process = new Process(['node', base_path('scripts/fetch_artist_info.js'), $this->artist->spotify_id]);
            $process->setTimeout($this->timeout - 10);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('Process failed', ['output' => $process->getErrorOutput()]);
                throw new ProcessFailedException($process);
            }

            $output = json_decode($process->getOutput(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode JSON output from fetch_artist_info.js', ['output' => $process->getOutput()]);
                return;
            }

            Log::info('Updating artist contact info', ['artist_id' => $this->artist->id, 'output' => $output]);

            $this->artist->update([
                'email' => $output['email'] ?? $this->artist->email,
                'instagram' => $output['instagram'] ?? $this->artist->instagram,
                'facebook' => $output['facebook'] ?? $this->artist->facebook,
                'twitter' => $output['twitter'] ?? $this->artist->twitter,
                'website' => $output['website'] ?? $this->artist->website,
                'youtube' => $output['youtube'] ?? $this->artist->youtube,
            ]);

            Log::info('Artist contact info updated successfully', ['artist_id' => $this->artist->id]);
        } catch (\Exception $e) {
            Log::error('Error in FetchArtistContactInfoFromWebsite job', ['message' => $e->getMessage()]);
        } finally {
            Cache::forget('fetch-artist-contact-info-lock');
        }
    }
}
