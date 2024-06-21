<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class SpotifyService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.spotify.com/v1/',
        ]);
    }

    public function getAccessToken()
    {
        if (Cache::has('spotify_access_token')) {
            return Cache::get('spotify_access_token');
        }

        $client = new Client();
        $response = $client->post('https://accounts.spotify.com/api/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(env('SPOTIFY_CLIENT_ID') . ':' . env('SPOTIFY_CLIENT_SECRET')),
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $token = $data['access_token'];
        Cache::put('spotify_access_token', $token, $data['expires_in']);

        return $token;
    }

    public function getNewReleases()
    {
        $token = $this->getAccessToken();
        $response = $this->client->get('browse/new-releases', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getArtistDetails($artistId)
    {
        $token = $this->getAccessToken();
        $response = $this->client->get('artists/' . $artistId, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
