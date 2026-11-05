<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        return view('admin.genres.index', ['genres' => Genre::withCount('songs')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:100']);
        Genre::create($data + ['slug' => Str::slug($data['name'])]);

        return back()->with('success', 'Genre ditambahkan.');
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate(['name' => 'required|string|max:100']);
        $genre->update($data + ['slug' => Str::slug($data['name'])]);

        return back()->with('success', 'Genre diperbarui.');
    }

    public function destroy(Genre $genre)
    {
        // Genre yang masih dipakai lagu tidak boleh dihapus
        if ($genre->songs()->exists()) {
            return back()->with('error', 'Genre masih dipakai lagu, tidak bisa dihapus.');
        }
        $genre->delete();

        return back()->with('success', 'Genre dihapus.');
    }
}