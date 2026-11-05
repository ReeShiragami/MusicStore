<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Album, Genre, Song};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function index()
    {
        return view('admin.songs.index', ['songs' => Song::with(['album', 'genre'])->latest()->get()]);
    }

    public function create()
    {
        return view('admin.songs.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Song::create($data);

        return redirect()->route('admin.songs.index')->with('success', 'Lagu ditambahkan.');
    }

    public function edit(Song $song)
    {
        return view('admin.songs.edit', $this->formData() + ['song' => $song]);
    }

    public function update(Request $request, Song $song)
    {
        $data = $this->validated($request);

        // Kalau ada gambar baru, hapus gambar lama dari storage
        if (isset($data['cover_image']) && $song->cover_image) {
            Storage::disk('public')->delete($song->cover_image);
        }
        $song->update($data);

        return redirect()->route('admin.songs.index')->with('success', 'Lagu diperbarui.');
    }

    public function destroy(Song $song)
    {
        if ($song->cover_image) {
            Storage::disk('public')->delete($song->cover_image);
        }
        // Riwayat pembelian tetap aman karena order_items menyimpan snapshot
        $song->delete();

        return back()->with('success', 'Lagu dihapus.');
    }

    // Data dropdown album & genre untuk form
    private function formData(): array
    {
        return ['albums' => Album::orderBy('title')->get(), 'genres' => Genre::orderBy('name')->get()];
    }

    // Validasi + simpan gambar. Gambar WAJIB persegi 1:1, maksimal 1 MB.
    // EDIT DI SINI: ubah max:1024 (KB) kalau mau batas ukuran file berbeda
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'album_id'         => 'required|exists:albums,id',
            'genre_id'         => 'required|exists:genres,id',
            'title'            => 'required|string|max:255',
            'price'            => 'required|integer|min:0',
            'duration_seconds' => 'required|integer|min:1',
            'track_number'     => 'required|integer|min:1',
            'cover_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024|dimensions:ratio=1/1',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        } else {
            unset($data['cover_image']); // jangan timpa gambar lama dengan null
        }

        return $data;
    }
}