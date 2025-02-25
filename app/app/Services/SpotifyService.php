<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

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
        //$client = new Client();

        /*Log::info('SP key.', ['key' => env('SPOTIFY_CLIENT_ID')]);
        Log::info('SP sec.', ['sec' => env('SPOTIFY_CLIENT_SECRET')]);

        Log::info('SP key.', ['key' => config('services.spotify.client_id')]);
        Log::info('SP sec.', ['sec' => config('services.spotify.client_secret')]);
*/


        $response = $this->client->post('https://accounts.spotify.com/api/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(config('services.spotify.client_id') . ':' . config('services.spotify.client_secret')),
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $token = $data['access_token'];

        Log::info('Fetched Spotify access token.');

        return $token;
    }

    public function getNewReleases($offset = 0)
    {
        $token = $this->getAccessToken();
        Log::info('Using token to fetch new releases.', ['token' => $token]);

        $response = $this->client->get('https://api.spotify.com/v1/browse/new-releases', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Cache-Control' => 'no-cache'
            ],
            'query' => [
                'limit' => 50,
                'offset' => $offset,
                'market' => 'GB'
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
