<?php

namespace App\Http\Controllers;

use App\Models\Song;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('favorites', [
            'songs'       => $user->favorites()->with(['album', 'genre'])->get(),
            'favoriteIds' => $user->favorites()->pluck('songs.id')->all(),
            'boughtIds'   => $user->purchasedSongIds(),
        ]);
    }

    // Tombol ♥: kalau belum favorit jadi favorit, kalau sudah maka dilepas
    public function toggle(Song $song)
    {
        auth()->user()->favorites()->toggle($song->id);

        return back();
    }
}