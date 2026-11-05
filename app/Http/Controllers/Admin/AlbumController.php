<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index()
    {
        return view('admin.albums.index', ['albums' => Album::withCount('songs')->orderByDesc('release_year')->get()]);
    }

    public function store(Request $request)
    {
        Album::create($this->validated($request));

        return back()->with('success', 'Album ditambahkan.');
    }

    public function update(Request $request, Album $album)
    {
        $album->update($this->validated($request));

        return back()->with('success', 'Album diperbarui.');
    }

    public function destroy(Album $album)
    {
        if ($album->songs()->exists()) {
            return back()->with('error', 'Album masih dipakai lagu, tidak bisa dihapus.');
        }
        $album->delete();

        return back()->with('success', 'Album dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'        => 'required|string|max:255',
            'release_year' => 'required|integer|min:1900|max:2100',
        ]);
    }
}