@php $s = $song ?? null; @endphp

<form class="panel form-panel" method="POST" enctype="multipart/form-data"
      action="{{ $s ? route('admin.songs.update', $s) : route('admin.songs.store') }}">
    @csrf
    @if ($s) @method('PUT') @endif

    <label>Judul lagu</label>
    <input type="text" name="title" value="{{ old('title', $s?->title) }}" required>

    <label>Album</label>
    <select name="album_id" required>
        @foreach ($albums as $album)
            <option value="{{ $album->id }}" @selected(old('album_id', $s?->album_id) == $album->id)>{{ $album->title }}</option>
        @endforeach
    </select>

    <label>Genre</label>
    <select name="genre_id" required>
        @foreach ($genres as $genre)
            <option value="{{ $genre->id }}" @selected(old('genre_id', $s?->genre_id) == $genre->id)>{{ $genre->name }}</option>
        @endforeach
    </select>

    <label>Harga (Rupiah, tanpa titik)</label>
    <input type="number" name="price" min="0" value="{{ old('price', $s?->price) }}" required>

    <label>Durasi (detik)</label>
    <input type="number" name="duration_seconds" min="1" value="{{ old('duration_seconds', $s?->duration_seconds) }}" required>

    <label>Nomor track</label>
    <input type="number" name="track_number" min="1" value="{{ old('track_number', $s?->track_number ?? 1) }}" required>

    <label>Gambar lagu (wajib persegi 1:1, maks 1 MB)</label>
    @if ($s?->cover_image)
        <img class="thumb" src="{{ $s->cover_url }}" alt="" style="margin-bottom:6px;">
    @endif
    <input type="file" name="cover_image" accept="image/*">

    <label>
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $s ? $s->is_active : true))>
        Tampilkan di katalog
    </label>

    <p>
        <button class="btn btn-primary" type="submit">Simpan</button>
        <a class="btn" href="{{ route('admin.songs.index') }}">Batal</a>
    </p>
</form>