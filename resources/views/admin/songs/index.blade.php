@extends('layouts.store')
@section('title', 'Kelola Lagu')

@section('content')
    <h1>Kelola Lagu</h1>
    <p><a class="btn btn-primary" href="{{ route('admin.songs.create') }}">+ Tambah Lagu</a></p>

    <div class="panel">
        <table>
            <thead><tr><th></th><th>Judul</th><th>Album</th><th>Genre</th><th>Harga</th><th>Aktif</th><th></th></tr></thead>
            <tbody>
                @foreach ($songs as $song)
                    <tr>
                        <td><img class="thumb" src="{{ $song->cover_url }}" alt=""></td>
                        <td>{{ $song->title }}</td>
                        <td>{{ $song->album->title }}</td>
                        <td>{{ $song->genre->name }}</td>
                        <td>{{ $song->price_formatted }}</td>
                        <td>{{ $song->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td style="display:flex; gap:6px;">
                            <a class="btn" href="{{ route('admin.songs.edit', $song) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.songs.destroy', $song) }}" onsubmit="return confirm('Hapus lagu ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection