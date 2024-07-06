<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id', 'title', 'release_date', 'genre', 'label'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }
}
