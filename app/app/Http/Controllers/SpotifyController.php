<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    public function getNewReleases()
    {
        $token = $this->getSpotifyToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://api.spotify.com/v1/browse/new-releases', [
            'country' => 'US',
            'limit' => 20,
        ]);

        $albums = $response->json()['albums']['items'];

        foreach ($albums as $album) {
            \App\Models\Album::updateOrCreate(
                ['spotify_id' => $album['id']],
                [
                    'name' => $album['name'],
                    'artist' => $album['artists'][0]['name'],
                    'release_date' => $album['release_date'],
                    'total_tracks' => $album['total_tracks'],
                    'image_url' => $album['images'][0]['url'],
                ]
            );
        }

        return response()->json($albums);
    }

    private function getSpotifyToken()
    {
        $client_id = env('SPOTIFY_CLIENT_ID');
        $client_secret = env('SPOTIFY_CLIENT_SECRET');

        $response = Http::asForm()->withBasicAuth($client_id, $client_secret)
            ->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
            ]);

        return $response->json()['access_token'];
    }
}
