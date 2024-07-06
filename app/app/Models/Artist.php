<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'spotify_id', 'name', 'email', 'instagram', 'facebook', 'website', 'youtube', 'twitter'
    ];

    public function newReleases()
    {
        return $this->hasMany(NewRelease::class, 'artist_id');
    }
}
