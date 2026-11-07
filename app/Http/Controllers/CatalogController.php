<?php

namespace App\Http\Controllers;

use App\Models\{Genre, Song};
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    // Halaman katalog: daftar lagu aktif, bisa difilter per genre (?genre=rock) dan dicari (?search=term)
    public function index(Request $request)
    {
        $songs = Song::with(['album', 'genre'])
            ->where('is_active', true)
            ->when($request->genre, fn ($q, $slug) => $q->whereHas('genre', fn ($g) => $g->where('slug', $slug)))
            ->when($request->search, function ($query) use ($request) {
                $searchTerm = strtolower($request->search);
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(title) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereHas('album', fn ($album) => $album->whereRaw('LOWER(title) LIKE ?', ['%' . $searchTerm . '%']))
                      ->orWhereHas('genre', fn ($genre) => $genre->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']));
                });
            })
            ->orderBy('album_id')->orderBy('track_number')
            ->get();

        $user = auth()->user();

        return view('catalog', [
            'songs'       => $songs,
            'genres'      => Genre::orderBy('name')->get(),
            'favoriteIds' => $user->favorites()->pluck('songs.id')->all(), // untuk warna ♥
            'boughtIds'   => $user->purchasedSongIds(),                    // untuk badge "Dimiliki"
            'searchTerm'  => $request->search,
        ]);
    }
}