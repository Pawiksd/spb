<?php

namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Cache;

class PreventJobOverlap
{
    protected $lockName;

    public function __construct($lockName)
    {
        $this->lockName = $lockName;
    }

    public function handle($job, $next)
    {
        $lock = Cache::lock($this->lockName, 600); // Blokada na 10 minut

        if ($lock->get()) {
            try {
                $next($job);
            } finally {
                $lock->release();
            }
        } else {
            // Job jest już w trakcie wykonywania, ponownie dodaj do kolejki
            $job->release(10);
        }
    }
}
