<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'spotify_id', 'title', 'artist_id', 'genre', 'label', 'release_date'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
