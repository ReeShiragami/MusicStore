<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['title', 'release_year', 'cover_image'];

    // Satu album punya banyak lagu
    public function songs() { return $this->hasMany(Song::class); }
}