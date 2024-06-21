<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    /**
     * Show the settings form.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('settings.edit');
    }

    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'spotify_api_key' => 'required|string',
            'spotify_api_secret' => 'required|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_username' => 'required|string',
            'smtp_password' => 'required|string',
        ]);

        // Save settings to .env or other storage
        $this->setEnv('SPOTIFY_API_KEY', $request->spotify_api_key);
        $this->setEnv('SPOTIFY_API_SECRET', $request->spotify_api_secret);
        $this->setEnv('MAIL_HOST', $request->smtp_host);
        $this->setEnv('MAIL_PORT', $request->smtp_port);
        $this->setEnv('MAIL_USERNAME', $request->smtp_username);
        $this->setEnv('MAIL_PASSWORD', $request->smtp_password);

        // Clear cache
        Artisan::call('config:cache');
        Artisan::call('config:clear');

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully');
    }

    /**
     * Set or update environment variable in .env file.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    private function setEnv($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $escaped = preg_quote('=' . env($key), '/');
            file_put_contents($path, preg_replace(
                "/^{$key}{$escaped}/m",
                "{$key}={$value}",
                file_get_contents($path)
            ));
        }
    }
}
