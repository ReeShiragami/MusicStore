@extends('layouts.store')
@section('title', 'Kelola Album')

@section('content')
    <h1>Kelola Album</h1>

    {{-- Form tambah album --}}
    <form class="add-form" method="POST" action="{{ route('admin.albums.store') }}">
        @csrf
        <input type="text" name="title" placeholder="Judul album" required>
        <input type="number" name="release_year" placeholder="Tahun" min="1900" max="2100" required style="max-width:120px;">
        <button class="btn btn-primary" type="submit">Tambah</button>
    </form>

    <div class="panel">
        <table>
            <thead><tr><th>Judul & tahun</th><th>Jumlah lagu</th><th></th></tr></thead>
            <tbody>
                @foreach ($albums as $album)
                    <tr>
                        <td>
                            <form class="inline-form" method="POST" action="{{ route('admin.albums.update', $album) }}">
                                @csrf @method('PUT')
                                <input type="text" name="title" value="{{ $album->title }}" required>
                                <input type="number" name="release_year" value="{{ $album->release_year }}" required style="max-width:100px;">
                                <button class="btn" type="submit">Simpan</button>
                            </form>
                        </td>
                        <td>{{ $album->songs_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.albums.destroy', $album) }}" onsubmit="return confirm('Hapus album ini?')">
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